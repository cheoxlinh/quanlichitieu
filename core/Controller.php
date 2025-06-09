<?php
class Controller {
    public function view($view, $data = []) {
        // Biến các key của mảng thành các biến riêng lẻ
        // Ví dụ: $data['transactions'] sẽ trở thành biến $transactions
        extract($data); 
        
        // Đường dẫn tới file view
        $viewPath = "../app/Views/{$view}.php";

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View '{$view}' not found.");
        }
    }

    public function model($model) {
        $modelPath = "../app/Models/{$model}.php";
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $model();
        } else {
            die("Model '{$model}' not found.");
        }
    }
    
    public function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /quanlichitieu/public/auth/login');
            exit();
        }
    }
}