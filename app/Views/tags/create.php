<?php require_once '../app/Views/layouts/header.php'; ?>
<div class="container">
    <h1>Create Transaction Tag</h1>
    <div class="content-box">
        <form action="store" method="POST">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <button type="submit" class="button">Create</button>
            <a href="../tags" class="button cancel">Cancel</a>
        </form>
    </div>
</div>
<?php require_once '../app/Views/layouts/footer.php'; ?>