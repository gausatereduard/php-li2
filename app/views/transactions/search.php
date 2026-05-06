<?php $title = 'Search Transactions'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Search Transactions</h2>
    <a href="/transactions" class="btn btn-secondary">Back to Transactions</a>
</div>

<section>
    <div class="card" style="max-width: 100%;">
        <form method="GET">
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px;">
                <div class="form-group">
                    <label for="description">Description</label>
                    <input type="text" id="description" name="description" value="<?= esc($filters['description'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="type">Type</label>
                    <select id="type" name="type">
                        <option value="">All</option>
                        <option value="income" <?= ($filters['type'] ?? '') === 'income' ? 'selected' : '' ?>>Income</option>
                        <option value="expense" <?= ($filters['type'] ?? '') === 'expense' ? 'selected' : '' ?>>Expense</option>
                        <option value="transfer" <?= ($filters['type'] ?? '') === 'transfer' ? 'selected' : '' ?>>Transfer</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="category_id">Category</label>
                    <select id="category_id" name="category_id">
                        <option value="">All</option>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="wallet_id">Wallet</label>
                    <select id="wallet_id" name="wallet_id">
                        <option value="">All</option>
                        <?php foreach ($wallets as $w): ?>
                            <option value="<?= $w['id'] ?>" <?= ($filters['wallet_id'] ?? '') == $w['id'] ? 'selected' : '' ?>>
                                <?= esc($w['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_from">Date From</label>
                    <input type="date" id="date_from" name="date_from" value="<?= esc($filters['date_from'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="date_to">Date To</label>
                    <input type="date" id="date_to" name="date_to" value="<?= esc($filters['date_to'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="amount_min">Amount Min</label>
                    <input type="number" id="amount_min" step="0.01" name="amount_min" value="<?= esc($filters['amount_min'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="amount_max">Amount Max</label>
                    <input type="number" id="amount_max" step="0.01" name="amount_max" value="<?= esc($filters['amount_max'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label for="sort">Sort By</label>
                    <select id="sort" name="sort">
                        <option value="date_desc" <?= ($filters['sort'] ?? '') === 'date_desc' ? 'selected' : '' ?>>Date (newest)</option>
                        <option value="date_asc" <?= ($filters['sort'] ?? '') === 'date_asc' ? 'selected' : '' ?>>Date (oldest)</option>
                        <option value="amount_desc" <?= ($filters['sort'] ?? '') === 'amount_desc' ? 'selected' : '' ?>>Amount (high)</option>
                        <option value="amount_asc" <?= ($filters['sort'] ?? '') === 'amount_asc' ? 'selected' : '' ?>>Amount (low)</option>
                    </select>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="/transactions/search" class="btn btn-secondary">Clear</a>
            </div>
        </form>
    </div>
</section>

<section>
    <h3>Results (<?= count($transactions) ?>)</h3>
    <?php if (empty($transactions)): ?>
        <div class="empty-state">
            <h3>No results found</h3>
            <p>Try adjusting your search filters.</p>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Amount</th>
                    <th>Type</th>
                    <th>Wallet</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= esc($t['date']) ?></td>
                        <td><?= esc($t['category_name']) ?></td>
                        <td><?= esc($t['amount']) ?></td>
                        <td><span class="badge <?= $t['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= esc($t['type']) ?></span></td>
                        <td><?= esc($t['wallet_name']) ?></td>
                        <td><?= esc($t['description'] ?? '-') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>