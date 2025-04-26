<?php
session_start();

// Verificar se product_id e action estão definidos
if (isset($_POST['product_id'], $_POST['action'])) {
    // Sanitizar os dados recebidos via POST
    $product_id = intval($_POST['product_id']);
    $action = htmlspecialchars($_POST['action']);

    // Verificar se o produto já está no carrinho
    if (isset($_SESSION['cart'][$product_id])) {
        // Incrementar a quantidade do produto no carrinho
        if ($action == 'increase') {
            $_SESSION['cart'][$product_id]++;
        } 
        // Decrementar a quantidade do produto no carrinho
        elseif ($action == 'decrease') {
            $_SESSION['cart'][$product_id]--;
            // Remover o produto do carrinho se a quantidade for menor ou igual a zero
            if ($_SESSION['cart'][$product_id] <= 0) {
                unset($_SESSION['cart'][$product_id]);
            }
        }
    }
}

// Redirecionar de volta para a página do carrinho
header('Location: ../view/shop/cart.php');
exit();
?>
