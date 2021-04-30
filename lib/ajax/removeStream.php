<?php

include_once('../../load.php');


$confId = isset($_GET['id']) ? $_GET['id'] : 0;

if(is_user())
{
    $confExists = $cachedb->get_results("SELECT id,moderator_id from ".DB_PREFIX."conferences where id = ".toDb($confId)." AND moderator_id = ".toDb(user_id()));

    if($confExists)
    {
        $db->query("DELETE FROM ".DB_PREFIX."conferences WHERE id = $confId");

        $result = json_encode([
            'result'=>'success'
        ]);
        
        echo $result;
        return $result;
    }
}

$result = json_encode([
    'result' => 'error'
]);

echo $result;
return $result;
?>