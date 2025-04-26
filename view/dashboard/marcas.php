<?php

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
require 'db_connect.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Marcas</title>
    <!-- Link para o Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Link para o Font Awesome CSS -->
    <link rel="stylesheet" href="css/style_admin.css">
    <script src="js/script.js"></script>
</head>
<body>

<div class="dashboard">
    <div class="content">
    <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Lista de Produtos</h2>
            <form class="form-inline" method="GET" action="">
                <input class="form-control mr-sm-2" type="search" placeholder="Pesquisar" aria-label="Pesquisar" name="search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit"><i class="fas fa-search"></i></button>
            </form>
        </div>
        <table class="table centered-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Logomarca</th>
                    <th>Nome</th>
                    <th>Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                        <?php
                $stmt = $conn->prepare("SELECT id, logomarca, nomemarca FROM marca");
                $stmt->execute();
                $result = $stmt->get_result();
                
                while ($marca = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $marca['id'] . "</td>";
                    echo "<td><img src='" . $marca['logomarca'] . "' alt='Logomarca' style='width: auto; height: 50px;'></td>";
                    echo "<td>" . $marca['nomemarca'] . "</td>";
                    echo "<td>
                    <a href='edit_marca.php?id=" . $marca['id'] . "' class='btn btn-sm btn-info'><i class='fas fa-edit'></i></a>
                    <a href='view_marca.php?id=" . $marca['id'] . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                    <a href='../services/delete_marca.php?id=" . $marca['id'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></a>
                    
                    
                            </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
        </div>
        
        <button type="button" class="btn btn-primary add-btn" data-toggle="modal" data-target="#addMarcaModal">
    
        <i class="fas fa-plus"></i>
    </button>
    <!-- Modal -->

<div class="modal fade" id="addMarcaModal" tabindex="-1" aria-labelledby="addMarcaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="services/add_marca.php" method="post" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="addMarcaModalLabel"><i class="fas fa-plus"></i> Adicionar Nova Marca</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nomemarca"><i class="fas fa-tag"></i> Nome da Marca</label>
                        <input type="text" class="form-control" id="nomemarca" name="nomemarca" required>
                    </div>
                    <div class="form-group">
                        <label for="logomarca"><i class="fas fa-image"></i> Logomarca</label>
                        <input type="file" class="form-control" id="logomarca" name="logomarca" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Scripts do Bootstrap e do Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
