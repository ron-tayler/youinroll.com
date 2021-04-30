<?php

$rtfId = token_id();
$rtfName = token();

class rtfException1 extends Exception{}
class rtfException2 extends Exception{}
class rtfException3 extends Exception{}

Class ControllerRtfTest{
    public static function action($id,$name){
        switch ($name){
            case 'page_home':
                exit(json_encode([
                    'header'=>'RTF Test Page',
                    'text'=>'Lorem ipsum dolor sit amet, consectetur adipisicing elit. Autem, neque!'
                ]));
                break;
            case 'page_profile':
                exit(json_encode([
                    'name'=>'Ron_Tayler',
                    'description'=>'Back-end developer'
                ]));
                break;
            default:
                $data = array();
                global $YNRtemplate;
                $YNRtemplate->include('/rtf.php',[],[], $data);
        }
    }
    public static function test_sql(){
        $sql_old = "SELECT " . DB_PREFIX . "videos.id, " . DB_PREFIX . "videos.title, " . DB_PREFIX . "videos.user_id, " . DB_PREFIX . "videos.thumb, " . DB_PREFIX . "videos.views, " . DB_PREFIX . "videos.liked, " . DB_PREFIX . "videos.duration, " . DB_PREFIX . "videos.nsfw, " . DB_PREFIX . "users.group_id, " . DB_PREFIX . "users.name AS owner
                    FROM " . DB_PREFIX . "playlist_data
                    LEFT JOIN " . DB_PREFIX . "videos ON " . DB_PREFIX . "playlist_data.video_id = " . DB_PREFIX . "videos.id
                    LEFT JOIN " . DB_PREFIX . "users ON " . DB_PREFIX . "videos.user_id = " . DB_PREFIX . "users.id
                    WHERE " . DB_PREFIX . "playlist_data.playlist =  '" . 11 . "'
                    ORDER BY " . DB_PREFIX . "playlist_data.id DESC " . this_offset(bpp());

        $sql_new = "
            SELECT
                v.id,
                v.media,
                v.title,
                v.user_id,
                v.thumb,
                v.views,
                v.liked,
                v.duration,
                v.nsfw, 
                u.group_id, 
                u.name AS owner
            FROM PREFIX_playlist_data AS pd
            LEFT JOIN PREFIX_videos AS v
                ON pd.video_id = v.id
            LEFT JOIN PREFIX_users  AS u
                ON v.user_id = u.id
            WHERE pd.playlist =  '%s'
            ORDER BY pd.id DESC ".this_offset(bpp());
        $sql_new = str_replace('PREFIX_',DB_PREFIX,$sql_new);
        $sql_new = sprintf($sql_new, 11);

        echo '<pre>';
        echo gettype($sql_old).$sql_old.PHP_EOL;
        echo gettype($sql_new).$sql_new.PHP_EOL;
        echo '</pre>';
    }
    public static function test_Exc($id){
        header("Content-Type: text/html; charset=utf-8");
        try{
            if($id==1){
                throw new rtfException1();
            }elseif($id==2){
                throw new rtfException2();
            }elseif($id==3){
                throw new rtfException3();
            }else{
                echo 'Исключение не сработало';
            }
        }catch (rtfException1 $ex){
            echo 'Сработало исключение 1';
        }catch (rtfException2 $ex){
            echo 'Сработало исключение 2';
        }catch (Exception $ex){
            echo 'Сработало неизвестное исключение: '.PHP_EOL.$ex->getFile().': '.$ex->getLine();
        }
    }
    public static function test_reg(){
        $pat = str_replace('/','\\/','/api/(\w+)/(\d+)');
        $res = preg_match('/'.$pat.'/','/api/getUsers4chat/13',$params);
        if($res===false){echo 'error';}
        print_r($params);
        array_splice($params,0,1);
        print_r($params);
    }
}

ControllerRtfTest::test_reg();







