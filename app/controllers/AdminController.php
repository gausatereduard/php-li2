<?php

class AdminController extends BaseController {
    private $userModel;
    private $categoryModel;
    private $transactionModel;
    
    public function __construct() {
        if (!$this->isAuthenticated() || !$this->isAdmin()) {
            $this->redirect('/');
        }
        $this->userModel = new User();
        $this->categoryModel = new Category();
        $this->transactionModel = new Transaction();
    }
    
    public function index() {
        $users = $this->userModel->getAll();
        $this->render('admin/index', ['users' => $users]);
    }
    
    public function updateRole() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $this->userModel->updateRole($_POST['user_id'], $_POST['role']);
        }
        $this->redirect('/admin');
    }
    
    public function deleteUser() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $this->userModel->delete($_POST['user_id']);
        }
        $this->redirect('/admin');
    }
}
