<?php
session_start();

// Verificar se o usuário está logado
if (!isset($_SESSION['username'])) {
    // Redirecionar para a página de login se não estiver logado
    header("Location: ../index.php");
    exit();
}

$pedido_id = isset($_GET['pedido_id']) ? (int)$_GET['pedido_id'] : 0;

if ($pedido_id > 0) {
    // Consulta para obter os itens do pedido
    $sql = "SELECT p.nome, ip.quantidade 
            FROM itens_pedido ip 
            JOIN produto p ON ip.produto_id = p.id 
            WHERE ip.pedido_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $pedido_id);
    $stmt->execute();
    $result = $stmt->get_result();

    $itens = [];
    while ($row = $result->fetch_assoc()) {
        $itens[] = $row;
    }

    // Retorna os itens em formato JSON
    echo json_encode($itens);
} else {
    echo json_encode([]);
}
?>