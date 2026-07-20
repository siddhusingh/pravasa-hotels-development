ALTER TABLE `facebook_forms`
    ADD COLUMN `is_deleted` TINYINT(1) NOT NULL DEFAULT 0 AFTER `id`,
    ADD COLUMN `deleted_at` DATETIME NULL DEFAULT NULL AFTER `is_deleted`,
    ADD INDEX `idx_facebook_forms_department_deleted` (`department_id`, `is_deleted`),
    ADD INDEX `idx_facebook_forms_form_deleted` (`form_id`, `is_deleted`);
