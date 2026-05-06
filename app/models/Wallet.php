<?php

require_once __DIR__ . '/../../config/database.php';

class Wallet {
    private function getPDO() {
        return getPDO();
    }
    
    public function getByUser($userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM wallets WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    public function create($data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("INSERT INTO wallets (name, balance, currency, user_id) VALUES (?, ?, ?, ?)");
        return $stmt->execute([$data['name'], $data['balance'] ?? 0, $data['currency'] ?? 'USD', $data['user_id']]) 
            ? $pdo->lastInsertId('wallets_id_seq') 
            : false;
    }
    
    public function update($id, $data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("UPDATE wallets SET name = ?, balance = ?, currency = ? WHERE id = ? AND user_id = ?");
        return $stmt->execute([$data['name'], $data['balance'], $data['currency'], $id, $data['user_id']]);
    }
    
    public function delete($id, $userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("DELETE FROM wallets WHERE id = ? AND user_id = ?");
        return $stmt->execute([$id, $userId]);
    }
    
    public function getById($id, $userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("SELECT * FROM wallets WHERE id = ? AND user_id = ?");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
}
