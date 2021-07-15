<?php

namespace Controller\User;
use Engine\Request;
use Engine\Response;
use Error\Controller\User\ProfileError;
use ErrorURL;
use ErrorRequest;
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
     * @throws
     */
    static function index(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new ProfileError('Переданный ID имеет значение: '.$param['id'],6,'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат',4,'Ошибка в параметре ID');
        $profile = $response->row;

        $gid = $profile['group_id']??0;
        if($gid<1) throw new ProfileError('Ошибка с параметром group_id у пользователя $id',5);
        $response = self::$db->selectAll('users_groups','id='.$gid); // TODO перенести в Model
        if($response->num_rows==0) throw new ProfileError('Запрос группы с id '.$gid.' в БД выдал пустой результат',5);
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
     * @throws
     */
    static function subscribers(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new ProfileError('Переданный ID имеет значение: '.$param['id'],6,'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат',4,'Ошибка в параметре ID');

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
     * @throws
     */
    static function subscriptions(array $param = []){
        $id = (int)($param['id']??0);

        if($id<1) throw new ProfileError('Переданный ID имеет значение: '.$param['id'],'Ошибка в параметре ID');

        $response = self::$db->selectAll('users','id='.$id); // TODO перенести в Model
        if($response->num_rows==0) throw new ProfileError('Запрос пользователя с id '.$id.' в БД выдал пустой результат','Ошибка в параметре ID');

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

    /**
     * Получить список видео
     * @param array $param
     * @api /profile/:id/videos
     * @throws
     */
    static function videos(array $param = []){
        $user_id = $param['id']??'0';
        $offset = Request::$get['offset']??'0';

        if(preg_match('/^[1-9][0-9]*$/',$user_id)!==1) throw new ErrorURL('Переданный ID имеет значение: '.$user_id,'Ошибка в параметре :id');
        if(preg_match('/^[0-9]*$/',$offset)!==1) throw new ErrorRequest('Переданный offset имеет значение: '.$offset,'Ошибка в параметре offset');

        $user_id = (int)$user_id;
        $offset = (int)$offset;

        $videos = self::$db->selectAll('videos',"user_id=$user_id AND pub=1 ORDER BY id DESC LIMIT $offset,25");

        $videosPayload = [];
        foreach ($videos->rows as $video){
            $videoPayload = [
                'id'=>$video['id'],
                'user_id'=>$video['user_id'],
                'date'=>$video['date'], // Дата создания
                'title'=>$video['title'], // Название
                'thumb'=>$video['thumb'], // Изображение
                'description'=>$video['description'], // Описание
                'category_id'=>$video['category'], // ID категории
                'views'=>$video['views'], // кол-во просмотров
                'liked'=>$video['liked'], // Кол-во лайков
                'disliked'=>$video['disliked'], // Кол-во дизлайков
                'duration'=>$video['duration'], // Длина в секундах
            ];
            $videosPayload []= $videoPayload;
        }

        Response::setOutput($videosPayload);
    }

    /**
     * Получить список уроков
     * @param array $param
     * @api /profile/:id/lessons
     * @throws
     */
    static function lessons(array $param = []){
        $user_id = $param['id']??'0';
        $offset = Request::$get['offset']??'0';

        if(preg_match('/^[1-9][0-9]*$/',$user_id)!==1){
            throw new ErrorURL(
                'Переданный ID имеет значение: '.$user_id,
                'Ошибка в параметре :id'
            );
        }
        if(preg_match('/^[0-9]*$/',$offset)!==1){
            throw new ErrorRequest(
                'Переданный offset имеет значение: '.$offset,
                'Ошибка в параметре offset'
            );
        }

        $user_id = (int)$user_id;
        $offset = (int)$offset;

        $lessons = self::$db->selectAll('conferences',"moderator_id=$user_id AND type='lesson' ORDER BY id DESC LIMIT $offset,25");

        $lessonsPayload = [];
        foreach ($lessons->rows as $lesson){
            $created = date_parse($lesson['created_at']);
            $started = date_parse($lesson['started_at']);

            $lessonPayload = [
                'id'=>$lesson['id'],
                'name'=>$lesson['name'],
                'img'=>$lesson['cover'],
                'description'=>$lesson['description'],
                'category_id'=>$lesson['category'],
                'likes'=>$lesson['likes'],
                'user_id'=>$lesson['moderator_id'],
                'views'=>$lesson['views'],
                'tags'=>$lesson['tags'],
                'on_air'=>$lesson['on_air'],
                'chatRoomToken'=>$lesson['chatRoom'],
                'started'=>[
                    'y'=>$started['year'],
                    'm'=>$started['month'],
                    'd'=>$started['day'],
                    'h'=>$started['hour'],
                    'M'=>$started['minute'],
                    's'=>$started['second']
                ],
                'created'=>[
                    'y'=>$created['year'],
                    'm'=>$created['month'],
                    'd'=>$created['day'],
                    'h'=>$created['hour'],
                    'M'=>$created['minute'],
                    's'=>$created['second']
                ]
            ];
            $lessonsPayload []= $lessonPayload;
        }

        Response::setOutput($lessonsPayload);
    }

    /**
     * Получить список трансляций
     * @param array $param
     * @api /profile/:id/lessons
     * @throws
     */
    static function streams(array $param = []){
        $user_id = $param['id']??'0';
        $offset = Request::$get['offset']??'0';

        if(preg_match('/^[1-9][0-9]*$/',$user_id)!==1){
            throw new ErrorURL(
                'Переданный ID имеет значение: '.$user_id,
                'Ошибка в параметре :id'
            );
        }
        if(preg_match('/^[0-9]*$/',$offset)!==1){
            throw new ErrorRequest(
                'Переданный offset имеет значение: '.$offset,
                'Ошибка в параметре offset'
            );
        }

        $user_id = (int)$user_id;
        $offset = (int)$offset;

        $streams = self::$db->selectAll('conferences',"moderator_id=$user_id AND type='stream' ORDER BY id DESC LIMIT $offset,25");

        $streamsPayload = [];
        foreach ($streams->rows as $stream){
            $created = date_parse($stream['created_at']);
            $started = date_parse($stream['started_at']);

            $streamPayload = [
                'id'=>$stream['id'],
                'name'=>$stream['name'],
                'img'=>$stream['cover'],
                'description'=>$stream['description'],
                'category_id'=>$stream['category'],
                'likes'=>$stream['likes'],
                'user_id'=>$stream['moderator_id'],
                'views'=>$stream['views'],
                'tags'=>$stream['tags'],
                'on_air'=>$stream['on_air'],
                'chatRoomToken'=>$stream['chatRoom'],
                'started'=>[
                    'y'=>$started['year'],
                    'm'=>$started['month'],
                    'd'=>$started['day'],
                    'h'=>$started['hour'],
                    'i'=>$started['minute'],
                    's'=>$started['second']
                ],
                'created'=>[
                    'y'=>$created['year'],
                    'm'=>$created['month'],
                    'd'=>$created['day'],
                    'h'=>$created['hour'],
                    'i'=>$created['minute'],
                    's'=>$created['second']
                ]
            ];
            $streamsPayload []= $streamPayload;
        }

        Response::setOutput($streamsPayload);
    }

    /**
     * Получение списка для расписания
     * @param array $param
     * @api /profile/:id/timeline
     * @throws
     */
    static function timeline(array $param = []){
        $user_id = $param['id'];
        $week_date = Request::$get['weekDate']; // 12.07.2021 - понедельник указывающий на неделю

        if(preg_match('/^(?:0[1-9]|[1-2][0-9]|3[01])\.(?:0[1-9]|1[0-2])\.(?=[0-9]{0,3}[1-9])[0-9]{4}$/',$week_date)!==1){
            throw new ErrorRequest(
                'Переданный weekDate имеет значение: '.$week_date,
                'Ошибка в параметре weekDate'
            );
        }

        $startWeekDate = new \DateTime($week_date);
        $endWeekDate = new \DateTime($week_date);
        $endWeekDate->add(date_interval_create_from_date_string('7 days'));

        $cards = self::$db->selectAll(
            'conferences',
            "moderator_id=$user_id AND 
            started_at > '{$startWeekDate->format('Y-m-d H:i:s.u')}' AND  
            started_at < '{$endWeekDate->format('Y-m-d H:i:s.u')}' ORDER BY id DESC"
        );
        $cardsPayload = [];

        foreach ($cards->rows as $card){
            $created = date_parse($card['created_at']);
            $started = date_parse($card['started_at']);

            $cardPayload = [
                'id'=>$card['id'],
                'name'=>$card['name'],
                'img'=>$card['cover'],
                'description'=>$card['description'],
                'category_id'=>$card['category'],
                'likes'=>$card['likes'],
                'user_id'=>$card['moderator_id'],
                'views'=>$card['views'],
                'tags'=>$card['tags'],
                'on_air'=>$card['on_air'],
                'chatRoomToken'=>$card['chatRoom'],
                'started'=>[
                    'y'=>$started['year'],
                    'm'=>$started['month'],
                    'd'=>$started['day'],
                    'h'=>$started['hour'],
                    'i'=>$started['minute'],
                    's'=>$started['second']
                ],
                'created'=>[
                    'y'=>$created['year'],
                    'm'=>$created['month'],
                    'd'=>$created['day'],
                    'h'=>$created['hour'],
                    'i'=>$created['minute'],
                    's'=>$created['second']
                ],
                'duration'=>60*60*2
            ];

            $cardsPayload []= $cardPayload;
        }

        Response::setOutput($cardsPayload);
    }

    /**
     * Получение списка публикаций
     * @param array $params
     * @api /profile/:id/images
     * @throws
     */
    static function images(array $params = []){
        $user_id = $param['id']??'0';
        $offset = Request::$get['offset']??'0';

        if(preg_match('/^[1-9][0-9]*$/',$user_id)!==1){
            throw new ErrorURL(
                'Переданный ID имеет значение: '.$user_id,
                'Ошибка в параметре :id'
            );
        }
        if(preg_match('/^[0-9]*$/',$offset)!==1){
            throw new ErrorRequest(
                'Переданный offset имеет значение: '.$offset,
                'Ошибка в параметре offset'
            );
        }

        $user_id = (int)$user_id;
        $offset = (int)$offset;

        $images = self::$db->selectAll('image',"user_id=$user_id ORDER BY id DESC LIMIT $offset,25");

        $imagesPayload = [];
        foreach ($images->rows as $image){
            $imagePayload = [
                'id'=>$image['id']
            ];
            $imagesPayload []= $imagePayload;
        }

        Response::setOutput($imagesPayload);
    }
}
