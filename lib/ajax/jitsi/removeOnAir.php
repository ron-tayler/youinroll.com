<?php

include_once('../../../load.php');
ini_set('display_errors', 0);

if (!is_user() || !isset($_GET['streamId'])){
    die();
}

$streamId = (int)$_GET['streamId'];

$streamInfo = $db->get_row("SELECT * FROM ".DB_PREFIX."conferences where id = '".$streamId."' AND moderator_id = '".toDb(user_id())."' limit  0,1");

if($streamInfo !== null)
{
    $userInfo = $db->get_row("UPDATE ".DB_PREFIX."conferences SET on_air = '0' WHERE id = '$streamId'");
}
?>