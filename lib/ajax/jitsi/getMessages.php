<?php
ini_set('display_errors', 0);
include_once('../../../load.php');

if ( !isset($_GET['streamId']) )
{
    die();
}

$messages = [];

$streamId = $_GET['streamId'];

/* 
Check if user have this conversation
*/
$lists = $db->get_results(
    "SELECT * FROM ".DB_PREFIX."conference_messages WHERE stream_id = ".toDb($streamId)
);

if($lists) {
    
    foreach($lists as $message) {

        $message->isMine = ((int)$message->user_id === user_id())
            ? true
            : false;

        $userOfMesage = $cachedb->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

        $message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
        $message->author = $userOfMesage->name;
        $message->avatarLink = profile_url($message->user_id, $userOfMesage->name);
        $message->text = base64_decode($message->text);

        if($message->file_id !== null)
        {
            $message->file = $db->get_row('SELECT * FROM vibe_chat_media WHERE id = '.toDb($message->file_id).' AND chat = 0');

            $message->file->path = '/download.php?path='.$message->file->path;
        }

        array_push($messages, $message);
    }
}
    
echo( json_encode($messages, true) );
?>