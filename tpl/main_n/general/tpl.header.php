<?php
register_style('phpvibe');

register_style('mobile_corrections');

if(!is_home() && !is_video()) {
  register_style('more');
}

register_style('bootstrap.min');

if(!is_home()) {
  register_style('jssocials');
  register_style('playerads');	
}

if(!is_video()) {
  register_style('owl');
}

register_style('https://fonts.googleapis.com/css?family=Material+Icons:300,400,500');

if(not_empty(get_option('rtl_langs',''))) {
  //Rtl	
  $lg = @explode(",",get_option('rtl_langs'));

  if(in_array(current_lang(),$lg)) {	
    register_style('rtl');
  }
}
global $page;
?>

<!doctype html>
<html prefix="og: http://ogp.me/ns#" dir="ltr" lang="en-US">

<head>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8">
    <title><?=seo_title()?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <base href="<?=site_url()?>" />
    <meta name="description" content="<?=seo_desc()?>">
    <meta name="generator" content="YouInRoll" />
    <?=render_styles(0)?>
    <link rel="alternate" type="application/rss+xml"
        title="<?=get_option('site-logo-text')?> <?=_lang('All Media Feed')?>" href="<?=site_url()?>feed/" />
    <link rel="alternate" type="application/rss+xml" title="<?=get_option('site-logo-text')?> <?=_lang('Video Feed')?>"
        href="<?=site_url()?>feed?m=1" />
    <link rel="alternate" type="application/rss+xml" title="<?=get_option('site-logo-text')?> <?=_lang('Music Feed')?>"
        href="<?=site_url()?>feed?m=2" />
    <link rel="alternate" type="application/rss+xml" title="<?=get_option('site-logo-text')?> <?=_lang('Images Feed')?>"
        href="<?=site_url()?>feed?m=3" />
    <link rel="canonical" href="<?=canonical()?>" />
    <meta property="og:site_name" content="<?=get_option('site-logo-text')?>" />
    <meta property="fb:app_id" content="<?=Fb_Key?>" />
    <meta property="og:url" content="<?=canonical()?>" />
    <link rel="stylesheet" href="tpl/main/fonts/fonts.css" type="text/css">
    <?=$additionalCss?>
    <link rel="stylesheet" href="tpl/main_n/fonts/fonts.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="tpl/main_n/styles/calendar.css" type="text/css">
    <?=$additionalCss?>
    <?if(com() == "profile") {?>
    <link rel="stylesheet" href="tpl/main_n/styles/profile-page.css" type="text/css">
    <?}?>

    <link rel="stylesheet" href="tpl/main_n/styles/sidebar.css" type="text/css">
    <link rel="apple-touch-icon" sizes="180x180" href="<?=site_url()?>lib/favicos/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="<?=site_url()?>lib/favicos/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="<?=site_url()?>lib/favicos/favicon-16x16.png">
    <link rel="manifest" href="<?=site_url()?>lib/favicos/site.webmanifest">
    <link rel="mask-icon" href="<?=site_url()?>lib/favicos/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="shortcut icon" href="<?=site_url()?>lib/favicos/favicon.ico">
    <meta name="msapplication-TileColor" content="#2b5797">
    <meta name="msapplication-config" content="<?=site_url()?>lib/favicos/browserconfig.xml">
    <meta name="theme-color" content="#ffffff">

    <?if(is_video()) {
    global $video,$qualities;
    if(isset($video) && $video) {
      if(isset($qualities) && !empty($qualities)){
      $max = max(array_keys($qualities));
        if(isset($qualities[$max])) {?>
    <meta property="og:video" content="<?=$qualities[$max]?>">
    <?}?>

    <?} else {?>
    <meta property="og:video" content="<?=$video->source?>">
    <?}?>

    <meta property="og:image" content="<?=thumb_fix($video->thumb)?>" />
    <meta property="og:description" content="<?=seo_desc()?>" />
    <meta property="og:title" content="<?=_html($video->title)?>" />
    <meta property="og:type" content="video.other" />
    <meta itemprop="name" content="<?=_html($video->title)?>">
    <meta itemprop="description" content="<?=seo_desc()?>">
    <meta itemprop="image" content="<?=thumb_fix($video->thumb)?>">
    <meta property="video:duration" content="<?=$video->duration?>">
    <?}}?>
    <?
    if(is_picture()) {
    global $image;
    if(isset($image) && $image) { ?>
    $meta .= '
    <meta property="og:image"
        content="<?=thumb_fix(str_replace('localimage','storage/'.get_option('mediafolder','media'),$image->source), false)?>" />
    <meta property="og:description" content="<?=seo_desc()?>" />
    <meta property="og:title" content="<?=_html($image->title)?>" />
    <meta property="og:type" content="image.gallery" />
    <meta itemprop="name" content="<?=_html($image->title)?>">
    <meta itemprop="description" content="<?=seo_desc()?>">
    <meta itemprop="image"
        content="<?=thumb_fix(str_replace('localimage','storage/'.get_option('mediafolder','media'),$image->source), false)?>">
    <?}}?>
    <?if(com() == profile) {
    global $profile;
    if(isset($profile) && $profile) {?>
    <meta property="og:image" content="<?=thumb_fix($profile->avatar)?>" />
    <meta property="og:description" content="<?=seo_desc()?>" />
    <meta property="og:title" content="<?=_html($profile->name)?>" />
    <?}}?>
    <link rel="stylesheet" type="text/css" media="screen" href="<?=site_url()?>tpl/main/styles/corrections.css" />
    <!-- ok -->
    <?=extra_css()?>
    <link rel="stylesheet" type="text/css" media="screen" href="/tpl/main_n/styles/search.css" />
    <?=players_js()?>
