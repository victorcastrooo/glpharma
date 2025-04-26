<?php
require 'config/routes.php';

// Obter o caminho da URL atual
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Resolver a rota
$page = resolveRoute($path, $routes);

// Incluir o arquivo correspondente
if (file_exists($page)) {
    include $page;
} else {
    include 'view/404.php'; // Página de erro 404
}
?>

