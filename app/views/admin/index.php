<?php $title = 'Admin Panel'; ?>
<?php ob_start(); ?>
<div class="header">
    <h2>Admin Panel</h2>
</div>

<section>
    <h3>All Users</h3>
    <?php if (empty($users)): ?>
        <div class="empty-state">
            <h3>No users found</h3>
        </div>
    <?php else: ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><strong><?= esc($user['username']) ?></strong></td>
                        <td><?= esc($user['email']) ?></td>
                        <td><span class="badge <?= $user['role'] === 'admin' ? 'badge-warning' : 'badge-info' ?>"><?= esc($user['role']) ?></span></td>
                        <td><?= date('M d, Y', strtotime($user['created_at'])) ?></td>
                        <td>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <select name="role" onchange="this.form.submit()" style="width: auto; padding: 6px 10px;">
                                    <option value="user" <?= $user['role'] === 'user' ? 'selected' : '' ?>>User</option>
                                    <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : '' ?>>Admin</option>
                                </select>
                            </form>
                            <form method="POST" action="/admin/delete-user" style="display: inline;">
                                <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
                                <input type="hidden" name="user_id" value="<?= $user['id'] ?>">
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>
<?php $content = ob_get_clean(); ?>
<?php require __DIR__ . '/../layout.php'; ?>