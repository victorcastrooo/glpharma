<?php
include 'db_connect.php';

$sql = "SELECT p.id, p.nome, p.preco, p.imagem, m.nomemarca AS nomemarca
        FROM produto p 
        JOIN marca m ON p.marca_id = m.id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <meta charset="UTF-8">
    <title>Loja Online</title>
</head>
<body>
    <h1>Produtos</h1>
    <div class="product-grid">
        <?php while($row = $result->fetch_assoc()): ?>
            <div class="product-card">
                <div class="product-image">
                    <img src='<?= htmlspecialchars($row['imagem']) ?>' alt='Foto do Produto' style='width: auto; height: 200px;'>
                </div>
                <div class="product-info">
                    <h2><?= htmlspecialchars($row['nome']) ?> - <?= htmlspecialchars($row['nomemarca']) ?></h2>
                    <p>R$ <?= number_format($row['preco'], 2, ',', '.') ?></p>
                    <div class="product-actions">
                        <form action="services/add_to_cart.php" method="post">
                            <input type="hidden" name="product_id" value="<?= htmlspecialchars($row['id']) ?>">
                            <button type="submit" class="add-to-cart">Adicionar ao Carrinho</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <a href="cart.php" class="floating-button"><i class="fas fa-shopping-cart"></i></a>
    
</body>
</html>