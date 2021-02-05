<?php

include_once('../../../load.php');

if (!is_user() || !isset($_GET['email'])){
    die();
}

$email = $_GET['email'];

$info = $cachedb->get_row("SELECT id,avatar,name FROM ".DB_PREFIX."users where email = '".$email."' limit  0,1");

$info->avatar = thumb_fix($info->avatar, true, 40, 40);

$info->avatarLink = profile_url($info->id, $info->name);

echo( json_encode($info, true) );
?>