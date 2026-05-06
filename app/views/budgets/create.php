<?php $title = 'Create Budget'; ?>
<?php ob_start(); ?>
<h2>Create Budget</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Category</label>
        <select name="category_id" required>
            <option value="">Select category</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div>
        <label>Limit Amount</label>
        <input type="number" step="0.01" name="limit_amount" required>
    </div>
    <button type="submit">Create</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
