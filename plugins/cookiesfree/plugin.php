<?php
/**
 * Plugin Name: Cookies warning for GDPR (Free)
 * Plugin URI: https://phpvibes.com/
 * Description: Adds an customizable cookie message.
 * Version: 1.1
 * Author: Interact.Software
 * Author URI: http://www.phpvibe.com
 * License: Free
 */
 //Start fCookies
function fCookies_local_check($url){
if (strpos($url,'localfile') !== false) {
	return true;	
	} else {
	return false;
	}
}
function _renderFreeCookies($text){
//Start page output	
echo '<div class="isBoxed" id="fCookiescontent" style="width:240px; max-width:100%; position:fixed; right:20px; bottom:10px; z-index:9999; background:#fff">';
echo '<div class="mbot20">';
echo _html(get_option("fCookies-content"));
echo '</div>';
echo '<a href="'._html(get_option("fCookies-more")).'" class="full block btn btn-default mbot20">'._lang("Privacy policy").'</a>';
echo '<a href="javascript:dokillfrcookiess()" class="full block btn btn-primary mbot20">'._lang("Accept and close").'</a>';
echo '</div>';
echo fCookiesJS();
//End fCookies

}
function fCookieslink($txt = '') {
return $txt.'
<li><a href="'.admin_url('fCookies').'"><i class="icon-hand-peace-o"></i>FreeCookies</a></li>
';
}
function _adminFreeCookies() {
global $db,$all_options;	
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
  update_option($key, $value);
}
$db->clean_cache();
 echo '<div class="msg-info">FreeCookies is updated.</div>';	
 $all_options = get_all_options();
}
echo '<h3>FreeCookies Settings</h3>
<form id="validate" class="form-horizontal styled" action="'.admin_url("fCookies").'" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 	
<div class="control-group">
	<label class="control-label">Pop-up content</label>
	<div class="controls">
	<textarea id="googletracking" name="fCookies-content" class="auto col-md-12">'.get_option("fCookies-content").'</textarea>
	<span class="help-block" id="limit-text">Add your "cookies warning" content (html?).</span>
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label">Find our more link</label>
	<div class="controls">
	<input type="text" id="fCookies-more" name="fCookies-more" class="form-control" value="'.get_option("fCookies-more").'">
	<span class="help-block" id="limit-text">Paste a link to a page that examplains your privacy policy and cookies use.</span>
	</div>
	</div>	
	<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit">Update settings</button>	
</div>
</fieldset>	
</form>		
';	
}
function fCookiesJS($txt = ''){
$otxt = '
<script type="text/javascript">
function dokillfrcookiess() {
  $( "#fCookiescontent" ).load( "'.site_url().'api/dokillfrcookies", function() {
  $("#fCookiescontent").remove();
});
  }
</script>
';	
return $txt.$otxt;
}
function _killFreeCookies(){
echo "Thanks!";	
if(token() == "dokillfrcookies") {	
$_SESSION['dokillfrcookies'] = 1;
setcookie('dokillfrcookies', 1 , time() + 60 * 60 * 24 * 5,'/', cookiedomain());
}	
}
/*
if(is_admin()) {
	var_dump($_SESSION);
}
*/
if(!isset($_SESSION['dokillfrcookies']) && !is_user() && !isset($_COOKIE['dokillfrcookies'])) {
/* Render only if "Close" was not clicked already */	
//add_filter( 'filter_extrajs', 'fCookiesJS' );
add_action('phpvibe-api', '_killFreeCookies'); 
add_action('vibe_footer', '_renderFreeCookies',0);
}
add_filter('configuration_menu', 'fCookieslink');
add_action('adm-fCookies', '_adminFreeCookies');
?>