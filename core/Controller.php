<?php
class Controller {
    public function view($view, $data = []) {
        // Biến các key của mảng $data thành các biến riêng lẻ
        extract($data); 
        
        // Tạo đường dẫn tuyệt đối đến tệp view
        $viewPath = ROOT_PATH . "/app/Views/{$view}.php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '{$view}' not found.");
        }
    }

    public function model($model) {
        // Tạo đường dẫn tuyệt đối đến tệp model
        $modelPath = ROOT_PATH . "/app/Models/{$model}.php";
        if (file_exists($modelPath)) {
            // Autoloader đã xử lý việc require, chỉ cần khởi tạo
            return new $model();
        } else {
            die("Model '{$model}' not found.");
        }
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            // Sử dụng BASE_URL để chuyển hướng đến trang đăng nhập
            header('Location: ' . BASE_URL . '/auth/login');
            exit();
        }
    }
}