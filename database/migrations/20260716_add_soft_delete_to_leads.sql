ALTER TABLE `leads`
    ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `id`,
    ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `is_deleted`,
    ADD INDEX `idx_leads_is_deleted` (`is_deleted`);
