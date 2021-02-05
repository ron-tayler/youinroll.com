<?php  //Global query options
$options = DB_PREFIX."conferences.id,".DB_PREFIX."conferences.name,".DB_PREFIX."conferences.description,".DB_PREFIX."conferences.created_at,".DB_PREFIX."conferences.started_at,".DB_PREFIX."conferences.moderator_id,".DB_PREFIX."conferences.cover,".DB_PREFIX."conferences.likes,".DB_PREFIX."conferences.views,".DB_PREFIX."conferences.category,".DB_PREFIX."conferences.on_air";
/* Define list to load */
$interval = '';
if(_get('sort'))
{
switch(_get('sort')){
case "w":
$interval = "AND WEEK( DATE ) = WEEK( CURDATE( ) ) ";
break;
case "m":
$interval = "AND MONTH(date) = MONTH(CURDATE( ))";
break;
case "y":
$interval = "AND YEAR( DATE ) = YEAR( CURDATE( ) ) ";
break;
}
}

switch ($_GET['sk']) {
	case lessons:
		$heading = _lang('Мои уроки');	
        $heading_plus = _lang('Мои уроки');        
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id "." WHERE ".DB_PREFIX."conferences.moderator_id = ".user_id()."  ORDER BY ".DB_PREFIX."conferences.id DESC ".this_limit();
        $active = lessons;
		break;

	case raspisanie:
		$heading = ('Расписание');	
		$heading_plus = _lang('Расписание уроков');
		$sortop = true;
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id ".DB_PREFIX."conferences.likes > 0 ORDER BY ".DB_PREFIX."conferences.likes DESC ".this_limit();
		$active = raspisanie;
		break;
	
	default:
		$heading = _lang('My Streams');	
		$heading_plus = _lang('My Streams');        
		$vq = "select ".$options.", ".DB_PREFIX."users.avatar as owner_avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id FROM ".DB_PREFIX."conferences LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id "." WHERE ".DB_PREFIX."conferences.moderator_id = ".user_id()." AND ".DB_PREFIX."conferences.type = 'stream' ORDER BY ".DB_PREFIX."conferences.id DESC ".this_limit();
		$active = my;
		break;
}

// Canonical url
if(_get('sort')) {
$canonical = lessonslist_url(token())."?sort="._get('sort'); 
} else {
$canonical = lessonslist_url(token()); 
}

// SEO Filters
function modify_title( $text ) {
global $heading;
    return strip_tags(stripslashes($heading));
}
function modify_desc( $text ) {
global $heading_plus;
    return _cut(strip_tags(stripslashes($heading_plus)), 160);
}
add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );
//Time for design
if (!is_ajax_call()) {  the_header(); the_sidebar(); }
include_once(TPL.'/streamslist.php');
if (!is_ajax_call()) { the_footer(); }
?>