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

// Xóa giao dịch
$stmt = $pdo->prepare("DELETE FROM transactions WHERE id = ? AND user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);

header("Location: dashboard.php");
?>