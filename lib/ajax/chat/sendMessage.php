<?php
ini_set('display_errors', 1);
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

    if($chat !== null)
    {
        $chat = $cachedb->get_row('SELECT * FROM '.DB_PREFIX."conversations WHERE conf_id = '".$chat->conf_id."' GROUP BY conf_id HAVING COUNT(*) > 2"); 
        
        if($chat)
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

    } else
    {
        $db->query(
            "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id)+1 FROM ".DB_PREFIX."conversations conv),'".toDb(user_id())."')"
        );
    
        $db->query(
            "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id) FROM ".DB_PREFIX."conversations conv),'".toDb($userId)."')"
        );
        
        $chatId = $db->get_row("SELECT MAX(conf_id) as id FROM ".DB_PREFIX."conversations");
    
        $chatId = $chatId->id;
    }
}

$db->query(
    "INSERT INTO ".DB_PREFIX."messages(`text`, `user_id`, `conversation_id`) VALUES ('".toDb($text)."','".toDb(user_id())."','".toDb($chatId)."')"
);

$allParticipants = $db->get_results(
    "SELECT user_id,conf_id FROM ".DB_PREFIX."conversations WHERE conf_id = ".toDb($chatId)
);

$participants = [];

foreach ($allParticipants as $participant)
{
    $user = $db->get_row("SELECT chatRoom from ".DB_PREFIX."users WHERE id = ".toDb($participant->user_id));

    if($user->chatRoom !== null)
    {
        array_push($participants, $user);
    }
}

$message = $db->get_row('SELECT * FROM '.DB_PREFIX.'messages
WHERE id = (
    SELECT MAX(id) FROM '.DB_PREFIX.'messages)');

$message->isMine = ( intval($message->user_id) === intval(user_id()))
    ? true
    : false;

$userOfMesage = $db->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

$message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
$message->author = $userOfMesage->name;
$message->type = 'message';
$message->chatId = $message->conversation_id;
$message->avatarLink = profile_url($message->user_id, $userOfMesage->name);

$result = ['message' => $message, 'users' => $participants];

echo( json_encode($result, true) );
?>