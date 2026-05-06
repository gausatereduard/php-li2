<?php

class BudgetController extends BaseController {
    private $budgetModel;
    private $categoryModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $this->budgetModel = new Budget();
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $budgets = $this->budgetModel->getForUser($_SESSION['user_id']);
        $this->render('budgets/index', ['budgets' => $budgets]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $this->budgetModel->create($data);
            $this->redirect('/budget');
        }
        
        $categories = $this->categoryModel->getForUser($_SESSION['user_id']);
        $this->render('budgets/create', ['categories' => $categories]);
    }
}
