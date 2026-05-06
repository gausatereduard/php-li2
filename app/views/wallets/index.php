<?php $title = 'Wallets'; ?>
<?php ob_start(); ?>
<h2>Your Wallets</h2>
<a href="/wallets/create">Add Wallet</a>
<ul>
    <?php foreach ($wallets as $wallet): ?>
        <li>
            <?= esc($wallet['name']) ?> - <?= esc($wallet['balance']) ?> <?= esc($wallet['currency']) ?>
            <a href="/wallets/edit/<?= $wallet['id'] ?>">Edit</a>
            <form method="POST" action="/wallets/delete/<?= $wallet['id'] ?>" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
