<?php
// SEO Filters
function modify_title( $text ) {
    global $streamInfo;
    return strip_tags(_html(get_option('seo-stream-pre','').$streamInfo->name.get_option('seo-stream-post','')));
}

function modify_desc( $text ) {
    global $streamInfo;
    return _cut(strip_tags(_html($streamInfo->description)), 160);
}

add_filter( 'phpvibe_title', 'modify_title' );
add_filter( 'phpvibe_desc', 'modify_desc' );

$streamId = token_id();
$streamInfo = $db->get_row("SELECT id,name,cover,description,category,likes,on_air,views,moderator_id,tags,type FROM ".DB_PREFIX."conferences where id = ".$streamId." limit  0,1");

if(is_user() and $streamInfo->moderator_id == user_id() and $streamInfo->on_air != 1){
    $db->query('UPDATE '.DB_PREFIX.'conferences SET on_air = 1 WHERE id = '.toDb($streamInfo->id));
    $streamInfo->on_air = '1';
}

if(is_user()){
    $YNRtemplate->include('/stream.php',[
        'https://cdn.jsdelivr.net/npm/video.js@7.11.4/dist/video-js.min.css',
        '/tpl/main_n/styles/pages/conference.css',
        '/tpl/main_n/styles/components/smiles.css'
    ],[
        '/tpl/main_n/styles/js/modules/videojs.min.js',
        '/tpl/main_n/styles/js/pages/conference.js'
    ], $streamInfo);
    
} else {
    redirect('/login?backurl='.conference_url($streamInfo->id, $streamInfo->name));
}

?>