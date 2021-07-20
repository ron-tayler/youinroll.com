CREATE TABLE `%s` (
    `id` int NOT NULL AUTO_INCREMENT COMMENT '№ пп',
    `group_chat_id` int NOT NULL COMMENT 'ID чата',
    PRIMARY KEY (`id`),
    INDEX (`group_chat_id`)
) ENGINE = InnoDB DEFAULT CHARSET = UTF8MB4;
