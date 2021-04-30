<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

define('DIR_SITE','/home/x/xatikont/youinroll.com/public_html');
define('FILE_LOG',DIR_SITE.'/system/debug.log');

$log = fopen(FILE_LOG,'a');
if($log) {
    fwrite($log, 'Hello');
    fclose($log);
}


