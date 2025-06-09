<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction Manager</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>/public/css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css"/>
</head>
<body>
    <?php if (isset($_SESSION['user_id'])): ?>
    <nav class="main-nav">
        <div class="nav-links">
            <div class="nav-links">
            <a href="<?php echo BASE_URL; ?>/transactions">Transactions</a>
            <a href="<?php echo BASE_URL; ?>/tags">Transaction Tags</a>
            <a href="<?php echo BASE_URL; ?>/setting">Settings</a> 
        </div>
        </div>
        <div class="user-actions">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="<?php echo BASE_URL; ?>/auth/logout">Logout</a>
        </div>
    </nav>
    <?php endif; ?>