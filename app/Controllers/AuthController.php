<?php
class AuthController extends Controller {
    public function __construct() {
        // Ghi đè constructor để không chạy checkAuth()
    }

    public function login() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/transactions');
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
                header('Location: ' . BASE_URL . '/transactions');
                exit();
            } else {
                header('Location: ' . BASE_URL . '/auth/login');
                exit();
            }
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL . '/auth/login');
        exit();
    }
}