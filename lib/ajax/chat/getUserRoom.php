<?php

include_once('../../../load.php');

if (!is_user()){
    die();
}

/* 
Get User Room hash
*/
$sql = "SELECT chatRoom FROM ".DB_PREFIX."users WHERE id = ".toDb( user_id() ).' LIMIT 0,1';

$user = $db->get_row($sql);

$chatRoom = $user->chatRoom;
    
echo $chatRoom;

?>