<?php include_once('../../load.php');
$user = intval($_POST['the_user']);
$type = intval($_POST['the_type']);

/*
if(is_user() && $user && $type) {
    if ($type < 2) {
        if(!has_activity('5', $user)) {
            $db->query("INSERT INTO ".DB_PREFIX."users_friends (`uid`, `fid`) VALUES ('".$user."', '".user_id()."')");
            //track subscription
            add_activity('5', $user);
            echo json_encode(array("title"=>_lang('Success!'),"type" => 'added',"text"=>_lang('You are now subscribed!')));
        } else {
            echo json_encode(array("title"=>_lang('Ohhh!'),"type" => 'already',"text"=>_lang('You are already subscribed!')));
        }
    }
    if ($type >= 2) {
        $db->query("DELETE FROM ".DB_PREFIX."users_friends where uid= '".$user."' and fid = '".user_id()."'");
        remove_activity('5', $user);
        echo json_encode(array("title"=>_lang('Subscription removed!'), "type" => 'removed',"text"=>_lang('You will no longer see this user\'s actions!')));
    }
}  */

if(is_user() && $user && $type) {

    if($type === 1) {
        
        if(premium_level(2, $user))
        {
            echo json_encode(array("title"=>_lang('Success!'),"type" => 'added', 'button' => _lang("Unsubscribe"),"text"=>_lang('You are now subscribed!')));
        
        } else
        {
            echo json_encode(array("title"=>_lang('Subscription removed!'),"type" => 'removed', 'button' => _lang("Subscribe"),"text"=>_lang('You are now unsubscribed!')));
        }

    } else
    {
        if(!has_activity('5', $user)) {
            $db->query("INSERT INTO ".DB_PREFIX."users_friends (`uid`, `fid`) VALUES ('".$user."', '".user_id()."')");
            //track subscription
            add_activity('5', $user);
            echo json_encode(array("title"=>_lang('Success!'),"type" => 'added', 'content'=>'', 'button' => 'icon-unfollow',"text"=>_lang('You are now subscribed!')));
        } else {
            $db->query("DELETE FROM ".DB_PREFIX."users_friends where uid= '".$user."' and fid = '".user_id()."'");
            remove_activity('5', $user);
            echo json_encode(array("title"=>_lang('Follow removed!'),'content'=>_lang('Follow'),'button' =>'icon-follow', "type" => 'removed',"text"=>_lang('You will no longer see this user\'s actions!')));
        }
    }
}

?>