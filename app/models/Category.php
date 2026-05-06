<?php

class Category extends BaseModel {
    /**
     * Get categories for user (including global)
     * @param int|null $userId
     * @return array
     */
    public function getForUser($userId = null) {
        $sql = "SELECT * FROM categories WHERE user_id IS NULL OR user_id = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Create category
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO categories (name, type, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['name'], $data['type'], $data['user_id'] ?? null]) 
            ? $this->pdo->lastInsertId() 
            : false;
    }
    
    /**
     * Update category
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE categories SET name = ?, type = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$data['name'], $data['type'], $id, $data['user_id']]);
    }
    
    /**
     * Delete user category
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM categories WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
    
    /**
     * Get latest public categories
     * @param int $limit
     * @return array
     */
    public function getLatestPublic($limit = 5) {
        $stmt = $this->pdo->prepare("SELECT name, type FROM categories WHERE user_id IS NULL ORDER BY id DESC LIMIT ?");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get category by id for user
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getById($id, $userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM categories WHERE id = ? AND (user_id IS NULL OR user_id = ?)");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
}
