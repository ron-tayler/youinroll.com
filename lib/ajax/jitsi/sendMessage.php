<?php
ini_set('display_errors', 0);
include_once('../../../load.php');

$result = ['result' => false];

if ( !is_user() || !isset($_POST['streamId']) || !isset($_POST['text']) )
{
    die();
}

$streamId = $_POST['streamId'];
$text = base64_encode($_POST['text']);

/* 
Check if stream exists
*/
$stream = $db->get_row(
    "SELECT * FROM ".DB_PREFIX."conferences WHERE id = ".toDb( $streamId )
);

if($stream !== null)
{
    $db->query(
        "INSERT INTO ".DB_PREFIX."conference_messages(`text`, `user_id`, `stream_id`) VALUES ('".toDb($text)."','".toDb(user_id())."','".toDb($streamId)."')"
    );
}

$message = $db->get_row('SELECT * FROM '.DB_PREFIX.'conference_messages
WHERE id = (
    SELECT MAX(id) FROM '.DB_PREFIX.'conference_messages)');

   

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
        "INSERT INTO ".DB_PREFIX."chat_media(`name`, `path`, `size`, `stream`, `author`, `type`)
            VALUES ('$name', '$uploadfile', '$size', '$streamId', '".user_id()."', '$type')"
    );

    $file = $db->get_row('SELECT id,name,path,type FROM '.DB_PREFIX.'chat_media
    WHERE id = (
        SELECT MAX(id) FROM '.DB_PREFIX.'chat_media) AND author = '.toDb(user_id()));

    $db->query('UPDATE vibe_conference_messages SET file_id = '.toDb($file->id).' WHERE id = '.toDb($message->id));
    
    $file->path = '/download.php?path='.$file->path;

    $message->file_id = $file->id;
    $message->file = $file;
}

$message->isMine = ( intval($message->user_id) === intval(user_id()))
    ? true
    : false;

$userOfMesage = $db->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$message->user_id."' limit  0,1");

$message->avatar = thumb_fix($userOfMesage->avatar, true, 40, 40);
$message->author = $userOfMesage->name;
$message->type = 'message';
$message->avatarLink = profile_url($message->user_id, $userOfMesage->name);

$message->text = base64_decode($message->text);

$roomName = $stream->chatRoom;

$db->query('DELETE n1 FROM vibe_conference_participants n1, vibe_conference_participants n2 WHERE n1.id > n2.id AND n1.user_id = n2.user_id AND n1.conference_id = n2.conference_id');

$queues = $db->get_results('SELECT
    users.chatRoom
    FROM
    vibe_conference_participants AS participants
    INNER JOIN vibe_users AS users
    ON
        participants.user_id = users.id
    WHERE
        participants.conference_id = '.toDb($streamId));

$result = ['message' => $message, 'views' => $stream->views, 'chatRoom' => $roomName, 'queues' => $queues];
$result['message']->chatId = $result['message']->stream_id;// TODO Временно

echo(json_encode($result, true));
?>