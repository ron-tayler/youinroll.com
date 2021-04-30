<?php

/**
 * Class Loader
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Loader implements IEngine{
    private Registry $registry;

    /**
     * Loader constructor.
     * @param Registry $registry
     */
    public function __construct(Registry $registry){
        $this->registry = $registry;
    }

    /**
     * Method controller - Загрузка контролеров
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public function controller(string $name){
        return $this->load($name,DIR_CONTROLLER,'controller_','Controller');
    }

    /**
     * Method model - Загрузка моделей
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public function model(string $name){
        return $this->load($name,DIR_MODEL,'model_','Model');
    }

    /**
     * Method library - Загрузка моделей
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public function library(string $name){
        return $this->load($name,DIR_LIB,'','');
    }

    /**
     * Method load - Универсальный загрузчик
     * @param string $name 'video' or 'user/chanel/video'
     * @param string $dir DIR_...
     * @param string $prefix
     * @param string $namespace
     * @return bool False при ошибке, True при успехе
     * @todo Добавить проверки
     * Проверка $name по регулярке
     * Проверка наличия файла
     * Проверка наличия класса
     */
    private function load(string $name,string $dir,string $prefix,string $namespace){
        $class = $namespace.'\\'.str_replace('/','\\', $name);
        $file = $dir.'/'.strtolower($name).'.php';
        $reg_name = strtolower($prefix).str_replace('/','_',strtolower($name));
        require_once $file;
        /** @var LMVCL $lmvcl */
        $lmvcl = new $class($this->registry);
        $this->registry->set($reg_name,$lmvcl);
        return true;
    }
}