<?php

include_once('../../load.php');


if(is_user())
{
    $currentUser = $cachedb->get_results("SELECT id,group_id from ".DB_PREFIX."users where id = ".toDb(user_id()));

    if( intval($currentUser[0]->group_id) === 1 || intval($currentUser[0]->group_id) === 11 || intval($currentUser[0]->group_id) === 12)
    {
        if(isset($_POST['email']) && isset($_POST['lesson']))
        {  
            $lessonId = toDb(intval($_POST['lesson']));

            $email = $_POST['email'];

            $row = "SELECT id,moderator_id FROM ".DB_PREFIX."conferences WHERE id = $lessonId AND moderator_id = ". toDb(user_id());

            $conference = $db->get_results($row);

            if($conference !== null)
            {                
                $newParticipant = $db->get_results("SELECT id,email FROM ".DB_PREFIX."users WHERE email LIKE '%$email%'");

                if($newParticipant === null)
                {
                    $result = json_encode([
                        'error' => 'user not found'
                    ]);

                    echo $result;
                    return $result;
                }

                $alreadyInserted = $db->get_results("SELECT * FROM ".DB_PREFIX."conference_participants WHERE user_id = ".toDb($newParticipant[0]->id)." AND conference_id = " . toDb($lessonId));
                if($alreadyInserted !== null)
                {
                    $result = json_encode([
                        'error' => 'user was added'
                    ]);

                    echo $result;
                    return $result;
                }

                $db->query("INSERT INTO ".DB_PREFIX."conference_participants(`conference_id`, `user_id`) VALUES ('".toDb($conference[0]->id)."', '".toDb($newParticipant[0]->id)."')");

                $result = json_encode([
                    'lesson' => $conference[0]->id,
                    'id' => $newParticipant[0]->id,
                    'email' => $newParticipant[0]->email
                ]);
                
                echo $result;
                return $result;
            }
        }

        if(isset($_POST['id']) && isset($_POST['lesson']))
        {
            $lessonId = toDb(intval($_POST['lesson']));

            $id = toDB(intval($_POST['id']));

            $conference = $db->get_results("SELECT id,moderator_id FROM ".DB_PREFIX."conferences WHERE id = $lessonId AND moderator_id = ". toDb(user_id()));
            
            if($conference !== null)
            {
                $newParticipant = $db->get_results("SELECT id,email FROM ".DB_PREFIX."users WHERE id = $id");

                if($newParticipant === null)
                {
                    $result = json_encode([
                        'error' => 'user not found'
                    ]);
                    
                    echo $result;
                    return $result;
                }

                $alreadyInserted = $db->get_results("SELECT * FROM ".DB_PREFIX."conference_participants WHERE user_id = ".toDb($newParticipant[0]->id)." AND conference_id = " . toDb($lessonId));
                if($alreadyInserted !== null)
                {
                    $result = json_encode([
                        'error' => 'user was added'
                    ]);
                    
                    echo $result;
                    return $result;
                }

                $db->query("INSERT INTO ".DB_PREFIX."conference_participants(`conference_id`, `user_id`) VALUES ('".$conference[0]->id."', '".$newParticipant[0]->id."')");

                $result = json_encode([
                    'lesson' => $conference[0]->id,
                    'id' => $newParticipant[0]->id,
                    'email' => $newParticipant[0]->email
                ]);
                
                echo $result;
                return $result;
            }
        }
    }
}

$result = json_encode([
    'error' => 'error'
]);

echo $result;
return $result;
?>