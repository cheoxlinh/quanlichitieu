<?php
session_start();
require './config/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = $_POST['type'];
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status_id = $_POST['status_id'];
    $description = $_POST['description'] ?? '';

    $stmt = $pdo->prepare("INSERT INTO transactions 
                          (user_id, category_id, amount, date, status_id, description)
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $category_id,
        $amount,
        $date,
        $status_id,
        $description
    ]);
    header("Location: ../dashboard.php");
}
?>