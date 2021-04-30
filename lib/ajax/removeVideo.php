<?php

include_once('../../load.php');


$id = isset($_GET['id']) ? $_GET['id'] : 0;

if(is_user())
{
    $confExists = $cachedb->get_results("SELECT id from ".DB_PREFIX."videos where id = ".toDb($id)." AND user_id = ".toDb(user_id()));

    if($confExists)
    {
        $db->query("DELETE FROM ".DB_PREFIX."videos WHERE id = $id");

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