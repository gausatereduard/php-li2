<?php $title = 'Create Transaction'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Create Transaction</h2>
    <a href="/transactions" class="btn btn-secondary">Back to Transactions</a>
</div>

<section>
    <div class="card" style="max-width: 600px;">
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?= esc($errors['general']) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" id="amount" step="0.01" name="amount" value="<?= esc($data['amount'] ?? '') ?>" required>
                <?php if (isset($errors['amount'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['amount']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type">
                    <option value="income" <?= ($data['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                    <option value="expense" <?= ($data['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                    <option value="transfer" <?= ($data['type'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer</option>
                </select>
                <?php if (isset($errors['type'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['type']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id">
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($data['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                            <?= esc($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['category_id'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['category_id']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="wallet_id">Wallet</label>
                <select id="wallet_id" name="wallet_id">
                    <option value="">Select wallet</option>
                    <?php foreach ($wallets as $w): ?>
                        <option value="<?= $w['id'] ?>" <?= ($data['wallet_id'] ?? '') == $w['id'] ? 'selected' : '' ?>>
                            <?= esc($w['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['wallet_id'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['wallet_id']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group" id="target-wallet-group" style="display: none;">
                <label for="target_wallet_id">Target Wallet (for transfer)</label>
                <select id="target_wallet_id" name="target_wallet_id">
                    <option value="">Select target wallet</option>
                    <?php foreach ($wallets as $w): ?>
                        <option value="<?= $w['id'] ?>" <?= ($data['target_wallet_id'] ?? '') == $w['id'] ? 'selected' : '' ?>>
                            <?= esc($w['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['target_wallet_id'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['target_wallet_id']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" id="date" name="date" value="<?= esc($data['date'] ?? date('Y-m-d')) ?>" required>
                <?php if (isset($errors['date'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['date']) ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3"><?= esc($data['description'] ?? '') ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Transaction</button>
                <a href="/transactions" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>

<script>
document.getElementById('type').addEventListener('change', function() {
    document.getElementById('target-wallet-group').style.display = this.value === 'transfer' ? 'block' : 'none';
});
</script>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>