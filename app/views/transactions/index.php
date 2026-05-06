<?php $title = 'Transactions'; ?>
<?php ob_start(); ?>
<h2>Your Transactions</h2>
<a href="/transactions/create">Add Transaction</a>
<a href="/transactions/search">Search</a>
<ul>
    <?php foreach ($transactions as $t): ?>
        <li>
            <?= esc($t['date']) ?> - <?= esc($t['category_name']) ?> - <?= esc($t['amount']) ?> 
            (<?= esc($t['type']) ?>) - <?= esc($t['wallet_name']) ?>
            <?php if ($t['description']): ?>
                : <?= esc($t['description']) ?>
            <?php endif; ?>
            <a href="/transactions/edit/<?= $t['id'] ?>">Edit</a>
            <form method="POST" action="/transactions/delete/<?= $t['id'] ?>" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
