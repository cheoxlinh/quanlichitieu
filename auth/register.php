<?php
require '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    if ($stmt->execute([$username, $password])) {
        header("Location: login.php");
    } else {
        echo "Đăng ký thất bại!";
    }
}
?>
<!-- Form đăng ký -->
<form method="post">
    <input type="text" name="username" required>
    <input type="password" name="password" required>
    <button type="submit">Đăng ký</button>
</form>