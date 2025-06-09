<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();

// Định nghĩa đường dẫn tuyệt đối đến thư mục gốc của dự án
define('ROOT_PATH', __DIR__);

// Định nghĩa URL gốc của dự án để tạo các liên kết chính xác
$baseUrl = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('BASE_URL', rtrim($baseUrl, '/'));

// Autoloader đơn giản để tự động tải các lớp khi cần
spl_autoload_register(function ($className) {
    // Các đường dẫn tìm kiếm lớp, sử dụng hằng số ROOT_PATH
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

// Khởi tạo bộ định tuyến để xử lý yêu cầu
$router = new Router();