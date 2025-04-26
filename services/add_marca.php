<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Conectar ao banco de dados
    require '../db_connect.php'; // Certifique-se de ajustar o caminho conforme necessário
    define('BASE_PATH', '/glpharma-main');
    // Verificar se o formulário foi submetido
    if (isset($_POST['nomemarca']) && isset($_FILES['logomarca'])) {
        $nomemarca = $_POST['nomemarca'];
        $logomarca = $_FILES['logomarca'];

        // Caminho para salvar a imagem
        $target_dir = "../uploads/";
        $target_file = $target_dir . basename($logomarca["name"]);
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Verificar se o arquivo é uma imagem real ou uma imagem falsa
        $check = getimagesize($logomarca["tmp_name"]);
        if ($check !== false) {
            $uploadOk = 1;
        } else {
            echo "O arquivo não é uma imagem.";
            $uploadOk = 0;
        }

        // Verificar se o arquivo já existe
        if (file_exists($target_file)) {
            echo "Desculpe, o arquivo já existe.";
            $uploadOk = 0;
        }

        // Verificar o tamanho do arquivo
        if ($logomarca["size"] > 500000) {
            echo "Desculpe, seu arquivo é muito grande.";
            $uploadOk = 0;
        }

        // Permitir certos formatos de arquivo
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
            && $imageFileType != "gif") {
            echo "Desculpe, apenas arquivos JPG, JPEG, PNG e GIF são permitidos.";
            $uploadOk = 0;
        }

        // Verificar se $uploadOk está configurado para 0 por causa de um erro
        if ($uploadOk == 0) {
            echo "Desculpe, seu arquivo não foi carregado.";
        // Se tudo estiver ok, tente fazer o upload do arquivo
        } else {
            if (move_uploaded_file($logomarca["tmp_name"], $target_file)) {
                // Inserir os dados no banco de dados
                $stmt = $conn->prepare("INSERT INTO marca (nomemarca, logomarca) VALUES (?, ?)");
                $stmt->bind_param("ss", $nomemarca, $target_file);

                if ($stmt->execute()) {
                    echo "A marca foi adicionada com sucesso.";
                    
                    header("Location:". BASE_PATH . "/admin/marcas"); // Redirecionar de volta para a página principal
                } else {
                    echo "Erro ao inserir a marca: " . $conn->error;
                }
            } else {
                echo "Desculpe, houve um erro ao fazer o upload do seu arquivo.";
            }
        }
    }
}
?>