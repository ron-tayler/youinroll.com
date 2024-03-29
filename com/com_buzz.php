<?php  //Global query options
$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.title,".DB_PREFIX."videos.date,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
/* Define list to load */

$heading = _lang('Newest videos');	
$heading_plus = _lang('Most recently submited videos');

$trendsDate =  date('Y-m-d ', strtotime('-4 day')).'00:00:00';

$vq = "select ".$options.", ".DB_PREFIX."users.name as owner,".DB_PREFIX."users.avatar as avatar,".DB_PREFIX."users.group_id FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.pub > 0 and ".DB_PREFIX."videos.media < 2 AND ".DB_PREFIX."videos.date > '".toDb($trendsDate)."' ORDER BY ".DB_PREFIX.'videos.views DESC, '.DB_PREFIX.'videos.liked DESC '.this_limit();
$active = browse;


// Canonical url
if(_get('sort')) {
$canonical = list_url(token())."?sort="._get('sort'); 
} else {
$canonical = list_url(token()); 
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
include_once(TPL.'/activity.php');
if (!is_ajax_call()) { the_footer(); }
?>