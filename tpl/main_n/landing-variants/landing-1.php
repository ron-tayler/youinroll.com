
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- SEO Meta Tags -->
    <meta name="title" content="<?=$info->title?>">
    <meta name="description" content="<?=$info->description?>">
    <meta name="author" content="h5xde">

    <!-- OG Meta Tags to improve the way the post looks when you share the page on LinkedIn, Facebook, Google+ -->
	<meta property="og:site_name" content="YouInRoll" /> <!-- website name -->
	<meta property="og:site" content="https://youinroll.com" /> <!-- website link -->
	<meta property="og:title" content="<?=$info->title?>"/> <!-- title shown in the actual shared post -->
	<meta property="og:description" content="<?=$info->description?>" /> <!-- description shown in the actual shared post -->
	<meta property="og:image" content="<?=$info->cover?>" /> <!-- image link, make sure it's jpg -->
	<meta property="og:url" content="" /> <!-- where do you want your post to link to -->
	<meta property="og:type" content="site" />

    <!-- Website Title -->
    <title><?=$info->title?></title>
    
    <!-- Styles -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,400i,700&display=swap&subset=latin-ext" rel="stylesheet">
    <link href="/tpl/main_n/landing-styles/css/bootstrap.css" rel="stylesheet">
    <link href="/tpl/main_n/landing-styles/css/fontawesome-all.css" rel="stylesheet">
    <link href="/tpl/main_n/landing-styles/css/swiper.css" rel="stylesheet">
	<link href="/tpl/main_n/landing-styles/css/magnific-popup.css" rel="stylesheet">
	<link href="/tpl/main_n/landing-styles/css/styles.css" rel="stylesheet">
	
	<!-- Favicon  -->
    <link rel="icon" href="https://youinroll.com/lib/favicos/favicon-32x32.png">
