<?php
define('COM',ABSPATH.'/com');

/**
 * Class ControllerModule
 * @author Ron_Tayler
 * @link https://vk.com/ron_tayler
 * @copyright YouInRoll.com 2021
 * @version 0.0.1
 */
class ControllerModule{
    private function __construct(){}

    private static $modules = [
        'courses'=>'ControllerModuleCourses'
    ];

    public static function action(Route $route){
        $moduleName = token();
        $moduleId = token_id();
        try{
            if(!array_key_exists($moduleName,self::$modules)) throw new Exception('Данные метод не обрабатывается'.$moduleName,2);
            $file_name = COM.'/modules/'.$moduleName.'.php';
            if(!is_file($file_name)) throw new Exception('Не найден файл модуля. FILE:'.$file_name,2);

            include_once($file_name);

            $class_name = self::$modules[$moduleName];

            if(!is_callable(Array($class_name, "index"))) throw new Exception('Ошибка вызова метода index у класса '.$class_name,2);

            $data = array();
            $response = call_user_func(Array($class_name, "index"));
            switch (gettype($response)){
                case 'string':
                case 'array':
                case 'int':
                    $data['response'] = $response;
                    break;
                case 'bool':
                    if($response){
                        $data['response'] = 'ok';
                        break;
                    }
                default:
                    throw new Exception('Не предвиденный тип возвращаемых данных из функции index. Type: '.gettype($response),2);
            }
            exit(json_encode($data,JSON_UNESCAPED_UNICODE));

        } catch (Exception $ex){
            $err_code = $ex->getCode();
            $data = array();
            $data['error'] = [
                'code'=>$err_code>0?$err_code:1,
                'message'=>$ex->getMessage()
            ];
            exit(json_encode($data,JSON_UNESCAPED_UNICODE));
        }
    }
}

global $route;
ControllerModule::action($route);