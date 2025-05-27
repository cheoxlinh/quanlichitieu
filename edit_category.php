<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Lấy ID danh mục từ URL
$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Lấy thông tin danh mục
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: dashboard.php");
    exit;
}

// Xử lý cập nhật danh mục
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $type = $_POST['type'];

    $stmt = $pdo->prepare("UPDATE categories SET name = ?, type = ? WHERE id = ?");
    $stmt->execute([$name, $type, $id]);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Danh Mục</title>
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
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Sửa Danh Mục</h2>
        <form method="post" action="">
            <div class="form-group">
                <label>Tên Danh Mục:</label>
                <input type="text" name="name" value="<?= htmlspecialchars($category['name']) ?>" required>
            </div>

            <div class="form-group">
                <label>Loại:</label>
                <select name="type">
                    <option value="income" <?= $category['type'] == 'income' ? 'selected' : '' ?>>Thu nhập</option>
                    <option value="expense" <?= $category['type'] == 'expense' ? 'selected' : '' ?>>Chi tiêu</option>
                </select>
            </div>

            <button type="submit" class="btn-submit">Lưu Thay Đổi</button>
        </form>
    </div>
</body>
</html>