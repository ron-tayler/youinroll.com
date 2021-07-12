<?php if(!is_user()) {redirect(site_url());}

$cid = token_id();

if($cid !== 0) {

    // SEO Filters
    function modify_title( $text ) {
        global $us,$other;
        return _lang("Chat with ").$us[$other]['name'];
    }

    add_filter( 'phpvibe_title', 'modify_title' );

} else {
    // SEO Filters
    function modify_title( $text ) {
        return _lang("Inbox");
    }

    add_filter( 'phpvibe_title', 'modify_title' );
}

//Time for design

$YNRtemplate->include('/conversation.php',[
    '/tpl/main_n/styles/pages/chat.css?v=290520210140',
    '/tpl/main_n/styles/components/smiles.css'
],[
    '/tpl/main_n/styles/js/pages/chat.js'
], $cid);

/*
the_header();
the_sidebar();
echo '<link rel="stylesheet" href="/tpl/main_n/styles/pages/chat.css?v=290520210140">';
echo '<link rel="stylesheet" href="/tpl/main_n/styles/components/smiles.css">';
include DIR_TPL.'/main_n/conversation.php';
the_footer();
echo '<script src="/tpl/main_n/styles/js/pages/chat.js"></script>';
echo '<script src="/tpl/main_n/styles/js/modules/moment.min.js"></script>';
echo '<script src="/tpl/main_n/styles/js/modules/moment-timezone.min.js"></script>';
*/
?>
