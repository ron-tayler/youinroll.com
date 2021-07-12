<?php
include_once('../../load.php');

global $db;
/** @var ezSQL_mysqli $db */
$token = '';

if(is_user()){

    $sql = 'SELECT token FROM vibe_users_tokens WHERE type=4 and user_id='.user_id();
    $token = $db->get_var($sql);
    $user_name = user_name();
    $user_id = user_id();

    if(empty($token)){
        do{
            $random = rand(0, PHP_INT_MAX);
            $data = "Access:{$user_id}:{$user_name}:$random";
            $token = hash('sha512', $data);
        }while( $db->get_var('SELECT count(*) FROM vibe_users_tokens WHERE token='.$token));
        $sql = "INSERT INTO `vibe_users_tokens`(`user_id`, `type`, `token`) VALUES ($user_id,4,'{$token}')";
        $db->query($sql);
    }

    echo json_encode(['token'=>$token], JSON_UNESCAPED_UNICODE);
}else{
    echo json_encode(['error'=>['code'=>3,'message'=>'Требуется авторизация']], JSON_UNESCAPED_UNICODE);
}

?>