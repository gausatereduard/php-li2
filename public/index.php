<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/router.php';
require_once __DIR__ . '/../helpers/functions.php';
require_once __DIR__ . '/../app/controllers/BaseController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/models/BaseModel.php';
require_once __DIR__ . '/../app/models/User.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/models/Wallet.php';
require_once __DIR__ . '/../app/controllers/WalletController.php';
require_once __DIR__ . '/../app/models/Category.php';
require_once __DIR__ . '/../app/controllers/CategoryController.php';
require_once __DIR__ . '/../app/models/Transaction.php';
require_once __DIR__ . '/../app/controllers/TransactionController.php';
require_once __DIR__ . '/../app/models/Budget.php';
require_once __DIR__ . '/../app/controllers/BudgetController.php';
require_once __DIR__ . '/../app/controllers/AdminController.php';

startSecureSession();

$router = new Router();

$router->add('GET', '', function() {
    (new HomeController())->index();
});

$router->add('GET', 'register', function() {
    (new AuthController())->showRegister();
});

$router->add('POST', 'register', function() {
    (new AuthController())->register();
});

$router->add('GET', 'login', function() {
    (new AuthController())->showLogin();
});

$router->add('POST', 'login', function() {
    (new AuthController())->login();
});

$router->add('GET', 'logout', function() {
    (new AuthController())->logout();
});
$router->add('POST', 'logout', function() {
    (new AuthController())->logout();
});

$router->add('GET', 'wallets', function() {
    (new WalletController())->index();
});

$router->add('GET', 'wallets/create', function() {
    (new WalletController())->create();
});

$router->add('POST', 'wallets/create', function() {
    (new WalletController())->create();
});

$router->add('GET', 'wallets/edit/{id}', function($id) {
    (new WalletController())->edit($id);
});

$router->add('POST', 'wallets/edit/{id}', function($id) {
    (new WalletController())->edit($id);
});

$router->add('POST', 'wallets/delete/{id}', function($id) {
    (new WalletController())->delete($id);
});

$router->add('GET', 'categories', function() {
    (new CategoryController())->index();
});

$router->add('GET', 'categories/create', function() {
    (new CategoryController())->create();
});

$router->add('POST', 'categories/create', function() {
    (new CategoryController())->create();
});

$router->add('GET', 'categories/edit/{id}', function($id) {
    (new CategoryController())->edit($id);
});

$router->add('POST', 'categories/edit/{id}', function($id) {
    (new CategoryController())->edit($id);
});

$router->add('POST', 'categories/delete/{id}', function($id) {
    (new CategoryController())->delete($id);
});

$router->add('GET', 'transactions', function() {
    (new TransactionController())->index();
});

$router->add('GET', 'transactions/create', function() {
    (new TransactionController())->create();
});

$router->add('POST', 'transactions/create', function() {
    (new TransactionController())->create();
});

$router->add('GET', 'transactions/edit/{id}', function($id) {
    (new TransactionController())->edit($id);
});

$router->add('POST', 'transactions/edit/{id}', function($id) {
    (new TransactionController())->edit($id);
});

$router->add('POST', 'transactions/delete/{id}', function($id) {
    (new TransactionController())->delete($id);
});

$router->add('GET', 'transactions/search', function() {
    (new TransactionController())->search();
});

$router->add('GET', 'budget', function() {
    (new BudgetController())->index();
});

$router->add('GET', 'budget/create', function() {
    (new BudgetController())->create();
});

$router->add('POST', 'budget/create', function() {
    (new BudgetController())->create();
});

$router->add('GET', 'admin', function() {
    (new AdminController())->index();
});

$router->add('POST', 'admin/update-role', function() {
    (new AdminController())->updateRole();
});

$router->add('POST', 'admin/delete-user', function() {
    (new AdminController())->deleteUser();
});

$router->dispatch();
