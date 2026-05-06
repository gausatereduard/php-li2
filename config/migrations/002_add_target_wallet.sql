ALTER TABLE transactions ADD COLUMN IF NOT EXISTS target_wallet_id INT;
ALTER TABLE transactions ADD FOREIGN KEY (target_wallet_id) REFERENCES wallets(id) ON DELETE CASCADE;
