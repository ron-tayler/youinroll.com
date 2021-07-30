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
        $parent = Request::$post['parent'];

        if(preg_match('/^([ugs])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) throw new ExceptionBase("Ошибка в переданном параметре peer_id='$peer_id'",7,'Ошибка в параметре peer_id');
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'Ошибка авторизации или у вас нет доступа');
        if(preg_match('/^.{0,65000}$/',$message)!==1) throw new ExceptionBase("Ошибка в переданном параметре message='$message'",7,'Ошибка в параметре message');
        if(!is_array($parent)) throw new \ErrorRequest('parent не является массивом','parent не является массивом');

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];
        $message = self::$db->escape(htmlspecialchars($message));

        switch ($chat_type){
            case 'u':case 'g':
                foreach ($parent as &$item){
                    switch($item->type){
                        case 'message':
                            preg_match('/^([ugs])([1-9]\d*)$/',$item->peer_id,$parent_peer_id_matches);
                            $parent_chat_type = $parent_peer_id_matches[1];
                            $parent_chat_id = $parent_peer_id_matches[2];
                            $message_model = false;
                            switch($parent_chat_type){
                                case 'u':
                                    $message_model = Model_Chats_User::getMessage($user_id,$parent_chat_id,$item->message_id);
                                    break;
                                case 'g':
                                    $message_model = Model_Chats_Group::getMessage(Model_Chats_Group::getChatId4PeerId($user_id,$parent_chat_id),$item->message_id);
                                    break;
                            }
                            if($message_model===false) break;
                            $item = [
                                'type'=>'message',
                                'text'=>$message_model['message'],
                                'user_id'=>$message_model['user_id'],
                                'parent'=>$message_model['parent']
                            ];
                            break;
                        default:
                            unset($item);
                    }
                }
        }

        switch ($chat_type){
            case 's':
                self::$db->query("INSERT INTO vibe_stream_message (stream_id, user_id, text) VALUES ($chat_id, $user_id, '$message')");
                $id = self::$db->getLastId();
                Response::setOutput($id);
                break;
            case 'u':
                Response::setOutput(Model_Chats_User::sendMessage($user_id,$chat_id,$message,$parent));
                break;
            case 'g':
                Response::setOutput(Model_Chats_Group::sendMessage($user_id,$chat_id,$message,$parent));
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

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];

        switch ($chat_type){
            case 'u':case 'g':
                if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'Ошибка авторизации или у вас нет доступа');
        }

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

    static function getChats(){
        $user_id = User::getId();

        if($user_id<1) throw new \ErrorForbidden("Требуется авторизация","Требуется авторизация");

        $chats = Model_Chats_Group::getGroupChats($user_id);

        $chatsPayload = [];
        foreach ($chats as $chat){
            $chat_info = Model_Chats_Group::getInfo($chat['group_chat_id']);
            $chatPayload = [
                'type'=>'g',
                'id'=>$chat['id'],
                'title'=>$chat_info['name'],
                'messages'=>Model_Chats_Group::getLastMessages($chat['group_chat_id'])
            ];
            foreach ($chatPayload['messages'] as &$message){
                $message['chat_id'] = $chat['id'];
            }
            $chatsPayload []= $chatPayload;
        }

        Response::setOutput($chatsPayload);
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

        $chat_id = Model_Chats_Group::createChat($user_id,$chat_name);
        $peer_id = Model_Chats_Group::getPeerId4ChatId($user_id,$chat_id);

        Response::setOutput($peer_id);
    }

    static function test(){
        $user_id = User::getId();

        $chat_id = Model_Chats_Group::getChatId4PeerId($user_id,2);
        Model_Chats_Group::addUser($chat_id,1,Model_Chats_Group::PERM_MODER);


        Response::setOutput('ок');
    }

    // Нерабочий псевдокод под JSON-RPC
    static function new_db(){
        Request::addRuleParams([
            'name'=>'peer_id',
            'regexp'=>'^([ugs])([1-9][0-9]*)$',
            'matches'=>['type','id'],
            'require'=>true,
        ]);
        Request::addRuleParams([
            'name'=>'message_id',
            'regexp'=>'^[1-9][0-9]*$',
            'require'=>true,
        ]);

        $user_id = User::getId();
        $chat_id = Request::getParamMatch('peer_id','id');
        $chat_type = Request::getParamMatch('peer_id','type');
        $message_id = Request::getParamMatch('message_id');

        switch($chat_type){
            case 'u':
                Model_User::getUserById($user_id)
                ->getGroupChatByPeerId($chat_id)
                ->getMessageById($message_id)
                ->isAuthor($user)
                ->delete();

                Model_User::getUserById($user_id){
                    $user_db = self::$db->tableUsers()->getById($user_id);
                    return new self($user_db);
                }->getGroupChatByPeerId($chat_id){
                    $chat_db = self::$db->tableUser_id_GroupChats($this->user_id)->getByPeerId($peer_id);
                    return new Model_Group_Chat($chat_db);
                }->getMessageById($message_id){
                    $message_db = self::$db->tableGroupChat_id_Messages($this->chat_id)->getById($message_id);
                    return new Model_Chat_Message($message_db);
                }->isAuthor($user_id){
                    $this->resultByIs = ($this->author_id===$user_id);
                    return $this;
                }->delete(){
                    if($this->resultByIs){
                        self::$db->tableGroupChat_id_Messages($this->chat_id)->deleteById($message_id)
                    }
                }
                break;
            default:
                throw new \ErrorUnknown();
        }

        Response::setOutput($message);


    }
}
