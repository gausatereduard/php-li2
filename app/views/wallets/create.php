<?php $title = 'Create Wallet'; ?>
<?php ob_start(); ?>
<h2>Create Wallet</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>Currency</label>
        <select name="currency">
            <option value="USD">USD</option>
            <option value="EUR">EUR</option>
            <option value="MDL">MDL</option>
        </select>
    </div>
    <button type="submit">Create</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
