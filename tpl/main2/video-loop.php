<?php do_action('videoloop-start');
// $vq = "select vibe_videos.id,vibe_videos.title,vibe_videos.date,vibe_videos.user_id,vibe_videos.thumb,vibe_videos.views,vibe_videos.duration,vibe_users.avatar,vibe_videos.nsfw, vibe_users.name as owner, vibe_users.group_id FROM vibe_videos LEFT JOIN vibe_users ON vibe_videos.user_id = vibe_users.id WHERE vibe_videos.pub > 0 and vibe_videos.media < 2 ORDER BY vibe_videos.id DESC LIMIT 0,28";
$vq = str_replace("vibe_videos.duration,vibe_videos.nsfw","vibe_videos.duration,vibe_users.avatar,vibe_videos.nsfw",$vq);
if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}

if(!isset($st)){ $st = ''; }
if(!isset($blockclass)){ $blockclass = ''; }
if(!isset($blockextra)){ $blockextra = ''; }
if(isset($heading) && !empty($heading)) { 
	switch (_html($heading)) {
    case 'Most Viewed':
        $h1_text = "Самые просматриваемые";
        break;
    case 'Most Liked':
        $h1_text = "Самые понравившиеся";
        break;
    case 'Most Commented':
        $h1_text = "Самые комментируемые";
        break;
    default:
       $h1_text = _html($heading);
	}
	echo '<h1 class="loop-heading"><span>'.$h1_text.'</span>'.$st.'</h1>';
}
if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
if(isset($heading_plus) && !empty($heading_plus)) { echo '<small class="videod">'.$heading_plus.'</small>';}
if ($videos) {

echo $blockextra.'<div class="loop-content phpvibe-video-list '.$blockclass.'">'; 
foreach ($videos as $video) {

			$title = _html(_cut($video->title, 70));			
			$full_title = _html(str_replace("\"", "",$video->title));			
			$url = video_url($video->id , $video->title);
			if(isset($video->group_id)) { $grcreative= group_creative($video->group_id); } else { $grcreative=''; };
			$watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
			$liked = (is_liked($video->id)) ? '' : '<a class="heartit" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="material-icons">&#xE8DC;</i></a>';
            $wlater = (is_user()) ? '<a class="laterit" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="material-icons">&#xE924;</i></a>' : '';
			echo '
<div id="video-'.$video->id.'" class="video">
<div class="video-thumb">
		<a class="clip-link" data-id="'.$video->id.'" title="'.htmlspecialchars($full_title, ENT_QUOTES).'" href="'.$url.'">
			<span class="clip">
				<img src="'.thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height')).'" data-name="'.addslashes(strtok($full_title, " ")).'" alt="image" /><span class="vertical-align"></span>
			</span>
          	<span class="overlay"></span>		
		</a>'.$liked.$watched.$wlater;
if($video->duration >= 0) { echo '   <span class="timer">'.video_time($video->duration).'</span>'; }
echo '</div>	
<div class="video-data">
  <div class="chanel-avatar"><img src="'.$video->avatar.'" alt="logo" /></div>
  <div class="video-description">
	<h4 class="video-title"><a href="'.$url.'" title="'.htmlspecialchars($full_title, ENT_QUOTES).'">'._html($title).'</a></h4>
  <ul class="stats">	
    <li class="uploaderlink"><span class="author">Автор: </span><a href="'.profile_url($video->user_id, $video->owner).'" title="'.$video->owner.'">'.$video->owner.' </a> '.$grcreative.'</li>';
if (_lang('views') == 'число просмотров') {
	$views_text = 'просмотров';
} else {
	$views_text = _lang('views');
}

echo '<li><img src="/tpl/main/images/eye.svg" class="eye" alt="views" /> '.number_format($video->views).'&nbsp;|&nbsp;';
if(isset($video->date)) { 
  $time_ago = time_ago($video->date);
  $time_ago = str_replace('2 неделя', '2 недели', $time_ago);
  $time_ago = str_replace('3 неделя', '3 недели', $time_ago);
  $time_ago = str_replace('месяцс', 'месяц', $time_ago);
  $time_ago = str_replace('2 месяц ', '2 месяца ', $time_ago);
  $time_ago = str_replace('3 месяц ', '3 месяца ', $time_ago);
  $time_ago = str_replace('4 месяц ', '4 месяца ', $time_ago);
  $time_ago = str_replace('5 месяц ', '5 месяцев ', $time_ago);
  $time_ago = str_replace('6 месяц ', '6 месяцев ', $time_ago);
  $time_ago = str_replace('7 месяц ', '7 месяцев ', $time_ago);
  $time_ago = str_replace('8 месяц ', '8 месяцев ', $time_ago);
  $time_ago = str_replace('9 месяц ', '9 месяцев ', $time_ago);
  $time_ago = str_replace('10 месяц ', '10 месяцев ', $time_ago);
  $time_ago = str_replace('11 месяц ', '11 месяцев ', $time_ago); 
  $time_ago = str_replace('годс', 'год', $time_ago);
  $time_ago = str_replace('2 год ', '2 года ', $time_ago);
  $time_ago = str_replace('3 год ', '3 года ', $time_ago);
  $time_ago = str_replace('4 год ', '4 года ', $time_ago);
  $time_ago = str_replace('5 год ', '5 лет ', $time_ago);
  $time_ago = str_replace('6 год ', '6 лет ', $time_ago);
  $time_ago = str_replace('7 год ', '7 лет ', $time_ago);
  $time_ago = str_replace('8 год ', '8 лет ', $time_ago);
  $time_ago = str_replace('9 год ', '9 лет ', $time_ago);
  $time_ago = str_replace('10 год ', '11 лет ', $time_ago);
  $time_ago = str_replace('тому ', '', $time_ago);
  // echo '<li>'.$time_ago.'</li>';
  echo $time_ago;
}
echo '</li>';
echo '</ul>
</div>
    </div>
	</div>
';
}
echo _ad('0','after-video-loop');
/* Kill for home if several blocks */
if(!isset($kill_infinite) || !$kill_infinite) { 
if(!_contains($canonical,"?")) {
echo '
<nav id="page_nav"><a href="'.$canonical.'?p='.next_page().'"></a></nav>
'; 
} else {
echo '
<nav id="page_nav"><a href="'.$canonical.'&p='.next_page().'"></a></nav>
'; 	
}
echo '
<div class="page-load-status">
  <div class="infinite-scroll-request" style="display:none">
    <div class="cp-spinner cp-flip"></div>  
    <p>'._lang('Loading...').'</p>
  </div>
  <p class="infinite-scroll-error infinite-scroll-last" style="display:none">
    '._lang('Congratulations, you have reached the end!').'
  </p>
</div>
';
}
echo '

</div>';
} else {
echo '<p class="empty-content">'._lang('Nothing here so far.').'</p>';
}
do_action('videoloop-end');
?>