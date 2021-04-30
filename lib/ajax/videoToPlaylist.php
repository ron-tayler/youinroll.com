<?php include_once('../../load.php');

$videoId = intval($_GET['vid']);
$playlistId = intval($_GET['pid']);

$playlist = $cachedb->get_row('SELECT id,owner FROM '.DB_PREFIX.'playlists WHERE id = '.toDb($playlistId).' AND owner = '.toDb(user_id()));

if(is_user() && $playlist && $videoId !== 0) {

    $db->query("UPDATE ".DB_PREFIX."videos SET course = '".toDb($playlistId)."' WHERE id = '".toDb($videoId)."' AND user_id = '".toDb(user_id())."'");
    $db->query("INSERT INTO ".DB_PREFIX."playlist_data (`playlist`, `video_id`) VALUES ('".intval($playlistId)."', '".$videoId."')");
    add_activity('2', $add_video, $play);

    echo _lang('All done!');

} else
{
    echo _lang('No playlist selected');
}
?>