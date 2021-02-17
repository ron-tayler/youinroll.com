<?php

include_once('../../../load.php');

if (!is_user() || !isset($_GET['streamId'])){
    die();
}

$streamId = (int)$_GET['streamId'];

$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,views,moderator_id FROM ".DB_PREFIX."conferences where id = '".$streamId."' AND type = 'stream' limit  0,1");

if($streamInfo !== null)
{
    $userInfo = $db->get_row("SELECT id FROM ".DB_PREFIX."users where id = ".toDb(user_id())." limit  0,1");
    $userInfo->isAuthor = ((int)$streamInfo->moderator_id === user_id());

    $authorInfo = $db->get_row("SELECT id,avatar,name,onAir FROM ".DB_PREFIX."users where id = ".toDb($streamInfo->moderator_id)." limit  0,1");

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