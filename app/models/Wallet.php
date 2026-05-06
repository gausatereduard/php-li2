<?php

class Wallet extends BaseModel {
    /**
     * Get all wallets for user
     * @param int $userId
     * @return array
     */
    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Create new wallet
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO wallets (name, balance, currency, user_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['balance'] ?? 0, $data['currency'] ?? 'USD', $data['user_id']]) 
            ? $this->pdo->lastInsertId() 
            : false;
    }
    
    /**
     * Update wallet
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update($id, $data) {
        $stmt = $this->pdo->prepare("UPDATE wallets SET name = ?, balance = ?, currency = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$data['name'], $data['balance'], $data['currency'], $id, $data['user_id']]);
    }
    
    /**
     * Delete wallet
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        $stmt = $this->pdo->prepare("DELETE FROM wallets WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
}
