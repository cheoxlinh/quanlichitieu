<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <h1>Create Transaction</h1>
    <div class="content-box">
        <form action="<?php echo BASE_URL; ?>/transactions/store" method="POST">
            <div class="form-group">
                <label for="formatted-amount">Amount *</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" id="formatted-amount" inputmode="numeric" required style="flex: 3; text-align: left;">
                    <input type="hidden" name="amount" id="actual-amount">

                    <select name="currency" style="flex: 1;">
                        <option value="VND" <?php echo ($settings['currency'] == 'VND') ? 'selected' : ''; ?>>VNĐ</option>
                        <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="transaction_type">Transaction type *</label>
                <select name="transaction_type" required>
                    <option value="Revenue">Revenue</option>
                    <option value="Expense">Expense</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status *</label>
                <select name="status" required>
                    <option value="Success">Success</option>
                    <option value="Pending">Pending</option>
                    <option value="Failed">Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tag_id">Transaction tag</label>
                <select name="tag_id">
                    <option value="">Select an option</option>
                    <?php foreach($tags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note"></textarea>
            </div>
            <button type="submit" class="button">Create</button>
            <a href="<?php echo BASE_URL; ?>/transactions" class="button cancel">Cancel</a>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>