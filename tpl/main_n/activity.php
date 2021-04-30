<?php the_sidebar();
//Add sorter 
if(isset($sortop) && $sortop) {
/* Most liked , Most viewed time sorting */
$st = '
<div class="btn-group pull-right">
       <a data-toggle="dropdown" class="btn btn-default btn-outline dropdown-toogle"> <i class="icon icon-calendar"></i>упорядочить<i class="icon icon-angle-down"></i> </a>
			<ul class="dropdown-menu dropdown-menu-right bullet">
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'?sort=w"><i class="icon icon-circle-thin"></i>'._lang("This Week").'</a></li>
			<li title="'._lang("This Month").'"><a href="'.list_url(token()).'?sort=m"><i class="icon icon-circle-thin"></i>'._lang("This Month").'</a></li>
			<li title="'._lang("This Year").'"><a href="'.list_url(token()).'?sort=y"><i class="icon icon-circle-thin"></i>'._lang("This Year").'</a></li>
			<li class="divider" role="presentation"></li>
			<li title="'._lang("This Week").'"><a href="'.list_url(token()).'"><i class="icon icon-circle-thin"></i>'._lang("All").'</a></li>
		</ul>
		</div>
';
}

 ?>

<style>
.category-block {
    display: block;
    position: relative;
    height: 200px;
    color: white !important;
    width: 200px;
    border-radius: 50%;
    background-color: #000000a1;
}

.category-block-filter:hover {
    background-color: #00000040;
}

.category-block-filter {
    display: block;
    position: absolute;
    height: 200px;
    color: white !important;
    width: 200px;
    border-radius: 50%;
    background-color: #000000a1;
}

.category-block h3 {
    text-align: center;
    vertical-align: middle;
    line-height: 150px;
    width: 200px;
    color: white !important;
    font-weight: bold;
}

.category-row {
    display: flex;
    flex-direction: row;
    justify-content: space-between;
}
</style>

<?
$categories = $cachedb->get_results('SELECT cat_id,cat_name,picture FROM '.DB_PREFIX.'channels where type = '.toDb(1).' AND child_of = '.toDb(0).' LIMIT 0,4');
?>

<div class="row main-holder" id='categoryBlock'>
    <h1>Категории</h1>
    <div class='category-row'>
        <? foreach ($categories as $category) {?>
        <div class='category-block' style="background-image: url('<?=$category->picture?>')">
            <div class='category-block-filter'>
                <div class="card" style="width: 18rem;">
                    <h3><?=$category->cat_name?></h3>
                </div>
            </div>
        </div>
        <?}?>
    </div>
</div>
<br>
<h1><?=_lang('Trends')?></h1>
<div class="row main-holder">
<div id="videolist-content" class="oboxed col-md-offset-2">
        <?=_ad('0','search-top');?>

        <?
          if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}

          if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
        ?>

        <?if ($videos) {?>

        <div id="SearchResults" class="loop-content phpvibe-video-list ">
            <?
            foreach ($videos as $video) {
                  $title = _html(_cut($video->title, 100));
                  $full_title = _html(str_replace("\"", "",$video->title));
                  $url = video_url($video->id , $video->title);
                  $watched = (is_watched($video->id)) ? '<span class="vSeen">'._lang("Watched").'</span>' : '';
                  $wlater = (is_user()) ? '<a class="laterit" title="'._lang("Add to watch later").'" href="javascript:Padd('.$video->id.', '.later_playlist().')"><i class="material-icons">&#xE924;</i></a>' : '';
                  
                  if(isset($video->group_id)) { $grcreative= group_creative($video->group_id); } else { $grcreative=''; }
                  $description = _html($video->description);
                  $description = _cut(trim($description),180);
                  if(empty($description)) {$description = $full_title;}                  
            ?>
            <div id="video-<?=$video->id?>" class="video">
                <div class="video-inner">
                    <div class="video-thumb">
                        <a class="clip-link" data-id="<?=$video->id?>" title="<?=$full_title?>" href="<?=$url?>">
                            <span class="clip">
                                <img src="<?=thumb_fix($video->thumb, true, get_option('thumb-width'), get_option('thumb-height'))?>"
                                    alt="<?=$full_title?>" /><span class="vertical-align"></span>
                            </span>
                            <span class="overlay"></span>
                        </a><?=$watched.$wlater?>
                        <?if($video->duration > 0){?>
                        <span class="timer"><?=video_time($video->duration)?></span>
                        <?}?>
                    </div>
                    <div class="video-data search-result">
                        <h4 class="video-title"><a href="<?=$url?>" title="<?=$full_title?>"><?=_html($title)?></a></h4>
                        <ul class="stats stats-searchresult">
                            <li class="search-row"><?=_lang("by")?> <a
                                    href="<?=profile_url($video->user_id, $video->owner)?>"
                                    title="<?=$video->owner?>"><?=$video->owner?></a> <?=$grcreative?></li>
                            <li class="views-serchresult"><img src="/tpl/main/images/eye.svg" class="eye"
                                    alt="views" /><?=number_format($video->views)?>
                                <?
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
                                  echo '&nbsp;|&nbsp;'.$time_ago;
                            }?>
                            </li>
                        </ul>
                        <p><?=$description?></p>
                    </div>
                </div>
            </div>
            <?}?>
            <?if(_get('sort')) {?>
            <nav id="page_nav"><a href="<?=$canonical?>?p=<?=next_page()?>&sort=<?=toDb(_get('sort'))?>"></a></nav>
            <?} else {?>
            <nav id="page_nav"><a href="<?=$canonical?>?p=<?=next_page()?>"></a></nav>
            <?}?>
            <div class="page-load-status">
                <div class="infinite-scroll-request" style="display:none">
                    <div class="cp-spinner cp-flip"></div>
                    <p><?=_lang('Loading...')?></p>
                </div>
                <p class="infinite-scroll-error infinite-scroll-last" style="display:none">
                    <?=_lang('The end!')?>
                </p>
            </div>
            <br style="clear:both;" />
        </div>

        <?} else {?>
        <?=_lang('Sorry but there are no results.')?>
        <?}?>

        <?=_ad('0','search-bottom');?>
    </div>
</div>