<?
    $conferences = $db->get_results('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb($user).' ORDER BY started_at DESC LIMIT 4');
?>

<h4 class="loop-heading">
	<span><?=_lang("Last Conferences")?></span>
</h4>
<div id="SearchResults" class="loop-content phpvibe-video-list vTrends bottom20 ">
    <? if($conferences !== null) { ?>
	<?foreach ($conferences as $conference) { ?>
		<div id="video-<?=$conference->id?>" class="video halfVideo item">
        <div class="video-inner">
            <div class="video-thumb">
                <a class="clip-link" data-id="" title="" href="">
                    <span class="clip">
                        <img src="<?=$conference->cover?>" alt="" /><span class="vertical-align"></span>
                    </span>
                    <span class="overlay"></span>
                </a>
                <span class="timer">2:00:20</span>
				<span class="was">2 days ago</span>
            </div>
            <div class="video-data">
				<img src='<?=$profile->avatar?>' class='img-rounded'/>
				<div>
                <h4 class="video-title"><a href="'.$url.'" title="'.$full_title.'"><?=$conference->name?></a></h4>
				<small><?= date('Y/m/d', strtotime($conference->started_at)) ?></small>
				</div>
            </div>
        </div>
    </div>
    <?}} else {?>
        <h3 style='width:100%;text-align:center;'><?=_lang('There is no content')?></h3>
    <?}?>
	
    <br style="clear:both">
</div>
<div class="clearfix"></div>
</div>