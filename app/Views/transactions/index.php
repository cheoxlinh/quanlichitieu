<?php require_once '../app/Views/layouts/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Transactions</h1>
        <a href="transactions/create" class="button">New transaction</a>
    </div>
    <div class="content-box" style="display: flex; gap: 20px;">
        <div style="flex: 3;">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Transaction type</th>
                        <th>Transaction Tag</th>
                        <th>Status</th>
                        <th>Note</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($t['id']); ?></td>
                        <td><?php echo number_format($t['amount']); ?>d</td>
                        <td><span class="pill revenue"><?php echo htmlspecialchars($t['transaction_type']); ?></span></td>
                        <td><span class="pill order"><?php echo htmlspecialchars($t['tag_name']); ?></span></td>
                        <td><span class="pill success"><?php echo htmlspecialchars($t['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($t['note']); ?></td>
                        <td><?php echo date('Y-m-d H:i:s', strtotime($t['created_at'])); ?></td>
                        <td><a href="transactions/edit/<?php echo $t['id']; ?>">Edit</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
                    <label for="tag_id">Transaction Tag</label>
                    <select name="tag_id" id="tag_id">
                        <option value="">All</option>
                        <?php foreach($tags as $tag): ?>
                            <option value="<?php echo $tag['id']; ?>" <?php echo (isset($filters['tag_id']) && $filters['tag_id'] == $tag['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($tag['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="button">Apply</button>
                 <a href="/quan-ly-giao-dich-mvc/public/transactions" class="button cancel">Reset</a>
            </form>
        </div>
    </div>
</div>
<?php require_once '../app/Views/layouts/footer.php'; ?>