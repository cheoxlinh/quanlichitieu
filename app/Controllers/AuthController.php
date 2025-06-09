<?php
class AuthController extends Controller {

    // THÊM HÀM NÀY VÀO
    // Hàm này sẽ ghi đè constructor của lớp Controller cha,
    // ngăn không cho checkAuth() tự động chạy với controller này.
    public function __construct() {
        // Để trống, không làm gì cả
    }

    public function login() {
        // Nếu người dùng đã đăng nhập rồi thì cho vào trang chính luôn
        if (isset($_SESSION['user_id'])) {
            header('Location: /quan-ly-giao-dich-mvc/public/transactions');
            exit();
        }
        $this->view('auth/login');
    }

    public function authenticate() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userModel = $this->model('User');
            $user = $userModel->findByUsername($_POST['username']);

            if ($user && password_verify($_POST['password'], $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                // Sửa đường dẫn chuyển hướng sau khi login thành công
                header('Location: /quan-ly-giao-dich-mvc/public/transactions');
                exit(); // Thêm exit() để dừng thực thi ngay lập tức
            } else {
                // Sửa đường dẫn chuyển hướng khi login thất bại
                header('Location: /quan-ly-giao-dich-mvc/public/auth/login');
                exit(); // Thêm exit()
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        // Sửa đường dẫn chuyển hướng sau khi logout
        header('Location: /quan-ly-giao-dich-mvc/public/auth/login');
        exit(); // Thêm exit()
    }
}