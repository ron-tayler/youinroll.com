<?php if (!is_ajax_call()) { 
the_header();
the_sidebar();
}

$YNRtemplate->include('/home.php');

?>