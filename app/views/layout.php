<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Finance Manager') ?></title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <nav>
        <a href="/">Home</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/wallets">Wallets</a>
            <a href="/transactions">Transactions</a>
            <a href="/budget">Budget</a>
            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="/admin">Admin</a>
            <?php endif; ?>
            <a href="/logout">Logout</a>
        <?php else: ?>
            <a href="/login">Login</a>
            <a href="/register">Register</a>
        <?php endif; ?>
    </nav>
    <main>
        <?= $content ?>
    </main>
</body>
</html>
