<?php

namespace Engine;
use ErrorEngine;

/**
 * Class Router - Маршрутизатор запросов
 * @package Engine
 * @author Ron_Tayler
 * @copyright 04.05.2021
 */
class Router {
    /** @var Route[] */
    private static array $routes;
    private static Route $baseRoute;
    private static Route $errorRoute;

    private function __construct(){}

    public static function setBase(string $target){
        self::$baseRoute = new Route('/',$target);
    }
    public static function setError(string $target){
        self::$errorRoute = new Route('/',$target);
    }

    public static function map(string $url, string $target, array $methods = [], array $filters = [], array $version = []){
        self::$routes[] = new Route($url,$target,$methods,$filters,$version);
    }

    /**
     * Method execute - Запуск обработки маршрутизации
     * @param string $version
     * @return array
     * @todo Продумать запуск Pre и Post маршрутов
     */
    public static function execute($version){
        $version = explode('.',$version);
        $url = Request::$server['REDIRECT_URL'];

        foreach (self::$routes as $route){
            $minVersion = explode('.',$route->getMinVersion());
            if($version[0]<$minVersion[0] OR $version[0]==$minVersion[0] AND $version[1]<$minVersion[1]) continue;
            if($route->getMaxVersion()!='') {
                $maxVersion = explode('.', $route->getMaxVersion());
                if ($version[0] > $maxVersion[0] OR $version[0]==$maxVersion[0] and $version[1] > $maxVersion[1]) continue;
            }

            $pat = str_replace('/','\\/',$route->getRegex());
            $res = preg_match('/^'.$pat.'$/',$url,$request_params);
            if($res!==1) continue;
            array_splice($request_params,0,1);
            $params = array_combine($route->getParams(),$request_params);
            foreach ($params as $name => $value){
                $res = preg_match('/'.$route->getFilters()[$name].'/',$value);
                if($res!==1) continue(2);
            }
            $response_params = $route->execute($params);
            break;
        }
        if(!isset($response_params)) throw new ErrorEngine('Контроллер не найден: '.$url.' - v'.$version[0].'.'.$version[1],4,'Не найден метод API или версия не верна');
        return $response_params;
    }

    private function checkingConvergence(){

    }
}