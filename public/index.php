<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Autoloader đơn giản để tải các lớp
spl_autoload_register(function ($className) {
    $paths = ['../core/', '../app/Controllers/', '../app/Models/'];
    foreach ($paths as $path) {
        $file = $path . $className . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Chạy bộ định tuyến
$router = new Router();