<?php
include_once('../../../load.php');

if(!is_user()) { redirect(site_url().'login/'); }

$token = md5(user_name().user_id().time());

$text  = '';
$allext = get_option('alext','flv,mp4,mp3,avi,mpeg');

if(get_option('ffa','0') == 1 ) {		
    $uphandler = site_url().'lib/upload_pl_ffmpeg.php?pvo='.$token;
} else {
    $uphandler = site_url().'lib/upload_pl.php?pvo='.$token;
}

$tok = $token;
$doit = $db->get_row("SELECT id from ".DB_PREFIX."videos where token = '".$tok."'");
if($doit) {
    if(get_option('ffa','0') <> 1 ) {
    if(!is_insecure_file($_FILES['play-img']['name'])) {
    //No ffmpeg
    $formInputName   = 'play-img';							
        $savePath	     = ABSPATH.'/storage/'.get_option('mediafolder').'/thumbs';								
        $saveName        = md5(time()).'-'.user_id();									
        $allowedExtArray = array('.jpg', '.png', '.gif');	
        $imageQuality    = 90;
    $uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
    if ($uploader->getIsSuccessful()) {
    $uploader -> resizeImage(get_option('thumb-width',205), get_option('thumb-height',115), 'crop');
    $uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
    $thumb  = $uploader->getTargetPath();
    $thumb = str_replace(ABSPATH.'/' ,'',$thumb);
    } else { $thumb  = 'storage/uploads/noimage.png'; 	}

    $sec = _tSec(_post('hours').":"._post('minutes').":"._post('seconds'));

    if(isset($_GET['for']) && $_GET['for'] === 'landing')
    {
        $db->query("UPDATE  ".DB_PREFIX."videos SET duration='".$sec."', thumb='".toDb($thumb )."' , privacy = '0', is_landing = '1', pub = '1', title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='0'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");

    } else
    {
        $db->query("UPDATE  ".DB_PREFIX."videos SET duration='".$sec."', thumb='".toDb($thumb )."' , privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."', title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
    }
    //$error .=$db->debug();
    } else { $thumb  = 'storage/uploads/noimage.png'; 	}
    } else {
    //Ffmpeg active
    if(isset($_GET['for']) && $_GET['for'] === 'landing')
    {
        $db->query("UPDATE  ".DB_PREFIX."videos SET privacy = '0', is_landing = '1', pub = '1',title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."' WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");

    } else
    {
        $db->query("UPDATE  ".DB_PREFIX."videos SET privacy = '".intval(_post('priv'))."', pub = '".intval(get_option('videos-initial'))."',title='".toDb(_post('title'))."', description='".toDb(_post('description') )."', category='".toDb(intval(_post('categ')))."', tags='".toDb(_post('tags') )."', nsfw='".intval(_post('nsfw') )."'  WHERE user_id= '".user_id()."' and id = '".intval($doit->id)."'");
    }

    }

    add_activity('4', $doit->id);
}

echo($uphandler);
?>
