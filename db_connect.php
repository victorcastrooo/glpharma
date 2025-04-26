<?php
require_once __DIR__ . '/vendor/autoload.php';

use Dotenv\Dotenv;

// Carregar as variáveis do .env
$dotenv = Dotenv::createImmutable(__DIR__ . '/');
$dotenv->load();

// Obter as variáveis do .env
$servername = $_ENV['DB_HOST'];
$username = $_ENV['DB_USERNAME'];
$password = $_ENV['DB_PASSWORD'];
$dbname = $_ENV['DB_NAME'];

// Criar conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}
?>
