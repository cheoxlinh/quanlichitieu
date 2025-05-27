<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
}

// Tổng thu nhập
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_income FROM transactions t 
                      JOIN categories c ON t.category_id = c.id
                      WHERE t.user_id = ? AND c.type = 'income'");
$stmt->execute([$_SESSION['user_id']]);
$total_income = $stmt->fetch()['total_income'] ?? 0;

// Tổng chi tiêu
$stmt = $pdo->prepare("SELECT SUM(amount) AS total_expense FROM transactions t 
                      JOIN categories c ON t.category_id = c.id
                      WHERE t.user_id = ? AND c.type = 'expense'");
$stmt->execute([$_SESSION['user_id']]);
$total_expense = $stmt->fetch()['total_expense'] ?? 0;

// Danh sách giao dịch
$stmt = $pdo->prepare("SELECT t.*, c.name AS category, c.type FROM transactions t
                      JOIN categories c ON t.category_id = c.id
                      WHERE t.user_id = ? ORDER BY t.date DESC LIMIT 10");
$stmt->execute([$_SESSION['user_id']]);
$transactions = $stmt->fetchAll();
?>

<h2>Tổng thu nhập: <?= number_format($total_income, 2) ?> VND</h2>
<h2>Tổng chi tiêu: <?= number_format($total_expense, 2) ?> VND</h2>
<h2>Số dư: <?= number_format($total_income - $total_expense, 2) ?> VND</h2>

<!-- Form xuất Excel -->
<form method="get" action="export_report.php">
    <label>Tháng:</label>
    <select name="month">
        <?php for ($i = 1; $i <= 12; $i++): ?>
            <option value="<?= $i ?>" <?= date('m') == $i ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <label>Năm:</label>
    <select name="year">
        <?php $current_year = date('Y'); ?>
        <?php for ($i = $current_year - 5; $i <= $current_year + 5; $i++): ?>
            <option value="<?= $i ?>" <?= $current_year == $i ? 'selected' : '' ?>><?= $i ?></option>
        <?php endfor; ?>
    </select>
    <button type="submit">Xuất Excel</button>
</form>

<!-- Bảng giao dịch -->
<table>
    <tr><th>Danh mục</th><th>Loại</th><th>Số tiền</th><th>Ngày</th></tr>
    <?php foreach ($transactions as $t): ?>
        <tr>
            <td><?= htmlspecialchars($t['category']) ?></td>
            <td><?= $t['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu' ?></td>
            <td><?= number_format($t['amount'], 2) ?> VND</td>
            <td><?= $t['date'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>