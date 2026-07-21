CREATE TABLE `pms_configurations` (
    `id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `provider_code` VARCHAR(50) NOT NULL,
    `api_url` VARCHAR(500) NOT NULL,
    `auth_code_encrypted` TEXT NOT NULL,
    `client_id` VARCHAR(150) NOT NULL,
    `chain_code` VARCHAR(100) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_pms_provider_code` (`provider_code`)
) ENGINE=InnoDB
DEFAULT CHARSET=utf8mb4
COLLATE=utf8mb4_unicode_ci;
