<?php
/**
 * Plugin Name: Izlesene.com source
 * Plugin URI: http://get.phpvibe.com/
 * Description: Adds Izlesene embed source to PHPVibe 4.0
 * Version: 1.0
 * Author: PHPVibe Crew
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */
function _Izlesene($hosts = array()){
$hosts[] = 'izlesene';
return $hosts;
}
function EmbedIzlesene($txt =''){
global $vid;
if(isset($vid)) {
if($vid->VideoProvider($vid->theLink()) == 'izlesene' ) {	
$link = $vid->theLink();
if(!nullval($link)) {	
$id = $vid->getLastNr($vid->theLink());
if(!nullval($id)) {
$tembed = ' <iframe src="//www.izlesene.com/embedplayer/'.$id.'/?showrel=0&amp;loop=0&amp;autoplay=1&amp;autohide=1" width="640" height="360" frameborder="0"></iframe> ';
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
add_filter('EmbedModify', 'EmbedIzlesene');
add_filter('vibe-video-sources', '_Izlesene');
?>