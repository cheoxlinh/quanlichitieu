<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container" style="max-width: 400px; margin-top: 100px;">
    <div class="content-box">
        <h2>Login</h2>
        <form action="<?php echo BASE_URL; ?>/auth/authenticate" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required value="admin">
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required value="123456">
            </div>
            <button type="submit" class="button">Login</button>
        </form>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>