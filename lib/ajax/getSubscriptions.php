<?php include_once('../../load.php');

$page = intval( isset(
    $_POST['page'])
    ? $_POST['page']
    : 1
);

$result = array();

if(is_user() && $page > 0)
{
    $itemsCount = 7;

    $offset = ($page - 1) * $itemsCount;

    //0,5 5,10 10,15
    
    $userFriends = $cachedb->get_results("SELECT id,avatar,name from ".DB_PREFIX."users where id in (select uid from ".DB_PREFIX."users_friends where fid ='".user_id()."') order by lastNoty desc limit $offset,$itemsCount");
    if($userFriends !== null)
    {

        foreach ($userFriends as $subscription)
        {
            $subscription->thumb = thumb_fix($subscription->avatar, true, 27, 27);
            $subscription->url = profile_url($subscription->id , $subscription->name);
        }

    }
}

echo json_encode($userFriends);
?>