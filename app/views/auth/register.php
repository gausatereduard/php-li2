<?php $title = 'Register'; ?>
<?php ob_start(); ?>
<h2>Register</h2>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Username</label>
        <input type="text" name="username" value="<?= esc($data['username'] ?? '') ?>">
        <?= isset($errors['username']) ? '<span>' . esc($errors['username']) . '</span>' : '' ?>
    </div>
    <div>
        <label>Email</label>
        <input type="email" name="email" value="<?= esc($data['email'] ?? '') ?>">
        <?= isset($errors['email']) ? '<span>' . esc($errors['email']) . '</span>' : '' ?>
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password">
        <?= isset($errors['password']) ? '<span>' . esc($errors['password']) . '</span>' : '' ?>
    </div>
    <div>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password">
        <?= isset($errors['confirm_password']) ? '<span>' . esc($errors['confirm_password']) . '</span>' : '' ?>
    </div>
    <button type="submit">Register</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
