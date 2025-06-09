<?php require_once '../app/Views/layouts/header.php'; ?>
<div class="container">
    <h1>Edit Transaction #<?php echo $transaction['id']; ?></h1>
    <div class="content-box">
        <form action="../update/<?php echo $transaction['id']; ?>" method="POST">
             <div class="form-group">
                <label for="amount">Amount *</label>
                <input type="number" name="amount" required value="<?php echo htmlspecialchars($transaction['amount']); ?>">
            </div>
            <div class="form-group">
                <label for="transaction_type">Transaction type *</label>
                <select name="transaction_type" required>
                    <option value="Revenue" <?php echo ($transaction['transaction_type'] == 'Revenue') ? 'selected' : ''; ?>>Revenue</option>
                    <option value="Expense" <?php echo ($transaction['transaction_type'] == 'Expense') ? 'selected' : ''; ?>>Expense</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Status *</label>
                <select name="status" required>
                    <option value="Success" <?php echo ($transaction['status'] == 'Success') ? 'selected' : ''; ?>>Success</option>
                    <option value="Pending" <?php echo ($transaction['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="Failed" <?php echo ($transaction['status'] == 'Failed') ? 'selected' : ''; ?>>Failed</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tag_id">Transaction tag</label>
                <select name="tag_id">
                    <option value="">Select an option</option>
                    <?php foreach($tags as $tag): ?>
                        <option value="<?php echo $tag['id']; ?>" <?php echo ($transaction['tag_id'] == $tag['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($tag['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="note">Note</label>
                <textarea name="note"><?php echo htmlspecialchars($transaction['note']); ?></textarea>
            </div>
            <button type="submit" class="button">Update</button>
            <a href="../../transactions" class="button cancel">Cancel</a>
        </form>
    </div>
</div>
<?php require_once '../app/Views/layouts/footer.php'; ?>