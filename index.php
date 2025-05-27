<?php
session_start();

// Kiểm tra nếu đã đăng nhập, chuyển đến dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản Lý Thu Chi Cá Nhân</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <h1>Chào mừng bạn đến với ứng dụng quản lý thu chi!</h1>
        <p>Hãy <a href="auth/login.php">đăng nhập</a> hoặc <a href="auth/register.php">đăng ký</a> để bắt đầu.</p>
    </div>
</body>
</html>