<?php

class Budget extends BaseModel {
    /**
     * Get budgets with spent amounts for user
     * @param int $userId
     * @return array
     */
    public function getForUser($userId) {
        $stmt = $this->pdo->prepare("
            SELECT b.*, c.name as category_name, c.type 
            FROM budgets b 
            JOIN categories c ON b.category_id = c.id 
            WHERE b.user_id = ?
        ");
        $stmt->execute([$userId]);
        $budgets = $stmt->fetchAll();
        
        foreach ($budgets as &$budget) {
            $budget['spent'] = $this->getSpentAmount($budget['category_id'], $userId);
            $budget['percentage'] = $budget['limit_amount'] > 0 
                ? min(100, ($budget['spent'] / $budget['limit_amount']) * 100) 
                : 0;
        }
        
        return $budgets;
    }
    
    /**
     * Calculate spent amount for category in current month
     * @param int $categoryId
     * @param int $userId
     * @return float
     */
    private function getSpentAmount($categoryId, $userId) {
        $stmt = $this->pdo->prepare("
            SELECT COALESCE(SUM(t.amount), 0) as total 
            FROM transactions t 
            JOIN wallets w ON t.wallet_id = w.id 
            WHERE t.category_id = ? AND w.user_id = ? 
            AND DATE_TRUNC('month', t.date) = DATE_TRUNC('month', CURRENT_DATE)
            AND t.type = 'expense'
        ");
        $stmt->execute([$categoryId, $userId]);
        return $stmt->fetch()['total'];
    }
    
    /**
     * Create budget
     * @param array $data
     * @return int|false
     */
    public function create($data) {
        $stmt = $this->pdo->prepare("INSERT INTO budgets (category_id, limit_amount, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['category_id'], $data['limit_amount'], $data['user_id']]) 
            ? $this->pdo->lastInsertId() 
            : false;
    }
}
