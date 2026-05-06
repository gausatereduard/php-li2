<?php $title = 'Edit Category'; ?>
<?php ob_start(); ?>
<h2>Edit Category</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" value="<?= esc($category['name']) ?>" required>
    </div>
    <div>
        <label>Type</label>
        <select name="type" required>
            <option value="income" <?= $category['type'] === 'income' ? 'selected' : '' ?>>Income</option>
            <option value="expense" <?= $category['type'] === 'expense' ? 'selected' : '' ?>>Expense</option>
        </select>
    </div>
    <button type="submit">Update</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
