<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Monthly Report</h1>
    </div>
    <div class="content-box">
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th style="text-align: right;">Total Revenue</th>
                    <th style="text-align: right;">Total Expense</th>
                    <th style="text-align: right;">Net Income</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Hàm định dạng tiền tệ (luôn là VND cho trang báo cáo)
                function format_vnd($amount) {
                    return number_format($amount) . 'đ';
                }

                foreach ($monthlyData as $data): 
                    $net_income = $data['total_revenue'] - $data['total_expense'];
                    // Tạo đối tượng ngày tháng để lấy tên tháng
                    $dateObj = DateTime::createFromFormat('!m', $data['month']);
                    $monthName = $dateObj->format('F'); // Tên tháng đầy đủ (e.g., June)
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($monthName . ' ' . $data['year']); ?></td>
                    <td style="text-align: right; color: var(--revenue-color);"><?php echo format_vnd($data['total_revenue']); ?></td>
                    <td style="text-align: right; color: var(--expense-color);"><?php echo format_vnd($data['total_expense']); ?></td>
                    <td style="text-align: right; font-weight: bold; color: <?php echo $net_income >= 0 ? 'var(--success-color)' : 'var(--expense-color)'; ?>">
                        <?php echo format_vnd($net_income); ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>