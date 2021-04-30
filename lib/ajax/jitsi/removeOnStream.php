<?php

include_once('../../../load.php');
ini_set('display_errors', 0);

if (!is_user() || !isset($_GET['streamId'])){
    die();
}

$streamId = (int)$_GET['streamId'];

$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,views,moderator_id FROM ".DB_PREFIX."conferences where id = '".$streamId."' AND type = 'stream' limit  0,1");

if($streamInfo !== null)
{
    $db->query('DELETE FROM '.DB_PREFIX.'conference_participants WHERE user_id = '.toDb(user_id()).' AND conference_id = '.toDb($streamInfo->id));

    $db->query('UPDATE '.DB_PREFIX.'conferences SET views = views - 1 WHERE id = '.toDb($streamInfo->id));
}
?>