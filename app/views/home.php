<?php $title = 'Home'; ?>
<?php ob_start(); ?>
<h1>Finance Manager</h1>
<p>Track your personal finances easily.</p>

<section>
    <h2>Latest Registered Users</h2>
    <ul>
        <?php foreach ($latestUsers as $user): ?>
            <li><?= esc($user['username']) ?> (joined <?= date('Y-m-d', strtotime($user['created_at'])) ?>)</li>
        <?php endforeach; ?>
    </ul>
</section>

<section>
    <h2>Public Categories</h2>
    <ul>
        <?php foreach ($latestCategories as $cat): ?>
            <li><?= esc($cat['name']) ?> (<?= esc($cat['type']) ?>)</li>
        <?php endforeach; ?>
    </ul>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
