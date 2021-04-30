<?php
include_once('../../../load.php');
ini_set('display_errors', 0);

$confId = $_POST['id'];

$conference = $db->get_row('SELECT * FROM '.DB_PREFIX."conferences WHERE id = '".toDb($confId)."' LIMIT 0,1");

if($conference)
{
    $title = ($_POST['title'] !== '') ? $_POST['title'] : $conference->name;
    $description = ($_POST['description'] !== '') ? $_POST['description'] : $conference->description;
    $tags = ($_POST['tags'] !== '') ? $_POST['tags'] : $conference->tags;
    $thumb = $conference->cover ?? '';

    $startedAt = date('Y-m-d H:i:s', (isset($_POST['started_at']) && isset($_POST['time']) && $_POST['started_at'] && $_POST['time'])
        ? strtotime($_POST['started_at'].' '.$_POST['time'].':00')
        : $conference->started_at
    );

    $categ = isset($_POST['categ']) ? (int)$_POST['categ'] : $conference->category;

    if($categ !== 0)
    {
        $categExists = $cachedb->get_row('SELECT cat_id FROM '.DB_PREFIX.'channels WHERE cat_id = '.toDb($categ));
        
        if($categExists === null)
        {
            $categ = null;
        }
    }

    if(isset($_FILES['preview_image'])) {
        //var_dump($_FILES);
            $savePath	     = ABSPATH.'/storage/'.get_option('mediafolder').'/';								# The folder to save the image
            $saveName        = md5(time()).'-'.user_id();									# Without ext
            $allowedExtArray = explode(',',get_option('alimgext','jpg,png,gif,jpeg,bmp'));
            $imageQuality    = 100;
            
        if(!is_insecure_file($_FILES['preview_image']['name'])) {

            $ext = substr($_FILES['preview_image']['name'], strrpos($_FILES['preview_image']['name'], '.') + 1);
            
            if(in_array($ext,$allowedExtArray )) {
                if (move_uploaded_file($_FILES['preview_image']['tmp_name'], $savePath.$saveName.'.'.$ext)) {
                
                $thumb  = $savePath.$saveName.'.'.$ext;
                $thumb = str_replace(ABSPATH.'/' ,'',$thumb);
                $source = str_replace('storage/'.get_option('mediafolder') ,'localimage',$thumb);
                
                //$db->clean_cache();
                } else {
                    $error .= '<div class="msg-info mtop20 mright20">'._lang("Upload failed. Check your image!").'</div>';
                }
            }
        } else {
            $error .= '<div class="msg-info mtop20 mright20">'._lang("Insecure file detected. Upload canceled.").'</div>';
        }
    }

    //Do the sql insert
    $sql = "UPDATE ".DB_PREFIX."conferences SET name = '".$title."', cover = '".$thumb."', description = '".$description."', tags = '".toDb($tags)."', category = '$categ', started_at = '$startedAt' WHERE id = '$confId'";
    print_r($sql);
    $db->query($sql);
}
?>