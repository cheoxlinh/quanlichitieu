<?php
session_start();
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username']; // ĐÃ SỬA
    $password = $_POST['password']; // ĐÃ SỬA

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header("Location: ../dashboard.php");
    } else {
        echo "Sai tên đăng nhập hoặc mật khẩu!";
    }
}
?>
<!-- Form đăng nhập -->
<form method="post">
    <input type="text" name="username" required>
    <input type="password" name="password" required>
    <button type="submit">Đăng nhập</button>
</form>