<?php $v_id = token_id();
if(_get('nsfw') == 1) { $_SESSION['nsfw'] = 1; }
//Global video weight & height
$width = get_option('video-width');  
$height = get_option('video-height'); 
$embedCode = '';
//Query this video
if(intval($v_id) > 0) { 

if(this_page() > 1) {
//return only comments ;)
echo comments('confernece_'.$v_id,this_page());
exit();
}

$sqlRaw = "SELECT ".DB_PREFIX."conferences.*, ".DB_PREFIX."channels.cat_name as channel_name ,".DB_PREFIX."users.avatar, ".DB_PREFIX."users.name as owner, ".DB_PREFIX."users.group_id, ".DB_PREFIX."users.avatar FROM ".DB_PREFIX."conferences 
LEFT JOIN ".DB_PREFIX."channels ON ".DB_PREFIX."conferences.category =".DB_PREFIX."channels.cat_id LEFT JOIN ".DB_PREFIX."users ON ".DB_PREFIX."conferences.moderator_id = ".DB_PREFIX."users.id WHERE ".DB_PREFIX."conferences.`id` = '".$v_id."' limit 0,1";

/* var_dump($sqlRaw);
die(); */

$cache_name = "conference-".$v_id;
$video = $cachedb->get_row($sqlRaw);
$cache_name = null; //reset

if ($video) {
    $is_owner = false;	
    $is_liked = false;
    $is_disliked = false;

    if(is_user()) {
        /* Check if current user is the owner */	
        if($video->moderator_id == user_id()){
            $is_owner = true;
        }	
    }
    // Canonical url
    $canonical = conference_url($video->name); 
    $origin = 1;
    //Check if it's private 
    if(($video->ispremium == 1) && !has_premium()) {
        //Premium video
        $embedvideo = '<div class="vprocessing">
        <div class="vpre">'._lang("This video is for premium users only!").'</div> 
        <div class="vex">'._lang("Become a premium user for as low as ").' '.get_option("monthlycurrency", "USD").' '.get_option("monthlyprice", "1").'
        <div class="full text-center mtop20"><a class="btn btn-primary" href="'.site_url().'payment">'._lang("Upgrade").'</a></div>
        </div>
        </div>';	
    }elseif($video->private == 1 && !im_following($video->moderator_id)) {
        //Video is not public
        $embedvideo = '<div class="vprocessing">
        <div class="vpre">'._lang("This video is for subscribers only!").'</div> 
        <div class="vex"><a href="'.profile_url($video->user_id,$video->owner).'">'._lang("Please subscribe to ").' '.$video->owner.' '._lang("to see this video").'</a>
        </div>
        </div>';

    //Check if it's processing
    }elseif(empty($video->name)) {
        $embedvideo = '<div class="vprocessing"><div class="vpre">'._lang("This video is being processed").'</div>
        <div class="vex">'._lang("Please check back in a few minutes.").'</div></div>';
    } else {
        //See what embed method to use
        if($video->remote) {
            //Check if video is remote/link
        $vid = new Vibe_Providers($width, $height);    
        $embedvideo = $vid->remotevideo($video->remote);
        $origin = 1;
        } elseif($video->embed) {
        //Check if has embed code
            $embedvideo	=  render_video(stripslashes($video->embed));
            $origin = 2;
        } else {
        //Embed from video providers
        $vid = new Vibe_Providers($width, $height);    
        $embedvideo = $vid->getEmbedCode($video->source);
        $origin = 0;
        }
        // Filter result
        $embedvideo = apply_filters('the_embedded_video' , $embedvideo);
        }
    // SEO Filters
    function modify_title( $text ) {
        global $video;
        return strip_tags(_html(get_option('seo-video-pre','').$video->name.get_option('seo-video-post','')));
    }
    function modify_desc( $text ) {
        global $video;
        return _cut(strip_tags(_html($video->description)), 160);
    }

    add_filter( 'phpvibe_title', 'modify_title' );
    add_filter( 'phpvibe_desc', 'modify_desc' );
    //Time for design
    the_header();
    include_once(TPL.'/conference.php');	 
    the_footer();

} else {
    print_r('asdasd');
    layout('404');
}
}else {
    print_r('asdasd');
    layout('404');
}
?>