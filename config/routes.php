<?php

$routes = [
    '/' => 'view/shop/index.php',
    '/login' => 'view/dashboard/login.php',
    '/dashboard' => 'view/dashboard.php',
    '/marcas' => 'view/marcas.php',
    '/admin' => 'view/dashboard.php',
    '/admin/login' => 'view/dashboard/login.php',
    '/admin/marcas' => 'view/dashboard.php?page=marcas',
    // Adicione mais rotas conforme necessário
];

function resolveRoute($path, $routes) {
    // Remover o prefixo do subdiretório (ajuste conforme necessário)
    $basePath = '/glpharma';
    if (strpos($path, $basePath) === 0) {
        $path = substr($path, strlen($basePath));
    }


    if (array_key_exists($path, $routes)) {
        return $routes[$path];
    }
    return 'view/404.php'; // Página de erro 404
}
?>