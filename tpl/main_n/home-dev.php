<style>
/* 
carousel animation is a little jank, might fix it at some point. Most buttons and links do not do anything; this was done for layout recreation, not functionailty :) Hope you like it, I learned a whole lot in the process!
*/

.carousel {
    margin-top: 6.3rem;
    flex: 0 1 100%;
    display: flex;
    justify-content: center;
    height: 23rem;
    position: relative;
    align-items: center;
    justify-content: space-evenly;
}

.carousel__chevron {
    font-size: 1.6rem;
    color: #898395;
    padding: 1rem 1.4rem;
    border-radius: 50%;
}

.carousel__chevron:hover {
    background-color: #d7d5dc;
    cursor: pointer;
}

.carousel__content {
    flex: 0 0 80%;
    height: 100%;
    position: relative;
}

.carousel__overlay {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 90%;
    height: 90%;
    border-radius: 5px;
    color: #fff;
    z-index: 2;
    display: flex;
    flex-wrap: wrap;
    align-content: space-between;
}

.carousel__overlay i:hover {
    cursor: pointer;
    color: #a991d4;
}

.carousel__overlay-top {
	flex: 0 1 100%;
    border-radius: 10px 10px 0 0;
    display: flex;
    position: absolute;
    width: 100%;
	z-index: inherit;
    color: white;
    padding: 20px;
    background: linear-gradient(360deg, transparent, black);
}
}

.carousel__overlay-picture {
    flex: 0 0 16%;
}

.carousel__overlay-picture img {
    height: 70px;
}

.carousel__overlay-text {
    flex: 0 0 50%;
    font-size: 12px;
    padding-left: 10px;
    font-weight: bold;
}

.carousel__overlay-name {
    font-weight: 700;
    font-size: 16px;
}

.carousel__overlay-title {
    margin-top: 2px;
}

.carousel__overlay-views {
    margin-top: 2px;
}

.carousel__overlay-live {
    flex: 0 0 16%;
    right: 20px;
    position: absolute;
    font-size: 16px;
    text-align: right;
}

.carousel__overlay-waiting {
	font-size: 16px;
    right: 0;
    flex: auto;
    text-align: right;
	font-weight: bold;
}

.carousel__overlay-live .record-button {
    height: 11px;
    width: 11px;
    display: inline-block;
    margin-right: 0;
	border-radius: 50%;
	background-color: red;
}

.carousel__overlay-live .u-inline-text {
    display: inline-block;
}

.carousel__overlay-bottom {
	flex: 0 1 100%;
    display: flex;
    border-radius: 0 0 10px 10px;
    width: 100%;
    height: 110px;
    z-index: inherit;
    color: white;
    padding: 20px;
    bottom: 0;
    position: absolute;
    background: linear-gradient(180deg, transparent, black);;
	overflow: hidden;
}

.carousel__overlay-icon {
    font-size: 1.2rem;
}

.carousel__overlay-icon--volume {
    margin-left: 1rem;
}

.carousel__overlay-icon--settings {
    margin-left: auto;
    margin-right: 1rem;
}

.carousel__overlay-icon--fullscreen {
    transform: rotate(-45deg);
}

.carousel__overlay .big-play {
	position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    color: #fff;
    font-size: 16rem;
    text-shadow: 0px 0px 10px black;
}

.carousel__overlay .big-play:hover {
    cursor: pointer;
	text-shadow: 0px 0px 12px black;
	color: #fff;
    font-size: 19rem;
    transition: all ease-in 0.25s;
}

.carousel__video {
    width: 550px;
    height: 310px;
    position: absolute;
    transition: all 0.5s;
}

.carousel__video .inactive {
    position: absolute;
    z-index: 1;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background-color: rgb(0 0 0 / 0.06);
    border-radius: 5px;
    transition: all 0.2s ease-out;
}

.carousel__video .inactive+img {
    transition: all 0.2s ease-out;
}

.carousel__video .inactive:hover {
    cursor: pointer;
    transform: scale(1.02);
    background-color: rgba(0, 0, 0, 0.4);
}

