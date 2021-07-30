<?php

namespace Model\Chats;
use Engine\IModel;
use Engine\Response;
use Library\DB;

/**
 * Модель для работы с личными сообщениями
 * @package Model\Chats
 * @author Ron_Tayler
 */
class User implements IModel{
    static DB $db;

    /**
     * Init
     * @throws
     */
    static function init(){
        self::$db = DB::init('base');
    }

    /**
     * Отправка личного сообщения
     * @param int $author_id Автор сообщения
     * @param int $peer_id Получатель сообщения
     * @param string $message Текстовое сообщение
     * @param array $parent Массив прикреплённых данных
     * @return int ID отправленного сообщения
     * @throws
     */
    static function sendMessage($author_id,$peer_id, $message,array $parent = null){
        $first_id = min($author_id,$peer_id);
        $second_id = max($author_id,$peer_id);

        $table_name = "user_chat_{$first_id}_{$second_id}";
        // Проверка существования таблицы
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        if($tables->num_rows<1){
            // Создание таблицы
            $sql = file_get_contents(__DIR__.'/create_table_user_chat.sql');
            self::$db->query(sprintf($sql,$table_name));
        }

        // Отправка сообщения
        $sql = file_get_contents(__DIR__.'/insert_user_chat.sql');
        self::$db->query(sprintf($sql,
            $table_name,
            $author_id,
            $message,
            json_encode($parent??[])
        ));

        // Возврат нового ID
        return self::$db->getLastId();
    }

    static function getMessage(int $author_id, int $peer_id, int $message_id){
        $first_id = min($author_id, $peer_id);
        $second_id = max($author_id, $peer_id);

        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE 'user_chat_{$first_id}_{$second_id}'");

        if($tables->num_rows==0) return false;

        $message_db = self::$db->query("SELECT * FROM user_chat_{$first_id}_{$second_id} WHERE id = {$message_id}");
        if($message_db->num_rows==0) return false;
        $message = $message_db->row;

        $message['parent'] = json_decode($message['parent']);
        foreach ($message['parent'] as $parent) {
            if ($parent->type === 'notify') {
                $message['type'] = 'notify';
            }
        }

        return $message->row;
    }

    /**
     * Редактирование сообщения
     * @param int $author_id Автор сообщения
     * @param int $peer_id Получатель сообщения
     * @param int $message_id ID сообщения
     * @param string $new_message Новое сообщения
     * @param arrow $new_parent Новый массив прикреплённых данных
     * @return bool true если удалось
     * @throws
     */
    static function editMessage($author_id,$peer_id, $message_id, $new_message = null, $new_parent = null){
        $first_id = min($author_id,$peer_id);
        $second_id = max($author_id,$peer_id);
        $table_name = "user_chat_{$first_id}_{$second_id}";

        // Проверка существования таблицы
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        if($tables->num_rows<1) return false;

        // Проверить наличие сообщения
        $message = self::$db->query("SELECT * FROM $table_name WHERE id=$message_id");
        if($message->num_rows<1) return false;

        // проверить авторство сообщения
        if($message->row['user_id']!=$author_id) return false;

        // Отредактировать сообщение
        $new_message = $new_message??$message->row['message'];
        $new_parent = isset($new_parent)?json_encode($new_parent):$message->row['parent'];

        self::$db->query("
            UPDATE `{$table_name}` SET 
                `user_id`= {$author_id},
                `message`= '{$new_message}',
                `parent`= '{$new_parent}'
            WHERE id = {$message_id}
        ");

        return true;
    }

    /**
     * Удаление сообщения
     * @param int $author_id Автор сообщения
     * @param int $peer_id Получатель сообщения
     * @param int $message_id ID сообщения
     * @return bool true если удалось
     * @throws
     */
    static function deleteMessage($author_id, $peer_id, $message_id){
        $first_id = min($author_id,$peer_id);
        $second_id = max($author_id,$peer_id);
        $table_name = "user_chat_{$first_id}_{$second_id}";

        // Проверка существования таблицы
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        if($tables->num_rows<1) return false;

        // Проверить наличие сообщения
        $message = self::$db->query("SELECT * FROM $table_name WHERE id=$message_id");
        if($message->num_rows<1) return false;

        if($message->row['user_id']!=$author_id) return false;

        // Удалить сообщение
        self::$db->query("
            UPDATE `{$table_name}` SET 
                `is_deleted`= true,
            WHERE id = {$message_id}
        ");

        return true;
    }

    /**
     * Получить последние 25 сообщений
     * @param int $author_id Автор сообщений
     * @param int $peer_id Получатель сообщений
     * @param int $offset Смещение
     * @return array
     * @throws
     */
    static function getMessages($author_id,$peer_id,$offset = 0){
        $first_id = min($author_id,$peer_id);
        $second_id = max($author_id,$peer_id);
        $table_name = "user_chat_{$first_id}_{$second_id}";

        // Проверка существования таблицы
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        if($tables->num_rows<1) return [];

        $messages = self::$db->query("SELECT * FROM {$table_name} WHERE is_deleted=0 ORDER BY id DESC LIMIT $offset,25");

        return $messages->rows;
    }

}
