<?php

class WalletController extends BaseController {
    public function index() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $walletModel = new Wallet();
        $wallets = $walletModel->getByUser($_SESSION['user_id']);
        $this->render('wallets/index', ['wallets' => $wallets]);
    }
    
    public function create() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $walletModel = new Wallet();
            $walletModel->create($data);
            $this->redirect('/wallets');
        }
        $this->render('wallets/create');
    }
    
    public function edit($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $walletModel = new Wallet();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $walletModel->update($id, $data);
            $this->redirect('/wallets');
        }
        $wallet = $walletModel->getById($id, $_SESSION['user_id']);
        $this->render('wallets/edit', ['wallet' => $wallet]);
    }
    
    public function delete($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $walletModel = new Wallet();
            $walletModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/wallets');
    }
}
