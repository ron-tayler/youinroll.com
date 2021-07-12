<ul class="nav nav-tabs nav-tabs-line hidden-md hidden-lg visible-xs visible-sm" id="myTabs" role="tablist">
    <li class="active"><a data-toggle="tab" href="#DashContent" role="tab"> <?php echo _lang('Dashboard'); ?></a></li>
    <li class=""><a data-toggle="tab" href="#DashSidebar" role="tab"><?php echo _lang('Menu'); ?></a></li>
</ul>
<script>
    $(document).ready(function () {
        if ($(window).width() < 972) {
            $('#DashContent').addClass('tab-pane active');
            $('#DashSidebar').addClass('tab-pane');
            $('#myTab a,#myTabs a').click(function (e) {
                e.preventDefault();
                $(this).tab('show');
            });
        }
    });
</script>
<div id="theHolder" class="row tab-content">

    <div id="DashContent" class="col-md-10 col-xs-12 isBoxed">
        <div class="row odet">
            <?php
            if (_get('msg')) {
                echo '<div class="msg-info">' . toDb(_get('msg')) . '</div>';
            }
            if (isset($msg)) {
                echo $msg;
            }
            do_action('dash-top');
            if ((_get('sk') == "edit") || isset($_POST['changeavatar']) || isset($_POST['changecover']) || isset($_POST['changeuser'])) {
                include_once(TPL . '/profile/edit.php');


                if (get_option('ffa', '0') == 1) {
                    $uphandler = site_url() . 'lib/upload_pl_ffmpeg.php?pvo=' . $token;
                } else {
                    $uphandler = site_url() . 'lib/upload_pl.php?pvo=' . $token;
                }


            } elseif (_get('sk') == "activity") { ?>
                <div class="row odet">
                    <div class="panel panel-transparent">
                        <div class="panel-heading">
                            <h4 class="panel-title"><?php echo _lang("Activity on your media"); ?></h4>
                        </div>
                        <div class="panel-body">
                            <?php
                            //Latest notifications
                            $count = $db->get_row("Select count(*) as nr from vibe_activity where (type not in (8,9) and vibe_activity.object in (select id from vibe_videos where user_id ='" . user_id() . "' ) ) or (type in (8,9) and vibe_activity.object in (select id from vibe_images where user_id ='" . user_id() . "' ) ) and user <> '" . user_id() . "'");

                            if ($count) {
                                if ($count->nr > 0) {
                                    echo '<p style="line-height:15px;"><span class="badge" style="font-size:16px">' . $count->nr . ' <i class="icon material-icons" style="font-size:16px">&#xE7F7;</i></span></p>';
                                    $a = new pagination;
                                    $ps = site_url() . 'dashboard/?sk=activity&p=';
                                    $a->set_current(this_page());
                                    $a->set_first_page(true);
                                    $a->set_pages_items(7);
                                    $a->set_per_page(bpp());
                                    $a->set_values($count->nr);
                                    $vq = "Select " . DB_PREFIX . "activity.*, " . DB_PREFIX . "users.avatar," . DB_PREFIX . "users.id as pid, " . DB_PREFIX . "users.name from " . DB_PREFIX . "activity left join " . DB_PREFIX . "users on " . DB_PREFIX . "activity.user=" . DB_PREFIX . "users.id where
                                    ((" . DB_PREFIX . "activity.type not in (8,9) and " . DB_PREFIX . "activity.object in (select id from " . DB_PREFIX . "videos where user_id ='" . user_id() . "' ))  or
                                    (" . DB_PREFIX . "activity.type in (8,9) and " . DB_PREFIX . "activity.object in (select id from " . DB_PREFIX . "images where user_id ='" . user_id() . "' ))) and " . DB_PREFIX . "activity.user <> '" . user_id() . "'
                                    ORDER BY " . DB_PREFIX . "activity.id DESC " . this_limit();
                                    $activity = $db->get_results($vq);
                                    if ($activity) {
                                        $did = array();
                                        echo '<div class="row"><ul id="user-timeline" class="timelist user-timeline">';
                                        $licon = array();
                                        $licon["1"] = "icon-heart";
                                        $licon["2"] = "icon-share";
                                        $licon["3"] = "icon-youtube-play";
                                        $licon["4"] = "icon-upload";
                                        $licon["5"] = "icon-rss";
                                        $licon["6"] = "icon-comments";
                                        $licon["7"] = "icon-thumbs-up";
                                        $licon["8"] = "icon-camera";
                                        $licon["9"] = "icon-star";
                                        $lback = array();
                                        $lback["1"] = $lback["9"] = "bg-smooth";
                                        $lback["2"] = "bg-success";
                                        $lback["3"] = "bg-flat";
                                        $lback["4"] = $lback["8"] = "bg-default";
                                        $lback["5"] = "bg-default";
                                        $lback["6"] = "bg-info";
                                        $lback["7"] = "bg-smooth";
                                        foreach ($activity as $buzz) {
                                            $did = get_activity($buzz);
                                            if (isset($did["what"]) && !nullval($did["what"])) {
                                                $time_ago = time_ago($buzz->date);
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
                                                echo '
                                                <li class="cul-' . $buzz->type . ' t-item">
                                                <div class="user-timeline-time">' . $time_ago . '</div>
                                                <i class="icon ' . $licon[$buzz->type] . ' user-timeline-icon ' . $lback[$buzz->type] . '"></i>
                                                <div class="user-timeline-content">
                                                <p><a href="' . profile_url($buzz->pid, $buzz->name) . '">' . _html($buzz->name) . '</a>  ' . $did["what"] . '</p>
                                                ';
                                                if (isset($did["content"]) && !nullval($did["content"])) {
                                                    echo '<div class="timeline-media">' . $did["content"] . '</div>';
                                                }
                                                echo '</div></li>';
                                                unset($did);
                                            }
                                        }
                                        echo '</ul><br style="clear:both;"/></div>';
                                    }
                                    $a->show_pages($ps);
                                } else {
                                    echo '<p>' . _lang("No activity on your media yet") . '</p>';
                                }
                            } else {
                                echo '<p>' . _lang("No activity yet") . '</p>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <?php
            } else {
                //Frontpage
                if (get_option('allowpremium') == 1) {
                    if (!is_empty(premium_upto())) {
                        if (new DateTime() > new DateTime(premium_upto())) {
                            echo '<div class="block isBoxed msg-content msg-warning"> <a href="' . site_url() . 'payment"> <i class="material-icons">&#xE8D0;</i> ' . _lang("Premium expired on") . ' ' . premium_upto() . ' </a></div>';
                        } else {
                            echo '<div class="block isBoxed msg-content msg-win"> <a href="' . site_url() . 'payment"> <i class="material-icons">&#xE8D0;</i> ' . _lang("You are a premium member until") . ' ' . premium_upto() . ' </a></div>';

                        }
                    } elseif (is_empty(premium_upto()) && !is_moderator()) {
                        echo '<div class="block isBoxed msg-content msg-hint"> <a href="' . site_url() . 'payment"><i class="material-icons">&#xE8D0;</i> ' . _lang("Why not try premium?") . '</a></div>';
                    }
                }

                $playlists = array([
                    'id'=>history_playlist(),'header'=>_lang("Watch it again")
                ],[
                    'id'=>likes_playlist(),'header'=>_lang("You've enjoyed this")
                ],[
                    'id'=>later_playlist(),'header'=>_lang("You wanted to check this")
                ]);
                foreach ($playlists as $playlist) {
                    echo '<h2>'.$playlist['header'].'</h2>';
                    $vq = sprintf(str_replace('PREFIX_',DB_PREFIX,"
                        SELECT
                            v.id,
                            v.media,
                            v.title,
                            v.user_id,
                            v.thumb,
                            v.views,
                            v.liked,
                            v.duration,
                            v.nsfw, 
                            u.group_id, 
                            u.name AS owner,
                            u.avatar AS avatar
                        FROM PREFIX_playlist_data AS pd
                        LEFT JOIN PREFIX_videos AS v
                            ON pd.video_id = v.id
                        LEFT JOIN PREFIX_users  AS u
                            ON v.user_id = u.id
                        WHERE pd.playlist =  '%s'
                        ORDER BY pd.id DESC ".this_offset(bpp())),
                        $playlist['id']
                    );
                    include(TPL . '/video-carousel.php');
                }

                echo '<div class="block full text-center mtop20 mbot10">
                <a class="btn btn-default" href="' . profile_url(user_id(), user_name()) . '"> ' . _lang("Go to profile") . ' </a>
                </div>
                ';
            }
            do_action('dash-bottom'); ?>
        </div>
        <?php do_action('dashboard-bottom'); ?>
    </div>
<link href="https://fonts.googleapis.com/icon?family=Material+Icons"
      rel="stylesheet">
<style>

.nav-item {
display: flex;
  vertical-align: middle;
}

.nav-ccc {
display: block; float: left;
}

.nav-text {
padding-left: 6px;
display: block; float: right;
}

</style>
    <div id="DashSidebar" class="col-md-2 col-xs-12">
        <?php do_action('dashSide-top'); ?>
        <div class="nav-tabs-vertical" style="min-width:250px">
            <ul class="nav nav-tabs nav-tabs-line">
             <!--   <li class=""><a href="<?php echo site_url(); ?>studio/"><i
                                class="icon icon-hashtag"></i><?php echo _lang("Overview"); ?></a></li>
                <li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=activity"><i class="material-icons">&#xE7F7;</i><?php echo _lang("Activities"); ?></a></li>
                <li class=""><a href="<?php echo site_url() . me; ?>?sk=subscriptions"><i class="material-icons">&#xE8A1;</i><?php echo _lang("Payments"); ?></a></li>
                <li class=""><a href="<?php echo site_url(); ?>dashboard/?sk=edit"><i class="icon icon-cogs"></i><?php echo _lang("Channel Settings"); ?></a></li>
                <li class=""><a href="<?php echo site_url(); ?>lessons"><i class="icon icon-film"></i><?php echo _lang("Studio"); ?></a></li>
                <li class=""><a href="<?php echo site_url() . me; ?>"><i class="icon icon-film"></i><?php echo _lang("Videos"); ?></a></li>
                <li class=""><a href="<?php echo site_url() . me; ?>?sk=playlists"><i class="icon icon-bars"></i><?php echo _lang("Playlists"); ?></a></li>
                <li class=""><a href="<?php echo site_url() . me; ?>?sk=images"><i class="icon icon-camera"></i><?php echo _lang("Images"); ?></a></li>
                <li class=""><a href="<?php echo site_url() . me; ?>?sk=albums"><i class="icon icon-bars"></i><?php echo _lang("Albums"); ?></a></li>-->
                <li class="nav-item"><a href="/me/?sk=likes"><span class="material-icons nav-ccc">thumb_up</span><span class="nav-text">Понравившиеся</span></a></li>
                <li class="nav-item"><a href="/me/?sk=history"><span class="material-icons nav-ccc">history</span><span class="nav-text">История просмотров</span> </a></li>
                <li class="nav-item"><a href="/me/?sk=later"><span class="material-icons nav-ccc">watch_later</span><span class="nav-text">Смотреть позже</span></a></li>
                <li class="nav-item"><a href="/me?sk=playlists"><span class="material-icons nav-ccc">playlist_play</span><span class="nav-text">Менеджер курсов</span></a></li>
         <!--   <li class=""><a href="<?php echo site_url() . me; ?>?sk=music"><i class="icon icon-headphones"></i><?php echo _lang("Music"); ?></a></li> -->
            </ul>
        </div>
        <?php
        do_action('dashSide-bottom'); ?>
        </span>
    </div>
</div>
