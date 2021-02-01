<?php

include_once('../../../load.php');

$result = ['result' => false];

if ( !is_user() || !isset($_POST['chatId']) || !isset($_POST['text']) )
{
    die();
}

$chatId = $_POST['chatId'];
$text = $_POST['text'];
$userId = (isset($_POST['userId']) || $_POST['userId'] !== 0) ? $_POST['userId'] : null;

/* 
Check if user have this conversation
*/
$chat = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' AND conf_id = '.toDb($chatId)
);

if($chat === null) {

    $chat = $db->get_row(
        "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' OR user_id = '.toDb( $userId ).' GROUP BY conf_id HAVING COUNT(*) > 1'
    );

    if($chat === null)
    {
        $db->query(
            "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id)+1 FROM ".DB_PREFIX."conversations conv),'".toDb(user_id())."')"
        );
    
        $db->query(
            "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id) FROM ".DB_PREFIX."conversations conv),'".toDb($userId)."')"
        );
        
        $chatId = $db->get_row("SELECT MAX(conf_id) as id FROM ".DB_PREFIX."conversations");
    
        $chatId = $chatId->id;

    } else
    {
        $chatId = $chat->conf_id;
    }
}

$db->query(
    "INSERT INTO ".DB_PREFIX."messages(`text`, `user_id`, `conversation_id`) VALUES ('".toDb($text)."','".toDb(user_id())."','".toDb($chatId)."')"
);

$anotherParticipants = $db->get_results(
    "SELECT user_id,conf_id FROM ".DB_PREFIX."conversations WHERE conf_id = ".toDb($chatId)." AND user_id <> ".toDb(user_id())
);

$participants = [];

foreach ($anotherParticipants as $participant)
{
    $user = $db->get_row("SELECT jitsiLogin from ".DB_PREFIX."users WHERE id = ".toDb($participant->user_id));

    if($user->jitsiLogin !== null)
    {
        array_push($participants, $user);
    }        
}

$result = ['result' => true, 'participants' => $participants];

echo( json_encode($result, true) );
?>