.carousel__video .inactive:hover+img {
    transform: scale(1.02);
}

.carousel__video-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: black;
    z-index: 3;
}

.carousel__video iframe {
    z-index: 100;
}

.carousel__video.main {
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: 3;
}

.carousel__video.main .inactive+img {
    transition: none;
}

.carousel__video.main .inactive:hover {
    cursor: initial;
    transform: scale(1);
    background-color: rgba(0, 0, 0, 0.6);
}

.carousel__video.main .inactive:hover+img {
    transform: scale(1);
}

.carousel__video.hidden {
    display: none;
}

.carousel__video.secondary {
    width: 467.5px;
    height: 263.5px;
    z-index: 2;
}

.carousel__video.secondary-left {
    top: 50%;
    left: 20%;
    transform: translate(-20%, -50%);
}

.carousel__video.secondary-right {
    top: 50%;
    left: 80%;
    transform: translate(-80%, -50%);
}

.carousel__video.tertiary {
    width: 385px;
    height: 217px;
    z-index: 1;
}

.carousel__video.tertiary-left {
    top: 50%;
    left: 0;
    transform: translate(0, -50%);
}

.carousel__video.tertiary-right {
    top: 50%;
    left: 100%;
    transform: translate(-100%, -50%);
}

.carousel__video-picture {
    width: 100%;
    height: 100%;
    border-radius: 5px;
}

.video-row {
    flex: 0 1 95%;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
}

.video-row__title {
    flex: 0 1 100%;
    margin-bottom: 10px;
}

.video-row__item {
    position: relative;
    flex: 0 1 32%;
}

.recommended-video {
    width: 100%;
    height: 100%;
    position: relative;
    display: flex;
    flex-wrap: wrap;
}

.recommended-video__thumbnail {
    flex: 0 1 100%;
    position: relative;
}

.recommended-video__thumbnail:hover {
    cursor: pointer;
}

.recommended-video__thumbnail img {
    width: 100%;
    height: 100%;
    border-radius: 4px;
}

.recommended-video__overlay {
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: 0;
    z-index: 1;
    color: #fff;
    background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0) 70%, rgba(0, 0, 0, 0.9));
}

.recommended-video__overlay-live {
    background-color: rgba(0, 0, 0, 0.6);
    border-radius: 2px;
    position: absolute;
    top: 6%;
    left: 3%;
    width: 50px;
    height: 17px;
    font-size: 12px;
    display: flex;
    justify-content: space-evenly;
    align-items: center;
}

.recommended-video__overlay-live .record-button {
    width: 8px;
    height: 8px;
}

.recommended-video__overlay-viewers {
    position: absolute;
    bottom: 5%;
    left: 3%;
    font-size: 12px;
}

.recommended-video__info {
    font-size: 12px;
    flex: 0 1 100%;
    display: flex;
    margin-top: 5px;
}

.recommended-video__info-content {
    flex: 0 1 80%;
}

.recommended-video__channel-pic {
    flex: 0 1 15%;
    position: relative;
}

.recommended-video__channel-pic img {
    height: 40px;
    border-radius: 2px;
}

.recommended-video__title {
    font-size: 14px;
}

.recommended-video__channel {
    margin-top: 5px;
    color: #322f37;
}

.recommended-video__game {
    margin-top: 5px;
    color: #322f37;
}

.recommended-video__tags {
    margin-top: 5px;
    display: flex;
}

.recommended-video__menu {
    flex: 0 1 5%;
}

.recommended-video__menu i {
    margin-top: 6px;
}

.tag {
    margin-right: 5px;
    font-size: 11px;
    border: 1px solid #dad8de;
    padding: 3px;
    border-radius: 3px;
}

.tag:hover {
    cursor: pointer;
    background-color: #f1eef1;
    color: red;
    color: #6441a4;
}

.category-row {
    flex: 0 1 95%;
    display: flex;
    margin-top: 25px;
    flex-wrap: wrap;
}

