<?php $title = 'Create Category'; ?>
<?php ob_start(); ?>
<h2>Create Category</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Name</label>
        <input type="text" name="name" required>
    </div>
    <div>
        <label>Type</label>
        <select name="type" required>
            <option value="income">Income</option>
            <option value="expense">Expense</option>
        </select>
    </div>
    <button type="submit">Create</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
