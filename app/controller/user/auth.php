<?php


namespace Controller\User;


use Engine\IController;
use Engine\Request;
use Engine\Response;
use Library\DB;

class Auth implements IController {

    static DB $db;

    static function init(){
        self::$db = DB::init('base');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    /**
     * @param array $param
     * @throws \ExceptionBase
     */
    static function login(array $param = []){
        $login = Request::$post['login']??'';
        $password = Request::$post['password']??'';

        if(empty($login)) throw new \ExceptionBase('Переменная login пустая',7,'Отсутствует переменная login');
        if(empty($password)) throw new \ExceptionBase('Переменная password пустая',7,'Отсутствует переменная password');

        if(preg_match('/^.+@.+$/',$login)!==1) throw new \ExceptionBase('Логин \''.$login.'\' не совпадает паттерну',7,'Ошибка в параметре login');
        if(preg_match('/^[A-Za-z1-9!@#$%^&*()_\-=+"№;:?\[\]{}]{8,}$/',$password)!==1) throw new \ExceptionBase('Пароль не совпадает паттерну',7,'Ошибка в параметре password');

        $user = self::$db->select('id,password','users','email=\''.self::$db->escape(nl2br($login)).'\'')->row;
        $db_hash = $user['password'];

        if( !hash_equals($db_hash,hash('sha1',$login.':'.$password)) and
            !hash_equals($db_hash,hash('sha1',$password)) ){
            throw new \ExceptionBase('проверка хеша дала не верный результат',3,'Данные не верны');
        }

        $token = self::getToken($user['id']);

        Response::setOutput(['user_id'=>$user['id'],'token'=>$token]);
    }

    private static function getToken($user_id){
        $user = self::$db->select('email,password','users','id='.$user_id)->row;

        do{
            $random = rand(0, PHP_INT_MAX);
            $data = "Access:{$user['email']}:{$user['password']}:$random";
            $token = hash('sha512', $data);
        }while( self::$db->selectAll('users_tokens','token=\''.$token.'\'')->num_rows>0 );

        self::$db->insert_into('users_tokens',[
            'user_id'=>$user_id,
            'type'=>'4',
            'token'=>'\''.self::$db->escape($token).'\''
        ]);

        return $token;
    }
}