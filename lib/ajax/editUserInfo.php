<?php
include_once('../../load.php');
ini_set('display_errors', 1);

$userModel = [];

if(is_user())
{
    if(isset($_POST['name'])) { user::Update('name',$_POST['name']); }
    if(isset($_POST['email'])) { user::Update('email',$_POST['email']); }
    if(isset($_POST['bio'])) { user::Update('bio',$_POST['bio']); }
    $db->clean_cache();
}
?>