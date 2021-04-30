<?php

include_once('../../../load.php');

if (!is_user() && !isset($_GET['value']))
{
    die();
}

$users = [];

$value = strtolower($_GET['value']);

/* 
All users from search
*/
$lists = $db->get_results("select * from ".DB_PREFIX."users where (LOWER(name) like '%" .$value. "%' OR LOWER(name) = '" .toDb($value). "') AND id <> '".toDb(user_id())."' order by lastNoty DESC LIMIT 25");

if($lists) {

    foreach($lists as $user) {

        $user->avatar = thumb_fix($user->avatar, true, 40, 40);
        $user->title = $user->name;

        $user->lastUpdate = $user->lastNoty;
        $user->lastMessage = '';
        $user->unreadCount = 0;

        array_push($users, $user);
    }

}

    
echo( json_encode($users, true) );
?>