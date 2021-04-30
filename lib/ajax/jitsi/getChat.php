<?php
include_once('../../../load.php');
ini_set('display_errors', 0);

if (!isset($_GET['streamId'])){
    die();
}

$streamId = intval($_GET['streamId']);

$stream = $db->get_row('SELECT id,moderator_id,chatRoom,views FROM '.DB_PREFIX.'conferences WHERE id = '.toDb($streamId).' LIMIT 0,1');

$chatRoom = '';
$chatRoomInfo = [];
$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);

if($stream)
{
    for ($i = 0; $i < 10; $i++) {
        $chatRoom .= $characters[rand(0, ($charactersLength - 1) )];
    }

    if(is_user()) {

        $participants = $db->get_results('SELECT id FROM '.DB_PREFIX.'conference_participants WHERE user_id = '.toDb(user_id()).' AND conference_id = '.toDb($stream->id));

        if($participants){
            foreach ($participants as $participant) {
                $db->query('DELETE FROM '.DB_PREFIX.'conference_participants WHERE user_id = '.toDb($participant->id).' AND conference_id = '.toDb($stream->id));
            }
        }

        $db->query('INSERT INTO '.DB_PREFIX."conference_participants (`user_id`, `conference_id`) VALUES ('".toDb(user_id())."', '".toDb($stream->id)."')");
    }

    
    if($stream->chatRoom === null)
    {
        $sql = 'UPDATE '.DB_PREFIX."conferences SET chatRoom = '".toDb($chatRoom)."'  WHERE id = ".toDb($stream->id);

        $res = $db->query($sql);
    
    } else
    {
        $chatRoom = $stream->chatRoom;
    }

}

$_SESSION['viewerId'] = $db->get_var('SELECT chatRoom FROM '.DB_PREFIX.'users WHERE id = '.toDb(user_id()));

$chatRoomInfo['room'] = $chatRoom;
$chatRoomInfo['viewerId'] = $_SESSION['viewerId'];

echo( json_encode($chatRoomInfo, true) );
?>