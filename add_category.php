<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("INSERT INTO categories (name, type) VALUES (?, ?)");
    $stmt->execute([$name, $type]);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Danh Mục</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 500px;
            margin: 50px auto;
            padding: 30px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
        }

        .form-group input,
        .form-group select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .btn-submit {
            background-color: #28a745;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Thêm Danh Mục</h2>
        <form method="post" action="">
            <div class="form-group">
                <label>Tên Danh Mục:</label>
                <input type="text" name="name" required>
            </div>

            <div class="form-group">
                <label>Loại:</label>
                <select name="type">
                    <option value="income">Thu nhập</option>
                    <option value="expense">Chi tiêu</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Lưu Danh Mục</button>
        </form>
    </div>
</body>
</html>