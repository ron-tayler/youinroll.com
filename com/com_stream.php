<?php

$streamId = token_id();

$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,views,moderator_id FROM ".DB_PREFIX."conferences where id = '".$streamId."' AND type = 'stream' limit  0,1");

if($streamInfo !== null)
{
    $streamInfo->categoryName = $db->get_row('SELECT cat_name FROM '.DB_PREFIX.'channels WHERE cat_id = '.toDb($streamInfo->category).'LIMIT(0,1)');
    $userInfo = $db->get_row("SELECT id,avatar,name,onAir FROM ".DB_PREFIX."users where id = ".toDb((int)$streamInfo->moderator_id)." limit  0,1");
    $userInfo->isAuthor = ((int)$streamInfo->moderator_id === user_id());

    if($userInfo->isAuthor)
    {
        $db->query('UPDATE '.DB_PREFIX.'users SET onAir = true WHERE id = '.toDb(user_id()));
    }
}

the_header();
include_once(TPL.'/stream.php');	 
the_footer();
?>