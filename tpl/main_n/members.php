<?php the_sidebar(); ?>
<div class="row">
<div class="col-md-12 nomargin">
  <div class="row">
 <div id="videolist-content" class="full"> 
<?php echo _ad('0','users-top');

if(!isset($st)){ $st = ''; }

if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}

echo '<h2 class="loop-heading text-left"><span>'._lang("Recently online").'</span></h2>';
if ($users) {

echo '<div id="ChannelResults" class="loop-content phpvibe-video-list ">'; 
foreach ($users as $user) {
			$title = _html(_cut($user->name, 70));
			$full_title = _html(str_replace("\"", "",$user->name));	
			if(isset($user->group_id)) { $grcreative= group_creative($user->group_id); } else { $grcreative=''; };
			$url = profile_url($user->id , $user->name);

		
echo '
<div id="video-'.$user->id.'" class="video">
<div class="video-inner">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$user->id.'" title="'.str_replace("\"","'",$full_title).'" href="'.$url.'">
			<span class="clip">
				<img class="img-circle" src="'.thumb_fix($user->avatar, true, 130, 130).'" alt="'.str_replace("\"","'",$full_title).'" style="width:130px; height:130px; background:none"/>
			</span>        	
		</a>';
	
echo '</div>	
<div class="video-data members-description">';

$mini_text = "";
$mini_text = str_replace ("</p>", "</p> ", htmlspecialchars_decode($user->bio));
$mini_text = _html(_cut(strip_tags($mini_text),170));

echo '	<h4 class="video-title"><a href="'.$url.'" title="'.str_replace("\"","'",$full_title).'">'._html($title).'</a> '.$grcreative.'</h4>
	<p class="text-description" style="font-size:11px">'.$mini_text.'</p>
<ul class="stats">	';
if($user->country || $user->local) {
if(empty($user->local)) {$user->local = _lang('Unknown');}	
echo '<li>		'._lang("from").' '.$user->local.', '.$user->country.'</li>';
}
echo '<li>';
if($user->lastNoty) {
if(date('d-m-Y', strtotime($user->lastNoty)) != date('d-m-Y')) {
echo '<i class="icon-circle-thin offline" style="margin-right:9px;"></i>';
} else {
echo '<i class="icon-circle-thin online" style="margin-right:9px;"></i>';
}}
echo time_ago($user->lastlogin).'</li>
</ul>
</div>	
	</div>
		</div>
';
}

echo ' <br style="clear:both;"/></div>';
} else {
echo _lang('Sorry but there are no results.');
}

echo _ad('0','users-bottom');
	
$a->show_pages($ps);
?>
</div>
<?php $ad = _ad('0','members-sidebar');
if(!empty($ad)) {
echo '<div id="SearchSidebar" class="col-md-4 oboxed">'.$ad.'</div>';
}
?>
</div>
