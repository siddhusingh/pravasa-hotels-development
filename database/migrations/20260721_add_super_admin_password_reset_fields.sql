ALTER TABLE `super_admin`
    ADD COLUMN IF NOT EXISTS `reset_token_hash` VARCHAR(64) NULL AFTER `password`,
    ADD COLUMN IF NOT EXISTS `reset_token_expires_at` DATETIME NULL AFTER `reset_token_hash`;

CREATE INDEX IF NOT EXISTS `idx_super_admin_reset_token_hash`
    ON `super_admin` (`reset_token_hash`);
