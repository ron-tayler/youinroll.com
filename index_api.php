<?php
// PHP init
error_reporting(E_ALL);
$time = explode(" ", microtime());
define('TIME_SEC',$time[1]);
define('TIME_USEC',$time[0]);
unset($time);

// Заголовки
header("Content-Type: text/html; charset=utf-8");

// Буферизация
ob_start();

// Конфиги
require_once __DIR__.'/config.php';

// Движок
require_once DIR_SYSTEM.'/startup.php';

//Дебаг
if($_GET['debug']='on'){
    ini_set('display_errors', 1);
}else{
    ini_set('display_errors', 0);
}

try{
    // Инициализация элементов
    Engine\Request::init();



    $db = new DB($registry,DB_DRIVER,DB_HOSTNAME,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
    $registry->set('db',$db);

    // Мапинг
    $router->map('/profile/:id','User/Profile',['GET'],['id'=>'\d+'],['1.0','1.0']);
    $router->map('/profile/:id/info','User/Profile/info()',['GET'],['id'=>'\d+'],['1.0']);
    $router->map('/profile/:id/subscribers','User/Profile/subscribers()',['GET'],['id'=>'\d+'],['1.0']);
    $router->map('/profile/:id/subscriptions','User/Profile/subscriptions()',['GET'],['id'=>'\d+'],['1.0']);
    $router->map('/channels','channel/list()',['GET'],[],['1.0']);
    $router->map('/debug/:method','Debug',['GET','POST'],['wait'=>'\w+']);

    // Версия
    preg_match('/v?([1-9]+[0-9]*\.[0-9]+)/',$_GET['api'],$matches);
    // Запуск маршрута
    $return = $router->execute($matches[1]);

    // Вывод ответа
    $resp = json_encode(['response'=>$return],JSON_UNESCAPED_UNICODE);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;

}catch (ErrorBase | ExceptionBase $err){

    $resp = json_encode(['error'=>[
        'code'=>$err->getCode(),
        'message'=>$err->getMessage()
    ]],JSON_UNESCAPED_UNICODE);

    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;

    trigger_error($err->getPrivateMessage(),E_USER_WARNING);
}