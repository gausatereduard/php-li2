<?php $title = 'Create Category'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Create Category</h2>
    <a href="/categories" class="btn btn-secondary">Back to Categories</a>
</div>

<section>
    <div class="card" style="max-width: 500px;">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="type">Type</label>
                <select id="type" name="type" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Category</button>
                <a href="/categories" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>