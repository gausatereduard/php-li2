<?php $title = 'Register'; ?>
<?php ob_start(); ?>
<div class="auth-container">
    <div class="auth-card">
        <h2>Create Account</h2>
        <p>Start tracking your finances today</p>
        
        <form method="POST">
            <input type="hidden" name="csrf_token" value="<?= generateCsrfToken() ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?= esc($data['username'] ?? '') ?>" required>
                <?php if (isset($errors['username'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['username']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?= esc($data['email'] ?? '') ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['email']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['password']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if (isset($errors['confirm_password'])): ?>
                    <span style="color: var(--danger); font-size: 0.875rem;"><?= esc($errors['confirm_password']) ?></span>
                <?php endif; ?>
            </div>
            <div class="form-actions">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Create Account</button>
            </div>
        </form>
        
        <p style="text-align: center; margin-top: 20px; margin-bottom: 0;">
            Already have an account? <a href="/login">Login</a>
        </p>
    </div>
</div>
<?php $content = ob_get_clean(); ?>
<?php require 'layout.php'; ?>