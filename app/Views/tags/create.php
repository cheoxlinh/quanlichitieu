<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <h1>Create Transaction Tag 1</h1>
    <div class="content-box">
        <form action="<?php echo BASE_URL; ?>/tags/store" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit" class="button">Create</button>
            <a href="<?php echo BASE_URL; ?>/tags" class="button cancel">Cancel</a>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>