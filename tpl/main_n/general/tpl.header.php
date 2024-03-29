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
    <link rel="stylesheet" href="tpl/main_n/fonts/fonts.css" type="text/css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <?=$additionalCss?>
    <?if(com() == "profile") {?>
    <link rel="stylesheet" href="tpl/main_n/styles/profile-page.css" type="text/css">
    <?}?>
    <link rel="stylesheet" href="tpl/main_n/styles/sidebar.css?v=300620211450" type="text/css">
    <link rel="stylesheet" href="tpl/main_n/styles/sidebar-mobile.css?v=250620211623" type="text/css">

    <script src="/tpl/main_n/styles/js/jquery.js"></script>
    <script src="/tpl/main_rtf/js/sidebar.js?v=125"></script>

    <link rel="manifest" href="manifest.json">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="application-name" content="YouInRoll">
    <meta name="apple-mobile-web-app-title" content="YouInRoll">
    <meta name="theme-color" content="#fff">
    <meta name="msapplication-navbutton-color" content="#fff">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="msapplication-starturl" content="/">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" sizes="16x16" href="lib/favicos/favicon(16x16).png">
    <link rel="apple-touch-icon" sizes="16x16" href="lib/favicos/favicon(16x16).png">
    <link rel="icon" sizes="32x32" href="lib/favicos/favicon(32x32).png">
    <link rel="apple-touch-icon" sizes="32x32" href="lib/favicos/favicon(32x32).png">
    <link rel="icon" sizes="64x64" href="lib/favicos/favicon(64x64).png">
    <link rel="apple-touch-icon" sizes="64x64" href="lib/favicos/favicon(64x64).png">
    <link rel="icon" sizes="192x192" href="/lib/favicos/android-chrome-192x192.png">
    <link rel="apple-touch-icon" sizes="192x192" href="/lib/favicos/android-chrome-192x192.png">
    <link rel="icon" sizes="256x256" href="/lib/favicos/android-chrome-256x256.png">
    <link rel="apple-touch-icon" sizes="256x256" href="/lib/favicos/android-chrome-256x256.png">

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
    <link rel="stylesheet" type="text/css" media="screen" href="/tpl/main/styles/corrections.css?v=260520211540" />
    <!-- ok -->
    <?=extra_css()?>
    <link rel="stylesheet" type="text/css" media="screen" href="/tpl/main_n/styles/search.css" />
    <?=players_js()?>
</head>

<body data-route="<?=$page?>" class="body-<?=$page?>">
    <?
    $type = 'iconic';

    if(is_video() || is_picture() || is_com('conversation') || is_com('stream'))
    {
        $type = 'normal';
    }
    ?>

    <? include(TPL.'/layouts/navbar.php'); ?>

    <?=the_sidebar()?>
    <?include(TPL.'/sidebar-mobile.php');?>
    <div id="wrapper" class="<?=wrapper_class()?> aside <?= ($page === 'stream') ? 'big-pic' : '' ?>">
        <div class="row block page p-<?=$page?>">
