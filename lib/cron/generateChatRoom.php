<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(getcwd().'/cron_load.php');

$characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
$charactersLength = strlen($characters);

$allUsers = $db->get_results("SELECT id,chatRoom FROM vibe_users WHERE chatRoom = 'MD5(RANDSTRING(8))'");
foreach ($allUsers as $user) {

    $chatRoom = '';

    for ($i = 0; $i < 10; $i++) {
        $chatRoom .= $characters[rand(0, $charactersLength - 1)];
    }

    $db->query('UPDATE '.DB_PREFIX."users SET chatRoom = '".$chatRoom."' WHERE id = '".$user->id."'");
}
?>