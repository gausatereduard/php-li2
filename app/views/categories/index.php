<?php $title = 'Categories'; ?>
<?php ob_start(); ?>
<h2>Categories</h2>
<a href="/categories/create">Add Category</a>
<ul>
    <?php foreach ($categories as $category): ?>
        <li>
            <?= esc($category['name']) ?> (<?= esc($category['type']) ?>)
            <?php if ($category['user_id']): ?>
                <a href="/categories/edit/<?= $category['id'] ?>">Edit</a>
                <form method="POST" action="/categories/delete/<?= $category['id'] ?>" style="display: inline;">
                    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                    <button type="submit">Delete</button>
                </form>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
