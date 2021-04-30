<?php

/**
 * Class Router - Маршрутизатор запросов
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Router implements IEngine{
    private Registry $registry;
    /** @var Route[] */
    private array $routes;
    /** @var Route[] */
    private array $preRoutes;
    /** @var Route[] */
    private array $postRoutes;
    private Route $baseRoute;
    private Route $errorRoute;

    public function __construct(Registry $registry){
        $this->registry = $registry;
    }

    public function setBase(string $target){
        $this->baseRoute = new Route('/',$target);
    }
    public function setError(string $target){
        $this->errorRoute = new Route('/',$target);
    }

    public function map(string $url, string $target, array $methods = [], array $filters = [], array $version = []){
        $this->routes[] = new Route($url,$target,$methods,$filters,$version);
    }
    public function preMap(string $url, string $target, array $methods = [], array $filters = []){
        $this->preRoutes[] = new Route($url,$target,$methods,$filters);
    }
    public function postMap(string $url, string $target, array $methods = [], array $filters = []){
        $this->postRoutes[] = new Route($url,$target,$methods,$filters);
    }

    /**
     * Method execute - Запуск обработки маршрутизации
     * @param string $version
     * @return array
     * @todo Продумать запуск Pre и Post маршрутов
     */
    public function execute($version){
        /** @var Request $request */
        $request = $this->registry->get('request');
        $version = explode('.',$version);
        $url = $request->server['REDIRECT_URL'];
        //$params = [];
        // Работа с PreRoutes
        // Работа с Routes
        foreach ($this->routes as $route){
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
            $response_params = $route->execute($this->registry,$params);
            break;
        }
        if(!isset($response_params)) throw new ErrorEngine('Контроллер не найден: '.$url.' - v'.$version[0].'.'.$version[1],4,'Не найден метод API или версия не верна');
        return $response_params;
        // Работа с PostRoutes
    }

    private function checkingConvergence(){

    }
}