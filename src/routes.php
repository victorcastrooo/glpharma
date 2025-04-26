<?php
// src/routes.php

require_once 'controllers/LoginController.php';

function route($url, $method) {
    $routes = [
        '/' => 'LoginController@index'
    ];

    if (array_key_exists($url, $routes)) {
        list($controller, $action) = explode('@', $routes[$url]);
        call_user_func([new $controller, $action]);
    } else {
        http_response_code(404);
        echo "Page not found.";
    }
}