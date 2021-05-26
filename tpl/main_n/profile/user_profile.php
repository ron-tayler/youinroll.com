
<style>

.widthcto {
width: 100%;
height: 130px;
overflow: hidden;
}

.widthcto img {
width: 100%;
}

.pad-3 {
padding: 3px;
}

.basic-grid {
    display: grid;
    gap: 1rem;
    width: 100%;
    grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
 }

.avatar {
  vertical-align: middle;
  padding-top: 3px;
  width: 50px;
  height: 50px;
  border-radius: 50%;
  display: inline-block;
}

.vidd {
display: inline-block;
}

.vid-descr {
width: 100%
}

.one {
  width: 20%;
  float: left;
}

.two {
  margin-left: 20%;
 padding-left: 3px;
 padding-top: 3px;
}

</style>


<?
    $conferences = $db->get_results('SELECT * FROM '.DB_PREFIX.'conferences WHERE moderator_id = '.toDb($profile->id)." AND type = '".toDb('stream')."' ORDER BY started_at DESC LIMIT 4");
?>

<h4 class="loop-heading">
	<span><?=_lang("Трансляции")?></span>
</h4>


<section class="basic-grid">
    <? if($conferences !== null && count($conferences) > 0 ) { ?>
    <?foreach ($conferences as $conference) { ?>
        <? $url2 = conference_url($conference->id, $conference->name); ?>
         <div id="video-<?=$conference->id?>" class="pad-3">

        <a class="clip-link" data-id="" title="" href="<?=$url2?>">
           <div class="widthcto">
              <img src="https://youinroll.com/<?=($conference->cover === '') ? 'https://youinroll.com/uploads/def-avatar.jpg' : $conference->cover?>" alt=""  />
           </div>
              <div class="vid-descr">
                <div class="one">
                   <img src='https://youinroll.com/<?=$profile->avatar?>' class='avatar'/>
                </div>
                <div class="two">
                   <h4 class="vidd"><a href="<?=$url2?>" title="<?=$full_title?>"><?=$conference->name?></a></h4>
              <small><?= date('Y/m/d', strtotime($conference->created_at)) ?></small>
                </div>
              </div>
              </a>
         </div>
    <?}} else {?>
        <h3 style='width:100%;text-align:center;'><?=_lang('There is no content')?></h3>
    <?}?>

</section>


<!--
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
<div class="clearfix"></div>
</div>
-->

