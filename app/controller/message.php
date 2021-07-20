<?php

namespace Controller;

use Engine\IController;
use Engine\Loader;
use Engine\Request;
use Engine\Response;
use ExceptionBase;
use Library\DB;
use Library\User;
use Model\Chats\User as Model_Chats_User;
use Model\Chats\Group as Model_Chats_Group;

/**
 * Class Message
 * @package Controller
 * @author Ron_Tayler
 */
class Message implements IController {

    private static DB $db;

    /**
     * Init
     * @throws
     */
    static function init(){
        self::$db = DB::init('base');
        Loader::model('Chats/User');
        Loader::model('Chats/Group');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    static function send(){
        // Получение.заполнение данных
        $peer_id = Request::$post['peer_id']; // s13
        $user_id = User::is_user()?User::getId():0; // 953
        $message = Request::$post['message']; // hello

        if(preg_match('/^([ugs])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) throw new ExceptionBase("Ошибка в переданном параметре peer_id='$peer_id'",7,'Ошибка в параметре peer_id');
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'Ошибка авторизации или у вас нет доступа');
        if(preg_match('/^.{1,65000}$/',$message)!==1) throw new ExceptionBase("Ошибка в переданном параметре message='$message'",7,'Ошибка в параметре message');

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];
        $message = self::$db->escape(htmlspecialchars($message));

        switch ($chat_type){
            case 's':
                self::$db->query("INSERT INTO vibe_stream_message (stream_id, user_id, text) VALUES ($chat_id, $user_id, '$message')");
                $id = self::$db->getLastId();
                Response::setOutput($id);
                break;
            case 'u':
                Response::setOutput(Model_Chats_User::sendMessage($user_id,$chat_id,$message));
                break;
            case 'g':
                Response::setOutput(Model_Chats_Group::sendMessage($user_id,$chat_id,$message));
                break;
        }
    }

    static function getAll(){
        $peer_id = Request::$get['peer_id']??'';
        $offset = Request::$get['offset']??0;
        $user_id = User::is_user()?User::getId():0; // 953

        // Проверка данных
        if(preg_match('/^([ugs])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) throw new ExceptionBase("Ошибка в переданном параметре peer_id='$peer_id'",7,'Ошибка в параметре peer_id');
        if(preg_match('/^\d+$/',$offset)!==1) throw new ExceptionBase("Ошибка в переданном параметре ts='$offset'",7,'Ошибка в параметре ts');
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'Ошибка авторизации или у вас нет доступа');

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];

        switch ($chat_type){
            case 's':
                $messages = self::$db->selectAll("stream_message","stream_id=$chat_id AND deleted=false ORDER BY `message_id` DESC LIMIT $offset, 25");
                $messages = $messages->rows;
                break;
            case 'u':
                $messages = Model_Chats_User::getMessages($user_id,$chat_id,$offset);
                break;
            case 'g':
                $_chat_id = Model_Chats_Group::getChatId4PeerId($user_id,$chat_id);
                $messages = Model_Chats_Group::getLastMessages($_chat_id,$offset);
                break;
        }

        $messagesPayload = [];
        switch ($chat_type){
            case 'u': case 'g':
                foreach ($messages??[] as $message) {
                    $messagePayload = [];
                    $messagePayload['id'] = $message['id'];
                    $messagePayload['user_id'] = $message['user_id'];
                    $messagePayload['message'] = htmlspecialchars_decode($message['message']);
                    $messagePayload['parent'] = $message['parent'];
                    $messagePayload['date_create'] = $message['date_create'];
                    $messagePayload['date_edit'] = $message['date_edit'];
                    $messagesPayload[] = $messagePayload;
                }
                break;
            case 's':
                foreach ($messages??[] as $message) {
                    $messagePayload = [];
                    $messagePayload['id'] = $message['message_id'];
                    $messagePayload['user_id'] = $message['user_id'];
                    $messagePayload['message'] = htmlspecialchars_decode($message['text']);
                    $messagePayload['date'] = $message['date'];
                    $messagesPayload[] = $messagePayload;
                }
                break;
        }
        Response::setOutput($messagesPayload);
    }

    static function createGroupChat(){
        $user_id = User::getId();
        $chat_name = Request::$post['name'];

        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) {
            throw new ExceptionBase(
                "Ошибка авторизации или у вас нет доступа, user_id='$user_id'",
                3,
                'Ошибка авторизации или у вас нет доступа'
            );
        }
        if(preg_match('/^.{1,255}$/',$chat_name)!==1) {
            throw new ExceptionBase(
                "Ошибка в переданном параметре chat_name='$chat_name'",
                7,
                'Ошибка в параметре name'
            );
        }

        $chat_id = Model_Chats_Group::createChat($user_id,"Новая беседа");
        $peer_id = Model_Chats_Group::getPeerId4ChatId($user_id,$chat_id);

        Response::setOutput($peer_id);
    }

    static function test(){
        $user_id = User::getId();

        $chat_id = Model_Chats_Group::createChat($user_id,"Новая беседа");
        Model_Chats_Group::addUser($chat_id,1,Model_Chats_Group::PERM_MODER);
        $peer_id = Model_Chats_Group::getPeerId4ChatId($user_id,$chat_id);
        $message_id = Model_Chats_Group::sendMessage($user_id,$peer_id,"Всем привет!");

        Response::setOutput([
            'chat_id'=>$chat_id,
            'peer_id'=>$peer_id,
            'message_id'=>$message_id
        ]);
    }
}
