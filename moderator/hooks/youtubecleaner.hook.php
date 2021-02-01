<?php function youtubecleanerlinks($txt = '') {
return $txt.'
<li><a href="'.admin_url('ytcleaner').'"><i class="icon-youtube"></i>Youtube Cleaner</a></li>
';
}
add_filter('midd_menu', 'youtubecleanerlinks')

?>