<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Manager</title>
    <link rel="stylesheet" href="/quan-ly-giao-dich-mvc/public/css/style.css">
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="main-nav">
        <div class="nav-links">
            <a href="/quan-ly-giao-dich-mvc/public/transactions">Transactions</a>
            <a href="/quan-ly-giao-dich-mvc/public/tags">Transaction Tags</a>
        </div>
        <div class="user-actions">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="/quan-ly-giao-dich-mvc/public/auth/logout">Logout</a>
        </div>
    </nav>
    <?php endif; ?>