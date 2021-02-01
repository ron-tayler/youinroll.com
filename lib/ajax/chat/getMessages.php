<?php

include_once('../../../load.php');

if ( !is_user() || !isset($_GET['chatId']) )
{
    die();
}

$messages = [];

$chatId = $_GET['chatId'];

/* 
Check if user have this conversation
*/
$lists = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' AND conf_id = '.toDb($chatId)
);

if($lists) {

    $messagesList = $db->get_results(
        "SELECT * FROM ".DB_PREFIX."messages WHERE conversation_id = ".toDb($chatId)." ORDER BY created_at ASC  LIMIT 0,50"
    );
    
    foreach($messagesList as $message) {

        $message->isMine = ((int)$message->user_id === user_id())
            ? true
            : false;

        $db->query(
            'UPDATE '.DB_PREFIX.'messages SET readed = '.toDb(true).' WHERE id = '.toDb($message->id)
        );

        $userOfMesage = $cachedb->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

        $message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
        $message->author = $userOfMesage->name;
        $message->avatarLink = profile_url($message->user_id, $userOfMesage->name);

        array_push($messages, $message);
    }

}

    
echo( json_encode($messages, true) );
?>