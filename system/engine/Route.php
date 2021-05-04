<?php

namespace Engine;
/**
 * Class Route - Маршрут для маршрутизатора
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Route{
    private string $url;
    private string $target;
    private array $methods = ['GET','POST','PUT','DELETE'];
    private array $filters = [];
    private array $params = [];
    private string $version_min = '1.0';
    private string $version_max = '';

    /**
     * Route constructor.
     * @param string $url Example: '/api/:method/:id/:token' or '/user/:id/chanel/getVideos'
     * @param string $target
     * @param array $methods
     * @param array $filters
     * @param array $version
     */
    public function __construct(string $url, string $target, array $methods = [], array $filters = [], array $version = []){
        $this->url = $url;
        $this->target = $target;
        $this->methods = $methods;
        $this->filters = $filters;
        preg_match_all('/:(\w+)/',$url,$params);
        $this->params = $params[1];
        $this->version_min = $version[0]??'1.0';
        $this->version_max = $version[1]??'';
    }

    public function execute(array $params):array{
        $path = explode('/',$this->target);
        $controller = $this->target;
        $method = 'index';
        if(preg_match('/(.+)\(\)/',$path[count($path)-1],$match)===1){
            $method = $match[1];
            $controller = implode('/',array_slice($path,0,-1));
        }
        Loader::controller($controller);
        $controller = str_replace('/','\\',$controller);
        return $controller::$method($params);
    }

    public function getUrl(){
        return $this->url;
    }
    public function getTarget(){
        return $this->target;
    }
    public function getMethods(){
        return $this->methods;
    }
    public function getFilters(){
        return $this->filters;
    }
    public function getParams(){
        return $this->params;
    }
    public function getMinVersion(){
        return $this->version_min;
    }
    public function getMaxVersion(){
        return $this->version_max;
    }
    public function getRegex(){
        return preg_replace_callback('/:(\w+)/', array(&$this, 'substituteFilter'), $this->url);
    }
    private function substituteFilter($matches) {
        if (isset($matches[1]) && isset($this->filters[$matches[1]])) {
            return '('.$this->filters[$matches[1]].')';
        }
        return "([\d\w-]+)";
    }
}