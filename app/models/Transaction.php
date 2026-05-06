<?php

class Transaction extends BaseModel {
    /**
     * Create transaction and update wallet balances
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        $this->pdo->beginTransaction();
        try {
            $stmt = $this->pdo->prepare("INSERT INTO transactions (amount, type, category_id, wallet_id, target_wallet_id, description, date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $data['amount'], $data['type'], $data['category_id'], $data['wallet_id'],
                $data['target_wallet_id'] ?? null, $data['description'], $data['date']
            ]);
            $transactionId = $this->pdo->lastInsertId();
            
            $this->applyTransaction($data);
            
            $this->pdo->commit();
            return $transactionId;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Get transactions for user with filters
     * @param int $userId
     * @param array $filters
     * @param int $page
     * @param int $perPage
     * @return array
     */
    public function search($userId, $filters = [], $page = 1, $perPage = 10) {
        $where = ["w.user_id = ?"];
        $params = [$userId];
        
        if (!empty($filters['description'])) {
            $where[] = "t.description ILIKE ?";
            $params[] = "%{$filters['description']}%";
        }
        if (!empty($filters['type'])) {
            $where[] = "t.type = ?";
            $params[] = $filters['type'];
        }
        if (!empty($filters['category_id'])) {
            $where[] = "t.category_id = ?";
            $params[] = $filters['category_id'];
        }
        if (!empty($filters['wallet_id'])) {
            $where[] = "t.wallet_id = ?";
            $params[] = $filters['wallet_id'];
        }
        if (!empty($filters['date_from'])) {
            $where[] = "t.date >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $where[] = "t.date <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['amount_min'])) {
            $where[] = "t.amount >= ?";
            $params[] = $filters['amount_min'];
        }
        if (!empty($filters['amount_max'])) {
            $where[] = "t.amount <= ?";
            $params[] = $filters['amount_max'];
        }
        
        $sort = $filters['sort'] ?? 'date_desc';
        $orderBy = match($sort) {
            'date_asc' => 't.date ASC',
            'amount_asc' => 't.amount ASC',
            'amount_desc' => 't.amount DESC',
            default => 't.date DESC'
        };
        
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT t.*, c.name as category_name, w.name as wallet_name 
                FROM transactions t 
                JOIN wallets w ON t.wallet_id = w.id 
                JOIN categories c ON t.category_id = c.id 
                WHERE " . implode(' AND ', $where) . " 
                ORDER BY $orderBy 
                LIMIT ? OFFSET ?";
        
        $params[] = $perPage;
        $params[] = $offset;
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all transactions for user
     * @param int $userId
     * @return array
     */
    public function getByUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT t.*, c.name as category_name, w.name as wallet_name 
            FROM transactions t 
            JOIN wallets w ON t.wallet_id = w.id 
            JOIN categories c ON t.category_id = c.id 
            WHERE w.user_id = ?
            ORDER BY t.date DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get transaction by id for user
     * @param int $id
     * @param int $userId
     * @return array|false
     */
    public function getById($id, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT t.* FROM transactions t 
            JOIN wallets w ON t.wallet_id = w.id 
            WHERE t.id = ? AND w.user_id = ?
        ");
        $stmt->execute([$id, $userId]);
        return $stmt->fetch();
    }
    
    /**
     * Update transaction
     * @param int $id
     * @param array $data
     * @param int $userId
     * @return bool
     */
    public function update($id, $data, $userId) {
        $this->pdo->beginTransaction();
        try {
            $old = $this->getById($id, $userId);
            if (!$old) return false;
            
            // Reverse old transaction
            $this->reverseTransaction($old);
            
            // Apply new transaction
            $stmt = $this->pdo->prepare("UPDATE transactions SET amount = ?, type = ?, category_id = ?, wallet_id = ?, target_wallet_id = ?, description = ?, date = ? WHERE id = ?");
            $stmt->execute([
                $data['amount'], $data['type'], $data['category_id'], $data['wallet_id'],
                $data['target_wallet_id'] ?? null, $data['description'], $data['date'], $id
            ]);
            
            $this->applyTransaction($data);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    /**
     * Delete transaction
     * @param int $id
     * @param int $userId
     * @return bool
     */
    public function delete($id, $userId) {
        $this->pdo->beginTransaction();
        try {
            $old = $this->getById($id, $userId);
            if (!$old) return false;
            
            $this->reverseTransaction($old);
            
            $stmt = $this->pdo->prepare("DELETE FROM transactions WHERE id = ?");
            $stmt->execute([$id]);
            
            $this->pdo->commit();
            return true;
        } catch (Exception $e) {
            $this->pdo->rollBack();
            return false;
        }
    }
    
    private function applyTransaction($data) {
        if ($data['type'] === 'income') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '+');
        } elseif ($data['type'] === 'expense') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '-');
        } elseif ($data['type'] === 'transfer') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '-');
            $this->updateWalletBalance($data['target_wallet_id'], $data['amount'], '+');
        }
    }
    
    private function reverseTransaction($data) {
        if ($data['type'] === 'income') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '-');
        } elseif ($data['type'] === 'expense') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '+');
        } elseif ($data['type'] === 'transfer') {
            $this->updateWalletBalance($data['wallet_id'], $data['amount'], '+');
            $this->updateWalletBalance($data['target_wallet_id'], $data['amount'], '-');
        }
    }
    
    private function updateWalletBalance($walletId, $amount, $operation) {
        $stmt = $this->pdo->prepare("UPDATE wallets SET balance = balance $operation ? WHERE id = ?");
        $stmt->execute([$amount, $walletId]);
    }
}
