<?php namespace Model\Chats;
use Engine\IModel;
use Engine\Response;
use ErrorServer;
use Exception;
use Library\DB;

/**
 * Модель для работы с групповыми чатами
 * @package Model\Chats
 * @author Ron_Tayler
 */
class Group implements IModel{
    const PERM_ADMIN = 'admin';
    const PERM_MODER = 'moder';
    const PERM_USER = 'user';

    static DB $db;

    /**
     * Init
     * @throws
     */
    static function init(){
        self::$db = DB::init('base');
    }

    static function createChat($owner_id,$name){

        $name = self::$db->escape($name);
        self::$db->query("INSERT INTO group_chats(name) VALUES ('$name')");
        $chat_id = self::$db->getLastId();

        self::createTables_groupChat($chat_id);

        // Проверка
        if( !self::isCreatedTable_groupChatMessages($chat_id) or
            !self::isCreatedTable_groupChatUsers($chat_id)
        ) throw new ErrorServer(__LINE__.": Не созданы таблицы группового чата $chat_id");

        // Добавление владельца в чат
        self::addUser($chat_id,$owner_id,seLf::PERM_ADMIN);

        return $chat_id;
    }

    static function addUser($chat_id,$user_id,$permission = self::PERM_USER){
        // TODO Проверить нет ли пользователя уже в чате

        // Добавить пользователя в чат
        self::$db->query("INSERT INTO group_chat_{$chat_id}_users(user_id,permission) VALUES ({$user_id},'{$permission}')");

        if (!self::isCreatedTable_userGroupChats($user_id)) self::createTable_UserGroupChats($user_id);

        // Добавить чат пользователю
        self::$db->query("INSERT INTO user_{$user_id}_group_chats(group_chat_id) VALUES ({$chat_id})");
        $peer_id = self::$db->getLastId();

        // Отправить сообщение в чат о добавлении пользователя
        self::sendMessage($user_id,$peer_id,"",[['type'=>'notify','code'=>'new_user','user_id'=>$user_id]]);

        return true;
    }
    static function kickUser(){}
    static function banUser(){}
    static function getUser(){}
    static function getUsers(){}
    static function getInfo($chat_id){
        $chat = self::$db->query("SELECT * FROM group_chats WHERE id = {$chat_id}");
        if($chat->num_rows==0) throw new \ErrorNotFound();
        return $chat->row;
    }
    static function setPermission($user_id,$chat_id,$permission){}
    static function getPeerId4ChatId(int $user_id, int $chat_id){
        if (!self::isCreatedTable_userGroupChats($user_id)) self::createTable_UserGroupChats($user_id);

        $chat = self::$db->query("SELECT * FROM user_{$user_id}_group_chats WHERE group_chat_id = {$chat_id}");
        if($chat->num_rows<1) throw new \ErrorNotFound("Не найден peer_id у chat_id: {$chat_id}");

        return $chat->row['id'];
    }
    static function getChatId4PeerId(int $user_id, int $peer_id){
        if (!self::isCreatedTable_userGroupChats($user_id)) self::createTable_UserGroupChats($user_id);

        $chat = self::$db->query("SELECT * FROM user_{$user_id}_group_chats WHERE id = {$peer_id}");
        if($chat->num_rows<1) throw new \ErrorNotFound("Не найден chat_id c peer_id: {$peer_id}");

        return $chat->row['group_chat_id'];
    }

    static function getGroupChats($user_id){
        if(!self::isCreatedTable_userGroupChats($user_id)) self::createTable_UserGroupChats($user_id);

        $chats = self::$db->query("SELECT * FROM user_{$user_id}_group_chats");

        return ($chats->num_rows>0)?$chats->rows:[];
    }

