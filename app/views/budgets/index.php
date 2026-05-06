<?php $title = 'Budget Planning'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Your Budgets</h2>
    <a href="/budget/create" class="btn btn-primary">Add Budget</a>
</div>

<section>
    <?php if (empty($budgets)): ?>
        <div class="empty-state">
            <h3>No budgets yet</h3>
            <p>Set up budgets to track your spending.</p>
            <a href="/budget/create" class="btn btn-primary">Add Budget</a>
        </div>
    <?php else: ?>
        <div class="card-grid">
            <?php foreach ($budgets as $budget): ?>
                <div class="card">
                    <h3><?= esc($budget['category_name']) ?></h3>
                    <div style="margin: 12px 0;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 8px; font-size: 0.875rem;">
                            <span>Spent: <strong><?= esc($budget['spent']) ?></strong></span>
                            <span>Limit: <strong><?= esc($budget['limit_amount']) ?></strong></span>
                        </div>
                        <div style="background: var(--bg); border-radius: 20px; height: 12px; overflow: hidden;">
                            <div style="width: <?= min($budget['percentage'], 100) ?>%; background: <?= $budget['percentage'] < 70 ? 'var(--success)' : ($budget['percentage'] <= 100 ? 'var(--warning)' : 'var(--danger)') ?>; height: 100%; border-radius: 20px; transition: width 0.3s ease;"></div>
                        </div>
                        <p style="text-align: center; margin-top: 8px; font-size: 0.875rem;">
                            <?= round($budget['percentage'], 1) ?>% used
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>