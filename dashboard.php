<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// Lấy tham số lọc từ URL
$month = $_GET['month'] ?? date('m');
$year = $_GET['year'] ?? date('Y');
$category_id = $_GET['category_id'] ?? '';
$status_id = $_GET['status_id'] ?? '';
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="dashboard-container">
        <h1>Quản Lý Thu Chi Cá Nhân</h1>

        <!-- Bộ lọc -->
        <div class="filter-form">
            <form method="get" action="">
                <label>Tháng:</label>
                <select name="month">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>" <?= $month == $i ? 'selected' : '' ?>>
                            <?= $i ?>
                        </option>
                    <?php endfor; ?>
                </select>

                <label>Năm:</label>
                <select name="year">
                    <?php $current_year = date('Y'); ?>
                    <?php for ($i = $current_year - 5; $i <= $current_year + 5; $i++): ?>
                        <option value="<?= $i ?>" <?= $year == $i ? 'selected' : '' ?>><?= $i ?></option>
                    <?php endfor; ?>
                </select>

                <label>Danh Mục:</label>
                <select name="category_id">
                    <option value="">Tất cả</option>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM categories ORDER BY type DESC");
                    $categories = $stmt->fetchAll();
                    foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= $category_id == $cat['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($cat['name']) ?> (<?= $cat['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu' ?>)
                        </option>
                    <?php endforeach; ?>
                </select>

                <label>Trạng Thái:</label>
                <select name="status_id">
                    <option value="">Tất cả</option>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM transaction_statuses");
                    $statuses = $stmt->fetchAll();
                    foreach ($statuses as $status): ?>
                        <option value="<?= $status['id'] ?>" <?= $status_id == $status['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($status['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">Lọc</button>
                <a href="dashboard.php" class="btn clear">Xóa Lọc</a>
            </form>
        </div>

        <!-- Thống kê tổng quan -->
        <div class="stats">
            <?php
            // Tổng thu nhập (có lọc)
            $income_sql = "SELECT SUM(amount) AS total_income FROM transactions t
                          JOIN categories c ON t.category_id = c.id
                          WHERE t.user_id = ? AND c.type = 'income'";
            $income_params = [$_SESSION['user_id']];

            // Tổng chi tiêu (có lọc)
            $expense_sql = "SELECT SUM(amount) AS total_expense FROM transactions t
                           JOIN categories c ON t.category_id = c.id
                           WHERE t.user_id = ? AND c.type = 'expense'";
            $expense_params = [$_SESSION['user_id']];

            // Thêm điều kiện lọc
            $conditions = [];
            if ($month) {
                $conditions[] = "MONTH(t.date) = ?";
                $income_params[] = $month;
                $expense_params[] = $month;
            }
            if ($year) {
                $conditions[] = "YEAR(t.date) = ?";
                $income_params[] = $year;
                $expense_params[] = $year;
            }
            if ($category_id) {
                $conditions[] = "t.category_id = ?";
                $income_params[] = $category_id;
                $expense_params[] = $category_id;
            }
            if ($status_id) {
                $conditions[] = "t.status_id = ?";
                $income_params[] = $status_id;
                $expense_params[] = $status_id;
            }

            if (!empty($conditions)) {
                $where_clause = " AND " . implode(" AND ", $conditions);
                $income_sql .= $where_clause;
                $expense_sql .= $where_clause;
            }

            $stmt = $pdo->prepare($income_sql);
            $stmt->execute($income_params);
            $total_income = $stmt->fetch()['total_income'] ?? 0;

            $stmt = $pdo->prepare($expense_sql);
            $stmt->execute($expense_params);
            $total_expense = $stmt->fetch()['total_expense'] ?? 0;
            ?>
            <div class="stat-card income">
                <h3>Tổng Thu Nhập</h3>
                <p><?= number_format($total_income, 2) ?> VND</p>
            </div>
            <div class="stat-card expense">
                <h3>Tổng Chi Tiêu</h3>
                <p><?= number_format($total_expense, 2) ?> VND</p>
            </div>
            <div class="stat-card balance">
                <h3>Số Dư</h3>
                <p><?= number_format($total_income - $total_expense, 2) ?> VND</p>
            </div>
        </div>
        <!-- Trong phần nút chức năng -->
        <a href="export_report.php?month=<?= $month ?>&year=<?= $year ?>&category_id=<?= $category_id ?>&status_id=<?= $status_id ?>" 
        class="btn export">📊 Xuất Excel</a>    
        <!-- Bảng giao dịch có lọc -->
        <h2>Giao Dịch</h2>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Danh Mục</th>
                    <th>Loại</th>
                    <th>Số Tiền</th>
                    <th>Ngày</th>
                    <th>Trạng Thái</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Lấy giao dịch có lọc
                $sql = "SELECT t.*, c.name AS category, c.type, s.name AS status 
                       FROM transactions t
                       JOIN categories c ON t.category_id = c.id
                       JOIN transaction_statuses s ON t.status_id = s.id
                       WHERE t.user_id = ?";
                $params = [$_SESSION['user_id']];

                if ($month) {
                    $sql .= " AND MONTH(t.date) = ?";
                    $params[] = $month;
                }
                if ($year) {
                    $sql .= " AND YEAR(t.date) = ?";
                    $params[] = $year;
                }
                if ($category_id) {
                    $sql .= " AND t.category_id = ?";
                    $params[] = $category_id;
                }
                if ($status_id) {
                    $sql .= " AND t.status_id = ?";
                    $params[] = $status_id;
                }

                $sql .= " ORDER BY t.date DESC LIMIT 10";

                $stmt = $pdo->prepare($sql);
                $stmt->execute($params);
                $transactions = $stmt->fetchAll();
                foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= htmlspecialchars($t['category']) ?></td>
                        <td><?= $t['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu' ?></td>
                        <td><?= number_format($t['amount'], 2) ?> VND</td>
                        <td><?= $t['date'] ?></td>
                        <td><?= $t['status'] ?></td>
                        <td>
                            <a href="edit_transaction.php?id=<?= $t['id'] ?>" class="btn edit">Sửa</a>
                            <a href="delete_transaction.php?id=<?= $t['id'] ?>" class="btn delete" onclick="return confirm('Bạn có chắc chắn muốn xóa giao dịch này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Bảng danh mục -->
        <h2>Danh Mục Hiện Tại</h2>
        <table class="transaction-table">
            <thead>
                <tr>
                    <th>Tên Danh Mục</th>
                    <th>Loại</th>
                    <th>Hành Động</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $stmt = $pdo->query("SELECT * FROM categories ORDER BY type DESC");
                $categories = $stmt->fetchAll();
                foreach ($categories as $cat): ?>
                    <tr>
                        <td><?= htmlspecialchars($cat['name']) ?></td>
                        <td><?= $cat['type'] == 'income' ? 'Thu nhập' : 'Chi tiêu' ?></td>
                        <td>
                            <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn edit">Sửa</a>
                            <a href="delete_category.php?id=<?= $cat['id'] ?>" class="btn delete" onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">Xóa</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>