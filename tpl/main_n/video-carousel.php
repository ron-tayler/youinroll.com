<?php
$st ??='';
$blockclass ??='';
$blockextra ??='';

try {

    if(isset($heading) && !empty($heading)) echo '<h3 class="loop-heading loop-carousel"><span>'._html($heading).'</span>'.$st.'</h3>';
    if(isset($heading_meta) && !empty($heading_meta)) echo $heading_meta;

    if(!isset($vq) or empty($vq)) throw new Exception(_lang('Nothing here so far.'));
    $videos = $cachedb->get_results($vq);
    if (!isset($videos)) throw new Exception(_lang('Nothing here so far.')); ?>
    <div class="loop-content">
        <div class="owl-carousel">
        <? foreach ($videos as $video) {
            $title = _html(_cut($video->title, 70));
            $full_title = _html(str_replace("\"", "",$video->title));
            $url = video_url($video->id , $video->title);
            $grcreative = isset($video->group_id)?group_creative($video->group_id):'';
            $var_1 = _lang("Watched");
            $var_2 = _lang("Like this video");
            $var_3 = _lang("Add to watch later");
            $var_4 = thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height'));
            $var_5 = htmlspecialchars($full_title, ENT_QUOTES);
            $var_6 = htmlspecialchars(str_replace("|","&#124;",$full_title), ENT_QUOTES);
            $var_7 = htmlspecialchars($full_title, ENT_QUOTES);
            $var_8 = profile_url($video->user_id, $video->owner);
            $views_text = 'просмотров'; // $views_text = _lang('views');
            $time_ago = time_ago($video->date);

            $liked = (!is_liked($video->id)) ? '<a class="heartit" title="'.$var_2.'" href="javascript:iLikeThis('.$video->id.')"><i class="material-icons">&#xE8DC;</i></a>' : '';
            ?>
            <div id="video-<?=$video->id?>" class="video">
                <div class="video-thumb">
                    <a class="clip-link" data-id="<?=$video->id?>" title="<?=$var_6?>" href="<?=$url?>">
                        <span class="clip">
                            <img src="<?=$var_4?>" alt="<?=$var_5?>" />
                            <span class="vertical-align"></span>
                        </span>
                        <span class="overlay"></span>
                    </a>
                    <? if(is_watched($video->id)){?>
                    <span class="vSeen"><?=$var_1?></span>
                    <?}?>
                    <? if(is_watched($video->id)){?>
                    <a class="laterit" title="<?=$var_3?>" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="material-icons">&#xE924;</i></a>
                    <?}?>
                    <?if($video->duration > 0) {?>
                    <span class="timer"><?=video_time($video->duration)?></span>
                    <?}?>
                </div>
                <div class="video-data video-data-carousel">
                    <div class="chanel-avatar">
                        <img src="<?=$video->avatar?>" alt="logo" />
                    </div>
                    <div class="video-description">
                        <h4 class="video-title"><a href="<?=$url?>" title="<?=$var_7?>"><?=$title?></a></h4>
                        <ul class="stats">
                            <li class="uploaderlink">
                                <span class="author">Автор: </span>
                                <a href="<?=$var_8?>" title="<?=$video->owner?>"><?=$video->owner?></a>
                                <?=$grcreative?>
                            </li>
                            <li>
                                <img src="/tpl/main/images/eye.svg" class="eye" alt="icon" /><?=number_format($video->views)?>&nbsp;|&nbsp;
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <? } ?>
        </div>
        <?=_ad('0','after-video-carousel')?>
    </div>
<?
}catch (Exception $ex){
    echo '<p class="empty-content">'.$ex->getMessage().'</p>';
};
?>
