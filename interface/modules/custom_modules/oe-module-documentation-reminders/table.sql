CREATE TABLE IF NOT EXISTS `module_documentation_reminders`(
    `id` INT(11)  PRIMARY KEY AUTO_INCREMENT NOT NULL,
    `userid` VARCHAR(255) NULL,
    `pid` INT(5) NULL,
    `documentation` VARCHAR(255) NULL,
    `encounter_type` VARCHAR(255) NULL,
    `time_interval` INT(2) NULL,
    `reminder_sent` DATE NULL,
    `last_updated` DATE NULL
    ) ENGINE = InnoDB COMMENT = 'Store reminders';