<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <h1>Settings</h1>

    <?php if (isset($_SESSION['flash_message'])): ?>
        <div class="content-box" style="margin-bottom: 20px; background-color: var(--success-bg); color: var(--success-color);">
            <?php echo $_SESSION['flash_message']; unset($_SESSION['flash_message']); ?>
        </div>
    <?php endif; ?>

    <div class="content-box">
        <form action="<?php echo BASE_URL; ?>/setting/update" method="POST">
            <div class="form-group">
                <label for="currency">Currency Unit</label>
                <select name="currency" id="currency">
                    <option value="VND" <?php echo ($settings['currency'] ?? 'VND') == 'VND' ? 'selected' : ''; ?>>VNĐ</option>
                    <option value="USD" <?php echo ($settings['currency'] == 'USD') ? 'selected' : ''; ?>>USD</option>
                </select>
            </div>
            <div class="form-group">
                <label for="usd_to_vnd_rate">Exchange Rate (1 USD to VND)</label>
                <div style="display: flex; gap: 10px;">
                    <input type="text" name="usd_to_vnd_rate" id="usd_to_vnd_rate" value="<?php echo htmlspecialchars($settings['usd_to_vnd_rate'] ?? '26500'); ?>" required>
                    <a href="<?php echo BASE_URL; ?>/setting/updateFromApi" class="button cancel" style="width: 200px; text-align: center;">Update from API</a>
                </div>
            </div>
            <button type="submit" class="button">Save Settings</button>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>