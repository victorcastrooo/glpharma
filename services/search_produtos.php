<?php
require '../db_connect.php';

$search = isset($_GET['search']) ? $_GET['search'] : '';

if ($search) {
    $stmt = $conn->prepare("SELECT p.id, p.nome, t.tipo, m.nomemarca, p.valor, p.descricao, p.foto 
                            FROM produto p 
                            JOIN tipo t ON p.tipo_id = t.id 
                            JOIN marca m ON p.marca_id = m.id 
                            WHERE p.nome LIKE ? OR t.tipo LIKE ? OR m.nomemarca LIKE ?");
    $search_param = "%" . $search . "%";
    $stmt->bind_param("sss", $search_param, $search_param, $search_param);
} else {
    $stmt = $conn->prepare("SELECT p.id, p.nome, t.tipo, m.nomemarca, p.valor, p.descricao, p.foto 
                            FROM produto p 
                            JOIN tipo t ON p.tipo_id = t.id 
                            JOIN marca m ON p.marca_id = m.id");
}

$stmt->execute();
$result = $stmt->get_result();

$output = '';
while ($produto = $result->fetch_assoc()) {
    $output .= "<tr>";
    $output .= "<td>" . $produto['id'] . "</td>";
    $output .= "<td>" . $produto['nome'] . "</td>";
    $output .= "<td>" . $produto['tipo'] . "</td>";
    $output .= "<td>" . $produto['nomemarca'] . "</td>";
    $output .= "<td>" . $produto['valor'] . "</td>";
    $output .= "<td>" . $produto['descricao'] . "</td>";
    $output .= "<td><img src='" . $produto['foto'] . "' alt='Foto do Produto' style='width: auto; height: 50px;'></td>";
    $output .= "<td>
                <a href='edit_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-info'><i class='fas fa-edit'></i></a>
                <a href='view_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                <a href='../services/delete_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></a>
            </td>";
    $output .= "</tr>";
}

echo $output;
?>
