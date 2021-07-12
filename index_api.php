<?php
// PHP init
error_reporting(E_ALL);
$time = explode(" ", microtime());
define('TIME_SEC',$time[1]);
define('TIME_USEC',$time[0]);
unset($time);

// Буферизация
ob_start();

//Дебаг
define('DEBUG_PRIVATE',1);
define('DEBUG_TRACE',1<<1);
define('DEBUG_ERROR',1<<2);
define('DEBUG_REQUEST',1<<3);
define('DEBUG_GET',1<<4);
define('DEBUG_POST',1<<5);
$debug_types =[
    '0'         => 0,
    'private'   => DEBUG_PRIVATE,
    'trace'     => DEBUG_TRACE,
    'error'     => DEBUG_ERROR,
    'request'   => DEBUG_REQUEST,
    'get'       => DEBUG_GET,
    'post'      => DEBUG_POST,
    'all'       => DEBUG_PRIVATE | DEBUG_TRACE | DEBUG_ERROR | DEBUG_REQUEST | DEBUG_GET | DEBUG_POST
];
$debug_text = ($_GET['dev_token']=='952C54')?($_GET['debug']??''):'';
foreach (explode(',',$debug_text) as $el) $debug_type = ($debug_type??0) | ($debug_types[$el]??0);
define('DEBUG_TYPE',$debug_type??0);
unset($debug_types,$debug_type,$debug_text);


// display error
if(DEBUG_TYPE & DEBUG_ERROR) define('DISPLAY_ERROR',true);

// Заголовки
header("Content-Type: text/html; charset=utf-8");
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET,POST');
header('Access-Control-Allow-Headers: Content-Type');

// Буферизация
ob_start();

// Конфиги
require_once __DIR__.'/config.php';

try{
    // Движок
    require_once DIR_SYSTEM.'/startup.php';

    // Request debug
    /*
    if(DEBUG_TYPE & DEBUG_REQUEST){
        \Engine\Log::init('debug')->print(file_get_contents('php://input'));
        \Engine\Log::init('debug')->print(print_r($_REQUEST,true));
    }
    */

    // Инициализация входных
    Engine\Request::init();

    if(DEBUG_TYPE & DEBUG_GET) var_dump(Engine\Request::$get);
    if(DEBUG_TYPE & DEBUG_POST) var_dump(Engine\Request::$post);

    // Инициализация Базы Данных
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
    Library\User::tokenAuth(Engine\Request::$post['access_token']??''); // Авторизация только по POST запросам

    // Мапинг
    foreach ([
        ['url'=>'/profile/:id/info',            'target'=>'User/Profile::info',             'methods'=>['GET'], 'filters'=>['id'=>'\d+']],
        ['url'=>'/profile/:id/subscribers',     'target'=>'User/Profile::subscribers',      'methods'=>['GET'], 'filters'=>['id'=>'\d+']],
        ['url'=>'/profile/:id/subscriptions',   'target'=>'User/Profile::subscriptions',    'methods'=>['GET'], 'filters'=>['id'=>'\d+']],
        ['url'=>'/channels',                    'target'=>'Channel::list',                  'methods'=>['GET']],
        ['url'=>'/listen/stream',               'target'=>'Listen::stream',                 'methods'=>['GET']],
        ['url'=>'/message/send',                'target'=>'Message::send',                  'methods'=>['POST']],
        ['url'=>'/message/getAll',              'target'=>'Message::getAll',                'methods'=>['GET']],
        ['url'=>'/login',                       'target'=>'User/Auth::login',               'methods'=>['POST']],
        ['url'=>'/categories',                  'target'=>'Category::getList',              'methods'=>['GET']],
        ['url'=>'/getNotification',             'target'=>'Notification::getNotification',  'methods'=>['POST']],
        ['url'=>'/getNotificationTags',         'target'=>'Notification::getTags',          'methods'=>['POST']],
        ['url'=>'/regPushToken',                'target'=>'Notification::regPushToken',     'methods'=>['POST']],
    ] as $route){
        Engine\Router::map($route['url'],$route['target'],$route['methods']??[],$route['filters']??[]);
    }

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

    if(DEBUG_TYPE & DEBUG_PRIVATE) {
        $msg['error']['private'] = $err->getPrivateMessage();
    }
    if(DEBUG_TYPE & DEBUG_TRACE) {
        echo PHP_EOL.$err->getTraceAsString();
    }

    $resp = json_encode($msg,JSON_UNESCAPED_UNICODE);

    //Engine\Log::init('error')->print($resp);
    //Engine\Log::init('error')->print($err->getPrivateMessage());
    //Engine\Log::init('error')->print($err->getTraceAsString());

    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;
}catch (Error | Exception $err){

    $msg = ['error'=>[
        'code'=>5,
        'message'=>'Ошибка на стороне сервера'
    ]];

    if(DEBUG_TYPE & DEBUG_PRIVATE) {
        $msg['error']['private'] = $err->getMessage();
    }
    if(DEBUG_TYPE & DEBUG_TRACE) {
        echo PHP_EOL.$err->getTraceAsString();
    }

    $resp = json_encode($msg,JSON_UNESCAPED_UNICODE);

    Engine\Log::init('error')->print($resp);
    Engine\Log::init('error')->print($err->getMessage());
    Engine\Log::init('error')->print($err->getTraceAsString());

    $buffer = ob_get_contents();
    ob_end_clean();
    echo $resp.$buffer;
}
