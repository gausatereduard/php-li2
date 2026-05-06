<?php

require_once __DIR__ . '/../../config/database.php';

class User {
    private function getPDO() {
        return getPDO();
    }
    
    public function register($data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([$data['username'], $data['email'], $hashed]) ? $pdo->lastInsertId('users_id_seq') : false;
    }
    
    public function findByEmail($email) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    public function findByUsername($username) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    public function getLatest($limit = 5) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getAll() {
        $pdo = $this->getPDO();
        $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    public function updateRole($userId, $role) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $userId]);
    }
    
    public function delete($userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}
