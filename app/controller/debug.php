<?php

namespace Controller;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Exception\AMQPTimeoutException;
use PhpAmqpLib\Message\AMQPMessage;
/**
 * Class Controller\Debug
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 22.04.2021
 */
class Debug extends \LMVCL implements \IController
{

    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    public function index(array $param)
    {
        $method = $param['method'];
        $ret = '';
        switch ($method){
            case 'listen':
                $wait = $this->request->get['wait']??0;
                $id = $this->request->get['user_id']??0;
                $ret = $this->connectAndListen($wait,$id);
                break;
            case 'send':
                $id = $this->request->get['peer_id']??0;
                $msg = $this->request->get['message']??'';
                $this->connectAndSend($id,$msg);
                $ret = 'Send \''.$msg.'\'';
                break;
            case 'work':

        }
        return ['message'=>$ret];
    }

    private function connectAndSend($id,$msg){
        $msg = new AMQPMessage($msg);
        
        $this->channel->basic_publish($msg, 'debug', 'id'.$id);
    }

    private function connectAndListen($wait,$id):string{

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

    private function worker(){

    }

    private function connect2rabbit(){
        $this->connection = new AMQPStreamConnection(
            'youinrolltinod.com',
            5672,
            'xatikont',
            'tester322'
        );
        $this->channel = $this->connection->channel();
    }

    private function disconnect2rabbit(){
        $this->channel->close();
        $this->connection->close();
    }

}