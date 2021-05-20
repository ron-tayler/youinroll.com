<?php

namespace Controller\User;
use Engine\Response;
use Library\DB;

/**
 * Class Controller\User\Profile
 * @package YouInRoll.com
 * @author Ron_Tayler
 * @copyright 2021
 */
final class Profile {
    static DB $db;

    static function init(){
        self::$db = DB::init('base');
    }

    /**
     * публичная информация о пользователе по id
     * @param array $param
     * @return array
     * @api /profile/:id
     * @version only 1.0
     */
    static function index(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new \Error\Controller\User\ProfileError('Переданный ID имеет значение: '.$param['id'],6,'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new \Error\Controller\User\ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат',4,'Ошибка в параметре ID');
        $profile = $response->row;

        $gid = $profile['group_id']??0;
        if($gid<1) throw new \Error\Controller\User\ProfileError('Ошибка с параметром group_id у пользователя $id',5);
        $response = self::$db->selectAll('users_groups','id='.$gid); // TODO перенести в Model
        if($response->num_rows==0) throw new \Error\Controller\User\ProfileError('Запрос группы с id '.$gid.' в БД выдал пустой результат',5);
        $group_name = $response->row['name'];

        $return_params = [
            'id'=>(int)$profile['id'],
            'email'=>(string)$profile['email'],
            'phone'=>(string)$profile['phone'],
            'last_login'=>(string)$profile['lastlogin'],
            'group'=>(string)$group_name,
            'avatar'=>(string)$profile['avatar'],
            'banner'=>(string)$profile['cover'],
            'date_registered'=>(string)$profile['date_registered'],
            'name'=>(string)$profile['name'],
            'bio'=>(string)$profile['bio'],
            'views'=>(int)$profile['views'],
            'onAir'=>(int)$profile['onAir']
        ];

        return $return_params;
    }

    /**
     * публичная информация о пользователе по id
     * @param array $param
     * @api /profile/:id/info
     * @version 1.1 - now
     */
    static function info(array $param = []){
        $user = self::index($param);
        unset($user['email']);
        unset($user['phone']);
        Response::setOutput($user);
    }

    /**
     * Публичный список подписчиков
     * @param array $param
     * @api /profile/:id/subscribers
     * @version 1.1 - now
     */
    static function subscribers(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new \Error\Controller\User\ProfileError('Переданный ID имеет значение: '.$param['id'],6,'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new \Error\Controller\User\ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат',4,'Ошибка в параметре ID');

        $sel = self::$db->select('fid','users_friends','uid='.$id,true);
        $res = self::$db->selectAll('users','id IN('.$sel.')');
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

    /**
     * Публичный список подписок
     * @param array $param
     * @api /profile/:id/subscriptions
     * @version 1.1 - now
     */
    static function subscriptions(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new \Error\Controller\User\ProfileError('Переданный ID имеет значение: '.$param['id'],6,'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new \Error\Controller\User\ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат',4,'Ошибка в параметре ID');

        $sel = self::$db->select('uid','users_friends','fid='.$id,true);
        $res = self::$db->selectAll('users','id IN('.$sel.')');
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