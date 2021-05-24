<style>

.widthcto {
width: 100%;
}

.pad-3 {
padding: 3px;
}


</style>

<?
    $conferences = $db->get_results('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb($profile->id)." AND type = '".toDb('lesson')."' ORDER BY started_at DESC LIMIT 4");
?>

<h4 class="loop-heading">
	<span><?=_lang("Последние уроки")?></span>
</h4>


<!-- start  -->

<div id="SearchResults" class="row row-cols-1 row-cols-md-3">
    <? if($conferences !== null && count($conferences) > 0 ) { ?>
    <?foreach ($conferences as $conference) { ?>
        <? $url2 = conference_url($conference->id, $conference->name); ?>
	 <div id="video-<?=$conference->id?>" class="pad-3   col-md-3 col-lg-3 col-xl-2"> 
           <div class="widthcto">
              <img src="<?=($conference->cover === '') ? '/uploads/def-avatar.jpg' : $conference->cover?>" alt="" class="widthcto" />
           </div>
           <div class="">
              <div class="">
                  <a class="clip-link" data-id="" title="" href="<?=$url2?>">
                      <span class="overlay"></span>
                  </a>
              </div>
              <div class="video-data">
				<img src='<?=$profile->avatar?>' class='img-rounded'/>
				<div>
                <h4 class="video-title"><a href="<?=$url?>" title="<?=$full_title?>"><?=$conference->name?></a></h4>
				<small><?= date('Y/m/d', strtotime($conference->created_at)) ?></small>
				</div>
            </div>
        </div>
    </div>
    <?}} else {?>
        <h3 style='width:100%;text-align:center;'><?=_lang('There is no content')?></h3>
    <?}?>
	
    <br style="clear:both">
</div>
<!-- start  -->



<div id="SearchResults" class="loop-content phpvibe-video-list vTrends bottom20 ">
    <? if($conferences !== null && count($conferences) > 0 ) { ?>
    <?foreach ($conferences as $conference) { ?>
        <? $url2 = conference_url($conference->id, $conference->name); ?>
		<div id="video-<?=$conference->id?>" class="video halfVideo item"> 
        <div class="video-inner">
            <div class="video-thumb">
                <a class="clip-link" data-id="" title="" href="<?=$url2?>">
                    <span class="clip">
                        <img src="<?=($conference->cover === '') ? '/uploads/def-avatar.jpg' : $conference->cover?>" alt="" /><span class="vertical-align"></span>
                    </span>
                    <span class="overlay"></span>
                </a>
              <!---  <span class="timer">2:00:20</span> -->
			 <!---	<span class="was">2 days ago</span> -->
            </div>
            <div class="video-data">
				<img src='<?=$profile->avatar?>' class='img-rounded'/>
				<div>
                <h4 class="video-title"><a href="<?=$url?>" title="<?=$full_title?>"><?=$conference->name?></a></h4>
				<small><?= date('Y/m/d', strtotime($conference->created_at)) ?></small>
				</div>
            </div>
        </div>
    </div>
    <?}} else {?>
        <h3 style='width:100%;text-align:center;'><?=_lang('There is no content')?></h3>
    <?}?>
	
    <br style="clear:both">
</div>
<!-- end  -->
<div class="clearfix"></div>
</div>
