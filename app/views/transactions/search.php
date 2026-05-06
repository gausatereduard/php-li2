<?php $title = 'Search Transactions'; ?>
<?php ob_start(); ?>
<h2>Search Transactions</h2>
<form method="GET">
    <div>
        <label>Description</label>
        <input type="text" name="description" value="<?= esc($filters['description'] ?? '') ?>">
    </div>
    <div>
        <label>Type</label>
        <select name="type">
            <option value="">All</option>
            <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
            <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
            <option value="transfer" <?= ($filters['type'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer</option>
        </select>
    </div>
    <div>
        <label>Category</label>
        <select name="category_id">
            <option value="">All</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                    <?= esc($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label>Wallet</label>
        <select name="wallet_id">
            <option value="">All</option>
            <?php foreach ($wallets as $w): ?>
                <option value="<?= $w['id'] ?>" <?= ($filters['wallet_id'] ?? '') == $w['id'] ? 'selected' : '' ?>>
                    <?= esc($w['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label>Date From</label>
        <input type="date" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>">
    </div>
    <div>
        <label>Date To</label>
        <input type="date" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>">
    </div>
    <div>
        <label>Amount Min</label>
        <input type="number" step="0.01" name="amount_min" value="<?= esc($filters['amount_min'] ?? '') ?>">
    </div>
    <div>
        <label>Amount Max</label>
        <input type="number" step="0.01" name="amount_max" value="<?= esc($filters['amount_max'] ?? '') ?>">
    </div>
    <div>
        <label>Sort By</label>
        <select name="sort">
            <option value="date_desc" <?= ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Date (newest)</option>
            <option value="date_asc" <?= ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Date (oldest)</option>
            <option value="amount_desc" <?= ($filters['sort'] ?? '') === 'amount_desc' ? 'selected' : '' ?>>Amount (high)</option>
            <option value="amount_asc" <?= ($filters['sort'] ?? '') === 'amount_asc' ? 'selected' : '' ?>>Amount (low)</option>
        </select>
    </div>
    <button type="submit">Search</button>
</form>

<h3>Results</h3>
<ul>
    <?php foreach ($transactions as $t): ?>
        <li>
            <?= esc($t['date']) ?> - <?= esc($t['category_name']) ?> - <?= esc($t['amount']) ?> 
            (<?= esc($t['type']) ?>) - <?= esc($t['wallet_name']) ?>
            <?php if ($t['description']): ?>
                : <?= esc($t['description']) ?>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
