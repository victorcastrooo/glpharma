<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
require '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM marca WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        try {
            if ($stmt->execute()) {
                header("Location: ../view/dashboard.php?page=marcas");
                exit();
            } else {
                throw new Exception("Erro ao excluir marca.");
            }
        } catch (mysqli_sql_exception $e) {
            // Verifica se o erro é de restrição de chave estrangeira
            if ($e->getCode() == 1451) {
                echo "<script>
                    alert('A marca não pode ser excluída porque está associada a produtos cadastrados.');
                    window.location.href = '../view/dashboard.php?page=marcas';
                </script>";
            } else {
                echo "Erro ao excluir marca: " . $e->getMessage();
            }
        }
    } else {
        echo "Erro ao preparar a declaração.";
    }
}
?>
