<?php
/**
 * Class Log
 * @package		YouInRoll.com
 * @author		Ron_Tayler
 * @copyright	2021
 */
class Log implements IEngine {
    private $handle_list;
    private $handle_error;
    private $handle_debug;
    private string $hash;

    /**
     * Log constructor
     * @param string $dir
     * @throws ErrorLog
     */
    public function __construct(string $dir) {
        $this->hash = date('d.m.Y-H:i:s-').md5(rand(0,PHP_INT_MAX));

        $this->handle_list = fopen($dir.'/log.list','a');
        if(!$this->handle_list) throw new ErrorLog("Не удалось открыть/создать файл .../log.list");

        $this->handle_debug = fopen($dir.'/'.$this->hash.'.debug.log','w');
        if(!$this->handle_debug) throw new ErrorLog("Не удалось открыть/создать файл .../*.debug.log");

        $this->handle_error = fopen($dir.'/'.$this->hash.'.error.log','w');
        if(!$this->handle_error) throw new ErrorLog("Не удалось открыть/создать файл .../*.error.log");
    }

    /**
     * Print in error.log
     * @param string $message
     */
    public function printError(string $message) {
        fwrite($this->handle_error, $message.PHP_EOL);
    }

    /**
     * print in debug.log
     * @param string $message
     */
    public function printDebug(string $message){
        fwrite($this->handle_debug, $message.PHP_EOL);
    }

    /**
     * print_r in debug.log
     * @param mixed $arr
     */
    public function printArrDebug($arr){
        $this->printDebug(print_r($arr,true));
    }

    /**
     * Log destructor
     */
    public function __destruct() {
        fwrite($this->handle_list,'['.date('d.m.Y-H:i:s').']: '.$_SERVER['REMOTE_ADDR'].' | '.$_SERVER['REQUEST_METHOD'].' '.$_SERVER['REQUEST_URI'].' | HASH '.$this->hash.PHP_EOL);
        fclose($this->handle_list);
        fclose($this->handle_error);
        fclose($this->handle_debug);
    }
}