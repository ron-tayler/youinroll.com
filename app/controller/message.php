<?php

namespace Controller;

use Engine\IController;
use Engine\Request;
use Engine\Response;
use ExceptionBase;
use Library\DB;
use Library\User;

/**
 * Class Message
 * @package Controller
 * @author Ron_Tayler
 */
class Message implements IController {

    private static DB $db;

    static function init(){
        self::$db = DB::init('base');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    static function send(array $param = []){
        // Получение.заполнение данных
        $peer_id = Request::$post['peer_id']; // s13
        $user_id = User::is_user()?User::getId():0; // 953
        $message = Request::$post['message']; // hello

        if(preg_match('/^([s])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) throw new ExceptionBase("Ошибка в переданном параметре peer_id='$peer_id'",7,'Ошибка в параметре peer_id');
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'Ошибка авторизации или у вас нет доступа');
        if(preg_match('/^.{1,65000}$/',$message)!==1) throw new ExceptionBase("Ошибка в переданном параметре message='$message'",7,'Ошибка в параметре message');

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];
        $message = htmlspecialchars($message);
        $message = self::$db->escape($message);

        switch ($chat_type){
            case 's':
                self::$db->query("INSERT INTO vibe_stream_message (stream_id, user_id, text) VALUES ($chat_id, $user_id, '$message')");
                $id = self::$db->getLastId();
                Response::setOutput($id);
                break;
        }
    }

    static function getAll(){
        $peer_id = Request::$get['peer_id']??'';
        $offset = Request::$get['offset']??0;

        // Проверка данных
        if(preg_match('/^([s])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) throw new ExceptionBase("Ошибка в переданном параметре peer_id='$peer_id'",7,'Ошибка в параметре peer_id');
        if(preg_match('/^\d+$/',$offset)!==1) throw new ExceptionBase("Ошибка в переданном параметре ts='$offset'",7,'Ошибка в параметре ts');

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];

        switch ($chat_type){
            case 's':
                $messages =  self::$db->selectAll("stream_message","stream_id=$chat_id AND deleted=false ORDER BY `message_id` DESC LIMIT $offset, 25");
                break;
        }

        $data = [];
        if (isset($messages)) foreach ($messages->rows??[] as $message) {
            $data_message = array();
            $data_message['id'] = $message['message_id'];
            $data_message['user_id'] = $message['user_id'];
            $data_message['message'] = htmlspecialchars_decode($message['text']);
            $data_message['date'] = $message['date'];
            $data[] = $data_message;
        }

        Response::setOutput($data);
    }
}