</head>
<body data-spy="scroll" data-target=".fixed-top">
    
    <!-- Preloader -->
	<div class="spinner-wrapper">
        <div class="spinner">
            <div class="bounce1"></div>
            <div class="bounce2"></div>
            <div class="bounce3"></div>
        </div>
    </div>
    <!-- end of preloader -->


    <!-- Header -->
    <header id="header" class="header">
        <div class="header-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-xl-5">
                        <div class="text-container">
                            <h1><?=$info->title?></h1>
                            <p class="p-large"><?=$info->description?></p>
                            <button class="btn-solid-lg page-scroll" data-toggle="modal" data-target="#initCourse">Записаться</button>
                        </div> <!-- end of text-container -->
                    </div> <!-- end of col -->
                    <div class="col-lg-6 col-xl-7">
                        <div class="image-container">
                            <div class="img-wrapper">
                                <!-- <img class="img-fluid" src="/tpl/main_n/landing-styles/images/header-software-app.png" alt="alternative"> -->
                                <img class="img-fluid" src="<?=$info->cover?>" alt="alternative">
                            </div> <!-- end of img-wrapper -->
                        </div> <!-- end of image-container -->
                    </div> <!-- end of col -->
                </div> <!-- end of row -->
            </div> <!-- end of container -->
        </div> <!-- end of header-content -->
    </header> <!-- end of header -->
    <svg class="header-frame" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1920 310"><defs><style>.cls-1{fill:#5f4def;}</style></defs><title>header-frame</title><path class="cls-1" d="M0,283.054c22.75,12.98,53.1,15.2,70.635,14.808,92.115-2.077,238.3-79.9,354.895-79.938,59.97-.019,106.17,18.059,141.58,34,47.778,21.511,47.778,21.511,90,38.938,28.418,11.731,85.344,26.169,152.992,17.971,68.127-8.255,115.933-34.963,166.492-67.393,37.467-24.032,148.6-112.008,171.753-127.963,27.951-19.26,87.771-81.155,180.71-89.341,72.016-6.343,105.479,12.388,157.434,35.467,69.73,30.976,168.93,92.28,256.514,89.405,100.992-3.315,140.276-41.7,177-64.9V0.24H0V283.054Z"/></svg>
    <!-- end of header -->

    <!-- Description -->
    <div class="cards-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="above-heading"></div>
                    <h2 class="h2-heading"><?=$info->title?></h2>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">
                    <?
                    $cards = json_decode($info->additionalJSON);

                    foreach($cards as $card) {?>                        

                        <!-- Card -->
                        <div class="card">
                            <div class="card-image">
                                <img class="img-fluid" src="<?=$card->image?>" alt="alternative">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"><?=$card->title?></h4>
                                <p><?=$card->description?></p>
                            </div>
                        </div>
                        <!-- end of card -->
                        
                    <?}?>

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of cards-1 -->
    <!-- end of description -->

    <!-- Features -->
    <div id="features" class="tabs">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="above-heading"><!-- Чему вы научитесь --></div>
                    <h2 class="h2-heading">Чему вы научитесь</h2>
                    <p class="p-heading"></p>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">

                    <!-- Tabs Links -->
                    <ul class="nav nav-tabs flex-nowrap" id="argoTabs" role="tablist">

                        <?
                            $tabs = json_decode($info->additionalJSON2);
                        ?>
                        <? foreach($tabs as $key => $tab) {?>
                        <li class="nav-item">
                            <a class="nav-link <?= ($key === 0) ? 'active' : ''?>" id="nav-tab-<?=$key+1?>" data-toggle="tab" href="#tab-<?=$key+1?>" role="tab" aria-controls="tab-<?=$key+1?>" aria-selected="true"><?=$tab->title?></a>
                        </li>
                        <?}?>
                    </ul>
                    <!-- end of tabs links -->

                    <!-- Tabs Content -->
                    <div class="tab-content" id="argoTabsContent">
                        <?
                            $tabs = json_decode($info->additionalJSON2);
                        ?>
                        <? foreach($tabs as $key => $tab) {?>
                            <!-- Tab -->
                            <div class="tab-pane fade show <?= ($key === 0) ? 'active' : ''?>" id="tab-<?=$key+1?>" role="tabpanel" aria-labelledby="tab-<?=$key+1?>">
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="image-container">
                                            <img class="img-fluid" src="/tpl/main_n/landing-styles/images/features-1.png" alt="alternative">
                                        </div> <!-- end of image-container -->
                                    </div> <!-- end of col -->
                                    <div class="col-lg-6">
                                        <div class="text-container">
                                            <h3><?=$tab->title?></h3>
                                            <p><?=$tab->description?></p>
                                        </div> <!-- end of text-container -->
                                    </div> <!-- end of col -->
                                </div> <!-- end of row -->
                            </div> <!-- end of tab-pane -->
                            <!-- end of tab -->
                            <!-- Tab -->
                        <?}?>
                        
                    </div> <!-- end of tab content -->
                    <!-- end of tabs content -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of tabs -->
    <!-- end of features -->

     <!-- Description -->
     <div class="cards-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="above-heading"></div>
                    <h2 class="h2-heading">Как проходит обучение</h2>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
            <div class="row">
                <div class="col-lg-12">
                    <?
                    $cards = json_decode($info->additionalJSON3);

                    foreach($cards as $key => $card) {?>                        

                        <!-- Card -->
                        <div class="card">
                            <div class="card-image">
                                <img class="img-fluid" src="<?=$card->image?>" alt="alternative">
                            </div>
                            <div class="card-body">
                                <h4 class="card-title"><?=$card->title?></h4>
                                <p><?=$card->description?></p>
                            </div>
                        </div>
                        <!-- end of card -->
                        
                    <?}?>

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of cards-1 -->
    <!-- end of description -->

    <!-- Details Lightboxes -->
    <!-- Details Lightbox 1 -->
	<div id="details-lightbox-1" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="container">
            <div class="row">
                <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
                <div class="col-lg-8">
                    <div class="image-container">
                        <img class="img-fluid" src="/tpl/main_n/landing-styles/images/details-lightbox.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-4">
                    <h3>List Building</h3>
                    <hr>
                    <h5>Core service</h5>
                    <p>It's very easy to start using Tivo. You just need to fill out and submit the Sign Up Form and you will receive access to the app.</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">List building framework</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Easy database browsing</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">User administration</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Automate user signup</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Quick formatting tools</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Fast email checking</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg mfp-close" href="sign-up.html">SIGN UP</a> <a class="btn-outline-reg mfp-close as-button" href="#screenshots">BACK</a>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of details lightbox 1 -->

    <!-- Details Lightbox 2 -->
	<div id="details-lightbox-2" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="container">
            <div class="row">
                <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
                <div class="col-lg-8">
                    <div class="image-container">
                        <img class="img-fluid" src="/tpl/main_n/landing-styles/images/details-lightbox.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-4">
                    <h3>Campaign Monitoring</h3>
                    <hr>
                    <h5>Core service</h5>
                    <p>It's very easy to start using Tivo. You just need to fill out and submit the Sign Up Form and you will receive access to the app.</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">List building framework</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Easy database browsing</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">User administration</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Automate user signup</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Quick formatting tools</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Fast email checking</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg mfp-close" href="sign-up.html">SIGN UP</a> <a class="btn-outline-reg mfp-close as-button" href="#screenshots">BACK</a>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of details lightbox 2 -->

    <!-- Details Lightbox 3 -->
	<div id="details-lightbox-3" class="lightbox-basic zoom-anim-dialog mfp-hide">
        <div class="container">
            <div class="row">
                <button title="Close (Esc)" type="button" class="mfp-close x-button">×</button>
                <div class="col-lg-8">
                    <div class="image-container">
                        <img class="img-fluid" src="/tpl/main_n/landing-styles/images/details-lightbox.png" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
                <div class="col-lg-4">
                    <h3>Analytics Tools</h3>
                    <hr>
                    <h5>Core service</h5>
                    <p>It's very easy to start using Tivo. You just need to fill out and submit the Sign Up Form and you will receive access to the app.</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">List building framework</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Easy database browsing</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">User administration</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Automate user signup</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Quick formatting tools</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body">Fast email checking</div>
                        </li>
                    </ul>
                    <a class="btn-solid-reg mfp-close" href="sign-up.html">SIGN UP</a> <a class="btn-outline-reg mfp-close as-button" href="#screenshots">BACK</a>
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of lightbox-basic -->
    <!-- end of details lightbox 3 -->
    <!-- end of details lightboxes -->


    <!-- Details -->
    <div id="details" class="basic-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="text-container">
                        <h2>Преподаватель</h2>
                        <h3><?=$userInfo->name?></h3>
                        <p><?=$userInfo->description?></p>
                    </div> <!-- end of text-container -->
                </div> <!-- end of col -->
                <div class="col-lg-6">
                    <div class="image-container" style="text-align: center;">
                        <img class="img-fluid" width="350" src="<?=$userInfo->avatar?>" alt="alternative">
                    </div> <!-- end of image-container -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of basic-1 -->
    <!-- end of details -->

    <!-- Pricing -->
    <div id="pricing" class="cards-2">
        <div class="container">
            <div class="col-lg">
                <div class="col-lg-12 w-100">

                    <!-- Card-->
                    <div class="card w-100">
                        <!--<div class="label">
                            <p class="best-value">Best Value</p>
                        </div> -->
                        <div class="card-body">
                            <div class="card-title">За весь курс</div>
                            <div class="price">
                                <span class='value-old'>
                                    <?=$info->prices?>
                                    <span class="percent badge badge-success">-<?=$info->price_percentage?>%</span>
                                </span>
                                <span class='value'> = <?=$info->price_new?></span>
                                <span class="currency">₽</span>
                            </div>
                            <div class="frequency"></div>
                            <div class="divider"></div>
                            <ul class="list-unstyled li-space-lg">
                                <? 
                                    $characteristics = json_decode($info->characteristics);
                                ?>
                                <? foreach ($characteristics as $char) {?>
                                    <li class="media">
                                        <div class="media-body"><?=$char?></div>
                                    </li>
                                <?} ?>
                            </ul>
                            <div class="button-wrapper">
                                <!-- <button class="btn-solid-reg page-scroll" data-toggle="modal" data-target="#buyCourse">Купить</button> -->
                                <? if(is_user()){?>
                                <a class="btn-solid-reg page-scroll" href="<?=playlist_url($info->playlist_id, $info->title)?>">Купить</a>
                                <?} else {?>
                                    <a class="btn-solid-reg page-scroll" href="https://youinroll.com/login?backurl=<?=str_replace('https://youinroll.com', '', playlist_url($info->playlist_id, $info->title))?>">Купить</a>
                                <?}?>
                            </div>
                        </div>
                    </div> <!-- end of card -->
                    <!-- end of card -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of cards-2 -->
    <!-- end of pricing -->


    <!-- Testimonials -->
    <div class="slider-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">      
                    
                    <!-- Text Slider -->
                    <div class="slider-container">
                        <div class="swiper-container text-slider">
                            <div class="swiper-wrapper">
                                
                                <? foreach($reviews as $review) {?>
                                <!-- Slide -->
                                <div class="swiper-slide">
                                    <div class="image-wrapper">
                                        <img class="img-fluid" src="https://youinroll.com/<?=$review->avatar?>" alt="alternative">
                                    </div> <!-- end of image-wrapper -->
                                    <div class="text-wrapper">
                                        <div class="testimonial-text"><?=$review->comment_text?></div>
                                        <div class="testimonial-author"><?=$review->name?></div>
                                    </div> <!-- end of text-wrapper -->
                                </div> <!-- end of swiper-slide -->
                                <!-- end of slide -->
                                <?}?>

                            </div> <!-- end of swiper-wrapper -->
                            
                            <!-- Add Arrows -->
                            <div class="swiper-button-next"></div>
                            <div class="swiper-button-prev"></div>
                            <!-- end of add arrows -->

                        </div> <!-- end of swiper-container -->
                    </div> <!-- end of slider-container -->
                    <!-- end of text slider -->

                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of slider-2 -->
    <!-- end of testimonials -->



    <!-- Footer -->
    <svg class="footer-frame" data-name="Layer 2" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none" viewBox="0 0 1920 79"><defs><style>.cls-2{fill:#5f4def;}</style></defs><title>footer-frame</title><path class="cls-2" d="M0,72.427C143,12.138,255.5,4.577,328.644,7.943c147.721,6.8,183.881,60.242,320.83,53.737,143-6.793,167.826-68.128,293-60.9,109.095,6.3,115.68,54.364,225.251,57.319,113.58,3.064,138.8-47.711,251.189-41.8,104.012,5.474,109.713,50.4,197.369,46.572,89.549-3.91,124.375-52.563,227.622-50.155A338.646,338.646,0,0,1,1920,23.467V79.75H0V72.427Z" transform="translate(0 -0.188)"/></svg>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <div class="footer-col first">
                        <!-- <h4>About Tivo</h4>
                        <p class="p-small">We're passionate about designing and developing one of the best marketing apps in the market</p> -->
                    </div>
                </div> <!-- end of col -->
                
                <div class="col-md-4">
                    <!-- <div class="footer-col last">
                        <h4>Контакты</h4>
                        <ul class="list-unstyled li-space-lg p-small">
                            <li class="media">
                                <i class="fas fa-map-marker-alt"></i>
                                <div class="media-body">$userInfo->email?></div>
                            </li>
                            <li class="media">
                                <i class="fas fa-envelope"></i>
                                <div class="media-body"><a class="white" href="mailto:$userInfo->email?>">$userInfo->email?></a> <i class="fas fa-globe"></i><a class="white" href="https://youinroll.com">youinroll.com</a></div>
                            </li>
                        </ul>
                    </div>  -->
                </div> <!-- end of col -->
            </div> <!-- end of row -->
        </div> <!-- end of container -->
    </div> <!-- end of footer -->  
    <!-- end of footer -->


    <!-- Copyright -->
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="p-small">Copyright © 2020 <a href="https://youinroll.com">YouInRoll</a></p>
                </div> <!-- end of col -->
            </div> <!-- enf of row -->
        </div> <!-- end of container -->
    </div> <!-- end of copyright --> 
    <!-- end of copyright -->
    
    	
    <!-- Scripts -->
    <script src="/tpl/main_n/landing-styles/js/jquery.min.js"></script> <!-- jQuery for Bootstrap's JavaScript plugins -->
    <script src="/tpl/main_n/landing-styles/js/popper.min.js"></script> <!-- Popper tooltip library for Bootstrap -->
    <script src="/tpl/main_n/landing-styles/js/bootstrap.min.js"></script> <!-- Bootstrap framework -->
    <script src="/tpl/main_n/landing-styles/js/jquery.easing.min.js"></script> <!-- jQuery Easing for smooth scrolling between anchors -->
    <script src="/tpl/main_n/landing-styles/js/swiper.min.js"></script> <!-- Swiper for image and text sliders -->
    <script src="/tpl/main_n/landing-styles/js/jquery.magnific-popup.js"></script> <!-- Magnific Popup for lightboxes -->
    <script src="/tpl/main_n/landing-styles/js/validator.min.js"></script> <!-- Validator.js - Bootstrap plugin that validates forms -->
    <script src="/tpl/main_n/landing-styles/js/scripts.js"></script> <!-- Custom scripts -->

    <!-- The Modal -->
    <div class="modal" id="initCourse">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title w-100 text-center">Записаться на курс</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <!-- adding Bootstrap Form here -->

                    <form id="myForm" action="/lib/ajax/joinCourse.php" class="needs-validation">
                        
                        <input type="hidden" name="course" value="<?=$info->id?>" />

                        <div class="container">

                            <div class="form-group row">
                                <label for="id" class="col-sm-2 col-form-label">Имя</label>
                                <div class="col-sm-10">
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Ваше имя" required />
                                        <div class="invalid-feedback">
                                            Ваше имя
                                        </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="validationCustomUsername"  class="col-sm-2 col-form-label">E-Mail</label>
                                <div class="input-group col-sm-10">
                                    <input type="text" class="form-control" id="mail" name="mail" placeholder="Электронная почта" aria-describedby="inputGroupPrepend" required>
                                    <div class="invalid-feedback">
                                        Электронная почта
                                    </div>
                                </div>
                            </div>

                            <div class="text-center">
                                <button class="btn btn-success" type="submit">Отправить</button>
                            </div>
                        </div>
                    </form>

                    <div class='successBlock' style='display:none'>
                        <h1>Ваше сообщение отправлено!</h1>
                    </div>

                </div>

            </div>
        </div>
    </div>

    <?include('buymodal.php');?>

</body>

<script>
    let userAuth = <?=is_user();?>
    document.addEventListener('DOMContentLoaded', function(){

        $('#myForm').on('submit', function(e){
            e.preventDefault();
            
            $.get($(this).attr('action'), $(this).serialize(), function(data){
                $('.successBlock').show();
                $('#myForm').remove();
                
                if(userAuth){
                    location.href = "<?=playlist_url($info->playlist_id, $info->title)?>";
                } else
                {
                    location.href = "https://youinroll.com/login?backurl=<?=str_replace('https://youinroll.com', '', playlist_url($info->playlist_id, $info->title))?>";
                }
                
            })
        })

    });
</script>
<?=$info->countersript?>
</html>