<?php

namespace Controller;
use Engine\Event;
use Engine\Log;
use Engine\Request;
use Engine\Response;
use Library\DB;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;

/**
 * Class Controller\Debug
 * @package Controller
 * @author Ron_Tayler
 * @copyright 22.04.2021
 */
final class Debug implements \Engine\IController {

    private static DB $db;
    private static Log $log;
    private static Log $dlog;

    private static AMQPStreamConnection $connection;
    private static AMQPChannel $channel;

    /**
     * Static method init
     * @throws \ExceptionBase
     */
    public static function init(){
        self::$db = DB::init('base');
        self::$log = Log::init('error');
        self::$dlog = Log::init('debug');
        Event::add('debug/event','Debug::event');
        Event::add('debug/event','Debug::event');
    }

    /**
     * Static method worker
     * @param array $param
     * @return array
     * @throws \Exception
     */
    public static function worker(array $param = []){
        Event::exec('debug/event',['text'=>'Hello event!']);
        $user = self::$db->select('*','users','id=1');
        Response::setOutput((array)$user->row);
    }

    public static function event(array $param = []){
        echo $param['text']??'text';
    }

    public static function index(array $param = [])
    {
        $method = $param['method'];
        $ret = '';
        switch ($method){
            case 'listen':
                $wait = Request::$get['wait']??0;
                $id = Request::$get['user_id']??0;
                $ret = self::connectAndListen($wait,$id);
                break;
            case 'send':
                $id = Request::$get['peer_id']??0;
                $msg = Request::$get['message']??'';
                self::connectAndSend($id,$msg);
                $ret = 'Send \''.$msg.'\'';
                break;
        }
        return ['message'=>$ret];
    }

    private static function connectAndSend($id,$msg){
        $msg = new AMQPMessage($msg);
        
        self::$channel->basic_publish($msg, 'debug', 'id'.$id);
    }

    private static function connectAndListen($wait,$id):string{

        // Подключаемся
        $connection = new AMQPStreamConnection(
            'youinrolltinod.com',
            5672,
            'xatikont',
            'tester322'
        );

        // Создаём канал связи
        $channel = $connection->channel();

        $queue_id = 'debug-id'.$id.'-'.md5(rand(PHP_INT_MIN,PHP_INT_MAX));
        $fanout_id = 'debug-id'.$id;

        // Создаём очередь
        $channel->queue_declare(
            $queue_id,
            false,
            false,
            true,
            true
        );

        // Создаём распределителя
        $channel->exchange_declare(
            $fanout_id,
            'fanout',
            false,
            false,
            false
        );

        // Подключаем очередь и распределитель
        $channel->exchange_bind(
            $fanout_id,
            'debug',
            'id'.$id
        );
        $channel->queue_bind($queue_id,$fanout_id);

        //Функция, которая будет обрабатывать данные, полученные из очереди
        $response = null;
        $callback = function($msg) use (&$response) {
            $this->log->printArrDebug($msg);
            $response .= $msg->body;
        };

        // Подписываемся на очередь
        $channel->basic_consume(
            $queue_id,
            '',
            false,
            true,
            false,
            false,
            $callback
        );

        $channel->basic_get('debug-rtf');

        try {
            // Уходим в прослушку
            $channel->wait(null, false, $wait);
        }catch (AMQPTimeoutException $ex){
            $response = 'timeout';
        }

        //Не забываем закрыть канал и соединение
        $channel->close();
        $connection->close();
        return $response??"Сообщение не получено";
    }

    private static function connect2rabbit(){
        self::$connection = new AMQPStreamConnection(
            'youinrolltinod.com',
            5672,
            'xatikont',
            'tester322'
        );
        self::$channel = self::$connection->channel();
    }

    private static function disconnect2rabbit(){
        self::$channel->close();
        self::$connection->close();
    }
}