<?php

require '../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'] ?? null;
    $nome = $_POST['nome'] ?? '';
    $descricao = $_POST['descricao'] ?? '';
    $principio_ativo = $_POST['principio_ativo'] ?? '';
    $dosagem = $_POST['dosagem'] ?? '';
    $forma_farmaceutica = $_POST['forma_farmaceutica'] ?? '';
    $tipo = $_POST['tipo'] ?? '';
    $necessita_receita = $_POST['necessita_receita'] ?? 0;
    $preco = $_POST['preco'] ?? 0.0;
    $estoque = $_POST['estoque'] ?? 0;
    $peso = $_POST['peso'] ?? 0.0;
    $altura = $_POST['altura'] ?? 0.0;
    $largura = $_POST['largura'] ?? 0.0;
    $comprimento = $_POST['comprimento'] ?? 0.0;
    $fabricante = $_POST['fabricante'] ?? '';
    $validade = $_POST['validade'] ?? null;
    $registro_anvisa = $_POST['registro_anvisa'] ?? '';
    $marca_id = $_POST['marca'] ?? null;

    // Verifique se os campos obrigatórios estão preenchidos
    if (empty($nome) || empty($preco) || empty($estoque)) {
        echo "Por favor, preencha todos os campos obrigatórios.";
        exit();
    }

    // Verifique se o campo marca foi preenchido
    if (empty($marca_id)) {
        echo "Por favor, selecione uma marca.";
        exit();
    }

    // Processamento da imagem permanece o mesmo
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $foto = $_FILES['foto'];
        $foto_nome = $foto['name'];
        $foto_tmp = $foto['tmp_name'];
        $diretorio_upload = '../uploads/';
        if (!is_dir($diretorio_upload)) {
            mkdir($diretorio_upload, 0777, true);
        }
        $foto_destino = $diretorio_upload . basename($foto_nome);

        if (move_uploaded_file($foto_tmp, $foto_destino)) {
            if ($id) {
                // Atualizar produto existente
                $stmt = $conn->prepare("UPDATE produto SET nome = ?, descricao = ?, principio_ativo = ?, dosagem = ?, forma_farmaceutica = ?, tipo = ?, necessita_receita = ?, preco = ?, estoque = ?, peso = ?, altura = ?, largura = ?, comprimento = ?, fabricante = ?, validade = ?, registro_anvisa = ?, imagem = ?, marca_id = ? WHERE id = ?");
                $stmt->bind_param("ssssssidiidddssssii", $nome, $descricao, $principio_ativo, $dosagem, $forma_farmaceutica, $tipo, $necessita_receita, $preco, $estoque, $peso, $altura, $largura, $comprimento, $fabricante, $validade, $registro_anvisa, $foto_destino, $marca_id, $id);
            } else {
                // Inserir novo produto
                $stmt = $conn->prepare("INSERT INTO produto (nome, descricao, principio_ativo, dosagem, forma_farmaceutica, tipo, necessita_receita, preco, estoque, peso, altura, largura, comprimento, fabricante, validade, registro_anvisa, imagem, marca_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssssidiidddssssi", $nome, $descricao, $principio_ativo, $dosagem, $forma_farmaceutica, $tipo, $necessita_receita, $preco, $estoque, $peso, $altura, $largura, $comprimento, $fabricante, $validade, $registro_anvisa, $foto_destino, $marca_id);
            }

            if ($stmt->execute()) {
                header("Location: ../view/dashboard.php?page=produtos");
            } else {
                echo "Erro ao salvar produto: " . $stmt->error;
            }

            $stmt->close();
        } else {
            echo "Erro ao fazer upload da imagem.";
        }
    } else {
        echo "Erro no envio do arquivo.";
    }
} else {
    header("Location: ../view/dashboard.php?page=produtos");
}

$conn->close();
?>
