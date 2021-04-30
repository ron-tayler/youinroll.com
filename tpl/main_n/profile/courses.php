<style>
/* CourseCard */

.card {
    margin-top: 20px;
    margin: 10px 10px 0 0;
    width: 245px;
    position: relative;
    height: 350px;
    box-shadow: 3px 5px 14px rgba(0, 0, 0, 0.4);
    border-radius: 2px;
    overflow: hidden;
    background: #fff;
    transition: box-shadow .3s;
}

.card:hover {
    box-shadow: 5px 7px 21px rgba(0, 0, 0, 0.56);
}

.card:hover .card__header .mask {
    background: rgba(141, 149, 157, .65);
}

.card:hover .card__header .fa-bookmark {
    color: #fff;
}

.card__header {
    height: 300px;
    position: relative;
    overflow: hidden;
}

.card__header .fa-bookmark {
    position: absolute;
    top: 10px;
    right: 10px;
    color: #21b7f8;
    font-size: 24px;
    z-index: 10000;
    transition: color .75s;
}

.card__header .after {
    position: absolute;
    bottom: 0;
    right: 0;
    width: 200%;
    height: 115px;
    z-index: 100;
}

.card__header .after .square {
    float: left;
    position: absolute;
    left: 0;
    width: 50%;
    height: inherit;
    background: #fff;
}

.card__header .after .tri {
    float: left;
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 0;
    height: 0;
    border-style: solid;
    border-width: 0 0 0 260px;
    border-color: transparent transparent transparent #fff;
}

.card__header .thumb {
    width: 100%;
    height: 65%;
    background-repeat: no-repeat;
    background-position: center;
    background-size: cover;
}

.card__header .category {
    position: absolute;
    bottom: 6px;
    left: 6px;
}

.card__header .category span {
    display: inline-block;
    padding: 3px 4px;
    font-size: 11px;
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
    background: #21b7f8;
    border-radius: 2px;
}

.card__header .mask {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(141, 149, 157, 0);
    transition: background .15s;
}

.card__body {
    height: 156px;
    width: 100%;
    position: absolute;
    bottom: 0;
}

.card__body .title {
    position: absolute;
    top: 0;
    left: 0;
    bottom: 0;
    width: 100%;
    margin: 12px 0;
    padding: 0 40px;
    text-align: center;
    color: rgba(0, 0, 0, .87);
    font-size: 14px;
    line-height: 1.5;
    font-weight: bold;
}

.card__body .description {
    position: absolute;
    font-size: 12px;
    padding: 20px;
    text-align: center;
    color: rgba(0, 0, 0, 0.65);
    z-index: 1000;
    opacity: 0;
}

.card__body .subtitle {
    margin: 0;
    position: absolute;
    bottom: 18px;
    left: 0;
    right: 0;
    padding: 0 30px;
    text-align: center;
    font-size: 11px;
    color: rgba(0, 0, 0, 0.4);
    font-weight: 500;
}

.card__footer .share {
    position: absolute;
    bottom: 10px;
    left: -50%;
    float: left;
    margin-left: 10px;
    background: #21b7f8;
    border-radius: 2px;
    border: 0;
    z-index: 1000;
    border-bottom: 2px solid #1593D3;
    outline: 0;
    outline-offset: 0;
    color: #fff;
    opacity: 0;
}

.card__footer .share::first-letter {
    text-transform: uppercase;
}

.card__footer .nb-share {
    position: absolute;
    bottom: 10px;
    left: -50%;
    float: left;
    margin-left: 10px;
    font-size: 12px;
    line-height: 1.7;
    opacity: 0;
    z-index: 999;
}

.card__footer .site {
    position: absolute;
    margin-right: 10px;
    bottom: 10px;
    right: -50%;
    float: right;
    font-size: 12px;
    font-weight: bold;
    line-height: 1.7;
    opacity: 0;
}
</style>
<?
$playlists = $cachedb->get_results("select ".DB_PREFIX."playlists.*, '".$profile->name."' as user from ".DB_PREFIX."playlists WHERE ".DB_PREFIX."playlists.picture not in ('[likes]','[history]','[later]') and owner = '".$profile->id."' order by ptype ASC,views DESC limit 0,5");
if($playlists) { ?>
<div id='channelCourses' class="panel-body">
    <h4 class="loop-heading">
    <span><?=_lang("Courses of channel")?></span>
    </h4>
    <div class="loop-content phpvibe-video-list vTrends bottom20">
    <?
        foreach($playlists as $pl)
        {
            $title = _html(_cut($pl->title, 170));
            $full_title = _html(str_replace("\"", "",$pl->title));			
            $url = playlist_url($pl->id , $pl->title);
            $plays = 0;

            if($pl->ptype === '1' || $pl->ptype === '3' ) {
            ?>
            <a class="card" href='<?=$url?>'>
                <header class="card__header">
                    <div class="thumb" style="background-image: url('<?=thumb_fix($pl->picture, 240, 240)?>')"></div>
                    <div class="category">
                        <span><?=(intval($pl->price) !== 0) ? $pl->price : 'Бесплатно'?></span>
                    </div>
                    <div class="after">
                    <div class="square"></div>
                    <div class="tri"></div>
                    </div>
                </header>
                <div class="card__body">
                    <h1 class="title">
                    <?=$title?>
                    </h1>
                    <h2 class="subtitle">
                    Просмотров: <?=$pl->views?>
                    </h2>
                </div>
            </a>
            
            <?
            }
        }
    ?>
    </div>
</div>
<?}?>