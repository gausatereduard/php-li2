<?php

require_once __DIR__ . '/../../config/database.php';

class Category {
    private function getPDO() {
        return getPDO();
    }
    
    public function getForUser($userId = null) {
        $pdo = $this->getPDO();
        $sql = "SELECT * FROM categories WHERE user_id IS NULL OR user_id = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("INSERT INTO categories (name, type, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['name'], $data['type'], $data['user_id'] ?? null]) 
            ? $pdo->lastInsertId('categories_id_seq') 
            : false;
    }
    
    public function update($id, $data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("UPDATE categories SET name = ?, type = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$data['name'], $data['type'], $id, $data['user_id']]);
    }
    
    public function delete($id, $userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
    
    public function getLatestPublic($limit = 5) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT name, type FROM categories WHERE user_id IS NULL ORDER BY id DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    public function getById($id, $userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ? AND (user_id IS NULL OR user_id = ?)");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
}
