<?php the_sidebar();?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/tiny-slider.css">

<style>

.price-columns {
    display:flex;
    margin-top: 20px;
    flex-direction:row;
    justify-content:center;
}

#carousel {
    display:none;
}


.price-promo-center {
    margin-top: 20px;
    display: flex;
    justify-content:center;
    flex-wrap: wrap;
}

.price-promo-text {
    margin-top: 20px;
    display: flex;
    justify-content:center;
    text-align:center;
}

.promo-block {
    text-align: center;
    width: 25%;
}

.promo-row {
    display: flex;
    flex-flow: row;
    justify-content: center;
}

.promo-block > h2 {
    margin-top: 2px;
    color: black;
    font-weigth: bold;
}

.promo-block > h3 {
    margin-top: 2px;
}

.pricing-header {
    text-align:center;
}

.btn-buy {
    width: 25%;
    border-radius: 0px;
    margin: 0 auto;
    font-weight: bold;
}

.center {
    display: flex;
    justify-content: center;
    flex-flow: column;
}
.hr {
    height: 1px;
    background: #00000042;
    width: 77%;
}
@media only screen and (max-width: 700px) {
    .price-columns {
        display:none;
    }

    #carousel > .tns-item {
        margin: 0 auto;
        display: contents;
    }

    #carousel {
        display:flex !important;
        display: -webkit-box;
        -webkit-box-orient: horizontal;
        -webkit-box-pack: center;   
        margin-top: 20px;
        flex-direction:row;
        justify-content:center;
    }

    .promo-row {
        display:none;
    }

    .btn-buy {
        width: 80%;
    }
}

</style>
<div class="center">
<div class="pricing-header text-center">
    <h1 style='font-weight:bold; color:black;'>YouinRoll Premium</h1>
    <h2 class="lead">Оформите подписку Premium и получите неограниченный доступ к сервисам YouinRoll.</h2>
</div>
<div class="price-promo-center">

    <? if(is_user()) { ?>

        <button class="btn-buy btn btn-primary btn-lg btn-block" data-toggle="modal" data-target="#SubscribeModal">Продолжить</a>
    
    <? } else { ?>
        <a class="btn-buy btn btn-primary btn-lg btn-block" href="/login">Продолжить</a>  
    <?}?>
    <br>

</div>
<div id='carousel'>
    <div class='item'>
        <div class='promo-block'>
            <div class="rounded-image-promo">
                <img id="img" class="style-scope yt-img-shadow" alt="" width="84" height="84" src="storage/media/playicon.png">
            </div>
            <h2>Неограниченный доступ к обучающим видео и курсам.</h2>
            <h4>Вы можете просматривать видеоконтент других участников и делиться своими работами.</h4>
        </div>
    </div>
    <div class='item'>
        <div class='promo-block'>
            <div class="rounded-image-promo">
            <img id="img" class="style-scope yt-img-shadow" alt="" width="84" height="84" src="storage/media/usericon.png">
            </div>
            <h2>Личный кабинет</h2>
            <h4>многофункциональный личный кабинет преподавателя или учебного заведения, где можно проводить онлайн занятия, неограниченно по времени, загружать домашние задания и учебные материалы.</h4>
        </div>
    </div>
    <div class='item'>
        <div class='promo-block'>
            <div class="rounded-image-promo">
            <img id="img" class="style-scope yt-img-shadow" alt="" width="84" height="84" src="storage/media/messengericon.png">
            </div>
            <h2>Мессенджер для учебных целей.</h2>
            <h4>больше не нужно смешивать личную информацию с учебными материалами. Теперь для этого есть отдельный мессенджер.</h4>
        </div>
    </div>
    <div class='item'>
        <div class='promo-block'>
            <div class="rounded-image-promo">
            <img id="img" class="style-scope yt-img-shadow" alt="" width="84" height="84" src="storage/media/videoicon.png">
            </div>
            <h2>Видеосвязь для онлайн занятий и лекций.</h2>
            <h4>Многопользовательская  видеосвязь с удобными функциями и настройками для преподавателя.</h4>
        </div>
    </div>
    <div class='item'>
        <div class='promo-block'>
            <div class="rounded-image-promo">
            <img id="img" class="style-scope yt-img-shadow" alt="" width="84" height="84" src="storage/media/musicicon.png">
            </div>
            <h2>Аудиосервис.</h2>
            <h4>Коллекция жанров и исполнителей со всего мира. Настройся на учебу с любимой и вдохновляющей музыкой. Создай свой уникальный плейлист.</h4>
        </div>
    </div>
