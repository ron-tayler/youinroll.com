<?php do_action('songloop-start');
if(!nullval($vq)) { $songs = $db->get_results($vq); } else {$songs = false;}
if(!isset($st)){ $st = ''; }
if(isset($heading) && !empty($heading)) { 
	switch (_html($heading)) {
    case 'новые аудио':
        $h1_text = "Новые аудио";
        break;
    case 'Most listened to':
        $h1_text = "Часто прослушиваемые";
        break;
    case 'Liked':
        $h1_text = "Большинство лайков";
        break;
    case 'Discussed songs':
        $h1_text = "Обсуждаемые аудио";
        break;
    default:
       $h1_text = _html($heading);
	}
	echo '<h1 class="loop-heading"><span>'.$h1_text.'</span>'.$st.'</h1>';
}
if(isset($heading_plus) && !empty($heading_plus)) { echo '<small class="songd">'.$heading_plus.'</small>';}
if ($songs) {
echo '<ul class="songs list-group list-group-dividered list-group-full">'; 
foreach ($songs as $song) {
			$title = _html(_cut($song->title, 70));
			$full_title = _html(str_replace("\"", "",$song->title));
			$description = _html(_cut($song->description, 370));
            $full_description = _html(str_replace("\"", "",$song->description));			
			$url = video_url($song->id , $song->title);
			//$watched = (is_watched($song->id)) ? '<span class="badge badge-primary badge-sm">'._lang("Listened").'</span>' : '';
			$liked = (is_liked($song->id)) ? '' : '<a class="heartit  pv_tip" data-toggle="tooltip" data-placement="left" title="'._lang("Like this song").'" href="javascript:iLikeThis('.$song->id.')"><i class="material-icons">&#xE8DC;</i></a>';
            $wlater = (is_user()) ? '<a class="laterit pv_tip" data-toggle="tooltip" data-placement="right" title="'._lang("Listen later").'" href="javascript:Padd('.$song->id.', '.later_playlist().')"><i class="material-icons">&#xE924;</i></a>' : '';
			echo '
            <li id="song-'.$song->id.'" class="list-group-item song">
                  <div class="media">
                    <div class="media-left">
                    <a class="song song-thumb" href="'.$url.'">';
					if(is_empty($song->thumb) || _contains($song->thumb,"xmp3.jpg")) {
                        echo '<img src="" class="NoAvatar" data-name="'.$song->title.'">';
					} else {
					   echo '<img src="'.thumb_fix($song->thumb, true, 270, 169).'" alt="'.$song->title.'" data-name="'.$song->title.'">';
	
					}
          $time_ago = time_ago($song->date);
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
				echo '	
				<span class="badge badge-radius badge-dark"> '.video_time($song->duration).'</span> 
				</a>
					
                    </div>
                    <div class="media-body">
                      <div>
                       <span> <a href="'.$url.'" class="song-title"><h4>'.$full_title.'</h4></a> </span>
                       <div class="song-owner">@<a href="'.profile_url($song->user_id, $song->owner).'" title="'.$song->owner.'">'.$song->owner.'</a> '.$time_ago.'</div>
					  <div class="song-icons">
					   <i class="material-icons">&#xe1b8</i> '.u_k($song->views).'
					   <i class="material-icons">&#xe8dc </i> '.u_k($song->liked).'
					  <div class="song-actions">
					   '.$liked.$wlater.'
					   </div>
					   </div>
					  </div>
                    </div>
                  </div>
                </li>';
}
echo '</ul>';
echo _ad('0','after-song-loop');
/* Kill for home if several blocks */

if(!isset($kill_infinite) || !$kill_infinite) { 
if(!_contains($canonical,"?")) {
echo '
<nav id="page_nav"><a href="'.$canonical.'?ajax=1&p='.next_page().'"></a></nav>
'; 
} else {
echo '
<nav id="page_nav"><a href="'.$canonical.'&ajax=1&p='.next_page().'"></a></nav>
'; 	
}
}
echo ' <br style="clear:both;"/>';
} else {
echo '<p class="empty-content">'._lang('Nothing here so far.').'</p>';
}
do_action('songloop-end');
?>