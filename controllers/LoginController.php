<?php
// src/controllers/HomeController.php

class LoginController {
    public function index() {
        $this->render('login');
    }

    private function render($view, $data = []) {
        extract($data);
        include __DIR__ . 'view/' . $view . '.php';
    }
}