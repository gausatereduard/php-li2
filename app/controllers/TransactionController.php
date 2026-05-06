<?php

class TransactionController extends BaseController {
    private $transactionModel;
    private $categoryModel;
    private $walletModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $this->transactionModel = new Transaction();
        $this->categoryModel = new Category();
        $this->walletModel = new Wallet();
    }
    
    public function index() {
        $transactions = $this->transactionModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/index', ['transactions' => $transactions]);
    }
    
    public function create() {
        $errors = [];
        $data = $_POST;
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            
            if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                $errors['amount'] = 'Amount must be > 0';
            }
            if (empty($data['type'])) $errors['type'] = 'Type required';
            if (empty($data['category_id'])) $errors['category_id'] = 'Category required';
            if (empty($data['wallet_id'])) $errors['wallet_id'] = 'Wallet required';
            if ($data['type'] === 'transfer' && empty($data['target_wallet_id'])) {
                $errors['target_wallet_id'] = 'Target wallet required for transfer';
            }
            if (empty($data['date']) || !strtotime($data['date'])) $errors['date'] = 'Valid date required';
            
            if (empty($errors)) {
                $result = $this->transactionModel->create($data);
                if ($result) $this->redirect('/transactions');
                else $errors['general'] = 'Failed to create transaction';
            }
        }
        
        $categories = $this->categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $this->walletModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/create', [
            'errors' => $errors,
            'data' => $data,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
    
    public function edit($id) {
        $errors = [];
        $transaction = $this->transactionModel->getById($id, $_SESSION['user_id']);
        if (!$transaction) $this->redirect('/transactions');
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            
            $data = $_POST;
            if (empty($data['amount']) || !is_numeric($data['amount']) || $data['amount'] <= 0) {
                $errors['amount'] = 'Amount must be > 0';
            }
            if (empty($data['type'])) $errors['type'] = 'Type required';
            if (empty($data['category_id'])) $errors['category_id'] = 'Category required';
            if (empty($data['wallet_id'])) $errors['wallet_id'] = 'Wallet required';
            if ($data['type'] === 'transfer' && empty($data['target_wallet_id'])) {
                $errors['target_wallet_id'] = 'Target wallet required for transfer';
            }
            if (empty($data['date']) || !strtotime($data['date'])) $errors['date'] = 'Valid date required';
            
            if (empty($errors)) {
                $result = $this->transactionModel->update($id, $data, $_SESSION['user_id']);
                if ($result) $this->redirect('/transactions');
                else $errors['general'] = 'Failed to update transaction';
            }
        }
        
        $categories = $this->categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $this->walletModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/edit', [
            'errors' => $errors,
            'transaction' => $transaction,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $this->transactionModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/transactions');
    }
    
    public function search() {
        $filters = $_GET;
        $page = max(1, intval($filters['page'] ?? 1));
        $transactions = $this->transactionModel->search($_SESSION['user_id'], $filters, $page);
        $categories = $this->categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $this->walletModel->getByUser($_SESSION['user_id']);
        
        $this->render('transactions/search', [
            'transactions' => $transactions,
            'filters' => $filters,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
}
