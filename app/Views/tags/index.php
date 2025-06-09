<?php require_once ROOT_PATH . '/app/Views/layouts/header.php'; ?>
<div class="container">
    <div class="page-header">
        <h1>Transaction Tags</h1>
        <a href="<?php echo BASE_URL; ?>/tags/create" class="button">New transaction tag</a>
    </div>
    <div class="content-box">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th style="width: 150px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tags as $tag): ?>
                <tr>
                    <td><?php echo htmlspecialchars($tag['name']); ?></td>
                    <td>
                        <a href="<?php echo BASE_URL; ?>/tags/edit/<?php echo $tag['id']; ?>">Edit</a>
                        <form action="<?php echo BASE_URL; ?>/tags/destroy/<?php echo $tag['id']; ?>" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                            <button type="submit" style="background:none; border:none; color:var(--accent-color); cursor:pointer; padding:0; font-size:14px;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php require_once ROOT_PATH . '/app/Views/layouts/footer.php'; ?>