<?php $title = 'Edit Transaction'; ?>
<?php ob_start(); ?>
<h2>Edit Transaction</h2>
<?= isset($errors['general']) ? '<p>' . esc($errors['general']) . '</p>' : '' ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    
    <div>
        <label>Amount</label>
        <input type="number" step="0.01" name="amount" value="<?= esc($transaction['amount']) ?>">
        <?= isset($errors['amount']) ? '<span>' . esc($errors['amount']) . '</span>' : '' ?>
    </div>
    
    <div>
        <label>Type</label>
        <select name="type" id="transaction-type">
            <option value="income" <?= $transaction['type'] === 'income' ? 'selected' : '' ?>>Income</option>
            <option value="expense" <?= $transaction['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
            <option value="transfer" <?= $transaction['type'] === 'transfer' ? 'selected' : '' ?>>Transfer</option>
        </select>
        <?= isset($errors['type']) ? '<span>' . esc($errors['type']) . '</span>' : '' ?>
    </div>
    
    <div>
        <label>Category</label>
        <select name="category_id">
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $transaction['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= isset($errors['category_id']) ? '<span>' . esc($errors['category_id']) . '</span>' : '' ?>
    </div>
    
    <div>
        <label>Wallet</label>
        <select name="wallet_id">
            <?php foreach ($wallets as $w): ?>
                <option value="<?= $w['id'] ?>" <?= $transaction['wallet_id'] == $w['id'] ? 'selected' : '' ?>>
                    <?= esc($w['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= isset($errors['wallet_id']) ? '<span>' . esc($errors['wallet_id']) . '</span>' : '' ?>
    </div>
    
    <div id="target-wallet" style="display: <?= $transaction['type'] === 'transfer' ? 'block' : 'none' ?>;">
        <label>Target Wallet (for transfer)</label>
        <select name="target_wallet_id">
            <option value="">Select target wallet</option>
            <?php foreach ($wallets as $w): ?>
                <option value="<?= $w['id'] ?>" <?= $transaction['target_wallet_id'] == $w['id'] ? 'selected' : '' ?>>
                    <?= esc($w['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <?= isset($errors['target_wallet_id']) ? '<span>' . esc($errors['target_wallet_id']) . '</span>' : '' ?>
    </div>
    
    <div>
        <label>Date</label>
        <input type="date" name="date" value="<?= esc($transaction['date']) ?>">
        <?= isset($errors['date']) ? '<span>' . esc($errors['date']) . '</span>' : '' ?>
    </div>
    
    <div>
        <label>Description</label>
        <textarea name="description"><?= esc($transaction['description']) ?></textarea>
    </div>
    
    <button type="submit">Update</button>
</form>

<script>
document.getElementById('transaction-type').addEventListener('change', function() {
    document.getElementById('target-wallet').style.display = this.value === 'transfer' ? 'block' : 'none';
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
