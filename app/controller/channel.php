<?php


namespace Controller;

/**
 * Class Controller/Channel
 * @package Youinroll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
class Channel extends \LMVCL implements \IController
{

    /**
     * index
     * @param array $param
     * @return array
     * @api
     * @version
     */
    public function index(array $param){
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
    public function list(array $param){
        $offset = $this->request->get['offset']??'0';
        // Проверка по регулярному
        if(preg_match('/^[0-9]?$/',$offset)!==1)
            throw new \Error\Controller\User\Profile('Переданный offset имеет значение: '.$offset,7,'Ошибка в параметре offset');

        $offset = (int)$offset;

        $res = $this->db->selectAll('users','1 ORDER BY lastNoty DESC,views DESC LIMIT '.$offset.',100');

        $data = [];
        foreach ($res->rows as $row){
            $data[] = [
                'id'=>(int)$row['id'],
                'avatar'=>(string)$row['avatar'],
                'name'=>(string)$row['name'],
                'onAir'=>(int)$row['onAir']
            ];
        }
        return $data;
    }
}