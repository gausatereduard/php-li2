<?php $title = 'Categories'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Categories</h2>
    <a href="/categories/create" class="btn btn-primary">Add Category</a>
</div>

<section>
    <?php if (empty($categories)): ?>
        <div class="empty-state">
            <h3>No categories yet</h3>
            <p>Create categories to organize your transactions.</p>
            <a href="/categories/create" class="btn btn-primary">Add Category</a>
        </div>
    <?php else: ?>
        <ul>
            <?php foreach ($categories as $category): ?>
                <li>
                    <div>
                        <strong><?= esc($category['name']) ?></strong>
                        <span class="badge <?= $category['type'] === 'income' ? 'badge-success' : 'badge-danger' ?>"><?= esc($category['type']) ?></span>
                    </div>
                    <?php if ($category['user_id']): ?>
                        <div class="btn-group">
                            <a href="/categories/edit/<?= $category['id'] ?>" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="/categories/delete/<?= $category['id'] ?>" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>