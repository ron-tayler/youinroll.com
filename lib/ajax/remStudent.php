<?php

include_once('../../load.php');


if(is_user())
{
    $currentUser = $cachedb->get_results("SELECT id,group_id from ".DB_PREFIX."users where id = ".toDb(user_id()));

    if(intval($currentUser[0]->group_id) === 1 || intval($currentUser[0]->group_id) === 11 || intval($currentUser[0]->group_id) === 12)
    {
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

                $db->query("DELETE FROM ".DB_PREFIX."conference_participants WHERE user_id = $id AND conference_id = $lessonId");

                $result = json_encode([
                    'success' => true
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