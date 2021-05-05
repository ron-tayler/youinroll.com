<?php

namespace Library;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;

/**
 * Class RabbitMQ
 * @package Library
 * @author Ron_Tayler
 * @copyright 05.05.2021
 */
class RabbitMQ{
    /** @var RabbitMQ[] */
    static array $data = [];

    public static function init(string $name = 'base', array $param = []){
        if(isset(self::$data[$name])){
            return self::$data[$name];
        }else{
            if(!isset($param['host'])) throw new \ExceptionBase('Не указан host для подключения к RabbitMQ',5);
            if(!isset($param['login'])) throw new \ExceptionBase('Не указан login для подключения к RabbitMQ',5);
            if(!isset($param['password'])) throw new \ExceptionBase('Не указан пароль для подключения к RabbitMQ',5);
            $host = $param['host'];
            $port = $param['port']??3306;
            $login = $param['login'];
            $password = $param['password'];

            try{
                return self::$data[$name] = new self($host, $port, $login, $password);
            }catch (\ExceptionBase $ex){
                unset(self::$data[$name]);
                throw new \ExceptionBase($ex->getPrivateMessage(),5,$ex->getMessage(),$ex);
            }
        }
    }
    public static function final(string $name){
        self::$data[$name]->__destruct();
        unset(self::$data[$name]);
    }

    private AMQPStreamConnection $connection;
    private AMQPChannel $channel;

    private function __construct($host, $port, $login, $password){
        $this->connection = new AMQPStreamConnection($host, $port, $login, $password);
        $this->channel = $this->connection->channel();
    }

    /**
     * Get Connection
     * @return AMQPStreamConnection
     */
    public function getConnection(): AMQPChannel{
        return $this->connection;
    }

    /**
     * Get Channel
     * @return AMQPChannel
     */
    public function getChannel(): AMQPChannel{
        return $this->channel;
    }

    private function __destruct(){
        $this->channel->close();
        $this->connection->close();
    }
}