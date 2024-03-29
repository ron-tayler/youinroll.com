<?php $user = token_id();

//Query this user
if($user > 0) {
    $profile = $db->get_row("SELECT * FROM ".DB_PREFIX."users where id = '".$user ."' limit  0,1");

    if ($profile) {

        // SEO Filters
        function modify_title( $text ) {
            global $profile;
            return get_option('seo-profile-pre','').strip_tags(stripslashes($profile->name)).get_option('seo-profile-post','');
        }
        function modify_desc( $text ) {
            global $profile;
            return _cut(strip_tags(stripslashes($profile->bio)), 160);
        }

        $active = isset($_GET["sk"]) ? $_GET["sk"] : "profile";

        //Filters

        add_filter( 'phpvibe_title', 'modify_title' );
        add_filter( 'phpvibe_desc', 'modify_desc' );
        /*
        $YNRtemplate->include('/profile.php', [
            'tpl/main_n/styles/calendar.css'
        ]);
        */
        the_header();
        the_sidebar();
        echo '<link rel="stylesheet" href="tpl/main_n/styles/calendar.css">';
        include DIR_TPL.'/main_n/profile.php';
        the_footer();

        //Track this view

        $db->query("UPDATE ".DB_PREFIX."users SET views = views+1 WHERE id = '".$profile->id."'");
    } else {
        //Oups, not found
        layout('404');
    }
} else {
    //Oups, not found
    layout('404');
}
?>
