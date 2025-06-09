<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Transactions</h1>
        <a href="<?php echo BASE_URL; ?>/transactions/create" class="button">New transaction</a>
    </div>
    <div class="content-box" style="display: flex; gap: 20px;">
        <div style="flex: 3;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Tag</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    // Helper function để định dạng tiền tệ
                    // Sửa lại hàm format_currency trong transactions/index.php
                    function format_currency($amount, $currency = 'VND') {
                        if ($currency == 'USD') {
                            return number_format($amount, 0). ' '.'USD';
                        }
                        return number_format($amount) .' '. 'VNĐ';
                    }
                    foreach ($transactions as $t): 
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t['id']); ?></td>
                        <td><?php echo format_currency($t['amount'], $t['currency']); ?></td>
                        <td>
                            <?php
                                // Đặt class CSS mặc định là 'expense'
                                $type_class = 'expense'; 
                                // Nếu là 'Revenue', thì đổi class thành 'revenue'
                                if ($t['transaction_type'] == 'Revenue') {
                                    $type_class = 'revenue';
                                }
                            ?>
                            <span class="pill <?php echo $type_class; ?>">
                                <?php echo htmlspecialchars($t['transaction_type']); ?>
                            </span>
                        </td>
                        <td><span class="pill order"><?php echo htmlspecialchars($t['tag_name']); ?></span></td>
                        <td><span class="pill success"><?php echo htmlspecialchars($t['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($t['note']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($t['created_at'])); ?></td>
                        <td><a href="<?php echo BASE_URL; ?>/transactions/edit/<?php echo $t['id']; ?>">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="1" style="font-weight: bold; text-align: right;">Total:</td>
                        <td colspan="7" style="font-weight: bold; font-size: 16px;">
                            
                            <?php echo number_format($total_amount) . 'đ'; ?>
                        </td>
                    </tr>
                </tfoot>
            </table>
            <div class="pagination" style="margin-top: 20px; text-align: right;">
                <?php if ($total_pages > 1): ?>
                    <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                        <?php
                            // Giữ lại các filter hiện tại khi chuyển trang
                            $query_params = http_build_query(array_merge($filters, ['page' => $i]));
                        ?>
                        <a href="?<?php echo $query_params; ?>" class="button <?php echo ($i == $current_page) ? '' : 'cancel'; ?>" style="margin-left: 5px;">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="filters" style="flex: 1;">
            <h3>Filters</h3>
            <form action="" method="GET">
                <div class="form-group">
                    <label for="created_date">Created Date</label>
                    <input type="date" name="created_date" id="created_date" value="<?php echo htmlspecialchars($filters['created_date'] ?? ''); ?>">
                </div>
                <div class="form-group">
                    <label for="transaction_type">Transaction type</label>
                    <select name="transaction_type" id="transaction_type">
                        <option value="">All</option>
                        <option value="Revenue" <?php echo (isset($filters['transaction_type']) && $filters['transaction_type'] == 'Revenue') ? 'selected' : ''; ?>>Revenue</option>
                        <option value="Expense" <?php echo (isset($filters['transaction_type']) && $filters['transaction_type'] == 'Expense') ? 'selected' : ''; ?>>Expense</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select name="status" id="status">
                        <option value="">All</option>
                        <option value="Success" <?php echo (isset($filters['status']) && $filters['status'] == 'Success') ? 'selected' : ''; ?>>Success</option>
                        <option value="Pending" <?php echo (isset($filters['status']) && $filters['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        <option value="Failed" <?php echo (isset($filters['status']) && $filters['status'] == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="tag_ids">Transaction Tag</label>
                    <select name="tag_ids[]" id="tag_ids" multiple size="5">
                        <?php 
                            $selected_tags = $filters['tag_ids'] ?? [];
                            foreach($tags as $tag): 
                        ?>
                            <option value="<?php echo $tag['id']; ?>" <?php echo in_array($tag['id'], $selected_tags) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="button">Apply</button>
                 <a href="<?php echo BASE_URL; ?>/transactions" class="button cancel">Reset</a>
            </form>
        </div>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>