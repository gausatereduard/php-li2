<?php $title = 'Login'; ?>
<?php ob_start(); ?>
<h2>Login</h2>
<?= isset($errors['auth']) ? '<p>' . esc($errors['auth']) . '</p>' : '' ?>
<form method="POST">
    <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
    <div>
        <label>Email or Username</label>
        <input type="text" name="email" value="<?= esc($data['email'] ?? '') ?>">
    </div>
    <div>
        <label>Password</label>
        <input type="password" name="password">
    </div>
    <button type="submit">Login</button>
</form>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>
