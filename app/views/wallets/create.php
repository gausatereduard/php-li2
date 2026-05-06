<?php $title = 'Create Wallet'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Create Wallet</h2>
    <a href="/wallets" class="btn btn-secondary">Back to Wallets</a>
</div>

<section>
    <div class="card" style="max-width: 500px;">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="currency">Currency</label>
                <select id="currency" name="currency">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="MDL">MDL</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Wallet</button>
                <a href="/wallets" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>