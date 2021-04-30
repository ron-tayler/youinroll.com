<?php
/**
 * @package		SITE
 * @author		Ron Tayler
 * @copyright	2020
 */

/**
 * Log class
 */
class Log {
	private $handle;
	private $random_hash;

    /**
     * Constructor
     * @param string $filename
     */
	public function __construct($filename) {
	    $this->random_hash = substr(md5($_SERVER['REMOTE_ADDR']),0,8).'-'.md5(rand(0,PHP_INT_MAX));
		$this->handle = fopen($filename.'_'.$this->random_hash.'.log', 'a');
		fwrite($this->handle,str_pad('',20,'-').'[ Start ]'.str_pad('',20,'-') . PHP_EOL);
	}
	
	/**
     * write
     * @param	string	$message
     */
	public function write($message) {
		fwrite($this->handle, date('Y-m-d G:i:s') . ' - ' . print_r($message, true) . PHP_EOL);
	}
	
	/**
     * Destructor
     */
	public function __destruct() {
        fwrite($this->handle,str_pad('',20,'-').'[ End ]'.str_pad('',20,'-') . PHP_EOL);
		fclose($this->handle);
	}
}