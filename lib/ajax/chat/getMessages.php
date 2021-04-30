<?php

include_once('../../../load.php');

if ( !is_user() || !isset($_GET['chatId']) )
{
    die();
}

$messages = [
    'messages' => [],
    'chatInfo' => []
];

$chatId = $_GET['chatId'];
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;

/* 
Check if user have this conversation
*/
$lists = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' AND conf_id = '.toDb($chatId)
);

if($lists) {

    $chatInfo = $db->get_results("SELECT
        conversation.*,
        USER.name as author,
        USER.avatar as authorImage
    FROM ".
    DB_PREFIX."conversations AS conversation
    INNER JOIN vibe_users AS USER
    ON
        conversation.user_id = USER.id AND conversation.conf_id = '".$chatId."'
    ORDER BY
        conversation.id
    ASC");

    $itemsCount = 25;

    $offset = ($page - 1) * $itemsCount;

    $messagesList = $db->get_results(
        "SELECT * FROM ".DB_PREFIX."messages WHERE conversation_id = ".toDb($chatId)." ORDER BY id DESC  LIMIT $offset, $itemsCount"
    );

    if($page === 1)
    {
        $messagesList = array_reverse($messagesList);   //
    }
    
    foreach($messagesList as $message) {

        $message->isMine = ((int)$message->user_id === user_id())
            ? true
            : false;

        $db->query(
            'UPDATE '.DB_PREFIX.'messages SET readed = '.toDb(true).' WHERE user_id <> '.toDb(user_id()).' AND id = '.toDb($message->id)
        );

        if(!$message->isMine){
            $message->readed = true;
        }

        $userOfMesage = $cachedb->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

        $message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
        $message->author = $userOfMesage->name;
        $message->avatarLink = profile_url($message->user_id, $userOfMesage->name);

        $message->text = base64_decode($message->text, true);

        if($message->file_id !== null)
        {
            $message->file = $db->get_row('SELECT * FROM vibe_chat_media WHERE id = '.toDb($message->file_id));

            $message->file->path = '/download.php?path='.$message->file->path;
        }

        array_push($messages['messages'], $message);
    }

    /* usort($messages['messages'], function($a, $b) {
        $ad = new DateTime($a->created_at);
        $bd = new DateTime($b->created_at);

        if($a->file_id !== null) 
        {
            if ($ad == $bd) {
                return 0;
            }
        
            return ($ad < $bd) ? -1 : 1;
        }
    }); */

    array_push($messages['chatInfo'], $chatInfo);

}
    
echo( json_encode($messages, true) );
?>