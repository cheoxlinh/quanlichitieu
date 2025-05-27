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

// Lấy thông tin giao dịch
$stmt = $pdo->prepare("SELECT t.*, c.type AS category_type FROM transactions t
                      JOIN categories c ON t.category_id = c.id
                      WHERE t.id = ? AND t.user_id = ?");
$stmt->execute([$id, $_SESSION['user_id']]);
$transaction = $stmt->fetch();

if (!$transaction) {
    header("Location: dashboard.php");
    exit;
}

// Lấy danh mục và trạng thái
$type = $transaction['category_type'];
$stmt = $pdo->prepare("SELECT * FROM categories WHERE type = ?");
$stmt->execute([$type]);
$categories = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM transaction_statuses");
$statuses = $stmt->fetchAll();

// Xử lý cập nhật giao dịch
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category_id = $_POST['category_id'];
    $amount = $_POST['amount'];
    $date = $_POST['date'];
    $status_id = $_POST['status_id'];
    $description = $_POST['description'] ?? '';

    $stmt = $pdo->prepare("UPDATE transactions SET 
                          category_id = ?, 
                          amount = ?, 
                          date = ?, 
                          status_id = ?, 
                          description = ? 
                          WHERE id = ?");
    $stmt->execute([$category_id, $amount, $date, $status_id, $description, $id]);
    header("Location: dashboard.php");
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa Giao Dịch</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-container {
            max-width: 600px;
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
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 16px;
        }

        .currency-input {
            position: relative;
        }

        .currency-input::after {
            content: "VND";
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #888;
            pointer-events: none;
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
        <h2>Sửa Giao Dịch</h2>
        <form method="post" action="" onsubmit="removeCurrencyFormat()">
            <input type="hidden" name="type" value="<?= $transaction['category_type'] ?>">

            <div class="form-group">
                <label>Danh Mục:</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $transaction['category_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group currency-input">
                <label>Số Tiền:</label>
                <input type="text" id="amount" name="amount" value="<?= number_format($transaction['amount'], 0, '', '.') ?>" required oninput="formatCurrency(this)">
            </div>

            <div class="form-group">
                <label>Ngày:</label>
                <input type="date" name="date" value="<?= $transaction['date'] ?>" required>
            </div>

            <div class="form-group">
                <label>Trạng Thái:</label>
                <select name="status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= $status['id'] == $transaction['status_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Mô Tả (tùy chọn):</label>
                <textarea name="description" rows="4"><?= htmlspecialchars($transaction['description']) ?></textarea>
            </div>

            <button type="submit" class="btn-submit">Lưu Thay Đổi</button>
        </form>
    </div>

    <script>
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) {
                input.value = parseInt(value).toLocaleString('vi-VN');
            }
        }

        function removeCurrencyFormat() {
            const amountInput = document.getElementById('amount');
            amountInput.value = amountInput.value.replace(/[^0-9]/g, '');
        }
    </script>
</body>
</html>