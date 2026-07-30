CREATE TABLE IF NOT EXISTS `lead_reserved_tables` (
    `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    `lead_id` INT(11) NOT NULL,
    `table_id` INT(11) NOT NULL,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_lead_reserved_table` (`lead_id`, `table_id`),
    KEY `idx_reserved_table_lead` (`lead_id`),
    KEY `idx_reserved_table_table` (`table_id`),
    CONSTRAINT `fk_reserved_table_lead`
        FOREIGN KEY (`lead_id`) REFERENCES `leads` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_reserved_table_table`
        FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- Preserve the table already stored by older restaurant bookings.
INSERT IGNORE INTO `lead_reserved_tables` (`lead_id`, `table_id`)
SELECT `id`, `table_id`
FROM `leads`
WHERE `is_deleted` = 0
  AND `restaurant_id` IS NOT NULL
  AND `restaurant_id` > 0
  AND `table_id` IS NOT NULL
  AND `table_id` > 0;
