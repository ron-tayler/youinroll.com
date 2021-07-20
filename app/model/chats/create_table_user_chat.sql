CREATE TABLE `%s` (
   `id` int NOT NULL AUTO_INCREMENT COMMENT '№ пп',
   `user_id` int NOT NULL COMMENT 'Автор сообщения',
   `message` text NULL COMMENT 'Сообщение',
   `parent` json DEFAULT NULL COMMENT 'Прикреплённые данные',
   `date_create` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT 'Дата написания',
   `date_edit` datetime DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP COMMENT 'Дата редактирования',
   `is_deleted` boolean NOT NULL DEFAULT '0' COMMENT 'Удаление',
   PRIMARY KEY (`id`),
   INDEX (`user_id`),
   INDEX (`date_create`),
   FULLTEXT (`message`)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8MB4;
