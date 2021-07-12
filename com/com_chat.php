<?php
/**
 * @file Контроллер страницы категорий
 * @author Ron_Tayler
 * @copyright 07.06.2021 YouInRoll.com
 */

/** @var ezSQL_mysqli $db */

$tpl_data = [];

the_header();
the_sidebar();
include DIR_TPL.'/main_n/widgets/streamchat.php';
the_footer();

