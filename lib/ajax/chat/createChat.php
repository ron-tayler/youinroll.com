<?php

include_once('../../../load.php');

if(!is_user() || !isset($_POST['chatId']) || !isset($_POST['userId']))
{
    die();
}

$userId = $_POST['userId'];
$chatId = $_POST['chatId'];

$chatExist = $cachedb->get_row("SELECT * FROM ".DB_PREFIX."conversations where conf_id = '".$chatId."' and user_id = '".$userId."' limit  0,1");

if($chatExist === null)
{
    $db->query("INSERT INTO ".DB_PREFIX."conversations(`conf_id`, `user_id`) VALUES ('".$chatId."','".$userId."')");
}

echo( json_encode($chatId, true) );
?>