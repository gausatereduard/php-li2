<?php

require_once __DIR__ . '/../../config/database.php';

class BaseModel {
    protected $pdo;
    
    public function __construct() {
        $this->pdo = getPDO();
    }
}
