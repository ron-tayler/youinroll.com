<?php 
$playlist = $globalTemplateVariable;


$isBuyed = true;

if($playlist->price !== null && (int)$playlist->owner !== user_id())
{
    $isBuyed = $db->get_row('SELECT * FROM '.DB_PREFIX.'users_courses WHERE user_id = '.toDb(user_id()).' AND playlist_id = '.$playlist->id." AND status = 'ready' LIMIT 0,1");
}

?>

<style>
.jumbotron {
    padding: 20px;
    display:flex;
    justify-content: space-between;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(10px);
}

.jumbotron-container {
    position: relative;
    background-repeat: no-repeat;
    background-size: cover;
}

.jumbotron-description {
    margin: 20px 50px auto;
}

.jumbotron-description > * {
    color: white;
}

</style>

<div id="playlist-content" class="main-holder pad-holder col-md-12 top10 nomargin">
    <div class='jumbotron-container'  style="background-image: url('<?=thumb_fix($playlist->picture, true, 260, 260)?>')">
        <div class="jumbotron">
            <div class='jumbotron-description'>
                <h1 class="media-heading">
                    <?=_html($playlist->title)?>
                </h1>
                <p>
                    <h3><?=_html($playlist->description)?></h3>
                </p>
                <?if($playlist->ptype == 1 && $isBuyed !== null) {?>
                <a class="btn btn-primary tipN pull-left" title="<?=_lang(" Play all")?>"
                    href="<?=site_url()?>forward/<?=$playlist->id?>/"><i class="icon icon-play-circle"></i><?=_lang("Play all")?></a>
                <?} else {?>
                    <?if(is_user()) {?>
                        <button data-toggle="modal" data-target="#BuyCourseModal" class="btn btn-primary tipN pull-left" title="<?=_lang("Buy course")?>"><i class="icon icon-play-circle"></i><?=_lang("Buy course for ")?><?=$playlist->price?>р</button>
                    <?} else {?>
                        <a class="btn btn-primary tipN pull-left" title="<?=_lang(" Login for Buy")?>"
                            href="/login"><i class="icon icon-play-circle"></i><?=_lang("Login for Buy")?></a>
                    <?}?>
                <?}?>
            </div>
            <div class="jumbotron-image">
                <?if(not_empty($playlist->picture)) {?>
                <img class="pic" src="<?=thumb_fix($playlist->picture, true, 160, 160)?>" />
                <?} else {?>
                <img class="pic NoAvatar" data-name="<?=trim($playlist->title)?>" src="" />
                <?}?>
            </div>
        </div>
    </div>
<? if($isBuyed !== null) {?>
    <?if($playlist->ptype ==1) {?>
    <?
        $options = DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
        $vq = "SELECT ".DB_PREFIX."videos.id, ".DB_PREFIX."videos.title, ".DB_PREFIX."videos.user_id, ".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration, ".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.name AS owner
        FROM ".DB_PREFIX."playlist_data
        LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
        LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id
        WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
        ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
        include_once(TPL.'/video-loop.php');
    ?>
    <?} else {?>
    <?
        $options = DB_PREFIX."images.id,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."images.thumb";
        $vq = "SELECT $options, ".DB_PREFIX."users.name AS owner, ".DB_PREFIX."users.avatar
        FROM ".DB_PREFIX."playlist_data
        LEFT JOIN ".DB_PREFIX."images ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."images.id
        LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id
        WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
        ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
    ?>
    <div id="imagelist-content mbot20">
        <?include_once(TPL.'/images-loop.php');?>
    </div>
    <?}?>
<?} else {
    include_once(TPL.'/modals/buy-course.php');
}?>
</div>