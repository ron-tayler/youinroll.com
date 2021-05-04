<?php

namespace Engine;
use ErrorLog;

/**
 * Class Log
 * @package	Engine
 * @author Ron_Tayler
 * @copyright 04.05.2021
 */
class Log {
    private static array $data;
    private string $hash;
    private $handle_list;
    private $handle_log;

    public static function init(string $name,string $dir = ''){
        if(isset(self::$data[$name])){
            return self::$data[$name];
        }else{
            if($dir=='') return null;
            return $data[$name] = new self($name,$dir);
        }
    }

    /**
     * Log constructor
     * @param string $name
     * @param string $dir
     * @throws ErrorLog
     */
    public function __construct(string $name,string $dir) {
        $this->hash = date('d.m.Y-H:i:s,u-').md5(rand(0,PHP_INT_MAX)).'.'.$name;

        $this->handle_list = fopen($dir.'/log.list','a');
        if(!$this->handle_list) throw new ErrorLog("Не удалось открыть/создать файл .../log.list");

        $this->handle_log = fopen($dir.'/'.$this->hash.'.log','w');
        if(!$this->handle_log) throw new ErrorLog("Не удалось открыть/создать файл .../*.log");
    }

    /**
     * Print in *.log
     * @param string $message
     */
    public function print(string $message) {
        fwrite($this->handle_log, $message.PHP_EOL);
    }

    /**
     * Log destructor
     */
    public function __destruct() {
        fwrite($this->handle_list,'['.date('d.m.Y-H:i:s').']: '.$_SERVER['REMOTE_ADDR'].' | '.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' | HASH '.$this->hash.PHP_EOL);
        fclose($this->handle_list);
        fclose($this->handle_log);
    }
}