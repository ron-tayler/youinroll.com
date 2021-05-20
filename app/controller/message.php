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
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new ExceptionBase("Ошибка в переданном параметре user_id='$user_id'",7,'Ошибка в параметре user_id');
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
        }
    }
}