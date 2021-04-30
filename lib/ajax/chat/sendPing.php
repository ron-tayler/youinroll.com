<?php
include_once('../../../load.php');
ini_set('display_errors', 0);


$result = [];

if ( !is_user() || !isset($_POST['chatId']) )
{
    die();
}

$chatId = $_POST['chatId'];
$userId = (isset($_POST['userId']) || $_POST['userId'] !== 0) ? $_POST['userId'] : null;

/* 
Check if user have this conversation
*/
$chat = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' AND conf_id = '.toDb($chatId)
);


$allParticipants = $db->get_results(
    "SELECT user_id,conf_id FROM ".DB_PREFIX."conversations WHERE user_id <> ".toDb(user_id())." AND conf_id = ".toDb($chatId)
);

foreach ($allParticipants as $participant)
{
    $user = $db->get_row("SELECT chatRoom from ".DB_PREFIX."users WHERE id = ".toDb($participant->user_id));

    $user->type = 'ping';
    array_push($result, $user);
}


echo( json_encode($result, true) );

class Chat {}
?>