<?php

require_once __DIR__ . '/../../config/database.php';

class Budget {
    private function getPDO() {
        return getPDO();
    }
    
    public function getForUser($userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("
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
    
    private function getSpentAmount($categoryId, $userId) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("
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
    
    public function create($data) {
        $pdo = $this->getPDO();
        $stmt = $pdo->prepare("INSERT INTO budgets (category_id, limit_amount, user_id) VALUES (?, ?, ?)");
        return $stmt->execute([$data['category_id'], $data['limit_amount'], $data['user_id']]) 
            ? $pdo->lastInsertId('budgets_id_seq') 
            : false;
    }
}
