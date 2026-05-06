<?php

class BudgetController extends BaseController {
    public function index() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $budgetModel = new Budget();
        $categoryModel = new Category();
        $budgets = $budgetModel->getForUser($_SESSION['user_id']);
        $this->render('budgets/index', ['budgets' => $budgets]);
    }
    
    public function create() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $categoryModel = new Category();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $budgetModel = new Budget();
            $budgetModel->create($data);
            $this->redirect('/budget');
        }
        
        $categories = $categoryModel->getForUser($_SESSION['user_id']);
        $this->render('budgets/create', ['categories' => $categories]);
    }
}
