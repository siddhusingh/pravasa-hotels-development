ALTER TABLE `sales_visits`
    ADD COLUMN IF NOT EXISTS `attachment_image` VARCHAR(255) NULL AFTER `total_amount`,
    ADD COLUMN IF NOT EXISTS `latitude` DECIMAL(10, 8) NULL AFTER `attachment_image`,
    ADD COLUMN IF NOT EXISTS `longitude` DECIMAL(11, 8) NULL AFTER `latitude`,
    ADD COLUMN IF NOT EXISTS `location_details` VARCHAR(255) NULL AFTER `longitude`;
