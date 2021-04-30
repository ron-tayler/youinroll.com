<?php

include_once('../../../load.php');

if (!is_user() || !isset($_GET['userId']) ){
    die();
}

$userId = $_GET['userId'];

/* 
if conversation exist
*/
$sql = "SELECT * FROM ".DB_PREFIX."conversations WHERE (user_id = ".toDb( user_id() ).' OR user_id = '.toDb( $userId ).') GROUP BY conf_id HAVING COUNT(*) > 1';
$chats = $db->get_results(
    $sql
);

if($chats !== null)
{
    foreach ($chats as $chat)
    {
        $chatNotPrivate = $db->get_row('SELECT * FROM vibe_conversations WHERE conf_id = '.toDb($chat->conf_id).' HAVING COUNT(*) > 2');
        
        if($chatNotPrivate)
        {
            $chat = new Chat();

        } else
        {
            $normalchat = $db->get_row('SELECT * FROM vibe_conversations WHERE conf_id = '.toDb($chat->conf_id));
        }
    }
}

if($normalchat)
{
    $chat = $normalchat;
}

$firstUserInDialog = $cachedb->get_row("SELECT id,avatar,name FROM ".DB_PREFIX."users where id = '".$userId."' limit  0,1");	
        
if($firstUserInDialog) {
    $chat->userId = $firstUserInDialog->id;
    $chat->avatar = thumb_fix($firstUserInDialog->avatar, true, 40, 40);
    $chat->title = $firstUserInDialog->name;
    $chat->profileUrl = profile_url($firstUserInDialog->id, $firstUserInDialog->name);
} else
{
    $chat->userId = 0;
    $chat->avatar = thumb_fix('storage/uploads/noimage.png', true, 40, 40);
    $chat->title = _lang('Deleted user');
}

if($chat) {

    $lastUpdated = $db->get_row('SELECT * FROM '.DB_PREFIX.'messages WHERE created_at IN (SELECT max(created_at) FROM '.DB_PREFIX.'messages) AND conversation_id = '.toDb($chat->conf_id));

    $chat->lastUpdate = time_ago( (isset($lastUpdated->created_at)) ? $lastUpdated->created_at : date() );
    $chat->lastMessage = $lastUpdated->text;
    $chat->unreadCount = 0;

} else
{
    $chat->lastUpdate = time_ago( date() );
    $chat->lastMessage = '';
    $chat->unreadCount = 0;
}    
echo( json_encode($chat, true) );

class Chat {

}

?>