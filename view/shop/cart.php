<?php
session_start();
include '../../db_connect.php';

// Inicialize o carrinho da sessão
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Pegue os IDs dos produtos no carrinho
$product_ids = array_keys($cart);

// Verifique se há produtos no carrinho
if (empty($product_ids)) {
    $product_ids = [0]; // Para evitar erro na query SQL se o carrinho estiver vazio
}

// Query para buscar os produtos no carrinho
$sql = "SELECT * FROM produto WHERE id IN (" . implode(',', $product_ids) . ")";
$result = $conn->query($sql);

$total = 0;
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Carrinho de Compras</title>
<link rel="stylesheet" href="../../css/style.css">
<script src="../js/script.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <h1>Carrinho de Compras</h1>
    <ul>
        <?php while($row = $result->fetch_assoc()): ?>
            <?php
            $quantity = $cart[$row['id']];
            $subtotal = $row['preco'] * $quantity;
            $total += $subtotal;
            ?>
            <li>
                <?= htmlspecialchars($row['nome']) ?> - R$ <?= number_format($row['preco'], 2, ',', '.') ?> x <?= htmlspecialchars($quantity) ?> = R$ <?= number_format($subtotal, 2, ',', '.') ?>
                <form action="../../services/update_cart.php" method="post" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit" name="action" value="increase">+</button>
                    <button type="submit" name="action" value="decrease">-</button>
                </form>
                <form action="../../services/remove_from_cart.php" method="post" style="display:inline;">
                    <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                    <button type="submit">Remover</button>
                </form>
            </li>
        <?php endwhile; ?>
    </ul>
    <h2>Total: R$ <?= number_format($total, 2, ',', '.') ?></h2>
    <a href="index.php">Continuar Comprando</a>
    <form action="checkout.php" method="post">
        <input type="hidden" name="total" value="<?= htmlspecialchars($total) ?>">
        <?php foreach ($cart as $product_id => $quantity): ?>
            <input type="hidden" name="cart[<?= htmlspecialchars($product_id) ?>]" value="<?= htmlspecialchars($quantity) ?>">
        <?php endforeach; ?>
        <button type="submit">Ir para Checkout</button>
    </form>
</body>
</html>
