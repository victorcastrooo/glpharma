<?php
session_start();

// Incluir arquivo de conexão
include '../db_connect.php';

// Verificar se o método de requisição é POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Pegar dados do formulário - verificando se existem para evitar warnings
    $user = isset($_POST['username']) ? $_POST['username'] : '';
    $pass = isset($_POST['password']) ? $_POST['password'] : '';

    // Verificar se os campos não estão vazios
    if (empty($user) || empty($pass)) {
        echo "Por favor, preencha todos os campos.";
        exit();
    }

    // Proteger contra SQL Injection
    $user = $conn->real_escape_string($user);
    $pass = $conn->real_escape_string($pass);

    // Verificar se o usuário existe
    $sql = "SELECT * FROM usuarios WHERE email = '$user' AND senha = '$pass'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Login bem-sucedido
        $_SESSION['username'] = $user;
        echo "Login successful!";
        // Redirecionar para uma página protegida
        header("Location: ../view/dashboard.php");
        exit();
    } else {
        // Login falhou
        echo "Invalid username or password.";
    }
} else {
    // Se não for um POST, redirecionar para a página de login
    header("Location: ../index.php");
    exit();
}

$conn->close();
?>