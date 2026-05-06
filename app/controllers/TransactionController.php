<?php

class TransactionController extends BaseController {
    public function index() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $transactionModel = new Transaction();
        $transactions = $transactionModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/index', ['transactions' => $transactions]);
    }
    
    public function create() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $errors = [];
        $data = $_POST;
        $categoryModel = new Category();
        $walletModel = new Wallet();
        
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
                $data['target_wallet_id'] = $data['target_wallet_id'] ?: null;
                $transactionModel = new Transaction();
                $result = $transactionModel->create($data);
                if ($result) $this->redirect('/transactions');
                else $errors['general'] = 'Failed to create transaction';
            }
        }
        
        $categories = $categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $walletModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/create', [
            'errors' => $errors,
            'data' => $data,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
    
    public function edit($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $errors = [];
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        $walletModel = new Wallet();
        
        $transaction = $transactionModel->getById($id, $_SESSION['user_id']);
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
                $data['target_wallet_id'] = $data['target_wallet_id'] ?: null;
                $result = $transactionModel->update($id, $data, $_SESSION['user_id']);
                if ($result) $this->redirect('/transactions');
                else $errors['general'] = 'Failed to update transaction';
            }
        }
        
        $categories = $categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $walletModel->getByUser($_SESSION['user_id']);
        $this->render('transactions/edit', [
            'errors' => $errors,
            'transaction' => $transaction,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
    
    public function delete($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $transactionModel = new Transaction();
            $transactionModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/transactions');
    }
    
    public function search() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $transactionModel = new Transaction();
        $categoryModel = new Category();
        $walletModel = new Wallet();
        
        $filters = $_GET;
        $page = max(1, intval($filters['page'] ?? 1));
        $transactions = $transactionModel->search($_SESSION['user_id'], $filters, $page);
        $categories = $categoryModel->getForUser($_SESSION['user_id']);
        $wallets = $walletModel->getByUser($_SESSION['user_id']);
        
        $this->render('transactions/search', [
            'transactions' => $transactions,
            'filters' => $filters,
            'categories' => $categories,
            'wallets' => $wallets
        ]);
    }
}