.category-row__title {
    flex: 0 1 100%;
}

.category-row__content {
    flex: 0 1 100%;
    display: flex;
    justify-content: space-between;
    margin-top: 10px;
    position: relative;
}

.boxart {
    color: inherit;
    flex: 0 1 16%;
    position: relative;
    display: flex;
    flex-wrap: wrap;
}

.boxart__image {
    flex: 0 1 100%;
    position: relative;
}

.boxart__image:hover {
    cursor: pointer;
    color: #4b367c;
}

.boxart__image:hover+.boxart__content .boxart__title {
    color: #4b367c;
}

.boxart__image img {
    border-radius: 5px;
    width: 100%;
}

.boxart__content {
    flex: 0 1 100%;
    display: flex;
    margin-top: 5px;
}

.boxart__text {
    flex: 0 1 95%;
    font-size: 12px;
}

.boxart__title {
    font-size: 14px;
}

.boxart__title:hover {
    cursor: pointer;
    color: #4b367c;
}

.boxart__viewers {
    margin-top: 5px;
    color: #898395;
}

.boxart__tags {
    margin-top: 5px;
    display: flex;
}

.boxart__menu {
    flex: 0 1 5%;
    font-size: 12px;
}

.boxart__menu i {
    margin-top: 5px;
}

.u-bold {
    font-weight: 700;
}

.u-purple-link {
    text-decoration: none;
    color: #4b367c;
    font-weight: 700;
}

.u-purple-link:hover {
    text-decoration: underline;
}

.u-purple-hover:hover {
    text-decoration: underline;
    color: #4b367c;
    cursor: pointer;
}
[v-cloak] {
  display: none;
}
</style>
<div id="home-content" class="main-holder col-md-12">
<div v-cloak>
    <video-carousel inline-template class="carousel">
        <div>
            <i class="material-icons carousel__chevron" @click="changeVideo('right')">&#xe5cb;</i>
            <div class="carousel__content">
                <div class="carousel__video" v-for="(video, i) in videos" :class="video.position">
                    <div @click="changeVideo(0, video.position)" class="inactive" v-if="!video.active"></div>
					<div v-if='video.position === "main"' class="carousel__overlay-top">
						<div class="carousel__overlay-picture">
							<img :src="video.channelImage" alt="">
						</div>
						<div class="carousel__overlay-text">
							<div class="carousel__overlay-name">
								{{video.channelName}}
							</div>
							<div class="carousel__overlay-title">
								{{video.channelTitle}}
							</div>
							<div class="carousel__overlay-views">
								{{video.channelViews}} Просмотров
							</div>
						</div>
						<div v-if="video.isPlanned" class="carousel__overlay-waiting">
							<span class="badge badge-warning">Скоро начнётся</span>
						</div>
						<div v-else class="carousel__overlay-live">
							<div class="record-button"></div>
							<span class="u-inline-text">В эфире</span>
						</div>
					</div>
                    <div class="carousel__overlay" v-if="!video.active && video.position == 'main'">
                        <a v-bind:href="video.url"><i class="material-icons big-play" v-on:click="video.active = !video.active">
                        &#xe037;
						</i>
						</a>
                    </div>
					<div v-if='video.position === "main"' class="carousel__overlay-bottom">
						{{video.description}}
					</div>
                    <img v-if="!video.active" :src="video.thumbnail" alt="" class="carousel__video-picture">
                    
                    <div class="carousel__placeholder-background" v-if="video.active"></div>
                </div>
            </div>
            <i class="material-icons carousel__chevron" @click="changeVideo('left')">&#xe5cc;</i>
        </div>
    </video-carousel>
</div>
    <?php echo _ad('0','home-start');
