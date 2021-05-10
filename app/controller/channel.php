<?php

namespace Controller;
use Engine\IController;
use Engine\Request;
use Engine\Response;
use Library\DB;

/**
 * Class Controller/Channel
 * @package Youinroll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Channel implements IController{

    static DB $db;

    static function init(){
        self::$db = DB::init('base');
    }

    /**
     * index
     * @param array $param
     * @return array
     * @api
     * @version
     */
    static function index(array $param = []){
        return [];
    }

    /**
     * Список всех каналов с лимитом 100
     * @param array $param
     * @get int offset
     * @return array
     * @api /channels
     * @version 1.0
     */
    static function list(array $param){
        $offset = Request::$get['offset']??'0';
        // Проверка по регулярному
        if(preg_match('/^[0-9]?$/',$offset)!==1)
            throw new \Error\Controller\User\ProfileError('Переданный offset имеет значение: '.$offset,7,'Ошибка в параметре offset');

        $offset = (int)$offset;

        $res = self::$db->selectAll('users','1 ORDER BY lastNoty DESC,views DESC LIMIT '.$offset.',100');

        $data = [];
        foreach ($res->rows as $row){
            $data[] = [
                'id'=>(int)$row['id'],
                'avatar'=>(string)$row['avatar'],
                'name'=>(string)$row['name'],
                'onAir'=>(int)$row['onAir']
            ];
        }
        Response::setOutput($data);
    }
}