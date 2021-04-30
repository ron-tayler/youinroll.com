<?php

include_once('../../../load.php');
ini_set('display_errors', 0);

if (!isset($_GET['streamId'])){
    die();
}

$streamId = intval($_GET['streamId']);

$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,views,moderator_id,chatRoom,type FROM ".DB_PREFIX."conferences where id = '".$streamId."' limit  0,1");

if($streamInfo !== null)
{
    $streamsHistory = explode(',',$_SESSION['shistory']);

    if(count($streamsHistory) >= 10)
    {
        $_SESSION['shistory'] = '';
    }

    $streamInfo->token = md5($db->get_var('SELECT chatRoom FROM vibe_users WHERE id = '.$streamInfo->moderator_id));

    $streamInfo->transName = transliterate($streamInfo->name)."-user-".$streamInfo->moderator_id;

    $streamInfo->cover = thumb_fix($streamInfo->cover, 500, 500);

    if( !in_array($streamInfo->id, $streamsHistory) )
    {
        $_SESSION['shistory'] .= $streamInfo->id.',';
        $db->query('UPDATE '.DB_PREFIX.'conferences SET views = views + 1 WHERE id = '.toDb($streamInfo->id));
    }

    $userInfo = $db->get_row("SELECT id,avatar FROM ".DB_PREFIX."users where id = ".toDb(user_id())." limit  0,1");
    $userInfo->isAuthor = ((int)$streamInfo->moderator_id === user_id());

    $authorInfo = $db->get_row("SELECT id,avatar,name,onAir,chatRoom FROM ".DB_PREFIX."users where id = ".toDb($streamInfo->moderator_id)." limit  0,1");

    $authorInfo->chatRoom = md5($chatRoom);

    if($userInfo->isAuthor)
    {
        $db->query('UPDATE '.DB_PREFIX.'conferences SET on_air = true WHERE id = '.toDb($streamInfo->id));
    }
}

echo( json_encode([
    'user' => $userInfo,
    'stream' => $streamInfo,
    'author' => $authorInfo
], true) );
?>