    static function sendMessage(int $author_id, int $peer_id, string $message, array $parent = null){

        // Проверить наличие таблицы у пользователя
        if (!self::isCreatedTable_userGroupChats($author_id)) self::createTable_UserGroupChats($author_id);

        // Получить беседу из списка пользователя
        $user_group_chat = self::$db->query("SELECT * FROM user_{$author_id}_group_chats WHERE id = {$peer_id}");
        if($user_group_chat->num_rows<1) throw new \ErrorNotFound("У пользователя отсутствует беседа, peer_id: $peer_id");
        $chat_id = $user_group_chat->row['group_chat_id'];

        // Проверка существования таблицы
        if( !self::isCreatedTable_groupChatMessages($chat_id) or
            !self::isCreatedTable_groupChatUsers($chat_id)
        ) throw new ErrorServer(__LINE__.": Не созданы таблицы группового чата $chat_id");

        // Проверка Бана, Кика
        $user = self::$db->query("SELECT * FROM group_chat_{$chat_id}_users WHERE user_id = {$author_id}");
        if($user->num_rows<1) {
            self::addUser($chat_id, $author_id);
            $user = self::$db->query("SELECT * FROM group_chat_{$chat_id}_users WHERE user_id = {$author_id}");
        }

        if($user->row['is_ban']>0) throw new \ErrorForbidden("Пользователя выгнали из чата, peer_id: $peer_id, chat_id: $chat_id");
        if($user->row['is_kick']>0) self::addUser($chat_id, $author_id);

        // Отправка сообщения
        $message = self::$db->escape($message);
        $parent = json_encode($parent??[],JSON_UNESCAPED_UNICODE);
        self::$db->query("INSERT INTO group_chat_{$chat_id}_messages(user_id,message,parent) VALUES ({$author_id},'{$message}','{$parent}')");

        // Возврат ID нового сообщения
        return self::$db->getLastId();
    }
    static function editMessage(int $author_id, int $chat_id, int $message_id, string $message, array $parent = null){}
    static function deleteMessage(int $author_id, int $chat_id, int $message_id){}
    static function getMessage(int $chat_id, int $message_id){
        if(!self::isCreatedTable_groupChatMessages($chat_id)) return false;

        $messages = self::$db->query("SELECT * FROM group_chat_{$chat_id}_messages WHERE id={$message_id} AND is_deleted=0");
        if($messages->num_rows==0) return false;
        $message = $messages->row;
        $message['parent'] = json_decode($message['parent']);
        foreach ($message['parent'] as $parent) {
            if ($parent->type === 'notify') {
                $message['type'] = 'notify';
            }
        }
        return $message;
    }
    static function getLastMessages(int $chat_id, int $offset = 0){
        $messages = self::$db->query("SELECT * FROM group_chat_{$chat_id}_messages WHERE is_deleted=0 ORDER BY id LIMIT {$offset},25");
        if($messages->num_rows==0) return [];
        foreach ($messages->rows as &$message){
            $message['parent'] = json_decode($message['parent']);
            foreach ($message['parent'] as $parent){
                if($parent->type==='notify'){
                    $message['type'] = 'notify';
                }
            }
        }
        return $messages->rows;
    }
    static function getNewMessages(int $chat_id, int $ts = 0){
        $messages = self::$db->query("SELECT * FROM group_chat_{$chat_id}_messages WHERE id>{$ts} AND is_deleted=0 ORDER BY id LIMIT 0,25");
        if($messages->num_rows==0) return [];
        foreach ($messages->rows as &$message){
            $message['parent'] = json_decode($message['parent']);
            foreach ($message['parent'] as $parent){
                if($parent->type==='notify'){
                    $message['type'] = 'notify';
                }
            }
        }
        return $messages->rows;
    }

    private static function createTable_UserGroupChats($user_id){
        $table_name = "user_{$user_id}_group_chats";

        $table_sql = sprintf(file_get_contents(__DIR__.'/create_table_user_group_chats.sql'),$table_name);

        self::$db->query($table_sql);
    }
    private static function createTables_groupChat($chat_id){
        $table_messages = "group_chat_{$chat_id}_messages";
        $table_users = "group_chat_{$chat_id}_users";

        $table_messages_sql = sprintf(file_get_contents(__DIR__.'/create_table_group_chat_messages.sql'),$table_messages);
        $table_users_sql = sprintf(file_get_contents(__DIR__.'/create_table_group_chat_users.sql'),$table_users);

        self::$db->query($table_messages_sql);
        self::$db->query($table_users_sql);
    }
    private static function isCreatedTable_userGroupChats($user_id){
        $table_name = "user_{$user_id}_group_chats";
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        return ($tables->num_rows>0);
    }
    private static function isCreatedTable_groupChatMessages($chat_id){
        $table_name = "group_chat_{$chat_id}_messages";
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        return ($tables->num_rows>0);
    }
    private static function isCreatedTable_groupChatUsers($chat_id){
        $table_name = "group_chat_{$chat_id}_users";
        $tables = self::$db->query("SELECT TABLE_NAME AS 'table' 
                                     FROM INFORMATION_SCHEMA.TABLES 
                                     WHERE TABLE_SCHEMA = '".DB_DATABASE."' AND 
                                           TABLE_NAME LIKE '$table_name'");
        return ($tables->num_rows>0);
    }

}
