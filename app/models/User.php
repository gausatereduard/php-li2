<?php

class User extends BaseModel {
    /**
     * Register new user
     * @param array $data
     * @return int|false
     */
    public function register($data) {
        $stmt = $this->pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
        $hashed = password_hash($data['password'], PASSWORD_DEFAULT);
        return $stmt->execute([$data['username'], $data['email'], $hashed]) ? $this->pdo->lastInsertId() : false;
    }
    
    /**
     * Find user by email
     * @param string $email
     * @return array|false
     */
    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }
    
    /**
     * Find user by username
     * @param string $username
     * @return array|false
     */
    public function findByUsername($username) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }
    
    /**
     * Get latest registered users
     * @param int $limit
     * @return array
     */
    public function getLatest($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT username, created_at FROM users ORDER BY created_at DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all users (admin)
     * @return array
     */
    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM users ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }
    
    /**
     * Update user role
     * @param int $userId
     * @param string $role
     * @return bool
     */
    public function updateRole($userId, $role) {
        $stmt = $this->pdo->prepare("UPDATE users SET role = ? WHERE id = ?");
        return $stmt->execute([$role, $userId]);
    }
    
    /**
     * Delete user
     * @param int $userId
     * @return bool
     */
    public function delete($userId) {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$userId]);
    }
}
