<?php
session_start();
require './config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ./auth/login.php");
    exit;
}

$type = $_GET['type'] ?? 'expense';
$stmt = $pdo->prepare("SELECT * FROM categories WHERE type = ?");
$stmt->execute([$type]);
$categories = $stmt->fetchAll();

$stmt = $pdo->query("SELECT * FROM transaction_statuses");
$statuses = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm Giao Dịch</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .form-group label { font-weight: bold; }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        .btn-submit {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2><?= $type == 'income' ? 'Thêm Thu Nhập' : 'Thêm Chi Tiêu' ?></h2>
        <form method="post" action="save_transaction.php" onsubmit="removeCurrencyFormat()">
            <input type="hidden" name="type" value="<?= htmlspecialchars($type) ?>">

            <!-- Lựa chọn loại giao dịch -->
            <div class="form-group">
                <label>Loại Giao Dịch:</label>
                <select name="transaction_type" id="transactionType" onchange="toggleDateInput()">
                    <option value="daily">Theo Ngày</option>
                    <option value="monthly">Theo Tháng</option>
                </select>
            </div>

            <!-- Ô nhập ngày hoặc tháng -->
            <div class="form-group">
                <label id="dateLabel">Ngày:</label>
                <input type="date" id="dateInput" name="date" required>
            </div>

            <div class="form-group">
                <label>Danh Mục:</label>
                <select name="category_id" required>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Lựa chọn loại tiền tệ -->
            <div class="form-group">
                <label>Loại Tiền:</label>
                <select name="currency" id="currencySelect" onchange="updateCurrencySymbol()">
                    <option value="VND">VND</option>
                    <option value="USD">USD</option>
                </select>
            </div>

            <div class="form-group currency-input">
                <label>Số Tiền (<span id="currencySymbol">VND</span>):</label>
                <input type="text" id="amount" name="amount" required oninput="formatCurrency(this)">
            </div>

            <div class="form-group">
                <label>Trạng Thái:</label>
                <select name="status_id">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>"><?= htmlspecialchars($status['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label>Mô Tả (tùy chọn):</label>
                <textarea name="description" rows="4"></textarea>
            </div>

            <button type="submit" class="btn-submit">Lưu Giao Dịch</button>
        </form>
    </div>

    <script>
        // Thay đổi giữa nhập ngày/tháng
        function toggleDateInput() {
            const type = document.getElementById('transactionType').value;
            const dateInput = document.getElementById('dateInput');
            const dateLabel = document.getElementById('dateLabel');

            if (type === 'daily') {
                dateInput.type = 'date';
                dateLabel.textContent = 'Ngày:';
            } else {
                dateInput.type = 'month';
                dateLabel.textContent = 'Tháng:';
            }
        }

        // Cập nhật ký hiệu tiền tệ
        function updateCurrencySymbol() {
            const currency = document.getElementById('currencySelect').value;
            document.getElementById('currencySymbol').textContent = currency;
            formatCurrency(document.getElementById('amount')); // Định dạng lại số tiền nếu có
        }

        // Định dạng tiền tệ khi nhập
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9.]/g, '');
            const currency = document.getElementById('currencySelect').value;

            if (value) {
                if (currency === 'VND') {
                    input.value = parseInt(value).toLocaleString('vi-VN') + ' ₫';
                } else if (currency === 'USD') {
                    input.value = parseFloat(value).toLocaleString('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }) + ' $';
                }
            }
        }

        // Loại bỏ định dạng trước khi submit
        function removeCurrencyFormat() {
            const amountInput = document.getElementById('amount');
            amountInput.value = amountInput.value.replace(/[^0-9.]/g, '');
        }
    </script>
</body>
</html>