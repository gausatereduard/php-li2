<?php

class AuthController extends BaseController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function showRegister() {
        $this->render('auth/register');
    }
    
    public function register() {
        if (!validateCsrfToken($_POST['csrf_token'])) {
            die('CSRF validation failed');
        }
        
        $errors = [];
        $data = $_POST;
        
        if (empty($data['username'])) $errors['username'] = 'Username required';
        if (empty($data['email'])) $errors['email'] = 'Email required';
        if (empty($data['password'])) $errors['password'] = 'Password required';
        if ($data['password'] !== $data['confirm_password']) $errors['confirm_password'] = 'Passwords do not match';
        if ($this->userModel->findByEmail($data['email'])) $errors['email'] = 'Email already exists';
        if ($this->userModel->findByUsername($data['username'])) $errors['username'] = 'Username already exists';
        
        if (empty($errors)) {
            $userId = $this->userModel->register($data);
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $data['username'];
            $_SESSION['role'] = 'user';
            $this->redirect('/');
        }
        
        $this->render('auth/register', ['errors' => $errors, 'data' => $data]);
    }
    
    public function showLogin() {
        $this->render('auth/login');
    }
    
    public function login() {
        if (!validateCsrfToken($_POST['csrf_token'])) {
            die('CSRF validation failed');
        }
        
        $errors = [];
        $data = $_POST;
        
        $user = $this->userModel->findByEmail($data['email']) ?? $this->userModel->findByUsername($data['email']);
        
        if (!$user || !password_verify($data['password'], $user['password_hash'])) {
            $errors['auth'] = 'Invalid credentials';
        }
        
        if (empty($errors)) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $this->redirect('/');
        }
        
        $this->render('auth/login', ['errors' => $errors, 'data' => $data]);
    }
    
    public function logout() {
        session_destroy();
        $this->redirect('/');
    }
}
