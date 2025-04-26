<?php
session_start();
include '../../db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['login'])) {
        // Login
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $conn->real_escape_string($_POST['senha']);

        $result = $conn->query("SELECT id, nome, email FROM clientes WHERE email = '$email' AND senha = '$senha'");
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $_SESSION['cliente_id'] = $user['id'];
            $_SESSION['cliente_nome'] = $user['nome'];
            $_SESSION['cliente_email'] = $user['email'];
            header('Location: checkout.php');
            exit();
        } else {
            $erro = "Email ou senha inválidos.";
        }
    } elseif (isset($_POST['cadastro'])) {
        // Cadastro
        $nome = $conn->real_escape_string($_POST['nome']);
        $email = $conn->real_escape_string($_POST['email']);
        $senha = $conn->real_escape_string($_POST['senha']);

        $conn->query("INSERT INTO clientes (nome, email, senha) VALUES ('$nome', '$email', '$senha')");
        header('Location: login.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login ou Cadastro</title>
<link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <h1>Login</h1>
    <?php if (isset($erro)): ?>
        <p style="color: red;"><?= htmlspecialchars($erro) ?></p>
    <?php endif; ?>
    <form action="login.php" method="post">
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit" name="login">Entrar</button>
    </form>

    <h1>Cadastro</h1>
    <form action="login.php" method="post">
        <div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required>
        </div>
        <div>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div>
            <label for="senha">Senha:</label>
            <input type="password" id="senha" name="senha" required>
        </div>
        <button type="submit" name="cadastro">Cadastrar</button>
    </form>
</body>
</html>