<?php

namespace Controller;

use Engine\Loader;
use Engine\Request;
use Engine\Response;
use ExceptionBase;
use Library\DB;
use Library\User;
use Model\Chats\Group as Model_Chats_Group;

//use Library\RabbitMQ;

/**
 * Class Listen
 * @package Controller
 */
class Listen implements \Engine\IController {
    static DB $db;
    //static RabbitMQ $model_rabbit;

    static function init(){
        self::$db = DB::init('base');
        Loader::model('Chats/Group');
        //\Engine\Loader::model('Rabbit');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    /**
     * @param array $param
     * @throws ExceptionBase
     */
    public static function stream(){
        // Полученные\заполнение данных
        $stream_id = Request::$get['stream_id']??'';
        $ts = Request::$get['ts']??0;
        $wait = Request::$get['wait']??25;

        // Проверка данных
        if(preg_match('/^\d+$/',$stream_id)!==1) throw new ExceptionBase("Ошибка в переданном параметре stream_id='$stream_id'",7,'Ошибка в параметре stream_id');
        if(preg_match('/^\d+$/',$ts)!==1) throw new ExceptionBase("Ошибка в переданном параметре ts='$ts'",7,'Ошибка в параметре ts');
        if(preg_match('/^\d+$/',$wait)!==1) throw new ExceptionBase("Ошибка в переданном параметре wait='$wait'",7,'Ошибка в параметре wait');

        // Проверить БД
        // ...

        // Начать слушать RabbitMQ
        // $messages = self::$model_rabbit->listen('user_000_hash',$wait);
        $time = time();
        while($time+$wait>time()){
            $messages =  self::$db->selectAll("stream_message","stream_id=$stream_id AND message_id>$ts and deleted=false ORDER BY `message_id` ASC LIMIT 0, 25");
            if($messages->num_rows>0){
                $data = array();
                foreach ($messages->rows as $message){
                    $data_message = array();
                    $data_message['id'] = $message['message_id'];
                    $data_message['user_id'] = $message['user_id'];
                    $data_message['message'] = htmlspecialchars_decode($message['text']);
                    $data_message['date'] = $message['date'];
                    $data[]=$data_message;
                }
                Response::setOutput($data);
                return;
            }
            sleep(1);
        }
        Response::setOutput('timeout');


        // При получении попробовать получить ещё
        // Сформировать данные
        // Отправить данные
    }

    /**
     * @param array $param
     * @throws ExceptionBase
     */
    public static function user_im(){
        // Полученные\заполнение данных
        $user_id = User::getId();
        $peer_id = Request::$get['peer_id']??'';
        $ts = Request::$get['ts']??0;
        $wait = Request::$get['wait']??25;

        // Проверка данных
        if(preg_match('/^([ug])([1-9]\d*)$/',$peer_id,$peer_id_matches)!==1) {
            throw new ExceptionBase(
                "Ошибка в переданном параметре peer_id='$peer_id'",
                7,
                'Ошибка в параметре peer_id'
            );
        }
        if(preg_match('/^\d+$/',$ts)!==1) throw new ExceptionBase("Ошибка в переданном параметре ts='$ts'",7,'Ошибка в параметре ts');
        if(preg_match('/^\d+$/',$wait)!==1) throw new ExceptionBase("Ошибка в переданном параметре wait='$wait'",7,'Ошибка в параметре wait');
        if($user_id<1) throw new \ErrorForbidden("Требуется авторизация","Требуется авторизация");

        $chat_type = $peer_id_matches[1];
        $chat_id = $peer_id_matches[2];

        // Проверить БД
        // ...

        // Начать слушать RabbitMQ
        // $messages = self::$model_rabbit->listen('user_000_hash',$wait);
        switch ($chat_type){
            case 'u':
                $first_user_id = min($user_id,$chat_id);
                $second_user_id = max($user_id,$chat_id);
                $time = time();
                while($time+$wait>time()){
                    $messages =  self::$db->query("SELECT * FROM user_chat_{$first_user_id}_{$second_user_id} WHERE id>$ts and is_deleted=false ORDER BY id LIMIT 0, 25");
                    if($messages->num_rows>0){
                        $data = array();
                        foreach ($messages->rows as $message){
                            $data_message = array();
                            $data_message['id'] = $message['id'];
                            $data_message['user_id'] = $message['user_id'];
                            $data_message['message'] = htmlspecialchars_decode($message['message']);
                            $data_message['date'] = $message['date_create'];
                            $data_message['chat_id'] = $chat_id;
                            $data[]=$data_message;
                        }
                        Response::setOutput($data);
                        return;
                    }
                    sleep(1);
                }
                Response::setOutput('timeout');
                break;
            case 'g':
                $time = time();
                while($time+$wait>time()){
                    $messages = Model_Chats_Group::getNewMessages(Model_Chats_Group::getChatId4PeerId($user_id,$chat_id),$ts);
                    if(count($messages)>0){
                        foreach ($messages as &$message){
                            $message['chat_id'] = $chat_id;
                        }
                        Response::setOutput($messages);
                        return;
                    }
                    sleep(1);
                }
                Response::setOutput('timeout');
                break;
        }



        // При получении попробовать получить ещё
        // Сформировать данные
        // Отправить данные
    }
}