</div>
<div class="price-columns">
    <div class='promo-block'>
        <div class="rounded-image-promo">
            <img id="img" class="style-scope yt-img-shadow" alt="" width="84" src="storage/media/playicon.png">
        </div>
        <h2>Неограниченный доступ к обучающим видео и курсам.</h2>
        <h4>Вы можете просматривать видеоконтент других участников и делиться своими работами.</h4>
    </div>
    <div class='promo-block'>
        <div class="rounded-image-promo">
        <img id="img" class="style-scope yt-img-shadow" alt="" width="84" src="storage/media/usericon.png">
        </div>
        <h2>Личный кабинет</h2>
        <h4>многофункциональный личный кабинет преподавателя или учебного заведения, где можно проводить онлайн занятия, неограниченно по времени, загружать домашние задания и учебные материалы.</h4>
    </div>
    <div class='promo-block'>
        <div class="rounded-image-promo">
        <img id="img" class="style-scope yt-img-shadow" alt="" width="84" src="storage/media/messengericon.png">
        </div>
        <h2>Мессенджер для учебных целей.</h2>
        <h4>больше не нужно смешивать личную информацию с учебными материалами. Теперь для этого есть отдельный мессенджер.</h4>
    </div>
</div>
<hr class='hr' />
<div class='promo-row'>
    <div class='promo-block'>
        <div class="rounded-image-promo">
        <img id="img" class="style-scope yt-img-shadow" alt="" width="84" src="storage/media/videoicon.png">
        </div>
        <h2>Видеосвязь для онлайн занятий и лекций.</h2>
        <h4>Многопользовательская  видеосвязь с удобными функциями и настройками для преподавателя.</h4>
    </div>
    <div class='promo-block'>
        <div class="rounded-image-promo">
        <img id="img" class="style-scope yt-img-shadow" alt="" width="84" src="storage/media/musicicon.png">
        </div>
        <h2>Аудиосервис.</h2>
        <h4>Коллекция жанров и исполнителей со всего мира. Настройся на учебу с любимой и вдохновляющей музыкой. Создай свой уникальный плейлист.</h4>
    </div>
</div>
<!-- <div class='price-promo-text'>
    *За 7 дней до окончания бесплатного пробного периода мы отправим вам напоминание.
    Бесплатный пробный период доступен только новым подписчикам. • Регулярные платежи. • Отказаться от подписки можно в любой момент.   
</div> -->
</div>

<? include(TPL.'/modals/subscribe-complete.php'); ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/tiny-slider/2.9.3/min/tiny-slider.js" integrity="sha512-D/zaRVk05q6ERt1JgWB49kL6tyerY7a94egaVv6ObiGcw3OCEv0tvoPDEsVqL28HyAZhDd483ix8gkWQGDgEKw==" crossorigin="anonymous"></script>
<script>
    tns({
        container: '#carousel',
        items: 1,
        controls: false,
        nav: false,
        center: true,
        autoplayButtonOutput: false,
        autoplay: true,
        autoplaySpeed: 1500
    });
</script>
<? if($type === 'success') {?>
    <h1> Поздравляем, вы теперь премиум </h1>
    <script>
        setTimeout(() => {
            location.href = '/';
        }, 1500);
    </script>
<?}?>