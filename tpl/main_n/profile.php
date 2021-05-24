<?php the_sidebar();

// Canonical url
$canonical = profile_url($profile->id, $profile->name);

do_action('profile-start');
$vd = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr, sum(views) as vnr , sum(liked) as lnr FROM " . DB_PREFIX . "videos where user_id='" . $profile->id . "'");
$imgs = $cachedb->get_row("SELECT count(*) as imgnr, sum(views) as vnr, sum(liked) as lnr FROM " . DB_PREFIX . "images where user_id='" . $profile->id . "'");
$ad = $cachedb->get_row("SELECT count(*) as nr FROM " . DB_PREFIX . "activity where user='" . $profile->id . "'");
$md = $cachedb->get_row("SELECT count(case when pub = 1 then 1 else null end) as nr FROM " . DB_PREFIX . "videos where media > 1 and user_id='" . $profile->id . "'");
?>

    <div id="profile-content" class="row" id='channelBlock'>
        <div id="profile-content" class="col-md-12">
            <?php if (not_empty($profile->cover)) {
                $pcover = (not_empty($profile->cover)) ? thumb_fix($profile->cover) : tpl() . 'images/default-cover.jpg'; ?>
                <div id="profile-cover" class="row"
                     style="height:300px; background-image: url(<?php echo $pcover; ?>); background-size: cover; background-position: center;
                             background-attachment: scroll;">
                </div>
            <?php } ?>
            <div class='channel-info'>
                <div class="channel-head">
                    <img class="channel-image img-circle inline mright20"
                         src="<?= thumb_fix($profile->avatar, true, 130, 130); ?>"
                         data-name="<?= addslashes($profile->name); ?>">
                    <div class="channel-name">
                        <h1><?= _html($profile->name); ?></h1>
                        <div class="channel-subheader row mtop10">
                            <div class="mright20">
                                <strong class="profile-stat-count"><?= u_k(get_subscribers($profile->id)); ?></strong>
                                <span><?= _lang("Subscribers"); ?></span>
                            </div>
                            <div class="mright20">
                                <strong class="profile-stat-count"><?= u_k($vd->nr); ?></strong>
                                <span><?= _lang("Videos"); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='channel-buttons'>
                    <div class="btn-group">
                        <?if(intval($profile->id) !== intval(user_id())){?>

                            <?subscribe_box($profile->id, '', false, 'modal')?>
                            <?subscribe_box($profile->id, 'btn btn-primary tipS mright10', false, 'follow')?>

                        <?}else{?>
                            <?subscribe_box($profile->id)?>
                        <?}?>
                        <div class='last-buttons'>
                            <button class="btn btn-secondary2"><i class="icon-threedot"></i></button>
                            <?php
                                /** @var $db ezSQL_mysqli */
                                $is_sub = $db->get_var('SELECT count(*) as `count` FROM vibe_users_friends WHERE fid='.user_id().' AND uid='.$profile->id)
                            ?>
                            <?if(intval($profile->id) !== intval(user_id()) and (bool)$is_sub){?>
                                <button class="btn btn-secondary2"><i class="icon-ring"></i></button>
                            <?}?>
                        </div>
                    </div>
                </div>
                <div class='channel-buttons-mobile'>
                    <div class="btn-group">
                        <? if (intval($profile->id) !== intval(user_id())) { ?>

                            <?php subscribe_box($profile->id, '', false, 'modal'); ?>
                            <?php subscribe_box($profile->id, 'btn btn-primary tipS mleft10', false, 'follow'); ?>

                        <? } else { ?>
                            <?php subscribe_box($profile->id); ?>
                        <? } ?>
                        <!-- <div class='last-buttons'>
                        <button class="btn btn-secondary2"><i class="icon-threedot"></i></button>
                        <button class="btn btn-secondary2"><i class="icon-ring"></i></button>
                        </div> -->
                    </div>
                </div>
            </div>
            <style>
                .channel-info{
                    display: inherit;
                }
                .channel-head{
                    float: left;
                    display: inline-flex;
                }
                .channel-buttons{
                    float: right;
                }
                .channel-buttons-mobile{
                    float: right;
                }
                .channel-buttons .btn-group{
                    display: inline-flex;
                }
                .channel-buttons a{
                    height: fit-content;
                }
                .channel-buttons-mobile .btn-group{
                    display: inline-flex;
                }
            </style>
            <div id="profile-nav" class="red-nav">
                <ul>
                    <li class="<?= aTab("profile"); ?>"><a
                                href="<?= $canonical; ?>"><?= _lang("Channel"); ?></a></li>
                    <li class="<?= aTab("about"); ?>"><a
                                href="<?= $canonical; ?>?sk=about"><?= _lang("About"); ?></a></li>

                    <li class="<?= aTab("videos"); ?>"><a
                                href="<?= $canonical; ?>?sk=videos"><?= _lang("Videos"); ?></a></li>
                    <?
                    $profile->conferences = $db->get_row('SELECT id,name FROM ' . DB_PREFIX . 'conferences WHERE moderator_id = ' . toDb($profile->id) . ' AND type = "stream" GROUP BY started_at ORDER BY started_at DESC LIMIT 0,1');
                    ?>
                    <? if ($profile->conferences) { ?>

                    <? } ?>
                    <?php if (get_option('imagesmenu', '1') == 1) { ?>
                        <li class="<?= aTab("images"); ?>"><a
                                    href="<?= $canonical; ?>?sk=images"><?= _lang("Images"); ?></a></li>
                    <?php } ?>
                    <?php if (get_option('musicmenu', '1') == 1) { ?>
                        <li class="<?= aTab("music"); ?>"><a
                                    href="<?= $canonical; ?>?sk=music"><?= _lang("Music"); ?></a></li>
                    <?php } ?>
                    <li class="<?= aTab("timeline"); ?>"><a
                                href="<?= $canonical; ?>?sk=timeline"><?= _lang("Расписание"); ?></a></li>
                </ul>

                <div id="channel-subheader-viewer" class="channel-subheader mtop10">
                    <? if (u_k($vd->vnr) !== null) { ?>
                        <div class="mright20">
                            <strong class="profile-stat-count"><?= u_k($profile->views); ?></strong>
                            <span><?= _lang("Media views"); ?></span>
                        </div>
                    <? } ?>
                    <? if (u_k($vd->lnr) !== null) { ?>
                        <div class="mright20">
                            <strong class="profile-stat-count"><?= u_k($vd->lnr); ?></strong>
                            <span><?= _lang("Likes"); ?></span>
                        </div>
                    <? } ?>
                </div>

            </div>
            <div id="panel-<?php echo $active; ?>" class="panel-body">
                <?php
                switch (_get('sk')) {
                    case 'subscribed':
                        $count = $cachedb->get_row("Select count(*) as nr from " . DB_PREFIX . "users where " . DB_PREFIX . "users.id in ( select uid from " . DB_PREFIX . "users_friends where fid ='" . $profile->id . "')");
                        $vq = "select id,avatar,name,lastnoty from " . DB_PREFIX . "users where " . DB_PREFIX . "users.id in ( select uid from " . DB_PREFIX . "users_friends where fid ='" . $profile->id . "') ORDER BY " . DB_PREFIX . "users.views DESC " . this_offset(18);
                        include_once(TPL . '/profile/users.php');
                        $pagestructure = $canonical . '?sk=subscribed&p=';
                        $bp = bpp();
                        break;

                    case 'comments':
                        include_once(TPL . '/profile/user_coms.php');
                        break;

                    case 'images':
                        include_once(TPL . '/profile/user_images.php');
                        break;

                    case 'subscribers':
                        $count = $cachedb->get_row("Select count(*) as nr from " . DB_PREFIX . "users where " . DB_PREFIX . "users.id in ( select fid from " . DB_PREFIX . "users_friends where uid ='" . $profile->id . "')");
                        $vq = "select id,avatar,name,lastnoty from " . DB_PREFIX . "users where " . DB_PREFIX . "users.id in ( select fid from " . DB_PREFIX . "users_friends where uid ='" . $profile->id . "') ORDER BY " . DB_PREFIX . "users.views DESC " . this_offset(18);
                        include_once(TPL . '/profile/users.php');
                        $pagestructure = $canonical . '?sk=subscribers&p=';
                        $bp = bpp();
                        break;

                    case 'activity':
                        $sort = (isset($_GET['sort']) && (intval($_GET['sort']) > 0)) ? "and type='" . intval($_GET['sort']) . "'" : "";
                        $count = $cachedb->get_row("Select count(*) as nr from " . DB_PREFIX . "activity where user='" . $profile->id . "' " . $sort);
                        $vq = "Select * from " . DB_PREFIX . "activity where user='" . $profile->id . "' " . $sort . " ORDER BY id DESC " . this_offset(45);
                        include_once(TPL . '/profile/activity.php');
                        break;

                    case 'videos':
                        $catParams = isset($_GET['cat']) ? '&cat=' . $_GET['cat'] : '';
                        $pagestructure = $canonical . '?sk=videos' . $catParams . '&p=';
                        $canonical = $canonical . '?sk=videos' . $catParams;
                        $bp = bpp();
                        include_once(TPL . '/profile/user_videos.php');
                        break;

                    case 'collections':
                        $pagestructure = $canonical . '?sk=collections&p=';
                        $canonical = $canonical . '?sk=collections';
                        include_once(TPL . '/profile/user_collections.php');
                        break;

                    case 'timeline':
                        $pagestructure = $canonical . '?sk=timeline&p=';
                        $canonical = $canonical . '?sk=timeline';
                        include_once(TPL . '/profile/user_timeline.php');
                        break;

                    case 'about':
                        $pagestructure = $canonical . '?sk=about&p=';
                        $canonical = $canonical . '?sk=about';
                        include_once(TPL . '/profile/user_about.php');
                        break;

                    case 'music':
                        $pagestructure = $canonical . '?sk=music&p=';
                        $canonical = $canonical . '?sk=music';
                        include_once(TPL . '/profile/user_music.php');
                        break;

                    default:
                        $pagestructure = $canonical . '?p=';
                        include_once(TPL . '/profile/user_profile.php');

                        if (
                            (int)$profile->group_id === 16
                            || (int)$profile->group_id === 2
                            || (int)$profile->group_id === 1
                            || (int)$profile->group_id === 18
                            || (int)$profile->group_id === 19
                            || (int)$profile->group_id === 11
                            || (int)$profile->group_id === 14
                            || (int)$profile->group_id === 12
                        ) {
                            include_once(TPL . '/profile/courses.php');
                        }
                        break;
                }
                if (isset($bp) && $pagestructure) {
                    $a = new pagination;
                    $a->set_current(this_page());
                    $a->set_first_page(true);
                    $a->set_pages_items(7);
                    $a->set_per_page($bp);
                    $a->set_values($count->nr);
                    $a->show_pages($pagestructure);
                }
                ?>
                <? if (
                    (int)$profile->group_id === 11
                    || (int)$profile->group_id === 14
                    || (int)$profile->group_id === 15
                ) { ?>
                    <div id='channelAuthors' class="panel-body">
                        <h4 class="loop-heading">
                            <span><?= _lang("Authors of channel") ?></span>
                        </h4>
                        <? /*<div class="loop-content phpvibe-video-list vTrends bottom20">
                <?foreach (range(0, 3) as $value) { ?>
                  <div id="author-'.$video->id.'" class="author-channel item">
                      <img class='author-image <?= ($value === 0) ? 'online' : ''; ?>' src="https://via.placeholder.com/150" />
                      <? if($value === 0) { ?>
                        <div class="bubble">
                          <span class="bubble-outer-dot">
                          <?=_lang('On air');?>
                          </span>
                        </div>
                      <?}?>
                      <h3>Test Author</h3>
                  </div>
                <? }?>
              </div>*/ ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>

    <script>
        window.onscroll = function () {
            myFunction()
        };

        // Get the header
        var header = document.getElementById("profile-nav");

        // Get the offset position of the navbar
        var sticky = header.offsetTop;

        // Add the sticky class to the header when you reach its scroll position. Remove "sticky" when you leave the scroll position
        function myFunction() {
            if (window.pageYOffset > sticky) {
                header.classList.add("sticky");
            } else {
                header.classList.remove("sticky");
            }
        }
    </script>

<?
if ((int)$profile->id !== (int)user_id()) {
    include_once(TPL . '/modals/subscribe.php');
}
?>
