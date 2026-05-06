<?php

class CategoryController extends BaseController {
    public function index() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $categoryModel = new Category();
        $categories = $categoryModel->getForUser($_SESSION['user_id']);
        $this->render('categories/index', ['categories' => $categories]);
    }
    
    public function create() {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $categoryModel = new Category();
            $categoryModel->create($data);
            $this->redirect('/categories');
        }
        $this->render('categories/create');
    }
    
    public function edit($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        $categoryModel = new Category();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $data = $_POST;
            $data['user_id'] = $_SESSION['user_id'];
            $categoryModel->update($id, $data);
            $this->redirect('/categories');
        }
        $category = $categoryModel->getById($id, $_SESSION['user_id']);
        $this->render('categories/edit', ['category' => $category]);
    }
    
    public function delete($id) {
        if (!$this->isAuthenticated()) $this->redirect('/login');
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!validateCsrfToken($_POST['csrf_token'])) die('CSRF failed');
            $categoryModel = new Category();
            $categoryModel->delete($id, $_SESSION['user_id']);
        }
        $this->redirect('/categories');
    }
}
