ALTER TABLE `sales_visits`
    ADD COLUMN IF NOT EXISTS `follow_up_1_date` DATE NULL AFTER `report_date`,
    ADD COLUMN IF NOT EXISTS `follow_up_2_date` DATE NULL AFTER `follow_up_1_date`,
    ADD COLUMN IF NOT EXISTS `visit_type` ENUM('Relationship Visit', 'Follow-up Visit', 'Support & Service') NULL AFTER `follow_up_2_date`,
    ADD COLUMN IF NOT EXISTS `visit_mode` ENUM('Physical Visit', 'Online Meeting', 'Phone Call', 'Teams Meeting', 'Google Meet') NULL AFTER `visit_type`;

UPDATE `sales_visits`
SET
    `follow_up_1_date` = COALESCE(`follow_up_1_date`, `report_date`, CURRENT_DATE),
    `follow_up_2_date` = COALESCE(`follow_up_2_date`, `report_date`, CURRENT_DATE),
    `visit_type` = COALESCE(`visit_type`, 'Relationship Visit'),
    `visit_mode` = COALESCE(`visit_mode`, 'Physical Visit')
WHERE
    `follow_up_1_date` IS NULL
    OR `follow_up_2_date` IS NULL
    OR `visit_type` IS NULL
    OR `visit_mode` IS NULL;

ALTER TABLE `sales_visits`
    MODIFY COLUMN `follow_up_1_date` DATE NOT NULL,
    MODIFY COLUMN `follow_up_2_date` DATE NOT NULL,
    MODIFY COLUMN `visit_type` ENUM('Relationship Visit', 'Follow-up Visit', 'Support & Service') NOT NULL DEFAULT 'Relationship Visit',
    MODIFY COLUMN `visit_mode` ENUM('Physical Visit', 'Online Meeting', 'Phone Call', 'Teams Meeting', 'Google Meet') NOT NULL DEFAULT 'Physical Visit';
