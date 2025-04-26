<!-- filepath: c:\xampp\htdocs\glpharma-main\view\dashboard\editar_produto.php -->
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit();
}
require '../../db_connect.php';

$produto_editar = null;
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT * FROM produto WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $produto_editar = $result->fetch_assoc();
    if (!$produto_editar) {
        echo "Produto não encontrado.";
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="../css/style_admin.css">
</head>
<body>
<div class="container mt-5">
    <h2>Editar Produto</h2>
    <form action="../../services/add_produto.php" method="post" enctype="multipart/form-data">
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
            <label for="estoque"><i class="fas fa-boxes"></i> Estoque</label>
            <input type="number" class="form-control" id="estoque" name="estoque" 
                   value="<?php echo $produto_editar['estoque'] ?? ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="descricao"><i class="fas fa-align-left"></i> Descrição</label>
            <textarea class="form-control" id="descricao" name="descricao" rows="3" required><?php echo $produto_editar['descricao'] ?? ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="principio_ativo"><i class="fas fa-flask"></i> Princípio Ativo</label>
            <input type="text" class="form-control" id="principio_ativo" name="principio_ativo" 
                   value="<?php echo $produto_editar['principio_ativo'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="dosagem"><i class="fas fa-prescription-bottle"></i> Dosagem</label>
            <input type="text" class="form-control" id="dosagem" name="dosagem" 
                   value="<?php echo $produto_editar['dosagem'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="forma_farmaceutica"><i class="fas fa-pills"></i> Forma Farmacêutica</label>
            <input type="text" class="form-control" id="forma_farmaceutica" name="forma_farmaceutica" 
                   value="<?php echo $produto_editar['forma_farmaceutica'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label for="foto"><i class="fas fa-image"></i> Foto</label>
            <input type="file" class="form-control" id="foto" name="foto">
        </div>
        <?php if ($produto_editar): ?>
            <input type="hidden" name="id" value="<?php echo $produto_editar['id']; ?>">
        <?php endif; ?>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Salvar</button>
        <a href="../dashboard.php?page=produtos" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>