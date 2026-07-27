ALTER TABLE `weekly_planner`
    ADD COLUMN IF NOT EXISTS `sales_user_id` INT(11) NULL AFTER `description`,
    ADD COLUMN IF NOT EXISTS `approval_status`
        ENUM('pending', 'approved') NOT NULL DEFAULT 'approved'
        AFTER `sales_user_id`,
    ADD COLUMN IF NOT EXISTS `approved_by` INT(11) NULL
        AFTER `approval_status`,
    ADD COLUMN IF NOT EXISTS `approved_at` DATETIME NULL
        AFTER `approved_by`;

ALTER TABLE `weekly_planner`
    ADD INDEX IF NOT EXISTS `idx_weekly_planner_sales_user`
        (`sales_user_id`, `approval_status`, `is_deleted`);
