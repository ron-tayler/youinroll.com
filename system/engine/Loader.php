<?php

namespace Engine;
/**
 * Class Loader
 * @package Engine
 * @author Ron_Tayler
 * @copyright 04.05.2021
 */
class Loader {

    /**
     * Loader constructor.
     */
    private function __construct(){}

    /**
     * Method controller - Загрузка контролеров
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public static function controller(string $name){
        return self::load($name,DIR_CONTROLLER);
    }

    /**
     * Method model - Загрузка моделей
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public static function model(string $name){
        return self::load($name,DIR_MODEL);
    }

    /**
     * Method library - Загрузка моделей
     * @param string $name
     * @return bool False при ошибке, True при успехе
     */
    public static function library(string $name){
        return self::load($name,DIR_LIB);
    }

    /**
     * Method load - Универсальный загрузчик
     * @param string $name 'video' or 'user/chanel/video'
     * @param string $dir DIR_...
     * @return bool False при ошибке, True при успехе
     * @todo Добавить проверки
     * Проверка $name по регулярке
     */
    private static function load(string $name,string $dir){
        $file = $dir.'/'.strtolower($name).'.php';
        if(!is_file($file)) return false;
        return require_once $file;
    }
}