<?php  //Global query options


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
$YNRtemplate->include('/streamslist.php', [
    'tpl/main_n/styles/calendar.css'
],
[
	'/tpl/main_n/styles/js/pages/stream.js',
    '/tpl/main_n/styles/js/pages/streams.js'
]);
?>