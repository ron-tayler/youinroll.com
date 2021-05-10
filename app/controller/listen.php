<?php

namespace Controller;

use Engine\Request;
use Engine\Response;
use ExceptionBase;
use Library\DB;
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
        //\Engine\Loader::model('Rabbit');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    /**
     * @param array $param
     * @throws ExceptionBase
     * @todo Частично является псевдокодом!
     */
    public static function stream(array $param = []){
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
                    $data_message['message'] = $message['text'];
                    $data_message['date'] = $message['date'];
                    $data[]=$data_message;
                }
                Response::setOutput($data);
                return;
            }
            sleep(1);
        }


        // При получении попробовать получить ещё
        // Сформировать данные
        // Отправить данные
    }
}