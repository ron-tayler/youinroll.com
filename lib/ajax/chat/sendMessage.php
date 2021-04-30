<?php
include_once('../../../load.php');
ini_set('display_errors', 0);

$result = ['result' => false];

if ( !is_user() || !isset($_POST['chatId']) || !isset($_POST['text']) )
{
    die();
}

$chatId = $_POST['chatId'];
$text = base64_encode($_POST['text']);
$participants = [];
$message = '';
$userId = (isset($_POST['userId']) || $_POST['userId'] !== 0) ? $_POST['userId'] : null;

/* 
Check if user have this conversation
*/
$chat = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() ).' AND conf_id = '.toDb($chatId)
);


if($chat === null) {    

    if($userId !== null)
    {

        /* Проверка на то, есть ли приватный чат с пользователем */
        $sql = "SELECT * FROM ".DB_PREFIX."conversations WHERE (user_id = ".toDb( user_id() ).' OR user_id = '.toDb( $userId ).') GROUP BY conf_id HAVING COUNT(*) > 1';
        $chats = $db->get_results(
            $sql
        );
        
        if($chats !== null)
        {
            $isEx = false;

            foreach ($chats as $chat)
            {
                $chatNotPrivate = $db->get_row('SELECT * FROM vibe_conversations WHERE conf_id = '.toDb($chat->conf_id).' HAVING COUNT(*) > 2');
                
                if($chatNotPrivate)
                {
                    $chat = new Chat();
        
                } else
                {
                    $isEx = true;
                    $normalchat = $db->get_row('SELECT * FROM vibe_conversations WHERE conf_id = '.toDb($chat->conf_id));
                }
            }

            if($isEx === true)
            {
                $chatId = $normalchat->conf_id;

            } else
            {
                $db->query(
                    "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id)+1 FROM ".DB_PREFIX."conversations conv),'".toDb(user_id())."')"
                );
            
                $db->query(
                    "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id) FROM ".DB_PREFIX."conversations conv),'".toDb($userId)."')"
                );
        
                $chat = $db->get_row("SELECT MAX(conf_id) as id FROM ".DB_PREFIX."conversations");
        
                $chatId = $chat->id;
            }

            
        } else
        {
            $db->query(
                "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id)+1 FROM ".DB_PREFIX."conversations conv),'".toDb(user_id())."')"
            );
        
            $db->query(
                "INSERT INTO ".DB_PREFIX."conversations(conf_id, user_id) VALUES ((SELECT MAX(conf_id) FROM ".DB_PREFIX."conversations conv),'".toDb($userId)."')"
            );
    
            $chat = $db->get_row("SELECT MAX(conf_id) as id FROM ".DB_PREFIX."conversations");
    
            $chatId = $chat->id;
        }

    } else
    {
        /* $chatId = $db->get_row(
            "SELECT * FROM ".DB_PREFIX."conversations WHERE (user_id = ".toDb( user_id() ).' OR user_id = '.toDb( $userId ).') GROUP BY conf_id HAVING COUNT(*) > 1'
        ); */
    }

}

$db->query(
    "INSERT INTO ".DB_PREFIX."messages(`text`, `user_id`, `conversation_id`) VALUES ('".toDb($text)."','".toDb(user_id())."','".toDb($chatId)."')"
);

$message = $db->get_row('SELECT * FROM '.DB_PREFIX.'messages
WHERE id = (
    SELECT MAX(id) FROM '.DB_PREFIX.'messages)');

if(isset($_FILES['chatfile']))
{
    $name = $_FILES['chatfile']['name'];
    $type = $_FILES['chatfile']['type'];
    $size = $_FILES['chatfile']['size'];
    $tmp = $_FILES['chatfile']['tmp_name'];

    $uploaddir = ABSPATH.'/storage/chat/';

    $uploadfile = $uploaddir . $name;

    move_uploaded_file($_FILES['chatfile']['tmp_name'], $uploadfile);

    $uploadfile = str_replace(ABSPATH, '', $uploaddir) . $name;


    $db->query(
        "INSERT INTO ".DB_PREFIX."chat_media(`name`, `path`, `size`, `chat`, `author`, `type`)
            VALUES ('$name', '$uploadfile', '$size', '$chatId', '".user_id()."', '$type')"
    );

    $file = $db->get_row('SELECT id,name,path,type FROM '.DB_PREFIX.'chat_media
    WHERE id = (
        SELECT MAX(id) FROM '.DB_PREFIX.'chat_media) AND author = '.toDb(user_id()));

    $db->query('UPDATE vibe_messages SET file_id = '.toDb($file->id).' WHERE id = '.toDb($message->id));
    
    $file->path = '/download.php?path='.$file->path;

    $message->file_id = $file->id;
    $message->file = $file;
}

$allParticipants = $db->get_results(
    "SELECT user_id,conf_id FROM ".DB_PREFIX."conversations WHERE conf_id = ".toDb($chatId)
);

foreach ($allParticipants as $participant)
{
    $user = $db->get_row("SELECT chatRoom from ".DB_PREFIX."users WHERE id = ".toDb($participant->user_id));

    if($user->chatRoom !== null)
    {
        array_push($participants, $user);
    }
}

$message->isMine = ( intval($message->user_id) === intval(user_id()))
    ? true
    : false;

$userOfMesage = $db->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

$message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
$message->author = $userOfMesage->name;
$message->type = 'message';
$message->text = base64_decode($message->text);
$message->chatId = $message->conversation_id;
$message->avatarLink = profile_url($message->user_id, $userOfMesage->name);

$result = ['message' => $message, 'users' => $participants];

echo( json_encode($result, true) );

class Chat {}
?>