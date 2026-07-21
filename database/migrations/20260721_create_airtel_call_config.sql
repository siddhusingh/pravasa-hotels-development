CREATE TABLE `airtel_call_config` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `api_url` VARCHAR(500) NOT NULL,
    `api_key_encrypted` TEXT NOT NULL,
    `caller_id` VARCHAR(20) NOT NULL,
    `customer_id` VARCHAR(150) NOT NULL,
    `call_flow_id` TEXT NOT NULL,
    `notify_url` VARCHAR(500) NOT NULL,
    `is_active` TINYINT(1) NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    KEY `idx_airtel_config_active` (`is_active`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
