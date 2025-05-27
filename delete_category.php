<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: dashboard.php");
    exit;
}

// Kiểm tra xem danh mục có đang được sử dụng không
$stmt = $pdo->prepare("SELECT COUNT(*) FROM transactions WHERE category_id = ?");
$stmt->execute([$id]);
if ($stmt->fetchColumn() > 0) {
    echo "Không thể xóa danh mục này vì nó đang được sử dụng trong giao dịch.";
    exit;
}

// Xóa danh mục
$stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$id]);

header("Location: dashboard.php");
?>