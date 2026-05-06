<?php $title = 'Edit Wallet'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Edit Wallet</h2>
    <a href="/wallets" class="btn btn-secondary">Back to Wallets</a>
</div>

<section>
    <div class="card" style="max-width: 500px;">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" value="<?= esc($wallet['name']) ?>" required>
            </div>
            <div class="form-group">
                <label for="balance">Balance</label>
                <input type="number" id="balance" step="0.01" name="balance" value="<?= esc($wallet['balance']) ?>">
            </div>
            <div class="form-group">
                <label for="currency">Currency</label>
                <select id="currency" name="currency">
                    <option value="USD" <?= $wallet['currency'] === 'USD' ? 'selected' : '' ?>>USD</option>
                    <option value="EUR" <?= $wallet['currency'] === 'EUR' ? 'selected' : '' ?>>EUR</option>
                    <option value="MDL" <?= $wallet['currency'] === 'MDL' ? 'selected' : '' ?>>MDL</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Update Wallet</button>
                <a href="/wallets" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>