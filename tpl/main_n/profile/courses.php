<?
$playlists = $cachedb->get_results("select ".DB_PREFIX."playlists.*, '".$profile->name."' as user from ".DB_PREFIX."playlists WHERE ".DB_PREFIX."playlists.picture not in ('[likes]','[history]','[later]') and owner = '".$profile->id."' order by ptype ASC,views DESC limit 0,5");
if($playlists) { ?>
<div id='channelCourses' class="panel-body">
    <h4 class="loop-heading">
    <span><?=_lang("Courses of channel")?></span>
    </h4>
    <div class="loop-content phpvibe-video-list vTrends bottom20">
    <?
        foreach($playlists as $pl)
        {
            $title = _html(_cut($pl->title, 170));
            $full_title = _html(str_replace("\"", "",$pl->title));			
            $url = playlist_url($pl->id , $pl->title);
            $plays = 0;

            if($pl->ptype === '1' || $pl->ptype === '3' ) {
            ?>
            <div id="course-<?=$pl->id;?>" class="course-channel item">
                <div class='course-name'>
                    <?=$title?>
                    <? if($pl->ptype === '3') {?>
                        <a href='<?=$url;?>' class='btn btn-primary'><?=_lang('Buy');?></a>
                    <? } ?>
                    <? if($pl->ptype === '1') {?>
                        <a href='<?=$url;?>' class='btn btn-primary'><?=_lang('Watch');?></a>
                    <? } ?>
                </div>
                <img title='<?=$title;?>' class='course-image' src="<?=thumb_fix($pl->picture, true, 350, 250);?>" />
            </div>
            <?
            }
        }
    ?>
    </div>
</div>
<?}?>