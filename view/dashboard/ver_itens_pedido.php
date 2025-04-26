<?php

require '../../db_connect.php';

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
} else {
    echo "Pedido inválido.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Itens do Pedido #<?= htmlspecialchars($pedido_id) ?></title>
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Itens do Pedido #<?= htmlspecialchars($pedido_id) ?></h1>
    <ul>
        <?php foreach ($itens as $item): ?>
            <li><?= htmlspecialchars($item['nome']) ?> - Quantidade: <?= htmlspecialchars($item['quantidade']) ?></li>
        <?php endforeach; ?>
    </ul>
    <a href="../dashboard.php?page=pedidos">Voltar à Lista de Pedidos</a>
</body>
</html>
