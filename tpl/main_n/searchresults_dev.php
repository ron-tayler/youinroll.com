<?=the_sidebar();?>
<?
$vqc = "select id,name,bio,avatar,lastnoty,group_id FROM ".DB_PREFIX."users WHERE concat(bio,name) LIKE '%".$key."%' OR concat(bio,name) LIKE '%".substr($key,0, -1)."%'  ORDER by name DESC LIMIT 0,4";
if(!nullval($vqc)) { $channels = $db->get_results($vqc); } else {$channels = false;}
?>
<?if ($channels) {?>
<div class="row">
    <a href='https://youinroll.com/show/<?=$key?>?type=channel' class='row-link'><?=_lang('Show All')?></a>
    <div id="channelsearch-content" class="oboxed col-md-offset-2">
        <?=_ad('0','search-top');?>
        <?
          if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._lang('Channels').'</span>'.$st.'</h1>';}
          if(isset($heading_meta) && !empty($heading_meta)) { echo $heading_meta;}
        ?>

        <div id="SearchResultsChannels" class="loop-content phpvibe-video-list ">
            <?
            foreach ($channels as $channel) {
              $title = _html(_cut($channel->name, 100));
              $full_title = _html(str_replace("\"", "",$channel->name));
              $url = profile_url($channel->id , $channel->name);
              
              if(isset($channel->group_id)) { $grcreative = group_creative($channel->group_id); } else { $grcreative=''; }
              $description = _html($channel->description);
              $description = _cut(trim($description),180);
              if(empty($description)) {$description = $full_title;} 
            ?>
            <div id="channel-<?=$channel->id?>" class="channel-result">
                <div class="video-inner">
                    <div class="video-thumb">
                        <a class="clip-link" data-id="<?=$channel->id?>" title="<?=$title?>"
                            href="<?=$url?>">
                            <span class="clip">
                                <img class="img-circle"
                                    src="<?=thumb_fix($channel->avatar, 130, 130)?>"
                                    alt="<?=$title?>">
                            </span>
                        </a>
                    </div>
                    <div class="video-data members-description">
                        <h4 class="video-title"><a href="<?=$url?>"
                                title="<?=$title?>"><?=$title?></a><?=$grcreative?></h4>
                    </div>
                </div>
            </div>
            <?}?>
        </div>
    </div>
</div>
<? } ?>

<div class="row">
  <div class="btn-group pull-right">
      <a data-toggle="dropdown" class="btn dropdown-toogle text-uppercase"><i class="material-icons">&#xE152;</i>
          <?=_lang("Refine")?></a>
      <ul class="dropdown-menu dropdown-menu-right bullet">
          <li title="<?=_lang("This Week")?>"><a
                  href="<?=site_url().show.url_split.str_replace(array(" "),array("-"),$key)?>?sort=w"><i
                      class="icon material-icons">&#xE425;</i><?=_lang("This Week")?></a></li>
          <li title="<?=_lang("This Month")?>"><a
                  href="<?=site_url().show.url_split.str_replace(array(" "),array("-"),$key)?>?sort=m"><i
                      class="icon material-icons">&#xE425;</i><?=_lang("This Month")?></a></li>
          <li title="<?=_lang("This Year")?>"><a
                  href="<?=site_url().show.url_split.str_replace(array(" "),array("-"),$key)?>?sort=y"><i
                      class="icon material-icons">&#xE425;</i><?=_lang("This Year")?></a></li>
          <li class="divider" role="presentation"></li>
          <li title="<?=_lang("All the time")?>"><a
                  href="<?=site_url().show.url_split.str_replace(array(" "),array("-"),$key)?>"><i
                      class="icon material-icons">&#xE922;</i><?=_lang("Always")?></a></li>
      </ul>
  </div>
    <div id="videolist-content" class="oboxed col-md-offset-2">
        <?=_ad('0','search-top');?>

        <?
          if(!nullval($vq)) { $videos = $db->get_results($vq); } else {$videos = false;}

          if(isset($heading) && !empty($heading)) { echo '<h1 class="loop-heading"><span>'._lang('Videos').'</span>'.$st.'</h1>';}
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