<?php $title = 'Home'; ?>
<?php ob_start(); ?>
<div class="hero">
    <h1>Finance Manager</h1>
    <p>Track your personal finances easily. Manage wallets, transactions, and budgets all in one place.</p>
    <?php if (!isset($_SESSION['user_id'])): ?>
        <a href="/register" class="btn">Get Started</a>
    <?php endif; ?>
</div>

<section>
    <h3>Latest Registered Users</h3>
    <ul>
        <?php if (empty($latestUsers)): ?>
            <li class="empty-state">No users yet</li>
        <?php else: ?>
            <?php foreach ($latestUsers as $user): ?>
                <li><?= esc($user['username']) ?> <span class="badge badge-info"><?= date('M d, Y', strtotime($user['created_at'])) ?></span></li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</section>

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
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/layout.php'; ?>