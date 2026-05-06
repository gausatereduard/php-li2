<?php $title = 'Login'; ?>
<?php ob_start(); ?>
<div class="auth-container">
    <div class="auth-card">
        <h2>Welcome Back</h2>
        <p>Sign in to your account</p>
        
        <?php if (isset($errors['auth'])): ?>
            <div class="alert alert-danger"><?= esc($errors['auth']) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="email">Email or Username</label>
                <input type="text" id="email" name="email" value="<?= esc($data['email'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 20px; margin-bottom: 0;">
            Don't have an account? <a href="/register">Register</a>
        </p>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>