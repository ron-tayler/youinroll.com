<?php
    if (is_video() || is_picture() || is_com('stream') || is_com('conversation')) {
        $sidebar_type = 'normal';
        $sidebar_status = 'close';
        $sidebar_class = 'hide-all';
    } else{
        $sidebar_type = 'iconic';
        $sidebar_status = 'open';
        $sidebar_class = '';
    }
    $isActive_myChannel = ((!isset($_SERVER['REQUEST_URI']) || ltrim($_SERVER['REQUEST_URI'], '/') === '') && !is_user())?'item-activ':'';
    $myChannelHref = is_user()? profile_url(user_id(), user_name()).'?sk=about':'/login';
?>
<div id="sidebar" class="<?=$sidebar_class?> animated zoomInLeft" data-type="<?=$sidebar_type?>" data-status="<?=$sidebar_status?>">
    <div class="sidescroll">
        <?php do_action('sidebar-start'); ?>
        <?=_ad('0', 'sidebar-start')?>

        <div class="sidebar-nav blc">
            <ul>
                <li class="lihead <?=$isActive_myChannel?>">
                    <a href="<?=$myChannelHref?>">
                        <span class="iconed"><img src="/tpl/main/icon-menu/home.svg" alt="icon" style="width: 30px !important;height: 30px !important;"></span>
                        Мой канал
                    </a>
                    <span class="tooltip-item" style="margin-left:-8px">Мой канал</span>
                </li>
                <? if (is_user()) {
                    global $db;
                    $messagesCount = 0;
                    try {
                        $sql = 'SELECT * FROM '.DB_PREFIX.'conversations WHERE user_id = '.toDb(user_id());
                        $conferences = $db->get_results($sql);
                        foreach ($conferences as $conference) {
                            $sql = 'SELECT * FROM '.DB_PREFIX.'messages WHERE user_id <> '.toDb(user_id()).' AND readed = 0 AND conversation_id = '.toDb($conference->conf_id);
                            $sql2 = $db->get_results($sql);
                            $messagesCount = $messagesCount + count($sql2 ?? []);
                        }
                    }catch(\Throwable $th){ }

                    if ($messages !== null) {
                        $messagesCount = count($messages);
                    }
                    ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/conversation/0/')?'item-activ':''?>">
                        <a href="<?=site_url()?>conversation/0/" id="myInbox">
                            <span class="iconed">
                                <img src="/tpl/main/icon-menu/plane.svg" alt="icon" style="width: 30px !important;height: 30px !important;">
                                <span id="chatNotifyBadge" class="badge badge-coral" style="position: absolute;bottom: -8px;left: 15px;<?=($messagesCount==0)?'display: none':''?>">
                                    <?=$messagesCount?>
                                </span>
                            </span>
                            <?=_lang('Чат')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Чат')?></span>
                    </li>
                    <hr/>
                <? } ?>
                <? if(get_option('premiumhub', 1)==1){ ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == "/premiumhub/browse/")?'item-activ':''?>">
                        <a href="<?=hub_url(browse)?>"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span><?=_lang('Premium Hub')?></a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Premium Hub')?></span>
                    </li>
                <? } ?>
                <? /* TODO Хард-код решение по отключению трендов
                echo '<li class="lihead';
                if ($_SERVER['REQUEST_URI'] == '/activity') {
                    echo ' item-activ';
                }
                echo '"><a href="' . site_url() . buzz . '"><span class="iconed"><img src="/tpl/main/icon-menu/activ.png" alt="icon" /></span> ' . _lang('What\'s up') . '</a><span class="tooltip-item" style="margin-left:-15px">' . _lang('What\'s up') . '</span></li>';
                */ ?>
                <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/categories')?'item-activ':''?>">
                    <style>
                        #sidebar-love-a{
                            position: relative;
                            left: 5px;
                        }
                        #sidebar.hide #sidebar-love-a{
                            left: -5px;
                        }
                    </style>
                    <a href="/categories" id="sidebar-love-a">
                        <span class="iconed"><svg aria-label="Что нового" class="_8-yf5 " fill="#262626" height="22" viewBox="0 0 48 48" width="22" style="width: 24px !important;height: 24px !important;"><path d="M34.6 6.1c5.7 0 10.4 5.2 10.4 11.5 0 6.8-5.9 11-11.5 16S25 41.3 24 41.9c-1.1-.7-4.7-4-9.5-8.3-5.7-5-11.5-9.2-11.5-16C3 11.3 7.7 6.1 13.4 6.1c4.2 0 6.5 2 8.1 4.3 1.9 2.6 2.2 3.9 2.5 3.9.3 0 .6-1.3 2.5-3.9 1.6-2.3 3.9-4.3 8.1-4.3m0-3c-4.5 0-7.9 1.8-10.6 5.6-2.7-3.7-6.1-5.5-10.6-5.5C6 3.1 0 9.6 0 17.6c0 7.3 5.4 12 10.6 16.5.6.5 1.3 1.1 1.9 1.7l2.3 2c4.4 3.9 6.6 5.9 7.6 6.5.5.3 1.1.5 1.6.5.6 0 1.1-.2 1.6-.5 1-.6 2.8-2.2 7.8-6.8l2-1.8c.7-.6 1.3-1.2 2-1.7C42.7 29.6 48 25 48 17.6c0-8-6-14.5-13.4-14.5z"></path></svg></span>
                        <?=_lang('Категории')?>
                    </a>
                    <span class="tooltip-item" style="margin-left:-9px"><?=_lang('Категории')?></span>
                </li>
                <? if(get_option('musicmenu')==1){ ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/music/browse/')?'item-activ':''?>">
                        <a href="<?=music_url(browse)?>">
                            <span class="iconed"><img src="/tpl/main/icon-menu/music.png" alt="icon" /></span>
                            <?=_lang('Music')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Music')?></span>
                    </li>
                <? } ?>
                <? if(get_option('showplaylists', 1)==1){ ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/lists/')?'item-activ':''?>">
                        <a href="<?=site_url().playlists?>/">
                            <span class="iconed"><img src="/tpl/main/icon-menu/audio.png" alt="icon" /></span>
                            <?=_lang('Playlists')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Playlists')?></span>
                    </li>
                <? } ?>
                <? if(get_option('showusers', 1)==1){ ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/users/')?'item-activ':''?>">
                        <a href="<?=site_url() . members?>/">
                            <span class="iconed"><img src="/tpl/main/icon-menu/people.svg" alt="icon" style="width: 30px !important;height: 30px !important;"></span>
                            <?=_lang('Channels')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Channels')?></span>
                    </li>
                <? } ?>
                <? if (get_option('showblog', 1)==1){ ?>
                    <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/blog/')?'item-activ':''?>">
                        <a href="<?=site_url().blog?>/">
                            <span class="iconed"><img src="/tpl/main/icon-menu/blog.png" alt="icon" /></span>
                            <?=_lang('Blog')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Blog')?></span>
                    </li>
                <? } ?>
            </ul>
        </div>
        <hr/>
        <? if(is_user()){ ?>
            <? /* ?>
            <div class='relative'>
                <h4 class="li-heading li-heading-iconed">
                    <a style="color: #797E89;" href="https://youinroll.com/me?sk=playlists" title="<?=_("View all")?>">
                        <h5><?= _lang('My playlists') ?></h5>
                    </a>
                </h4>
                <div class="sidebar-nav blc">
                    <ul id="playlistItems">
                        <i id="playlistDropdown" class="material-icons" type="button"data-page="1" title="<?=_lang('Expand')?>">&#xe313;</i>
                        <i id="playlistMinimize" class="material-icons" title="<?=_lang('Minimize')?>">&#xe316;</i>
                    </ul>
                </div>
            </div>
            <hr/>
            <? //*/ ?>
            <div class='relative'>
                <h4 class="li-heading">
                    <a style="color: #797E89;" href="<?=profile_url(user_id(), user_name())?>?sk=subscribed" title="<?php echo _("View all"); ?>">
                        <?=_lang('My subscriptions')?>
                    </a>
                </h4>
                <div class="sidebar-nav blc">
                    <ul id="subscribtionItems">
                    </ul>
                    <div id="subscriptionDropdown" class="" style="cursor: pointer"  type="button" data-page="1">
                    <!--    <i class="material-icons" type="button" data-page="1" title="<?=_lang('Expand')?>">&#xe313;</i> -->
                        <span style="color:#fe2c55" ><?=_lang('Expand')?></span>
                    </div>
                    <div id="subscriptionMinimize" class="" style="cursor: pointer" type="button">
                      <!--  <i class="material-icons" title="<?=_lang('Minimize')?>">&#xe316;</i> -->
                        <span style="color:#fe2c55" ><?=_lang('Minimize')?></span>
                    </div>
                </div>
            </div>
      <!--  <hr/> -->
            <? do_action('user-sidebar-end') ?>
        <? }else{ ?>
            <div class="blc mtop20 odet fs300" id="guestButton">
                <?=_lang('Share videos, music and pictures, follow friends and keep track of what you enjoy!')?>
                <p class="small mright20 mleft10">
                    <a href="/login" class="sidebar-btn-login">
                    <?=_lang("Join us")?></a>
                </p>
            </div>
            <? do_action('guest-sidebar') ?>
        <? } ?>
        <? do_action('sidebar-end') ?>
        <?=_ad('0', 'sidebar-end')?>
        <div class="blc">&nbsp;</div>
    </div>
</div>