do_action('home-start');
$boxes = $db->get_results("SELECT * FROM ".DB_PREFIX."homepage ORDER BY ord ASC");
if ($boxes) {
$blockclass = 'hide';	
$blockextra = '<div class="homeLoader sLoad">
    <div class="cp-spinner cp-flip"></div>  
</div>';
$bnr = $db->num_rows;
$i= 1;
foreach ($boxes as $box) {
/* Box start */	
if(is_empty($box->mtype)) {$box->mtype = 1;}
if(is_empty($box->type) || ($box->type == 2) ) {
$type = $box->mtype;
switch($type){	
case "1":
default:
include(TPL.'/box_video.php');
break;	
case "2":
include(TPL.'/box_music.php');
break;
case "3":
include(TPL.'/box_pictures.php');
break;
}
} elseif($box->type == 1) {
	// Html box
	echo '<div class="row">
	<h1 class="loop-heading">'._html($box->title).'</h1>	
	<div class="'.$box->querystring.'">
	'._html($box->ident).'
	</div>
	</div>';
} elseif($box->type == 3) {
	$heading = _html($box->title);	
	$playlist =$db->get_row("SELECT id,ptype FROM ".DB_PREFIX."playlists where id = '".$box->ident."' limit  0,1");
	if($playlist->ptype ==1) {
		$options = DB_PREFIX."videos.id,".DB_PREFIX."videos.media,".DB_PREFIX."videos.title,".DB_PREFIX."videos.user_id,".DB_PREFIX."videos.thumb,".DB_PREFIX."videos.views,".DB_PREFIX."videos.liked,".DB_PREFIX."videos.duration,".DB_PREFIX."videos.nsfw";
		$vq = "SELECT ".DB_PREFIX."videos.id, ".DB_PREFIX."videos.title, ".DB_PREFIX."videos.user_id, ".DB_PREFIX."videos.thumb, ".DB_PREFIX."videos.views, ".DB_PREFIX."videos.liked, ".DB_PREFIX."videos.duration, ".DB_PREFIX."videos.nsfw, ".DB_PREFIX."users.group_id, ".DB_PREFIX."users.name AS owner
		FROM ".DB_PREFIX."playlist_data
		LEFT JOIN ".DB_PREFIX."videos ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."videos.id
		LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."videos.user_id = ".DB_PREFIX."users.id
		WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
		ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
		if(isset($box->car) && ($box->car > 0)){
		include(TPL.'/video-carousel.php');
		} else {
		include(TPL.'/video-loop.php');
		}
	} else {
		$heading = _html($box->title);	
		$options = DB_PREFIX."images.id,".DB_PREFIX."images.title,".DB_PREFIX."images.user_id,".DB_PREFIX."images.thumb";
		$vq = "SELECT $options, , ".DB_PREFIX."users.group_id, ".DB_PREFIX."users.name AS owner, ".DB_PREFIX."users.avatar
		FROM ".DB_PREFIX."playlist_data
		LEFT JOIN ".DB_PREFIX."images ON ".DB_PREFIX."playlist_data.video_id = ".DB_PREFIX."images.id
		LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."images.user_id = ".DB_PREFIX."users.id
		WHERE ".DB_PREFIX."playlist_data.playlist =  '".$playlist->id."'
		ORDER BY ".DB_PREFIX."playlist_data.id DESC ".this_offset(bpp());
		if(isset($box->car) && ($box->car > 0)){
		include(TPL.'/images-carousel.php');
		} else {
		include(TPL.'/images-loop.php');
		}
	}
}elseif($box->type == 4) {
	include(TPL.'/box_channel.php');
}elseif($box->type == 6) {
	include(TPL.'/box_channels.php');
}elseif($box->type == 7) {
	include(TPL.'/box_playlists.php');
}

unset($box); 
if(isset($type)) { unset($type); }
if(isset($vq)) { unset($vq); }
if(isset($options)) { unset($options); }
if(isset($kill_infinite)) { unset($kill_infinite); }
}

/* Box ended */
do_action('home-after-block');
} else {
echo _lang('Nothing selected for home content.').'<p class="mtop20"><a href="'.site_url().ADMINCP.'//?sk=homepage">'._lang("Choose content").'</a> </p>';
}
do_action('home-end');
echo _ad('0','home-end');
?>
</div>