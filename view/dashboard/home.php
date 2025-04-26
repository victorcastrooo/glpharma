<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit();
}
require 'db_connect.php';

// Consultas para obter os dados do banco
$totalProdutos = $conn->query("SELECT COUNT(*) AS total FROM produto")->fetch_assoc()['total'];
$totalMarcas = $conn->query("SELECT COUNT(*) AS total FROM marca")->fetch_assoc()['total'];
$totalUsuarios = $conn->query("SELECT COUNT(*) AS total FROM usuarios")->fetch_assoc()['total'];
$totalVendas = $conn->query("SELECT SUM(total) AS total FROM pedidos")->fetch_assoc()['total'] ?? 0.0;
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Gestão</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="css/style_admin.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="js/script.js"></script>
</head>

<body>
    <div class="container mt-5">
        <h1 class="mb-4">Dashboard de Gestão e Análises</h1>
        <div class="row">
            <!-- Card de Vendas -->
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-dollar-sign"></i> Vendas</h5>
                        <p class="card-text">R$<?php echo number_format($totalVendas, 2, ',', '.'); ?></p>
                    </div>
                </div>
            </div>
            <!-- Card de Produtos -->
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-box"></i> Produtos</h5>
                        <p class="card-text"><?php echo $totalProdutos; ?></p>
                    </div>
                </div>
            </div>
            <!-- Card de Marcas -->
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-tags"></i> Marcas</h5>
                        <p class="card-text"><?php echo $totalMarcas; ?></p>
                    </div>
                </div>
            </div>
            <!-- Card de Usuários -->
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users"></i> Usuários</h5>
                        <p class="card-text"><?php echo $totalUsuarios; ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gráfico de Marcas Mais Vendidas -->
        <div class="mt-5">
            <h3>Resumo de Faturamento</h3>
            <div class="chart-container">
                <canvas id="marcaMaisVendidaChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $.getJSON('data.php', function(data) {
                var labels = data.marcas.map(function(item) {
                    return item.nomemarca;
                });
                var values = data.marcas.map(function(item) {
                    return parseFloat(item.total_vendido);
                });

                var marcaMaisVendidaCtx = document.getElementById('marcaMaisVendidaChart').getContext('2d');
                new Chart(marcaMaisVendidaCtx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Quantidade Vendida',
                            data: values,
                            backgroundColor: 'rgba(75, 192, 192, 0.2)',
                            borderColor: 'rgba(75, 192, 192, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            }).fail(function(jqXHR, textStatus, errorThrown) {
                console.error('Erro ao buscar dados:', textStatus, errorThrown);
            });
        });
    </script>
</body>

</html>