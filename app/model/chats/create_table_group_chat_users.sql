CREATE TABLE `%s` (
    `id` int NOT NULL AUTO_INCREMENT COMMENT '№ пп',
    `user_id` int NOT NULL COMMENT 'Автор сообщения',
    `permission` ENUM('admin','moder','user') NOT NULL DEFAULT 'user' COMMENT 'Роль',
    `is_kick` boolean NOT NULL DEFAULT '0' COMMENT 'Вышел',
    `is_ban` boolean NOT NULL DEFAULT '0' COMMENT 'Выгнали',
    PRIMARY KEY (`id`),
    INDEX (`user_id`)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8MB4;
