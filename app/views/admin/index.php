<?php $title = 'Admin Panel'; ?>
<?php ob_start(); ?>
<h2>Admin Panel</h2>
<h3>All Users</h3>
<ul>
    <?php foreach ($users as $user): ?>
        <li>
            <?= esc($user['username']) ?> (<?= esc($user['email']) ?>) - Role: <?= esc($user['role']) ?>
            <form method="POST" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <select name="role" onchange="this.form.submit()">
                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                </select>
            </form>
            <form method="POST" action="/admin/delete-user" style="display: inline;">
                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                <button type="submit">Delete</button>
            </form>
        </li>
    <?php endforeach; ?>
</ul>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
