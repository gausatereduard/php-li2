<?php $title = 'Wallets'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Your Wallets</h2>
    <a href="/wallets/create" class="btn btn-primary">Add Wallet</a>
</div>

<section>
    <?php if (empty($wallets)): ?>
        <div class="empty-state">
            <h3>No wallets yet</h3>
            <p>Create your first wallet to start tracking finances.</p>
            <a href="/wallets/create" class="btn btn-primary">Add Wallet</a>
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($wallets as $wallet): ?>
                <div class="card">
                    <h3><?= esc($wallet['name']) ?></h3>
                    <div class="value" style="font-size: 1.5rem; margin: 12px 0;">
                        <?= esc($wallet['balance']) ?> <small style="color: var(--text-light); font-size: 0.875rem;"><?= esc($wallet['currency']) ?></small>
                    </div>
                    <div class="btn-group">
                        <a href="/wallets/edit/<?= $wallet['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                        <form method="POST" action="/wallets/delete/<?= $wallet['id'] ?>" style="display: inline;">
                            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>