<?php

class AdminController extends BaseController {
    public function index() {
        if (!$this->isAuthenticated() || !$this->isAdmin()) $this->redirect('/');
        $userModel = new User();
        $users = $userModel->getAll();
        $this->render('admin/index', ['users' => $users]);
    }
    
    public function updateRole() {
        if (!$this->isAuthenticated() || !$this->isAdmin()) $this->redirect('/');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $userModel = new User();
            $userModel->updateRole($_POST['user_id'], $_POST['role']);
        }
        $this->redirect('/admin');
    }
    
    public function deleteUser() {
        if (!$this->isAuthenticated() || !$this->isAdmin()) $this->redirect('/');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $userModel = new User();
            $userModel->delete($_POST['user_id']);
        }
        $this->redirect('/admin');
    }
}
