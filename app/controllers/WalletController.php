<?php

class WalletController extends BaseController {
    private $walletModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $this->walletModel = new Wallet();
    }
    
    public function index() {
        $wallets = $this->walletModel->getByUser($_SESSION['user_id']);
        $this->render('wallets/index', ['wallets' => $wallets]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $this->walletModel->create($data);
            $this->redirect('/wallets');
        }
        $this->render('wallets/create');
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $this->walletModel->update($id, $data);
            $this->redirect('/wallets');
        }
        $wallet = $this->getWallet($id);
        $this->render('wallets/edit', ['wallet' => $wallet]);
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $this->walletModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/wallets');
    }
    
    private function getWallet($id) {
        $stmt = $this->walletModel->pdo->prepare("SELECT * FROM wallets WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $_SESSION['user_id']]);
        return $stmt->fetch();
    }
}
