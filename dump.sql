
CREATE TABLE `dl_xlsx_data` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `tab_name` varchar(255) NOT NULL,
    `url` varchar(255) NOT NULL,
    `col0` text,
    `col1` text,
    `col2` text,
    `col3` text,
    `col4` text,
    `col5` text,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


CREATE TABLE `dl_event_fields` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `event_id` int unsigned NOT NULL,
    `parent_id` int unsigned DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `value` varchar(255) NOT NULL,
    `type` tinyint unsigned,
    PRIMARY KEY (`id`),
    KEY `parent` (`parent_id`),
    CONSTRAINT `fk_event` FOREIGN KEY (`event_id`) REFERENCES `dl_xlsx_data` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_parent` FOREIGN KEY (`parent_id`) REFERENCES `dl_event_fields` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


CREATE TABLE `dl_unique_fields` (
    `id` int unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(255) DEFAULT NULL,
    `implemented` tinyint unsigned NOT NULL,
    `type` tinyint unsigned NOT NULL,
    `type_name` varchar(255) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;


UPDATE dl_event_fields
    JOIN dl_unique_fields ON dl_unique_fields.name = dl_event_fields.name
    SET dl_event_fields.type = dl_unique_fields.type;

UPDATE dl_event_fields SET value='' WHERE value=',';
