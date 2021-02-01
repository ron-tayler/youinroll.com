<?php
/**
 * Plugin Name: Tune.pk
 * Plugin URI: http://get.phpvibe.com/
 * Description: Adds Tune.pk embed source to PHPVibe 4.0
 * Version: 1.0
 * Author: PHPVibe Crew
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */
function _tune($hosts = array()){
$hosts[] = 'tune.pk';
return $hosts;
}
function EmbedTune($txt =''){
global $vid;
if(isset($vid)) {
if($vid->VideoProvider($vid->theLink()) == 'tune.pk' ) {	
$link = $vid->theLink();
if(!nullval($link)) {	
$id = $vid->getVideoId("video/", '/');
if(!nullval($id)) {
$tembed = ' <iframe width="600" height="350" src="http://tune.pk/player/embed_player.php?vid='.$id.'&width=600&height=350&autoplay=no" frameborder="0" allowfullscreen scrolling="no"></iframe> ';
$txt .= $tembed;
}
/* End link check */
}
/* End provider check */
}
/* End isset(vid) */
}
/* End function */
return $txt;
}
add_filter('EmbedModify', 'EmbedTune');
add_filter('vibe-video-sources', '_tune');
?>