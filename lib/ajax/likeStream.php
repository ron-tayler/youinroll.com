<?php include_once('../../load.php');
$id = intval($_REQUEST['stream_id']);
$type = intval($_REQUEST['type']);
$stype = $type + 2;
$tran = array();
$tran[1] = _lang('like');
$tran[2] = _lang('dislike');
$tran[3] = 'like';
$tran[4] = 'dislike';
if(is_user() && ($id > 0)) {

    $check = $db->get_row("SELECT count(*) as nr, type FROM ".DB_PREFIX."likes WHERE sid = '".$id ."' AND uid ='".user_id()."' order by id desc");
    if($check->nr > 0) {
        if(($check->type == 'like') && ($type == 1)) {
            //If already liked
            //Remove liked
            $db->query("delete from ".DB_PREFIX."likes where uid ='".user_id()."' and sid='".$id."' and type = 'like'");
            $db->query("UPDATE ".DB_PREFIX."conferences set likes = likes-1 where id = '".$id."'");	

            echo json_encode(array("added"=>"0","title"=>_lang('Hmm!'),"text"=>_lang('Like removed!')));
            unset($_SESSION['uslikes']);

            $db->query("DELETE FROM ".DB_PREFIX."playlist_data WHERE `playlist` = '".likes_playlist()."' and `stream_id` = '".$id."')");

        } else {
            echo json_encode([
                "added"=>"2",
                "title"=>_lang('Hmm!'),
                "text"=>_lang('Something is wrong here!'),
                "stream"=>$id
            ]);	
        }
    } else {
        //Not yet rated	
        $db->query("INSERT INTO ".DB_PREFIX."likes (`uid`, `sid`, `type`) VALUES ('".user_id()."', '".$id."', '".$tran[$stype]."')");
        $db->query("UPDATE ".DB_PREFIX."conferences set likes = likes+1 where id = '".$id."'");
        if($type == 1) {
            $db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `stream_id`) VALUES ('".likes_playlist()."', '".$id."')");
            echo json_encode([
                "added"=>"1",
                "title"=>_lang('Hooray!'),
                "text"=>_lang('You'). ' '.$tran[$type].' '._lang('this'),
                "stream"=>$id
            ]);

            $_SESSION['uslikes'] .= $id.",";
        } else {
            echo json_encode([
                "added"=>"3",
                "title"=>_lang('Oh!'),
                "text"=>_lang('You'). ' '.$tran[$type].' '._lang('this'),
                "stream"=>$id
            ]);
        }
        add_activity('1', $id, $tran[$type]);
    }
} else {
    echo json_encode([
        "added"=>"0",
        "title"=>_lang('Hmm..have a name stranger?!'),
        "text"=>_lang('Please login in order to like a video! It\'s fast and worth it'),
        "stream"=>$id
    ]);
}
?>