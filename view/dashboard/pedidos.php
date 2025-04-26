
<?php

require 'db_connect.php';


// Consulta para obter todos os pedidos
$sql = "SELECT id, nome, total, status, email FROM pedidos ORDER BY id DESC";
$result = $conn->query($sql);

$pedidos = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $pedidos[] = $row;
    }
}


?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lista de Pedidos</title>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Marcas</title>
    <!-- Link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link para o Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style_admin.css">
    <script src="js/script.js"></script> 

</head>
<body>
<div class="dashboard">
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Pedidos</h2>
            <form class="form-inline" method="GET" action="">
                <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <table class="table centered-table">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Status</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Total</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pedidos as $pedido): ?>
        
                    
                    <tr>
                        <td>#<?= htmlspecialchars($pedido['id']) ?></td>
                        <td class=" <?= htmlspecialchars($pedido['status']) ?>">
                        <?= htmlspecialchars($pedido['status']) ?></td>
                        </td>
                        <td><?= htmlspecialchars($pedido['nome']) ?></td>
                        <td>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></td>
                        <td>
                            <a href="dashboard/ver_itens_pedido.php?pedido_id=<?= htmlspecialchars($pedido['id']) ?>" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye"></i> Ver Itens
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<!-- Scripts do Bootstrap e do Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>