<?php $title = 'Home'; ?>
<?php ob_start(); ?>

<?php if (!isset($_SESSION['user_id'])): ?>
<div class="hero">
    <h1>Finance Manager</h1>
    <p>Track your personal finances easily. Manage wallets, transactions, and budgets all in one place.</p>
    <a href="/register" class="btn">Get Started</a>
</div>
<?php endif; ?>

<div class="dashboard-grid">
    <?php if (isset($_SESSION['user_id']) && $userBalance !== null): ?>
    <div class="widget">
        <h3>Your Total Balance</h3>
        <div class="balance-amount">$<?= number_format($userBalance, 2) ?></div>
    </div>
    <?php endif; ?>
    
    <div class="widget">
        <h3>Exchange Rates (MDL)</h3>
        <?php if (empty($exchangeRates)): ?>
            <p>No rates available</p>
        <?php else: ?>
            <ul class="exchange-list">
                <?php foreach ($exchangeRates as $rate): ?>
                    <li>
                        <span>MDL/<?= esc($rate['target_currency']) ?></span>
                        <span class="rate-value"><?= number_format($rate['rate'], 4) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
            <small style="color: var(--text-muted);">Updated: <?= esc(date('d M H:i', strtotime($exchangeRates[0]['updated_at'] ?? 'now'))) ?></small>
        <?php endif; ?>
    </div>
</div>

<section>
    <h3>Public Categories</h3>
    <ul>
        <?php if (empty($latestCategories)): ?>
            <li class="empty-state">No categories yet</li>
        <?php else: ?>
            <?php foreach ($latestCategories as $cat): ?>
                <li><?= esc($cat['name']) ?> <span class="badge <?= $cat['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= esc($cat['type']) ?></span></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>

<section>
    <h3>Latest Transactions</h3>
    <ul>
        <?php if (empty($latestTransactions)): ?>
            <li class="empty-state">No transactions yet</li>
        <?php else: ?>
            <?php foreach ($latestTransactions as $t): ?>
                <li>
                    <?= esc($t['description'] ?: $t['category_name']) ?> 
                    <span class="badge <?= $t['type'] === 'income' ? 'badge-success' : ($t['type'] === 'expense' ? 'badge-danger' : 'badge-info') ?>">
                        <?= esc($t['type']) ?>
                    </span>
                    $<?= number_format($t['amount'], 2) ?>
                    <small style="color: var(--text-muted);"><?= esc($t['wallet_name']) ?></small>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>