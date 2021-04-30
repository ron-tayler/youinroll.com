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

        $chatUsers = $db->get_results("
        SELECT
            USER.name as name,
            USER.avatar as avatar
        FROM ".
        DB_PREFIX."conversations AS conversation
        INNER JOIN vibe_users AS USER
        ON
            conversation.user_id = USER.id AND conversation.conf_id = '".$chat->conf_id."'
            AND user_id <> '".toDb(user_id())."'
        ORDER BY
            conversation.id, conversation.user_id
        DESC");

        $firstUserInDialog = $chatUsers[0];

        $chat->avatar = thumb_fix($firstUserInDialog->avatar, true, 40, 40);
        $chat->title = $firstUserInDialog->name;

        if( count($chatUsers) > 1 )
        {
            $chat->title = '';

            foreach ($chatUsers as $chatUser) {
                $chat->title .= $chatUser->name.', ';
            }
        }
        
        $chat->profileUrl = profile_url($firstUserInDialog->id, $firstUserInDialog->name);

        $lastUpdated = $db->get_row('SELECT * FROM '.DB_PREFIX.'messages WHERE conversation_id = '.toDb($chat->conf_id).' ORDER BY created_at DESC LIMIT 0,1');
        
        $lastUpdateDay = date_diff(new DateTime('NOW'), new DateTime($lastUpdated->created_at))->format('%d');

        if(intval($lastUpdateDay) < 1)
        {
            $lastUpdateDay = date("l, H:i", strtotime($lastUpdated->created_at));

            $search  = [
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
                'Sunday'
            ];
            $replace = [
                'Понедельник',
                'Вторник',
                'Среда',
                'Четверг',
                'Пятница',
                'Суббота',
                'Воскресенье'
            ];

            $lastUpdateDay = str_replace($search, $replace, $lastUpdateDay);
        }

        $chat->lastUpdate = $lastUpdateDay;
        $chat->lastMessage = base64_decode($lastUpdated->text);
        $chat->created_at = $lastUpdated->created_at;
        $chat->unreadCount = $db->get_row(
            'SELECT COUNT(*) as count FROM '.DB_PREFIX.'messages WHERE conversation_id = '.toDb($chat->conf_id).' AND readed = '.toDb(0).' AND user_id <> '.toDb(user_id())
        )->count;

        array_push($chats, $chat);
    }

    usort($chats, function($a, $b) {
        $ad = new DateTime($a->created_at);
        $bd = new DateTime($b->created_at);
      
        if ($ad == $bd) {
          return 0;
        }
      
        return $ad < $bd ? -1 : 1;
    });

}

    
echo( json_encode($chats, true) );
?>