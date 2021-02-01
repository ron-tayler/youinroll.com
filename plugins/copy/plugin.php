<?php
/**
 * Plugin Name: Easy copyright editor
 * Plugin URI: http://get.phpvibe.com/buy?id=15
 * Description: Removes the "Powered by" text in footer and replaces it with the field "license to" in PHPVibe 4.0+
 * Version: 2.0
 * Author: PHPVibe Crew
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */
function _copyrightremoval($text){
$text = _html(get_option('licto'));
return $text;
}
add_filter('tsitecopy', '_copyrightremoval');
?>