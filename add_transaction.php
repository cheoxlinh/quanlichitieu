<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $description = $_POST['description'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO transactions (user_id, category_id, amount, date, description)
                          VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$_SESSION['user_id'], $category_id, $amount, $date, $description]);
    header("Location: dashboard.php");
}

// Lấy danh mục
$type = $_GET['type'] ?? 'expense';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE type = ?");
$stmt->execute([$type]);
$categories = $stmt->fetchAll();
?>
<!-- Form thêm giao dịch -->
<form method="post">
    <select name="category_id" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <input type="number" step="0.01" name="amount" placeholder="Số tiền" required>
    <input type="date" name="date" required>
    <textarea name="description" placeholder="Mô tả (tùy chọn)"></textarea>
    <button type="submit">Lưu</button>
</form>