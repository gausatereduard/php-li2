<?php $title = 'Budget Planning'; ?>
<?php ob_start(); ?>
<h2>Your Budgets</h2>
<a href="/budget/create">Add Budget</a>
<ul>
    <?php foreach ($budgets as $budget): ?>
        <li>
            <strong><?= esc($budget['category_name']) ?></strong><br>
            Limit: <?= esc($budget['limit_amount']) ?> | Spent: <?= esc($budget['spent']) ?> 
            (<?= round($budget['percentage'], 1) ?>%)
            <div style="width: 200px; background: #f0f0f0; height: 20px;">
                <div style="width: <?= $budget['percentage'] ?>%; background: 
                    <?= $budget['percentage'] < 70 ? 'green' : ($budget['percentage'] <= 100 ? 'yellow' : 'red') ?>; 
                    height: 100%;"></div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
