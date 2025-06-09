<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// **BỔ SUNG PHẦN NÀY**

// Định nghĩa đường dẫn tuyệt đối đến thư mục gốc của dự án (quanlichitieu)
// dirname(__DIR__) sẽ trỏ lên một cấp, tức là từ 'public' lên 'quanlichitieu'
define('ROOT_PATH', dirname(__DIR__));

// Định nghĩa URL gốc của dự án.
// $_SERVER['SCRIPT_NAME'] sẽ là /quanlichitieu/public/index.php
// Chúng ta muốn BASE_URL là /quanlichitieu
$baseUrl = str_replace('/public/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', rtrim($baseUrl, '/'));


// Autoloader đơn giản để tải các lớp
spl_autoload_register(function ($className) {
    // Cập nhật đường dẫn để sử dụng ROOT_PATH
    $paths = [
        ROOT_PATH . '/core/',
        ROOT_PATH . '/app/Controllers/',
        ROOT_PATH . '/app/Models/'
    ];
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