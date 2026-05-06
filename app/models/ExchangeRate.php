<?php

require_once __DIR__ . '/../../config/database.php';

class ExchangeRate {
    private function getPDO() {
        static $initialized = false;
        if (!$initialized) {
            $pdo = getPDO();
            $pdo->exec("CREATE TABLE IF NOT EXISTS exchange_rates (
                id SERIAL PRIMARY KEY,
                base_currency VARCHAR(3) NOT NULL,
                target_currency VARCHAR(3) NOT NULL,
                rate DECIMAL(15,6) NOT NULL,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                UNIQUE(base_currency, target_currency)
            )");
            $initialized = true;
        }
        return getPDO();
    }

    public function getAll() {
        $pdo = $this->getPDO();
        $stmt = $pdo->query("SELECT * FROM exchange_rates ORDER BY target_currency");
        return $stmt->fetchAll();
    }

    public function getNeedsUpdate() {
        $pdo = $this->getPDO();
        $stmt = $pdo->query("SELECT * FROM exchange_rates WHERE updated_at < NOW() - INTERVAL '24 hours'");
        return $stmt->fetchAll();
    }

    public function updateRates() {
        $currencies = ['USD', 'EUR'];
        $pdo = $this->getPDO();
        
        try {
            $context = stream_context_create([
                'http' => ['timeout' => 10]
            ]);
            $response = @file_get_contents('https://api.frankfurter.app/latest?from=MDL', false, $context);
            
            if ($response === false) {
                return false;
            }
            
            $data = json_decode($response, true);
            if (!isset($data['rates'])) {
                return false;
            }
            
            foreach ($currencies as $currency) {
                if (isset($data['rates'][$currency])) {
                    $stmt = $pdo->prepare("
                        INSERT INTO exchange_rates (base_currency, target_currency, rate, updated_at)
                        VALUES ('MDL', ?, ?, NOW())
                        ON CONFLICT (base_currency, target_currency) 
                        DO UPDATE SET rate = EXCLUDED.rate, updated_at = NOW()
                    ");
                    $stmt->execute([$currency, $data['rates'][$currency]]);
                }
            }
            
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getOrUpdate() {
        $rates = $this->getAll();
        
        if (empty($rates) || count($rates) < 2) {
            $this->updateRates();
            return $this->getAll();
        }
        
        $needsUpdate = false;
        foreach ($rates as $rate) {
            $updated = strtotime($rate['updated_at']);
            if (time() - $updated > 86400) {
                $needsUpdate = true;
                break;
            }
        }
        
        if ($needsUpdate) {
            $this->updateRates();
            return $this->getAll();
        }
        
        return $rates;
    }
}