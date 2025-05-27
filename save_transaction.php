<?php
session_start();
require 'config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $category_id = $_POST['category_id'];
    $status_id = $_POST['status_id'];
    $description = $_POST['description'] ?? '';
    $currency = $_POST['currency'] ?? 'VND';

    // Lấy giá trị từ input ẩn (chỉ số thuần)
    $amount = $_POST['amount'] ?? '0';

    // Xử lý ngày/tháng
    $transaction_type = $_POST['transaction_type'];
    $raw_date = $_POST['date'];

    if ($transaction_type === 'monthly') {
        $date = date('Y-m-01', strtotime($raw_date));
    } else {
        $date = $raw_date;
    }

    // Lưu vào cơ sở dữ liệu
    $stmt = $pdo->prepare("INSERT INTO transactions 
                          (user_id, category_id, amount, date, status_id, description, currency)
                          VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $category_id,
        $amount,
        $date,
        $status_id,
        $description,
        $currency
    ]);
    header("Location: dashboard.php");
}
?>