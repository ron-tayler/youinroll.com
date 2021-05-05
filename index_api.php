<?php
// PHP init
error_reporting(E_ALL);
$time = explode(" ", microtime());
define('TIME_SEC',$time[1]);
define('TIME_USEC',$time[0]);
unset($time);
ini_set('display_errors', 1);

// Заголовки
header("Content-Type: text/html; charset=utf-8");

// Буферизация
ob_start();

//Дебаг
if(isset($_GET['debug']) and ($_GET['debug']=='error' or $_GET['debug']=='all')){
    define('DISPLAY_ERROR',true);
}

// Конфиги
require_once __DIR__.'/config.php';

try{
    // Движок
    require_once DIR_SYSTEM.'/startup.php';

    // Инициализация элементов
    Engine\Request::init();
    Engine\Loader::library('DB');
    Library\DB::init('base',[
        'adaptor'=>'mysqli',
        'hostname'=>'mysql',
        'port'=>3306,
        'username'=>'root',
        'password'=>'root',
        'database'=>'youinroll'
    ]);

    // Мапинг
    Engine\Router::map('/profile/:id','User/Profile',['GET'],['id'=>'\d+'],['1.0','1.0']);
    Engine\Router::map('/profile/:id/info','User/Profile::info',['GET'],['id'=>'\d+'],['1.0']);
    Engine\Router::map('/profile/:id/subscribers','User/Profile::subscribers',['GET'],['id'=>'\d+'],['1.0']);
    Engine\Router::map('/profile/:id/subscriptions','User/Profile::subscriptions',['GET'],['id'=>'\d+'],['1.0']);
    Engine\Router::map('/channels','Channel::list',['GET'],[],['1.0']);
    Engine\Router::map('/debug','Debug::worker',['GET','POST'],['wait'=>'\w+']);

    // Версия
    preg_match('/v?([1-9]+[0-9]*\.[0-9]+)/',$_GET['api'],$matches);
    // Запуск маршрута
    Engine\Router::execute($matches[1]);

    // Вывод ответа
    \Engine\Response::getOutput();
    $resp = json_encode(['response'=>\Engine\Response::getOutput()],JSON_UNESCAPED_UNICODE);
    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;

}catch (ErrorBase | ExceptionBase $err){
    // TODO Отделить Исключения и вызывать ErrorBase с ошибкой непойманного исключения
    // Всегда при любых фатальных ошибках нужно генерировать JSON Error Code 5

    $msg = ['error'=>[
        'code'=>$err->getCode(),
        'message'=>$err->getMessage()
    ]];

    if(isset($_GET['debug']) and ($_GET['debug']=='private' or $_GET['debug']=='all')) {
        $msg['error']['private'] = $err->getPrivateMessage();
        echo '<br />'.$err->getTraceAsString();
    }
    Engine\Log::init('error')->print($err->getPrivateMessage());
    Engine\Log::init('error')->print($err->getTraceAsString());

    $resp = json_encode($msg,JSON_UNESCAPED_UNICODE);

    $buffer = ob_get_contents();
    ob_end_clean();
    echo (isset($_GET['debug'])?'<pre>':'').$resp.$buffer;
}