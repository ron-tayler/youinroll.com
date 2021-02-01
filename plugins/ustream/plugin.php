<?php
/**
 * Plugin Name: Ustream.tv 
 * Plugin URI: http://get.phpvibe.com/
 * Description: Adds Ustream.tv embed source to PHPVibe 4.0
 * Version: 1.0
 * Author: PHPVibe Crew
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */
function _ustreamtv($hosts = array()){
$hosts[] = 'ustream.tv';
return $hosts;
}
function Embedustreamtv($txt = ''){
global $vid;
if(isset($vid)) {
if($vid->VideoProvider($vid->theLink()) == 'ustream.tv' ) {	
$link = $vid->theLink();
if(!nullval($link)) {	
$txt .= ' <iframe src="'.str_replace("recorded","embed/recorded",$vid->theLink()).'?v=3&amp;wmode=direct" tyle="border: 0px none transparent;" scrolling="no" width="640" height="360" frameborder="0"></iframe> ';

/* End link check */
}
/* End provider check */
}
/* End isset(vid) */
}
/* End function */
return $txt;
}
add_filter('EmbedModify', 'Embedustreamtv');
add_filter('vibe-video-sources', '_ustreamtv');
?>