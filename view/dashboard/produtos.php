<?php
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
require 'db_connect.php';

$produto_editar = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM produto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto_editar = $result->fetch_assoc();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Produtos</title>
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
                    <th>Foto</th>
                    <th>Nome</th>
                    <th>Tipo</th>
                    <th>Marca</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $conn->prepare("SELECT p.id, p.nome, t.tipo, m.nomemarca, p.preco, p.descricao, p.imagem FROM produto p JOIN tipo t ON p.tipo = t.id JOIN marca m ON p.marca_id = m.id");
                $stmt->execute();
                $result = $stmt->get_result();

                while ($produto = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $produto['id'] . "</td>";
                    echo "<td><img src='" . $produto['imagem'] . "' alt='Foto do Produto' style='width: 50px; height: 50px;'></td>";
                    echo "<td>" . $produto['nome'] . "</td>";
                    echo "<td>" . $produto['tipo'] . "</td>";
                    echo "<td>" . $produto['nomemarca'] . "</td>";
                    echo "<td>" . $produto['preco'] . "</td>";
                    echo "<td>" . $produto['descricao'] . "</td>";
                    echo "<td>
                        <a href='dashboard/editar_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-info'><i class='fas fa-edit'></i></a>
                        <a href='view_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-primary'><i class='fas fa-eye'></i></a>
                        <a href='../services/delete_produto.php?id=" . $produto['id'] . "' class='btn btn-sm btn-danger'><i class='fas fa-trash-alt'></i></a>
                    </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
    <button type="button" class="btn btn-primary add-btn" data-toggle="modal" data-target="#addProdutoModal">
        <i class="fas fa-plus"></i>
    </button>
    <!-- Modal -->
    <div class="modal fade" id="addProdutoModal" tabindex="-1" aria-labelledby="addProdutoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="/services/add_produto.php" method="post" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addProdutoModalLabel"><i class="fas fa-plus"></i> Adicionar Novo Produto</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="nome"><i class="fas fa-tag"></i> Nome do Produto</label>
                            <input type="text" class="form-control" id="nome" name="nome" 
                                   value="<?php echo $produto_editar['nome'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="tipo"><i class="fas fa-list"></i> Tipo</label>
                            <select class="form-control" id="tipo" name="tipo" required>
                                <?php
                                $stmt_tipo = $conn->prepare("SELECT id, tipo FROM tipo");
                                $stmt_tipo->execute();
                                $result_tipo = $stmt_tipo->get_result();
                                while ($tipo = $result_tipo->fetch_assoc()) {
                                    $selected = ($produto_editar && $produto_editar['tipo'] == $tipo['id']) ? 'selected' : '';
                                    echo "<option value='" . $tipo['id'] . "' $selected>" . $tipo['tipo'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="marca"><i class="fas fa-building"></i> Marca</label>
                            <select class="form-control" id="marca" name="marca" required>
                                <?php
                                $stmt_marca = $conn->prepare("SELECT id, nomemarca FROM marca");
                                $stmt_marca->execute();
                                $result_marca = $stmt_marca->get_result();
                                while ($marca = $result_marca->fetch_assoc()) {
                                    $selected = ($produto_editar && $produto_editar['marca_id'] == $marca['id']) ? 'selected' : '';
                                    echo "<option value='" . $marca['id'] . "' $selected>" . $marca['nomemarca'] . "</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="preco"><i class="fas fa-dollar-sign"></i> Preço</label>
                            <input type="number" step="0.01" class="form-control" id="preco" name="preco" 
                                   value="<?php echo $produto_editar['preco'] ?? ''; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="descricao"><i class="fas fa-align-left"></i> Descrição</label>
                            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo $produto_editar['descricao'] ?? ''; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="foto"><i class="fas fa-image"></i> Foto</label>
                            <input type="file" class="form-control" id="foto" name="foto" required>
                        </div>
                        <div class="form-group">
                            <label for="principio_ativo"><i class="fas fa-flask"></i> Princípio Ativo</label>
                            <input type="text" class="form-control" id="principio_ativo" name="principio_ativo" required>
                        </div>
                        <div class="form-group">
                            <label for="dosagem"><i class="fas fa-prescription-bottle"></i> Dosagem</label>
                            <input type="text" class="form-control" id="dosagem" name="dosagem">
                        </div>
                        <div class="form-group">
                            <label for="forma_farmaceutica"><i class="fas fa-pills"></i> Forma Farmacêutica</label>
                            <input type="text" class="form-control" id="forma_farmaceutica" name="forma_farmaceutica">
                        </div>
                        <div class="form-group">
                            <label for="tipo"><i class="fas fa-list"></i> Tipo</label>
                            <select class="form-control" id="tipo" name="tipo">
                                <option value="tarja preta">Tarja Preta</option>
                                <option value="tarja vermelha">Tarja Vermelha</option>
                                <option value="isento de prescrição">Isento de Prescrição</option>
                                <option value="genérico">Genérico</option>
                                <option value="outros">Outros</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="necessita_receita"><i class="fas fa-file-prescription"></i> Necessita Receita?</label>
                            <select class="form-control" id="necessita_receita" name="necessita_receita">
                                <option value="1">Sim</option>
                                <option value="0">Não</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="peso"><i class="fas fa-weight"></i> Peso (kg)</label>
                            <input type="number" step="0.001" class="form-control" id="peso" name="peso" required>
                        </div>
                        <div class="form-group">
                            <label for="altura"><i class="fas fa-ruler-vertical"></i> Altura (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="altura" name="altura" required>
                        </div>
                        <div class="form-group">
                            <label for="largura"><i class="fas fa-ruler-horizontal"></i> Largura (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="largura" name="largura" required>
                        </div>
                        <div class="form-group">
                            <label for="comprimento"><i class="fas fa-ruler-combined"></i> Comprimento (cm)</label>
                            <input type="number" step="0.01" class="form-control" id="comprimento" name="comprimento" required>
                        </div>
                        <div class="form-group">
                            <label for="fabricante"><i class="fas fa-industry"></i> Fabricante</label>
                            <input type="text" class="form-control" id="fabricante" name="fabricante">
                        </div>
                        <div class="form-group">
                            <label for="validade"><i class="fas fa-calendar-alt"></i> Validade</label>
                            <input type="date" class="form-control" id="validade" name="validade">
                        </div>
                        <div class="form-group">
                            <label for="registro_anvisa"><i class="fas fa-id-card"></i> Registro ANVISA</label>
                            <input type="text" class="form-control" id="registro_anvisa" name="registro_anvisa">
                        </div>
                        <div class="form-group">
                            <label for="preco"><i class="fas fa-dollar-sign"></i> Preço</label>
                            <input type="number" step="0.01" class="form-control" id="preco" name="preco" required>
                        </div>
                        <div class="form-group">
                            <label for="estoque"><i class="fas fa-boxes"></i> Estoque</label>
                            <input type="number" class="form-control" id="estoque" name="estoque" required>
                        </div>
                        <?php if ($produto_editar): ?>
                            <input type="hidden" name="id" value="<?php echo $produto_editar['id']; ?>">
                        <?php endif; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Fechar</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts do Bootstrap e do Font Awesome -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

