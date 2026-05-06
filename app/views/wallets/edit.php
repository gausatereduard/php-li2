<?php $title = 'Edit Wallet'; ?>
<?php ob_start(); ?>
<h2>Edit Wallet</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?= esc($wallet['name']) ?>" required>
    </div>
    <div>
        <label>Balance</label>
        <input type="number" step="0.01" name="balance" value="<?= esc($wallet['balance']) ?>">
    </div>
    <div>
        <label>Currency</label>
        <select name="currency">
            <option value="USD" <?= $wallet['currency'] === 'USD' ? 'selected' : '' ?>>USD</option>
            <option value="EUR" <?= $wallet['currency'] === 'EUR' ? 'selected' : '' ?>>EUR</option>
            <option value="MDL" <?= $wallet['currency'] === 'MDL' ? 'selected' : '' ?>>MDL</option>
        </select>
    </div>
    <button type="submit">Update</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
