<?php $activity = $db->get_results($vq);
if ($activity) {
$did =  array();
echo '<div class="row">
<ul id="user-timeline" class="timelist user-timeline">
'; 
$licon = array();
$licon["1"] = "icon-heart";
$licon["2"] = "icon-share";
$licon["3"] = "icon-youtube-play";
$licon["4"] = "icon-upload";
$licon["5"] = "icon-rss";
$licon["6"] = "icon-comments";
$licon["7"] = "icon-thumbs-up";
$licon["8"] = "icon-camera";
$licon["9"] = "icon-star";
$lback = array();
$lback["1"] = $lback["9"] = "bg-smooth";
$lback["2"] = "bg-success";
$lback["3"] = "bg-flat";
$lback["4"] = $lback["8"] = "bg-default";
$lback["5"] = "bg-default";
$lback["6"] = "bg-info";
$lback["7"] = "bg-smooth";
foreach ($activity as $buzz) {
$did = get_activity($buzz);	
if(isset($did["what"]) && !nullval($did["what"])) {
$time_ago = time_ago($buzz->date);
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
<li class="cul-'.$buzz->type.' t-item">
 <div class="user-timeline-time">'.$time_ago.'</div>
<i class="icon '.$licon[$buzz->type].' user-timeline-icon '.$lback[$buzz->type].'"></i>
<div class="user-timeline-content"><p>';
if(isset($buzz->avatar) && not_empty(($buzz->avatar))) {
$av = '<img src="'.thumb_fix($buzz->avatar, true,32,32).'" class="isBoxed nopad user-time-avatar"/>';	
} else {
$av = '<img src="" class="user-time-avatar NoAvatar isBoxed nopad" data-name="'.$buzz->name.'"/>';	
}
echo '<a href="'.canonical().'">'.$av.' '._html($buzz->name).'</a>  '.$did["what"].'</p>
';
if(isset($did["content"]) && !nullval($did["content"])) {
echo '<div class="timeline-media">'.$did["content"].'</div>';
}
echo '</div>

</li>';
unset($did);
}
}
echo '</ul><br style="clear:both;"/></div>';
}
?>
