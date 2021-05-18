<?php
    if (is_video() || is_picture() || is_com('stream') || is_com('conversation')) {
        $sidebar_class = 'hide-all';
    } elseif (is_com('home')) {
        $sidebar_class = 'hide';
    } else {
        $sidebar_class = '';
    }
?>
<div id="sidebar" class="<?=$sidebar_class?> animated zoomInLeft">
    <div class="sidescroll">
        <?php do_action('sidebar-start'); ?>
        <?=_ad('0', 'sidebar-start')?>

        <div class="sidebar-nav blc">
            <ul>
                <li class="lihead <?=((!isset($_SERVER['REQUEST_URI']) || ltrim($_SERVER['REQUEST_URI'], '/') === '') && !is_user())?'item-activ':''?>">
                    <a href="<?=profile_url(user_id(), user_name())?>?sk=about">
                        <span class="iconed"><img src="/tpl/main/icon-menu/home.png" alt="icon" /></span>
                        Мой канал
                    </a>
                    <span class="tooltip-item" style="margin-left:-8px">Мой канал</span>
                </li>
                <? if (is_user()) {
                    global $db;

                    $messagesCount = 0;

                    try {

                        $sql = 'SELECT * FROM ' . DB_PREFIX . 'conversations WHERE user_id = ' . toDb(user_id());

                        $conferences = $db->get_results($sql);

                        foreach ($conferences as $conference) {

                            $sql = 'SELECT * FROM ' . DB_PREFIX . 'messages WHERE user_id <> ' . toDb(user_id()) . ' AND readed = 0 AND conversation_id = ' . toDb($conference->conf_id);

                            $sql2 = $db->get_results($sql);

                            $messagesCount = $messagesCount + count($sql2 ?? []);
                        }


                    } catch (\Throwable $th) {

                    }

                    if ($messages !== null) {
                        $messagesCount = count($messages);
                    }

                    echo '<li class="lihead';
                    if ($_SERVER['REQUEST_URI'] == '/conversation/0/') {
                        echo ' item-activ';
                    }
                    echo '"><a href="' . site_url() . 'conversation/0/" id="myInbox">
                    <span class="iconed"><img src="/tpl/main/icon-menu/blog-mean.png" alt="icon" />' .
                        '<span class="badge badge-danger" style="
                        position: absolute;
                        bottom: -8px;
                        left: 15px;
                    ">' . $messagesCount . '</span></span>' . _lang('Чат') . '</a><span class="tooltip-item" style="margin-left:-15px">' . _lang('Чат') . '</span></li>';

                } ?>
                <!-- // TODO Тут сделать полоску -->
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
                <li class="lihead <?=($_SERVER['REQUEST_URI'] == '/videos/browse/')?'item-activ':''?>">
                    <a href="<?=list_url(browse)?>">
                        <span class="iconed"><img src="/tpl/main/icon-menu/video.png" alt="icon" /></span>
                        <?=_lang('Категории')?>
                    </a>
                    <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Категории')?></span>
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
                            <span class="iconed"><img src="/tpl/main/icon-menu/chanal.png" alt="icon" /></span>
                            <?=_lang('Channels')?>
                        </a>
                        <span class="tooltip-item" style="margin-left:-15px"><?=_lang('Channels')?></span>
                    </li>
                <? } ?>

        <?
        if (get_option('showblog', 1) == 1) {
            echo '<li class="lihead';
            if ($_SERVER['REQUEST_URI'] == '/blog/') {
                echo ' item-activ';
            }
            echo '"><a href="' . site_url() . blog . '/"><span class="iconed"><img src="/tpl/main/icon-menu/blog.png" alt="icon" /></span>' . _lang('Blog') . '</a><span class="tooltip-item" style="margin-left:-15px">' . _lang('Blog') . '</span></li>';;
        }

        echo '</ul></div>';
        /* End of menu */
        ?>
        <?php
        if (is_user()) {
            /* start my playlists */
            ?>
            <hr/>
            <div class='relative'>
                <h4 class="li-heading li-heading-iconed">
                    <a style="color: #797E89;"
                       href="https://youinroll.com/me?sk=playlists"
                       title="<?php echo _("View all"); ?>">
                        <h5><?= _lang('My playlists') ?></h5></a>

                </h4>
                <div class="sidebar-nav blc">
                    <ul id="playlistItems">
                        <i id="playlistDropdown" class="material-icons" type="button"
                           data-page="1" title="<?= _lang('Expand'); ?>">
                            &#xe313;
                        </i>
                        <i id="playlistMinimize" class="material-icons" title="<?= _lang('Minimize'); ?>">
                            &#xe316;
                        </i>
                    </ul>
                </div>
            </div>
            <hr/>
            <?
            /* end my playlists */
            /* start my albums */
            /* $albums = $cachedb->get_results("SELECT id, title FROM ".DB_PREFIX."playlists where owner= '".user_id()."' and picture not in ('[likes]','[history]','[later]') and ptype > 1 order by title asc limit 0,100");
            if($albums) {
            foreach ($albums as $play) {
            echo '<li>
            <a href="'.playlist_url($play->id, $play->title).'" original-title="'.$play->title.'" title="'.$play->title.'"><i class="material-icons">&#xE438;</i>
            '._html(_cut($play->title, 24)).'
            </a>
            </li>';
            }
            }
            echo '</ul>
            </div>'; */

            /* end my albums */
            /* start my  subscriptions */
            ?>
            <div class='relative'>
                <h4 class="li-heading">
                    <a style="color: #797E89;"
                       href="<?php echo profile_url(user_id(), user_name()); ?>?sk=subscribed"
                       title="<?php echo _("View all"); ?>"><?php echo _lang('My subscriptions'); ?> </a>
                </h4>
                <div class="sidebar-nav blc">
                    <ul id="subscribtionItems">
                    </ul>
                    <div id="subscriptionDropdown" class="" type="button" data-page="1">
                        <i class="material-icons" type="button"
                           data-page="1" title="<?= _lang('Expand'); ?>">
                            &#xe313;
                        </i><span><?= _lang('Expand'); ?></span>
                    </div>
                    <div id="subscriptionMinimize" class="" type="button">
                        <i class="material-icons" title="<?= _lang('Minimize'); ?>">
                            &#xe316;
                        </i><span><?= _lang('Minimize'); ?></span>
                    </div>
                </div>
            </div>
            <hr/>
            <?php
            /* end subscriptions */
            echo '<div id="endSidebar"><h4 class="li-heading">' . _lang('Другие возможности') . '</h4>';
            echo '<div class="sidebar-nav blc"><ul>';

            if (!has_premium()) {
                echo '<li class="lihead';
                if ($_SERVER['REQUEST_URI'] == '/payment') {
                    echo ' item-activ';
                }
                echo '"><a href="/payment"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span> ' . _lang("Get Premium") . '</a><span class="tooltip-item" style="margin-left:-8px">' . _lang("Get Premium") . '</span></li>';
            }

            echo '<li class="lihead';
            if ($_SERVER['REQUEST_URI'] == '/dashboard?sk=edit') {
                echo ' item-activ';
            }
            echo '"><a href="/dashboard?sk=edit"><span class="iconed"><img src="/tpl/main/icon-menu/settings.svg" alt="icon" /></span> ' . _lang("My Settings") . '</a><span class="tooltip-item" style="margin-left:-8px">' . _lang("My Settings") . '</span></li>';

            echo "</ul></div></div>";
            do_action('user-sidebar-end');
        } else {
            echo '<div class="blc mtop20 odet fs300" id="guestButton">';
            echo _lang('Share videos, music and pictures, follow friends and keep track of what you enjoy!');
            echo '<p class="small mright20 mleft10"><a href="/login" class="btn-primary1 btn-small btn-block mtop20">		
		' . _lang("Join us") . '</a> </p>';
            echo '</div>';
            echo '<div id="endSidebar"><h4 class="li-heading">' . _lang('Другие возможности') . '</h4>';
            echo '<div class="sidebar-nav blc"><ul>';

            if (!has_premium()) {
                echo '<li class="lihead';
                if ($_SERVER['REQUEST_URI'] == '/payment') {
                    echo ' item-activ';
                }
                echo '"><a href="/payment"><span class="iconed"><img src="/tpl/main/icon-menu/zvez.png" alt="icon" /></span> ' . _lang("Get Premium") . '</a><span class="tooltip-item" style="margin-left:-8px">' . _lang("Get Premium") . '</span></li>';
            }

            echo '</ul></div></div>';
            do_action('guest-sidebar');
        }
        do_action('sidebar-end');
        echo _ad('0', 'sidebar-end')
        ?>
        <div class="blc">&nbsp;</div>
    </div>
</div>