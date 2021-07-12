<?php

namespace Controller;
use Engine\Request;
use Engine\Response;
use Library\DB;
use Library\User;
use ExceptionBase;

/**
 * Class Notification
 * @package Controller
 */
class Notification implements \Engine\IController{
    static DB $db;
    static array $types_template = [
        '1'=>[
            'tags'=>[1,6],
            'text'=>'понравилось ваше видео',
            'icon'=>'thumb_up_alt'
        ],
        '2'=>[
            'tags'=>[5,6],
            'text'=>'добавил(а) новое видео',
            'icon'=>'add'
        ],
        '3'=>[
            'tags'=>[6],
            'text'=>'посмотрел(а) ваше видео',
            'icon'=>'visibility'
        ],
        '4'=>[
            'tags'=>[3,6],
            'text'=>'поделился(ась) вашим видео',
            'icon'=>'reply'
        ],
        '5'=>[
            'tags'=>[4],
            'text'=>'подписался(ась) на вас',
            'icon'=>'notifications'
        ],
        '6'=>[
            'tags'=>[2,6],
            'text'=>'прокомментировал(а) ваше видео',
            'icon'=>'edit'
        ],
        '7'=>[
            'tags'=>[1,2],
            'text'=>'понравился ваш комментарий',
            'icon'=>'thumb_up_alt'
        ],
        '8'=>[
            'tags'=>[3,8],
            'text'=>'поделился(ась) вашей публикацией',
            'icon'=>'reply'
        ],
        '9'=>[
            'tags'=>[1,8],
            'text'=>'понравилась ваша публикация',
            'icon'=>'thumb_up_alt'
        ]
    ];
    static array $tags = [
        '0'=>'Все',
        '1'=>'Лайки',
        '2'=>'Комментарии',
        '3'=>'Упоминания',
        '4'=>'Подписчики',
        '5'=>'Подписки',
        '6'=>'Видео',
        '7'=>'Трансляции',
        '8'=>'Публикации',
        '9'=>'Конференции'
    ];

    static function init(){
        self::$db = DB::init('base');
    }

    static function index(array $param = []){
        // TODO: Implement index() method.
    }

    /**
     * Получение всех уведомлений для пользователя
     * @api /getNotification
     */
    static function getNotification() {
        $user_id = user::is_user()?user::getid():0; // 953
        if(preg_match('/^[1-9]\d*$/',$user_id)!==1) throw new exceptionbase("ошибка авторизации или у вас нет доступа, user_id='$user_id'",3,'ошибка авторизации или у вас нет доступа');

        $sql_1 = self::$db->select('id','videos',"user_id=$user_id",true);
        $sql_2 = self::$db->select('id','images',"user_id=$user_id",true);
        $activity = self::$db->selectall('activity as a',"((a.type not in (8,9) and a.object in ($sql_1)) or (a.type in (8,9) and a.object in ($sql_2))) and a.user <> $user_id");

        $notifications_data = [];
        foreach ($activity->rows as $notify){
            $notification_data = [];

            $puser_name = self::$db->select('name, avatar','users','id='.$notify['user']);
            switch ($notify['type']){
                case '5':
                    $notification_data['refer'] = '';
                    break;
                case '1':case '2':case '3':case '4':case '6':case '7':
                    $notification_data['refer'] = self::$db->select('title','videos','id='.$notify['object'])->row['title'];
                    $notification_data['refer_url'] = "video/{$notify['object']}/video";
                    break;
                case '8':case '9':
                    $notification_data['refer'] = self::$db->select('title','images','id='.$notify['object'])->row['title'];
                    $notification_data['refer_url'] = 'image/{$notify[\'object\']}/image';
                    break;
            }

            $notification_data['tags'] = self::$types_template[$notify['type']]['tags'];
            $notification_data['icon'] = self::$types_template[$notify['type']]['icon'];
            $notification_data['img'] = (($puser_name->row['avatar']=='uploads/def-avatar.jpg')?'storage/':'').$puser_name->row['avatar'];
            $notification_data['title'] = $puser_name->row['name'];
            $notification_data['title_url'] = "profile/profile/{$notify['user']}";
            $notification_data['body'] = self::$types_template[$notify['type']]['text'];

            $notifications_data []= $notification_data;
        }

        response::setoutput($notifications_data);
    }

    /**
     * Получение всех тегов
     * @api /getNotificationTags
     */
    static function getTags(){
        Response::setOutput(self::$tags);
    }

    /**
     * Регистрация токена для Push
     * @api /regPushToken
     */
    static function regPushToken(){
        if(!User::is_user()) throw new \ErrorForbidden('Ошибка авторизации или у вас нет доступа, user_id=\''.User::getId().'\'');

        $pushToken = Request::$post['push_token'];
        $pushToken = self::$db->escape($pushToken);
        self::$db->query("INSERT INTO vibe_users_push_tokens(user_id,push_token) VALUES (".User::getId().", '$pushToken')");

        Response::setOutput('ok');
    }
}
