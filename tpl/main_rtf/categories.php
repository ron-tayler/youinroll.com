<?php
/**
 * @file PHP шаблок страницы категорий в новом оформлении
 * @author Ron_Tayler
 * @copyright 07.06.2021 YouInRoll.com
 */

// $tpl_data декларируется в контроллере
/** @var array $tpl_data */
$categories = $tpl_data;
?>
<link rel="stylesheet" href="/tpl/main_rtf/css/categories.css?v=150620211303">
<div class="categories-video-carousel">
    <? include DIR_TPL . '/main_rtf/video-carousel.php' ?>
</div>
<div class="categories-header">
    <h1>Просмотр</h1>
</div>
<div class="categories-box">
    <? foreach ($categories as $category) { ?>
        <div class="category-box">
            <div class="category-img-box">
                <a class="category-img-a" href="https://youinroll.com/channel/category/<?= $category->id ?>">
                    <div class="category-img-tl"></div>
                    <div class="category-img-br"></div>
                    <div class="category-img">
                        <img src="https://youinroll.com/<?= $category->img ?>" alt="<?= $category->id ?>">
                    </div>
                </a>
            </div>
            <div class="category-info">
                <div class="category-name-btn">
                    <div class="category-name">
                        <a href="https://youinroll.com/channel/category/<?= $category->id ?>"
                           title="<?= $category->name ?>"><?= $category->name ?></a>
                    </div>
                    <!--                    <div class="category-btn">-->
                    <!--                        <i class="material-icons">more_vert</i>-->
                    <!--                    </div>-->
                </div>
                <div class="category-sub">
                    <a href="https://youinroll.com/channel/category/<?= $category->id ?>"><?= $category->videos ?> видео
                </div>
                </a>
                <div class="category-tags-box">
                    <div class="category-tags">
                        <? foreach ($category->tags as $tag) { ?>
                            <div class="category-tag">
                                <a href="https://youinroll.com/channel/category/<?= $category->id ?>"
                                   title="<?= $tag ?>"><?= $tag ?></a>
                            </div>
                        <? } ?>
                    </div>
                </div>
            </div>
        </div>
    <? } ?>
</div>
