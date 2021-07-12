<?php


namespace Controller;

use Engine\Request;
use Engine\Response;
use Library\DB;

/**
 * Class Category
 * @package Controller
 * @author Ron_Tayler
 * @copyright 07.06.2021
 */
class Category implements \Engine\IController
{
    static DB $db;

    static function init()
    {
        self::$db = DB::init('base');
    }

    static function index(array $param = [])
    {
        // TODO: Implement index() method.
    }

    static function getList(){
        $ts = Request::$get['ts']??0;

        $count_sql = self::$db->select('count(*) as count','videos','category=c.cat_id',true);
        $categories = self::$db->select("*,($count_sql) as videos",'channels as c',"type=1 AND child_of=0 ORDER BY videos DESC LIMIT $ts,100");

        $data = [];
        foreach ($categories->rows as $category){
            $data []= [
                'id'=>$category['cat_id'],
                'img'=>$category['picture'],
                'name'=>$category['cat_name'],
                'tags'=>explode(',',$category['cat_desc']),
                'videos'=>$category['videos']
            ];
        }

        Response::setOutput($data);
    }
}