</head>

<body class="body-<?=$page?>">
    <?
$type = 'iconic';

if(is_video() || is_picture() || is_com('conversation') || is_com('stream'))
{
  $type = 'normal';
}
?>

    <div class="fixed-top">
        <div class="row block" style="position:relative;">
            <div class="logo-wrapper">
                <a id="show-sidebar" href="javascript:void(0)" title="<?=_lang('Show sidebar')?>"
                    data-type="<?=$type?>">
                    <div class="hamburger" id="hamburger">
                        <span class="line"></span>
                        <span class="line"></span>
                        <span class="line"></span>
                    </div>
                </a>
                <a href="<?=site_url()?>" title=""
                    class="logo"><?=str_replace("/>"," alt=\"logo\" />",show_logo())?></a>
                <br style="clear:both;" />
            </div>
            <div class="header">

                <div class="searchWidget">
                    <form action="" method="get" id="searchform"
                        <? if(get_option(' youtube-suggest',1)> 0) { echo 'autocomplete="off"'; } ?>>
                        <div class="search-holder">
                            <span class="search-button">
                                <button type="submit">
                                    <i class="search-icon"></i>
                                </button>
                            </span>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control input-lg empty" name="tag" value=""
                                    placeholder="<?=_lang(" Search media")?>">
                            </div>
                            <div></div>
                        </div>

                    </form>
                    <?if(get_option('youtube-suggest',1) > 0) {?>
                    <div id="suggest-results"></div>
                    <?}?>
                </div>
                <?
                if(is_user())
                {

                global $db;

                $messagesCount = 0;

                try {

                  $sql = 'SELECT * FROM '.DB_PREFIX. 'conversations WHERE user_id = '.toDb(user_id());

                  $conferences = $db->get_results($sql);

                  foreach ($conferences as $conference) {

                      $sql = 'SELECT * FROM '.DB_PREFIX. 'messages WHERE user_id <> '.toDb(user_id()). ' AND readed = 0 AND conversation_id = '.toDb($conference->conf_id);

                      $sql2 = $db->get_results($sql);

                      $messagesCount = $messagesCount + count($sql2 ?? []);
                  }


                } catch (\Throwable $th) {
                
                }

                if($messages !== null)
                {
                  $messagesCount = count($messages);
                }
                ?>

                <a href="<?=site_url()?>conversation/0/" id="myInbox" title='<?=_lang('Messages')?>'><i
                        class="icon material-icons">&#xE0C9;</i>
                    <span class="badge badge-danger"><?=$messagesCount?></span>
                </a>
                <?}?>
                <div class="user-quick">
                    <a data-target="#search-now" data-toggle="modal" href="javascript:void(0)" class="top-link"
                        id="show-search"><i class="material-icons">search</i></a>
                    <?if(!is_user()) {?>
                    <a id="openusr" class="btn uav btn-small no-user" href="/login" data-animation="scale-up"
                        role="button" title="<?=_lang('Login')?>">
                        <img title="<?=_lang('Guest')?>" data-name="<?=_lang('Guest')?>"
                            src="/tpl/main/images/man-avatar.svg" class="man-avatar-icon" alt="icon" />
                        Войти</a>
                </div>
                <?} else {?>
                <?if((get_option('upmenu') == 1) ||  is_moderator()) {?>
                <i class="material-icons"></i>
                <a id="uplBtn" href="<?=site_url().share?>" class="top-link" title="<?=_lang('Upload video')?>">
                    <img src="/tpl/main/images/download.svg" class="download-icon" alt="icon" /></a>
                <?}?>

                <a id="notifs" href="<?=site_url()?>dashboard?sk=activity" class="top-link">
                    <img src="/tpl/main/images/notifications.svg" class="notifications-icon" alt="icon" />
                </a>
                <?if(get_option('showusers','1') == 1 ) {?>
                <a id="chnls" class="top-link" href="<?=site_url()?>members/"><img src="/tpl/main/images/chanels.svg"
                        class="chanels-icon" alt="icon" /></a>
                <?}?>
                <a id="openusr" class="btn uav btn-small dropdown-toggle" data-toggle="dropdown" href="#"
                    aria-expanded="false" data-animation="scale-up" role="button" title="<?=_lang('Dashboard')?>">
                    <?if (user_name() != '') {?>
                    <img data-name="<?=addslashes(user_name())?>" src="<?=thumb_fix(user_avatar(), true, 35,35)?>"
                        alt="logo" />
                    <?} else {?>
                    <img src="/tpl/main/images/man-avatar.svg" class="man-avatar-icon" alt="icon" />
                    <?}?>
                </a>
                <ul class="dropdown-menu dropdown-left" role="menu">
                    <li role="presentation" class="drop-head"><?=group_creative(user_group())?>
                        <a href="<?=profile_url(user_id(), user_name())?>">
                            <?=user_name()?>
                        </a>
                        <? if( !is_empty(premium_upto())) {
                        if (new DateTime() > new DateTime(premium_upto())) {?>
                        <p class="small nomargin">
                            <a href="<?=site_url()?>payment"><?=_lang("Premium expired")?></a>
                        </p>
                        <?}}?>
                    </li>
                    <li class="divider" role="presentation"></li>
                    <?
                      if(get_option('allowpremium') == 1 ) {
                      if( is_empty(premium_upto())) {
                    ?>
                    <li>
                        <a href="<?=site_url()?>payment">
                            <i class="icon material-icons">&#xE8D0;</i>
                            <?=_lang("Get Premium")?>
                        </a>
                    </li>
                    <?}}?>
                    <li class="my-buzz" role="presentation">
                        <a href="<?=site_url()?>dashboard/">
                            <i class="icon material-icons">&#xE031;</i>
                            <?=_lang('Media Studio')?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="<?=site_url()?>dashboard/?sk=edit">
                            <i class="icon material-icons">&#xE8B8;</i>
                            <?=_lang("My Settings")?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="<?=site_url().me?>">
                            <i class="icon material-icons">&#xE04A;</i>
                            <?=_lang("Мои видео")?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="<?=site_url().me?>?sk=music">
                            <i class="icon material-icons">&#xE030;</i>
                            <?=_lang("My Music")?>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="<?=site_url().me?>?sk=images">
                            <i class="icon material-icons">&#xE413;</i>
                            <?=_lang("My Images")?>
                        </a>
                    </li>
                    <?if(is_admin()){?>
                    <li role="presentation">
                        <a href="<?=ADMINCP?>">
                            <i class="icon material-icons">&#xE8A4;</i>
                            <?=_lang("Administration")?>
                        </a>
                    </li>
                    <?}?>
                    <li role="presentation" class="drop-footer"><a href="<?=site_url()?> index.php?action=logout"><i
                                class="icon material-icons">&#xE14C;</i><?=_lang("Logout")?></a></li>
                </ul>
            </div>
            <?}?>
        </div>
    </div>
    </div>
    <div id="wrapper" class="<?=wrapper_class()?> aside">
        <div class="row block page p-<?=$page?>">