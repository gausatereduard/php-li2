<?php

class CategoryController extends BaseController {
    private $categoryModel;
    
    public function __construct() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $this->categoryModel = new Category();
    }
    
    public function index() {
        $categories = $this->categoryModel->getForUser($_SESSION['user_id']);
        $this->render('categories/index', ['categories' => $categories]);
    }
    
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $this->categoryModel->create($data);
            $this->redirect('/categories');
        }
        $this->render('categories/create');
    }
    
    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $this->categoryModel->update($id, $data);
            $this->redirect('/categories');
        }
        $category = $this->categoryModel->getById($id, $_SESSION['user_id']);
        $this->render('categories/edit', ['category' => $category]);
    }
    
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $this->categoryModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/categories');
    }
}
