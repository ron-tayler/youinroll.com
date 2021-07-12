<?php
/**
 * @file Контроллер страницы категорий
 * @author Ron_Tayler
 * @copyright 07.06.2021 YouInRoll.com
 */

/** @var ezSQL_mysqli $db */

$ts = $_GET['ts']??0;

$sql = "SELECT *,(SELECT count(*) FROM vibe_videos WHERE category=c.cat_id) as videos 
FROM vibe_channels as c WHERE type=1 AND child_of=0 ORDER BY videos DESC LIMIT $ts,100";
$categories = $db->get_results($sql);

$tpl_data = [];
foreach ($categories as $category){
    $cat_data = new stdClass;
    $cat_data->id=$category->cat_id;
    $cat_data->img=$category->picture;
    $cat_data->name=$category->cat_name;
    $cat_data->tags=explode(',',$category->cat_desc);
    $cat_data->videos=$category->videos;
    $tpl_data []= $cat_data;
}

include DIR_TPL.'/main_rtf/main_html.php';
//the_header();
//the_sidebar();
include DIR_TPL.'/main_rtf/categories.php';
the_footer();

