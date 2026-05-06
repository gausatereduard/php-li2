<?php $title = 'Create Budget'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Create Budget</h2>
    <a href="/budget" class="btn btn-secondary">Back to Budgets</a>
</div>

<section>
    <div class="card" style="max-width: 500px;">
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="category_id">Category</label>
                <select id="category_id" name="category_id" required>
                    <option value="">Select category</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= esc($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="limit_amount">Limit Amount</label>
                <input type="number" id="limit_amount" step="0.01" name="limit_amount" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary">Create Budget</button>
                <a href="/budget" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</section>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>