<?php
// PHP Init
define('DISPLAY_ERROR',false);
define('LOGFILE_ERROR',true);

// Вывод ошибок на экран
set_error_handler(function($code, $message, $file, $line){
    // error suppressed with @
    if (error_reporting() === 0) {
        return false;
    }
    switch ($code) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }
    if (DISPLAY_ERROR) {
        echo '<b>'.$error.'</b>: '.$message.' in <b>'.$file.'</b> on line <b>'.$line.'</b><br />';
    }
    return true;
});

// Стандартные константные конфигурации
require_once DIR_SYSTEM.'/config/system.php';

// Исключения и ошибки
require_once DIR_SYSTEM . '/Exceptions/Load_Exceptions.php';
require_once DIR_SYSTEM . '/Errors/Load_Errors.php';

ob_start();
try {
    //Composer autoload.php
    require_once DIR_SYSTEM . '/vendor/autoload.php';

    // Интерфейсы движка
    require_once DIR_ENGINE . '/IController.php';
    require_once DIR_ENGINE . '/IModel.php';

    // Классы движка
    require_once DIR_ENGINE . '/Loader.php';
    require_once DIR_ENGINE . '/Router.php';
    require_once DIR_ENGINE . '/Route.php';
    require_once DIR_ENGINE . '/Request.php';
    require_once DIR_ENGINE . '/Response.php';

    // Логирование ошибок
    require_once DIR_ENGINE . '/Log.php';
    Engine\Log::init('debug',DIR_LOGS);
    $log = Engine\Log::init('error',DIR_LOGS);
    set_error_handler(function ($code, $message, $file, $line) use ($log) {
        // error suppressed with @
        if (error_reporting() === 0) {
            return false;
        }
        switch ($code) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $error = 'Notice';
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $error = 'Warning';
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $error = 'Fatal Error';
                break;
            default:
                $error = 'Unknown';
                break;
        }
        if (DISPLAY_ERROR) {
            echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b><br />';
        }
        if (LOGFILE_ERROR) {
            $log->print('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
        }
        return true;
    });
    /*
    function library($class) {
        $file = DIR_LIB . '/' . str_replace('\\', '/', strtolower($class)).'.php';

        if (is_file($file)) {
            include_once($file);

            return true;
        } else {
            return false;
        }
    }

    spl_autoload_register('library');
    spl_autoload_extensions('.php');
    */
}catch(ErrorBase | ExceptionBase $err){

    $resp = json_encode(['error'=>[
        'code'=>$err->getCode(),
        'message'=>$err->getMessage()
    ]],JSON_UNESCAPED_UNICODE);

    $buffer = ob_get_contents();
    echo $resp.$buffer;
    trigger_error($err->getPrivateMessage(),E_USER_WARNING);
    exit();
} finally {
    ob_end_flush();
}

