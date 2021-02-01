<?php
/**
 * Plugin Name: Easy homepage slider
 * Plugin URI: https://phpvibes.com/
 * Description: Adds an playlist designed-alike slider or videos (own iframe).
 * Version: 4
 * Author: Interact.Software
 * Author URI: http://www.phpvibe.com
 * License: Commercial
 */
 //Start slider
function slider_local_check($url){
if (strpos($url,'localfile') !== false) {
	return true;	
	} else {
	return false;
	}
}
function _renderSlider($text){
global $db;

$options = DB_PREFIX."videos.id as vid,".DB_PREFIX."videos.title,".DB_PREFIX."videos.source,".DB_PREFIX."videos.user_id as owner, ".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
$query = get_option("slider-queries","recent") ;
$limit = get_option("slider-nr","25");
$usr = '';
if(not_empty(get_option("slider-user")) && (intval(get_option("slider-user")) > 0)) {
$usr = 'and user_id in ('.intval(get_option("slider-user")).')';	
}

if($query == "most_viewed"):
$vq = "select ".$options.", ".DB_PREFIX."users.name  FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views > 0 and pub > 0 ".$usr." and ".DB_PREFIX."videos.ispremium < 1 and media < 2 ORDER BY ".DB_PREFIX."videos.views DESC ".this_offset($limit);
elseif($query == "top_rated"):
$vq = "select ".$options.", ".DB_PREFIX."users.name  FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.liked > 0 and pub > 0 ".$usr." and ".DB_PREFIX."videos.ispremium < 1 and media < 2 ORDER BY ".DB_PREFIX."videos.liked DESC ".this_offset($limit);
elseif($query == "featured"):
$vq = "select ".$options.", ".DB_PREFIX."users.name  FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.featured = '1' ".$usr." and pub > 0  and ".DB_PREFIX."videos.ispremium < 1 and media < 2 ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset($limit);
else:
$vq = "select ".$options.", ".DB_PREFIX."users.name  FROM ".DB_PREFIX."videos LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."videos.views >= 0 ".$usr." and pub > 0 and ".DB_PREFIX."videos.ispremium < 1 and media < 2 ORDER BY ".DB_PREFIX."videos.id DESC ".this_offset($limit);
endif;
$result = $db->get_results($vq);
if ($result) {
echo '<div id="video-slider" class="row-fluid block player-in-list ">
<div id="video-content" class="col-md-8 col-xs-12">
<div class="video-player pull-left" style="min-height:490px">
<iframe id="vemb" class="vibe_embed" width="100%" height="480"  seamless="seamless" style="overflow: hidden;" src="" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true"></iframe>
</div>
</div>
 <div id="ListRelated" class="video-under-right nomargin pull-right col-md-4 col-xs-12">
<div class="video-player-sidebar">
<div class="slider-items">
<ul>
';	
	$i = 1;
	foreach ($result as $related) {
		if($i < 2) { 
		$tid = "playingNow";
		} else {
		$tid = $related->vid;
		}
		$nowP = '';
		if($i < 2) { $nowP = "playingNow";}
$duration = ($related->duration > 0) ? video_time($related->duration) : '<i class="icon-picture"></i>';		
$local = (slider_local_check($related->source)) ? "slideLocal" : "";
echo '
					<li id="'.$tid.'" data-id="'.$related->vid.'" class="item-post '.$nowP.' '.$local.'">
				<div class="inner">
	<div class="thumb">
		<a class="clip-link" data-id="'.$related->vid.'" title="'._html($related->title).'" href="'.video_url($related->vid , $related->title).'">
			<span class="clip">
				<img src="'.thumb_fix($related->thumb).'" alt="'._html($related->title).'" /><span class="vertical-align"></span>
			</span>
		<span class="timer">'.$duration.'</span>					
			<span class="overlay"></span>
		</a>
	</div>			
					<div class="data">
						<span class="title"><a href="'.video_url($related->vid , $related->title).'" rel="bookmark" title="'._html($related->title).'">'._cut(_html($related->title),124 ).'</a></span>
						<span class="usermeta">
							'._lang('by').' <a href="'.profile_url($related->owner, $related->name).'"> '._html($related->name).' </a>
						</span>
					</div>
				</div>
				</li>
	';
	$i++;
	}
echo '</ul></div></div></div>
</div>
<div id="LH" class="row nomargin">
        <div class="playlistvibe">
            <div class="cute">
                <h1 class="tt">
                   '._lang(get_option('slider-header', 'Hello!')).'
                    <span>
                      '._lang(get_option('slider-header2', 'Welcome!')).'
                    </span>
                </h1>
                <div class="cute-line"></div>
				            <div class="next-an list-next"></div>

            </div>
			</div>
			</div>
<script>
$(document).ready(function(){
	
	if ($(window).width() < 990) {
	$(".next-an").html(\'<a href="javascript:void(0)" class="vlist-pull"><i class="material-icons mpullup"></i></a>\');

	$(".vlist-pull").click(function() {
	   $("#ListRelated > .video-player-sidebar").toggleClass("hide");
       $(".vlist-pull > i").toggleClass("mpullup").toggleClass("mpulldown");
	   }); 		
   var vh = 280;
	} else {
	 var vh = $("#video-content").height() - 2;	
	}
$(\'.slider-items\').slimScroll({
            height: vh,
			start: $(\'li#playingNow\'),
			size: 5,
		    railVisible: true,
            railOpacity: \'0.9\',
            color: \'#c6c6c6\',
            railColor: \'#f5f5f5\',
		    wheelStep : 22
        });

	function changeIframe(vid,local) {
	$(".vibe_embed").attr("src", "'.site_url().embedcode.'/"+ vid +"/");
	var ew = $(".video-player").width();
	if(local) {
    var eh = Math.round((ew/16)*9) + 25;
	} else {
	var eh = Math.round((ew/16)*9);	
	}
    $(".vibe_embed").height(eh); 
    $(".slider-items").height(eh);	
	}	
	var firstSlide = $(".slider-items li").first().attr("data-id");
	if($(".slider-items li").first().hasClass( "slideLocal" )) {
	changeIframe(firstSlide,1);	
	} else {
	changeIframe(firstSlide);	
	}	
	if ($(window).width() < 990) {
	var firstID = $(".playingNow").attr("data-id");
	if(firstID){		
    setTimeout(function(){
     changeIframe(firstID);
    }, 2000);		
	}
	}
	$(".item-post a").click(function (e) {
		  e.preventDefault();		  
		  var idSlide = $(this).parents("li:first").attr("data-id");
		  var idTitle = $(this).attr("title");
		  $(".tt").html(idTitle);
		  $(".item-post").removeClass("playingNow");
		  $(this).closest("li").addClass("playingNow");
		  if($(this).parents("li:first").hasClass( "slideLocal" )) {
          changeIframe(idSlide,1);		
		  } else {
			changeIframe(idSlide);  
		  }
		});
 });	
</script>
<br style="clear:both">';
	}
//End slider
}
function sliderlink($txt = '') {
return $txt.'
<li><a href="'.admin_url('slider').'"><i class="icon-spinner"></i>Slider</a></li>
';
}
function _adminSlider() {
global $db,$all_options;	
if(isset($_POST['update_options_now'])){
foreach($_POST as $key=>$value)
{
  update_option($key, $value);
}
$db->clean_cache();
 echo '<div class="msg-info">Slider is updated.</div>';	
 $all_options = get_all_options();
}
echo '<h3>Slider Settings</h3>
<form id="validate" class="form-horizontal styled" action="'.admin_url("slider").'" enctype="multipart/form-data" method="post">
<fieldset>
<input type="hidden" name="update_options_now" class="hide" value="1" /> 	
<div class="control-group">
	<label class="control-label">Accent title</label>
	<div class="controls">
	<input type="text" id="slider-header" name="slider-header" class="form-control" value="'.get_option("slider-header","Hello!").'">
	<span class="help-block" id="limit-text">Slider header <em>(translatable trough languages)</em>.</span>
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label">Part 2 of title</label>
	<div class="controls">
	<input type="text" id="slider-header2" name="slider-header2" class="form-control" value="'.get_option("slider-header2","Welcome!").'">
	<span class="help-block" id="limit-text">Slider header <em>(translatable trough languages)</em>.</span>
	</div>
	</div>	
	<div class="control-group">
	<label class="control-label">Number of videos</label>
	<div class="controls">
	<input type="text" id="slider-nr" name="slider-nr" class="span6" value="'.get_option("slider-nr","25").'">
	<span class="help-block" id="limit-text">Video query limit.</span>
	</div>
	</div>	
		<div class="control-group">
	<label class="control-label">Video query:</label>
	<div class="controls">
	<select data-placeholder="Select type" name="slider-queries" id="slider-queries" class="select validate[required]" tabindex="2">
	<option value="most_viewed">Most viewed </option>
<option value="top_rated">Most Liked</option>
<option value="recent" >Recent</option>
<option value="featured">Featured</option>
	</select>
	</div>
	</div>	
	<script>
	      $(document).ready(function(){
	$(\'.select\').find(\'option[value="'.get_option("slider-queries","recent").'"]\').attr("selected",true);	
});
	</script>
	<div class="control-group">
	<label class="control-label">Restrict to uploaders</label>
	<div class="controls">
	<input type="text" id="slider-user" name="slider-user" class="form-control" value="'.get_option("slider-user").'">
	<span class="help-block" id="limit-text">Restict to specific user ids <em>(comma separated numeric ids of users)</em>.</span>
	</div>
	</div>	
	<div class="control-group">
<button class="btn btn-large btn-primary pull-right" type="submit">Update settings</button>	
</div>
</fieldset>	
</form>		
';	
}
add_filter('configuration_menu', 'sliderlink');
add_action('home-start', '_renderSlider');
add_action('adm-slider', '_adminSlider');
?>