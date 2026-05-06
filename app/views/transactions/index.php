<?php $title = 'Transactions'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Your Transactions</h2>
    <div class="header-actions">
        <a href="/transactions/create" class="btn btn-primary">Add Transaction</a>
        <a href="/transactions/search" class="btn btn-secondary">Search</a>
    </div>
</div>

<section>
    <?php if (empty($transactions)): ?>
        <div class="empty-state">
            <h3>No transactions yet</h3>
            <p>Start by adding your first transaction.</p>
            <a href="/transactions/create" class="btn btn-primary">Add Transaction</a>
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
                    <th>Actions</th>
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
                        <td>
                            <div class="btn-group">
                                <a href="/transactions/edit/<?= $t['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                                <form method="POST" action="/transactions/delete/<?= $t['id'] ?>" style="display: inline;">
                                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>