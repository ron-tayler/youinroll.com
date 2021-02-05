<?php
$options = "id,title,user_id,thumb,views,duration,category";

if(isset($_GET['cat']))
{
    $categoryId = (int)$_GET['cat'];

    if($categoryId !== 0)
    {
        $categories = $db->get_results('SELECT category,cat_name FROM `'.DB_PREFIX.'videos` INNER JOIN '.DB_PREFIX.'channels ON '.DB_PREFIX.'channels.cat_id = '.DB_PREFIX.'videos.category WHERE user_id = '.toDb($profile->id).' AND '.DB_PREFIX.'videos.category = '.toDb($categoryId).' GROUP BY category');
    }
    
} else
{
    $categories = $db->get_results('SELECT category,cat_name FROM `'.DB_PREFIX.'videos` INNER JOIN '.DB_PREFIX.'channels ON '.DB_PREFIX.'channels.cat_id = '.DB_PREFIX.'videos.category WHERE user_id = '.toDb($profile->id).' GROUP BY category');
}

?>
<div id="panel-videos" class="panel">
    <? if(!isset($_GET['cat'])) {?>
        <a href="<?=profile_url($profile->id, $profile->name)?>?sk=videos&cat=0&p=1" class='btn btn-lg btn-center'><?=_lang('Show all')?></a>
    <?}?><div class="panel-body">
        <div id="videolist-content">
            <?=_ad('0','user-video-list-top');?>
            
            <? if($categoryId === 0) {
                $vq = "select ".$options.", '".toDb($profile->name)."' as owner FROM ".DB_PREFIX."videos WHERE pub > 0 and date < now() and media < 2 and user_id ='".$profile->id."' ORDER BY date DESC ".this_limit(bpp());
                include_once(TPL.'/video-loop.php');
            } else {?>

            <?foreach ($categories as $category) {?>
            <div class="profile-videos">
                <h1 class="loop-heading"><span> <?=$category->cat_name?> </span> </h1>
                <? if ( !isset($_GET['cat'])) {?>
                    <a href='<?=profile_url($profile->id, $profile->name)?>?sk=videos&cat=<?=$category->category?>'><small class="videod"><?=_lang('Show all')?></small></a>
                <? } ?>
            </div>
            <?  
                $limit = isset($categoryId) ? '20' : '4';
                $vq = "select ".$options." FROM ".DB_PREFIX."videos WHERE pub > 0 and date < now() and media < 2 and user_id ='".$profile->id."' AND category = ".toDb($category->category)." ORDER BY date DESC LIMIT ".$limit;
                $videos = $db->get_results($vq);
            ?>
            <? if ($videos) { ?>
            <div id="videoLoop" class="loop-content phpvibe-video-list">
                <? foreach ($videos as $video) {
                    $title = _html(_cut($video->title, 70));			
                    $full_title = _html(str_replace("\"", "",$video->title));			
                    $url = video_url($video->id , $video->title);
                    if(isset($profile->group_id)) { $grcreative = group_creative($profile->group_id); } else { $grcreative=''; }
                    $watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
                    $liked = (is_liked($video->id)) ? '' : '<a class="heartit" title="'._lang("Like this video").'" href="javascript:iLikeThis('.$video->id.')"><i class="material-icons">&#xE8DC;</i></a>';
                    $wlater = (is_user()) ? '<a class="laterit" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="material-icons">&#xE924;</i></a>' : '';
                ?>
                <div id="video-<?=$video->id?>" class="video">
                    <div class="video-thumb">
                        <a class="clip-link" data-id="<?=$video->id?>"
                            title="<?=htmlspecialchars($full_title, ENT_QUOTES)?>" href="<?=$url?>">
                            <span class="clip">
                                <img src="<?=thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height'))?>"
                                    data-name="<?=addslashes(strtok($full_title, " "))?>" alt="image" /><span
                                    class="vertical-align"></span>
                            </span>
                            <span class="overlay"></span>
                        </a><?=$liked.$watched.$wlater?>;
                        <span class="timer"><?=video_time($video->duration)?></span>
                    </div>
                    <div class="video-data">
                        <div class="chanel-avatar"><img src="<?=$profile->avatar?>" alt="logo" /></div>
                        <div class="video-description">
                            <h4 class="video-title"><a href="<?=$url?>"
                                    title="<?=htmlspecialchars($full_title, ENT_QUOTES)?>"><?=_html($title)?></a></h4>
                            <ul class="stats">
                                <li class="uploaderlink"><span class="author">Автор: </span><a
                                        href="<?=profile_url($video->user_id, $video->owner)?>"
                                        title="<?=$profile->name?>"> <?=$profile->name?> </a><?=$grcreative?></li>

                                <li><img src="/tpl/main/images/eye.svg" class="eye"
                                        alt="views" /><?=number_format($video->views)?>
                                    <? if(isset($video->date)) {
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
                                            echo $time_ago;
                                        }?>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <?}?>
            </div>
            <?}?>
            <?=_ad('0','user-video-list-bottom');?>
            <?}?>
            <?}?>
        </div>
    </div>
</div>