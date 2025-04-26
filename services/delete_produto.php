<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}
require '../db_connect.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("DELETE FROM produto WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param('i', $id);
        if ($stmt->execute()) {
            header("Location: ../view/dashboard.php?page=produtos");
            exit();
        } else {
            echo "Erro ao excluir marca.";
        }
    } else {
        echo "Erro ao preparar a declaração.";
    }
}
?>
