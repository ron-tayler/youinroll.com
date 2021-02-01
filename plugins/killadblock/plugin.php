<?php
/**
 * Plugin Name: KillAdBlock
 * Plugin URI: http://get.phpvibe.com/
 * Description: Attempts to detect adblock and removes the video playback for AdBlock users. Based on F**kAdBlock javascript detector.
 * Version: 1
 * Author: Interact Software
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */

function killadblock($txt = '') {
$adbjs= "<script type=\"text/javascript\">
$(function() {
function adBlockNotDetected() { }
function adBlockDetected() {
	toastr.error( \"Please disable AdBlock to enjoy videos on this website.\", \""._lang('AdBlock is not nice!')."\", {timeOut: 50000, positionClass: 'toast-bottom-left'});
    $('.video-player').html('').html('<div class=\"msg-warning\">"._lang('We make financial efforts to maintain this multimedia website. Please show respect and disable AdBlock in order to load this video. Thank you!')."</div>');
	}

if(typeof fuckAdBlock !== 'undefined' || typeof FuckAdBlock !== 'undefined') {
	adBlockDetected();
} else {
	var importFAB = document.createElement('script');
	importFAB.onload = function() {
		// If all goes well, we configure FuckAdBlock
		fuckAdBlock.onDetected(adBlockDetected)
	};
	importFAB.onerror = function() {
		// If the script does not load (blocked, integrity error, ...)
		// Then a detection is triggered
		adBlockDetected(); 
	};
	importFAB.integrity = 'sha256-xjwKUY/NgkPjZZBOtOxRYtK20GaqTwUCf7WYCJ1z69w=';
	importFAB.crossOrigin = 'anonymous';
	importFAB.src = 'https://cdnjs.cloudflare.com/ajax/libs/fuckadblock/3.2.1/fuckadblock.min.js';
	document.head.appendChild(importFAB);
}
});
	</script>
";

return $txt.$adbjs;
}

add_filter('filter_extrajs', 'killadblock');

?>