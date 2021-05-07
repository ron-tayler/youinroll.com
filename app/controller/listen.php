<?php

namespace Controller;

/**
 * Class Listen
 * @package Controller
 */
class Listen implements \Engine\IController {
    static function init(){
        // TODO: Implement init() method.
    }

    static function index(array $param){
        // TODO: Implement index() method.
    }

    static function stream(){
        // Проверить данные
        // Проверить БД
        // Начать слушать RabbitMQ
        // При получении попробовать получить ещё
        // Сформировать данные
        // Отправить данные
    }
}