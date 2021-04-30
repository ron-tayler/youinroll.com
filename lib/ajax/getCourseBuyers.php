<?php
include_once('../../load.php');
ini_set('display_errors', 1);

$courseSallary = [];
$page = isset($_GET['page']) ? $_GET['page'] : null;

if(is_user())
{

    $courseSallary = $cachedb->get_results("SELECT
        course.id,
        playlist.title,
        playlist.price,
        USER.name,
        USER.avatar
    FROM
        vibe_playlists AS playlist
    INNER JOIN vibe_users_courses AS course
    ON
        course.playlist_id = playlist.id AND playlist.owner = '".toDb(user_id())."' AND course.status = 'ready'
    INNER JOIN vibe_users AS USER
    ON
        USER.id = course.user_id
    LIMIT 0,5");
}

echo json_encode($courseSallary, true);
?>