<?php
// PHP init
error_reporting(E_ALL);
$time = explode(" ", microtime());
define('TIME_SEC',$time[1]);
define('TIME_USEC',$time[0]);
unset($time);

// Заголовки
header("Content-Type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Content-Type');

// Буферизация
ob_start();

//Дебаг [error_to_display, trace_to_display, private_msg_to_json]
$debug_types =[
    '0'         =>0,
    'private'   =>0b1,
    'trace'     =>0b10,
    'error'     =>0b100,
    'localhost' =>0b1000,
    'request'   =>0b10000,
    'all'       =>0b11111
];
if($debug_types[$_REQUEST['debug']??0] & 0b100){
    define('DISPLAY_ERROR',true);
}

// Конфиги
require_once __DIR__.'/config.php';

try{
    // Движок
    require_once DIR_SYSTEM.'/startup.php';

    \Engine\Log::init('debug')->print(file_get_contents('php://input'));
    \Engine\Log::init('debug')->print(print_r($_REQUEST,true));

    // Инициализация элементов
    Engine\Request::init();
    Engine\Loader::library('DB');
    Library\DB::init('base',[
        'adaptor'=>'mysqli',
        'hostname'=>DB_HOSTNAME,
        'port'=>3306,
        'username'=>DB_USERNAME,
        'password'=>DB_PASSWORD,
        'database'=>DB_DATABASE
    ]);

    \Engine\Loader::library('user');
    Library\User::tokenAuth(Engine\Request::$request['access_token']??'');

    // Мапинг
    Engine\Router::map('/profile/:id','User/Profile',['GET'],['id'=>'\d+'],['1.0','1.0']);
    Engine\Router::map('/profile/:id/info','User/Profile::info',['GET'],['id'=>'\d+']);
    Engine\Router::map('/profile/:id/subscribers','User/Profile::subscribers',['GET'],['id'=>'\d+']);
    Engine\Router::map('/profile/:id/subscriptions','User/Profile::subscriptions',['GET'],['id'=>'\d+']);
    Engine\Router::map('/channels','Channel::list',['GET']);
    //Engine\Router::map('/listen/event','Listen::Event',['GET']);
    //Engine\Router::map('/listen/im','Listen::im',['GET']);
    Engine\Router::map('/listen/stream','Listen::stream',['GET']);
    //Engine\Router::map('/listen/conf','Listen::conf',['GET']);
    Engine\Router::map('/message/send','Message::send',['POST']);
    Engine\Router::map('/login','User/Auth::login',['POST']);

    // DebugMap
    Engine\Router::map('/debug','Debug::test_search_queue',['GET','POST']);

    // Версия
    preg_match('/v?([1-9]+[0-9]*\.[0-9]+)/',$v_api,$matches);
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

    if($debug_types[$_REQUEST['debug']??0] & 0b001) {
        $msg['error']['private'] = $err->getPrivateMessage();
    }
    if($debug_types[$_REQUEST['debug']??0] & 0b010) {
        echo PHP_EOL.$err->getTraceAsString();
    }
    Engine\Log::init('error')->print($err->getPrivateMessage());
    Engine\Log::init('error')->print($err->getTraceAsString());

    $resp = json_encode($msg,JSON_UNESCAPED_UNICODE);

    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;
}