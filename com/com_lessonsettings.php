<?php if(!is_user()) { redirect(site_url().'login/'); }
$error='';
// SEO Filters
function modify_title( $text ) {
 return strip_tags(stripslashes($text.' '._lang('share')));
}

if(isset($_POST['vtoken'])) {

}
function modify_content( $text ) {
	global $error, $token, $db;
	if(not_empty($error)) {
		return '<div style="margin:30px 0 50px">'.$error.'</div>';	
	}
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
include_once(TPL.'/lessonsettings.php');
the_footer();
?>
