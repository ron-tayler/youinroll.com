<?php

$roleId = $db->get_row("SELECT group_id from ".DB_PREFIX."users WHERE id='".$profile->id."'");
$role = $db->get_row("SELECT * FROM ".DB_PREFIX."users_groups WHERE id='".$roleId->group_id."'");

?>
<div id='channelAbout' class="panel-body">
    <h3><?=$role->group_creative?><?=$role->name?></h3>
    <? if($profile->landing_cover !== null) { ?>
    <img class='landingHead' src="<?=$profile->landing_cover?>" />
    <?}?>
    <? if($profile->bio) {?>
    <div class='landingBlock'>
        <span class='landingText'><?=_lang('Description')?></span>
        <? if($profile->landing_avatar !== null) { ?>
        <img src='<?=$profile->landing_avatar?>' class='landingAvatar' />
        <?}?>
        <?
            $string = preg_replace('/<br.*?(\/|)>/', ' <br> ', html_entity_decode($profile->bio));
        ?>
        <p class='landingDescription'><?=$string?></p>
        <div class='landingSocial'>
            <ul>
                <? if($profile->iglink !== null) {?>
                <li><img src='https://image.flaticon.com/icons/png/512/87/87390.png' width='30' height='30' /><span><a href='<?=$profile->iglink?>'>Instagram</a><span></li>
                <?} if($profile->fblink !== null) {?>
                <li><img src='https://image.flaticon.com/icons/png/512/87/87390.png' width='30' height='30' /><span><a href='<?=$profile->fblink?>'>Facebook</a></span></li>
                <?} if($profile->vklink !== null) {?>
                <li><img src='https://image.flaticon.com/icons/png/512/87/87390.png' width='30' height='30' /><span><a href='<?=$profile->vklink?>'>Vk</a></span></li>
                <? } ?>
            </ul>
        </div>
        <span class='registerChannel'><?=_lang('registered ')?> <?=date('yy-m',strtotime($profile->date_registered))?></span>
    </div>
    <?} else {?>
    <div class='landingBlock'>
        <span class='landingText'><?=_lang('Description')?></span>
        <h2><?=_lang('None description')?></h2>
        <span class='registerChannel'><?=_lang('registered ')?> <?=date('yy-m',strtotime($profile->date_registered))?></span>
    </div>
    <?}?>
    <a href="msg/<?=$profile->id?>/" class='btn btn-writeus'><?=_lang('Write Us');?></a>
</div>

<?
$profile->landing_video = $db->get_row('SELECT * FROM '.DB_PREFIX.'videos WHERE user_id = '.toDb(user_id())." AND is_landing = '1' ORDER BY date DESC LIMIT 0,1");
?>
<? if($profile->landing_video !== null) {?>
<div id="channelAboutVideo">
    <h3><?=_lang('Welcome')?></h3>
    <div id="video-content" class="<?php if(has_list()){ echo "col-md-8 col-xs-12";} else {echo "row block";}?>">
        <div class="video-player">
            <?
                
                $embedvideo = '';
            ?>
            <?=$embedvideo?>
            <div class="clearfix"></div>
        </div>
        <div class="clearfix"></div>
    </div>
</div>
<?}?>
</div>