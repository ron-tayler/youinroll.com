<?php

include_once('../../../load.php');

if (!is_user()){
    die();
}

$chats = [];

/* 
All conversations of user
*/
$lists = $db->get_results(
    "SELECT * FROM ".DB_PREFIX."conversations WHERE user_id = ".toDb( user_id() )
);

if($lists) {

    $conversations = [];

    foreach ($lists as $list) {
        $sql = 'SELECT * FROM '.DB_PREFIX."conversations WHERE conf_id = "
        .toDb($list->conf_id)
        ." AND user_id <> "
        .toDb( user_id() );
        
        array_push($conversations, 
        $db->get_row($sql));
    }

    foreach($conversations as $chat) {

        $firstUserInDialog = $cachedb->get_row("SELECT avatar,name FROM ".DB_PREFIX."users where id = '".$chat->user_id."' limit  0,1");	
        
        if($firstUserInDialog) {
            $chat->avatar = thumb_fix($firstUserInDialog->avatar, true, 40, 40);
            $chat->title = $firstUserInDialog->name;
        } else
        {
            $chat->avatar = thumb_fix('storage/uploads/noimage.png', true, 40, 40);
            $chat->title = _lang('Deleted user');
        }

        $lastUpdated = $db->get_row('SELECT * FROM '.DB_PREFIX.'messages WHERE created_at IN (SELECT max(created_at) FROM '.DB_PREFIX.'messages) AND conversation_id = '.toDb($chat->conf_id));

        $chat->lastUpdate = time_ago( (isset($lastUpdated->created_at)) ? $lastUpdated->created_at : date() );
        $chat->lastMessage = $lastUpdated->text;
        $chat->unreadCount = $db->get_row(
            'SELECT COUNT(*) as count FROM '.DB_PREFIX.'messages WHERE conversation_id = '.toDb($chat->conf_id).' AND readed = '.toDb(0).' AND user_id <> '.toDb(user_id())
        )->count;

        array_push($chats, $chat);
    }

}

    
echo( json_encode($chats, true) );
?>