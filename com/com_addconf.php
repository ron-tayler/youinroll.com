<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';

$confType = (isset($_GET['type']) && ($_GET['type'] === 'stream' || $_GET['type'] === 'lesson'))
	? $_GET['type']
	: 'stream';


// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}

function modify_content( $text ) {
	global $error, $token, $db;
	if(not_empty($error)) {
		return '<div style="margin:30px 0 50px">'.$error.'</div>';	
	}
}
try {
	//code...


if(isset($_POST['title'])) {

	$title = $_POST['title'];
	$description = $_POST['description'] ?? null;
	$tags = $_POST['tags'];
	$thumb = '';

	$startedAt = date('Y-m-d H:i:s', (isset($_POST['started_at']) && isset($_POST['time']))
		? strtotime($_POST['started_at'].' '.$_POST['time'].':00')
		: time()
	);

	$categ = isset($_POST['categ']) ? (int)$_POST['categ'] : 0;

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

	switch ($confType) {
		case 'lesson':
			$hasAccess = $cachedb->get_row('SELECT id FROM '.DB_PREFIX."users_groups WHERE is_bussines = '1' AND id = ".toDb(user_group()));

			if($hasAccess)
			{
				//Do the sql insert
				$db->query("INSERT INTO ".DB_PREFIX."conferences (`name`, `cover`, `description`, `tags`, `category`, `moderator_id`, `type`, `started_at`) VALUES 
				('".$title."', '".$thumb."', '".$description."', '".toDb(_post('tags'))."', '".$categ."', '".user_id()."','".$confType."', '".$startedAt."')");	
				
				$doit = $db->get_row("SELECT id,name from ".DB_PREFIX."conferences where moderator_id = '".user_id()."' order by id DESC limit 0,1");

				add_activity('10', $doit->id, 'lesson');

				$error .= '<div class="msg-info mleft20 mtop20 mright20">'.$title.' '._lang("created successfully.").' <a href="'.stream_url($doit->id, $doit->name).'" class="btn btn-primary btn-xs pull-right" style="color:#fff">'._lang("Start streaming").'</a></div>';
				
			}

			break;
		
		default:

			//Do the sql insert
			$db->query("INSERT INTO ".DB_PREFIX."conferences (`name`, `cover`, `description`, `tags`, `category`, `moderator_id`, `type`, `started_at`) VALUES 
			('".$title."', '".$thumb."', '".$description."', '".toDb(_post('tags'))."', '".$categ."', '".user_id()."','".$confType."', '".$startedAt."')");	
			
			$doit = $db->get_row("SELECT id,name from ".DB_PREFIX."conferences where moderator_id = '".user_id()."' order by id DESC limit 0,1");

			add_activity('10', $doit->id, 'lesson');

			$error .= '<div class="msg-info mleft20 mtop20 mright20">'.$title.' '._lang("created successfully.").' <a href="'.stream_url($doit->id, $doit->name).'" class="btn btn-primary btn-xs pull-right" style="color:#fff">'._lang("Start streaming").'</a></div>';

			break;
	}

	
}
} catch (\Throwable $th) {
	print_r($th);
	die();
}

add_filter( 'phpvibe_title', 'modify_title' );

if((get_option('uploadrule') == 1) ||  is_moderator()) {	

	add_filter( 'the_defaults', 'modify_content' );

} else {

	function udisabled() {
		return _lang("This uploading section is disabled");
	}

	add_filter( 'the_defaults', 'udisabled'  );
}

//Time for design
the_header();
include_once(TPL.'/addconf.php');
the_footer();
?>
