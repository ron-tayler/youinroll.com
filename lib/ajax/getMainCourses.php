<?php
include_once('../../load.php');
ini_set('display_errors', 1);


$courses = [];

$courses = $cachedb->get_results("SELECT
playlist.id,
playlist.picture AS image,
playlist.title,
playlist.description,
playlist.views,
playlist.price
FROM vibe_playlists AS playlist
WHERE
  playlist.ptype = 1 AND playlist.picture <> '[likes]' AND playlist.picture <> '[history]' AND playlist.picture <> '[later]'
ORDER BY
  playlist.views
DESC LIMIT 0,4");

foreach ($courses as $course) {
    $video = $cachedb->get_row("SELECT
            video.token
        FROM
            vibe_playlist_data
        INNER JOIN vibe_videos AS video
        ON
            video.id = video_id AND playlist = 1706 AND video.preview IS NOT NULL
        ORDER BY video.id DESC
        LIMIT 0,1");

    if($video)
    {
        $patternx = "{*".$video->token."*?\.mp4}";
        $folderx = ABSPATH.'/storage/media/thumbs/';
        $vl = glob($folderx.$patternx, GLOB_BRACE);

        foreach ($vl as $key => $v) {

            $mimeType = mime_content_type($v);

            $vl[$key] = str_replace(ABSPATH,"https://youinroll.com", $v);
        }

        /* title: 'Загрузка...',
                image: 'https://64.media.tumblr.com/2b713b06c28544c86b056488e6da3ed1/tumblr_inline_p7l30wzF9K1v1qfwi_540.gifv',
                description: 'Загрузка...'
                price: 'Бесплатно',
                category: 'Загрузка...',
                length: '0 мин' */

        $course->mime = $mimeType;

        $course->image = thumb_fix($course->image, 250, 250);

        $course->source = 'https://youinroll.com/storage/media/thumbs/023605905f2376b8a0c9e75975da2479-01.mp4';
    }

    $course->category = "Программирование";


    $course->url = playlist_url($course->id, $course->title);

}

echo json_encode($courses, true);

class VideoSrc {

}
?>