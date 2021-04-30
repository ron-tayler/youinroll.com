<?php  error_reporting(E_ALL);
require_once('../../../load.php');

$name = $_POST['name'];
$type = $_POST['type'];
$access = $_POST['access'];
$tags = $_POST['tags'];
$category = intval($_POST['category']);
$description = $_POST['description'];

$startedAt = $_POST['startedAt'];

$cover = null;

if($category !== 0)
{
    $categExists = $cachedb->get_row('SELECT cat_id FROM '.DB_PREFIX.'channels WHERE cat_id = '.toDb($category));
    
    if($categExists === null)
    {
        $category = null;
    }
}

if(isset($_FILES['cover'])) {
    //var_dump($_FILES);
        $savePath	     = ABSPATH.'/storage/'.get_option('mediafolder').'/';								# The folder to save the image
        $saveName        = md5(time()).'-'.user_id();									# Without ext
        $allowedExtArray = explode(',',get_option('alimgext','jpg,png,gif,jpeg,bmp'));
        $imageQuality    = 100;
        
    if(!is_insecure_file($_FILES['cover']['name'])) {

        $ext = substr($_FILES['cover']['name'], strrpos($_FILES['cover']['name'], '.') + 1);
        
        if(in_array($ext,$allowedExtArray )) {
            if (move_uploaded_file($_FILES['cover']['tmp_name'], $savePath.$saveName.'.'.$ext)) {
            
            $thumb  = $savePath.$saveName.'.'.$ext;
            $thumb = str_replace(ABSPATH.'/' ,'',$thumb);
            $source = str_replace('storage/'.get_option('mediafolder') ,'localimage',$thumb);
            
            $cover = $thumb;
            //$db->clean_cache();
            } else {
                $error .= _lang("Upload failed. Check your image!");
                $result = 'error';
            }
        }
    } else {
        $error .= _lang("Insecure file detected. Upload canceled.");
        $result = 'error';
    }
}

switch ($type) {
    case 'lesson':
        $hasAccess = $cachedb->get_row('SELECT id FROM '.DB_PREFIX."users_groups WHERE is_bussines = '1' AND id = ".toDb(user_group()));

        if($hasAccess)
        {
            //Do the sql insert
            $db->query("INSERT INTO ".DB_PREFIX."conferences (`name`, `cover`, `description`, `tags`, `category`, `moderator_id`, `type`, `access`, `started_at`, `chatRoom`) VALUES 
            ('".$name."', '".$cover."', '".$description."', '".toDb($tags)."', '".$category."', '".user_id()."','".$type."', '".$access."', '".$startedAt."', '".generateRandomString()."')");	
            
            $doit = $db->get_row("SELECT id,name from ".DB_PREFIX."conferences where moderator_id = '".user_id()."' order by id DESC limit 0,1");

            add_activity('10', $doit->id, 'lesson');

            $error .= stream_url($doit->id, $doit->name);
            $result = 'success';
        }

        break;
    
    default:

        //Do the sql insert
        $db->query("INSERT INTO ".DB_PREFIX."conferences (`name`, `cover`, `description`, `tags`, `category`, `moderator_id`, `type`, `access`, `started_at`, `chatRoom`) VALUES 
        ('".$name."', '".$cover."', '".$description."', '".toDb($tags)."', '".$category."', '".user_id()."','".$type."', '".$access."', '".$startedAt."', '".generateRandomString()."')");	
        
        $doit = $db->get_row("SELECT id,name from ".DB_PREFIX."conferences where moderator_id = '".user_id()."' order by id DESC limit 0,1");

        add_activity('10', $doit->id, 'stream');

        $error .= stream_url($doit->id, $doit->name);
        $result = 'success';

        break;
}

echo(json_encode([
    'type' => $result,
    'data' => $error
]));
?>