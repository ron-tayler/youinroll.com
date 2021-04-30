<?php include_once('../../load.php');

$page = intval( isset(
    $_GET['page'])
    ? $_GET['page']
    : 1
);

$videos = [];

if($page > 0)
{
    $itemsCount = 5;

    $offset = ($page - 1) * $itemsCount;

    //0,5 5,10 10,15
    $sql = "
        SELECT
            id,
            ispremium,
            date,
            user_id,
            source,
            title,
            preview,
            duration,
            description,
            token,
            tags,
            vibe_channels.cat_name,
            liked,
            views,
            disliked,
            nsfw
        FROM
            vibe_videos
        INNER JOIN vibe_channels ON vibe_channels.cat_id = vibe_videos.category
        WHERE
            course IS NULL AND source = 'up'
        ORDER BY
            id
        DESC
        LIMIT $offset, $itemsCount
    ";
    
    $videos = $cachedb->get_results($sql);
    if($videos !== null)
    {

        foreach ($videos as $video)
        {
            $video->thumb = thumb_fix($video->thumb, true, 27, 27);
            $video->description = base64_encode($video->description);
            $video->id = intval($video->id);
            $video->ispremium = intval($video->ispremium);
            $video->user_id = intval($video->user_id);
            $video->duration = intval($video->duration);
            $video->liked = intval($video->liked);
            $video->disliked = intval($video->disliked);
            $video->nsfw = intval($video->nsfw);
            $video->views = intval($video->views);

            $vid = new Vibe_Providers(10, 10);

            $source = $vid->VideoProvider($video->source);

            if(($source == "localimage") || ($source == "localfile") || ($source == "up") ) {

                $patternx = "{*".$video->token."*}";
                $folderx = ABSPATH.'/storage/'.get_option('mediafolder','media').'/';
                $vl = glob($folderx.$patternx, GLOB_BRACE);

                foreach ($vl as $key => $v) {

                    $mimeType = mime_content_type($v);

                    $vl[$key] = str_replace(ABSPATH,"https://youinroll.com", $v);

                    preg_match('/-(.*?)\./', $vl[$key], $vq);

                    $srcClass = new VideoSrc();

                    $srcClass->src = $vl[$key];
                    $srcClass->quality = $vq[1];

                    $vl[$key] = $srcClass;
                }

                $video->mime = $mimeType;

                $video->source = $vl;

                $patternx = "{*".$video->token."*}";
                $folderx = ABSPATH.'/storage/media/thumbs/';
                $vl = glob($folderx.$patternx, GLOB_BRACE);
                
                $videoFormats = [
                    "video/flv",
                    "video/mp4",
                    "video/avi",
                    "video/mpeg",
                    "video/mov",
                    "video/mkv",
                    "video/vob",
                    "video/3gp",
                    "video/rm",
                    "video/ra",
                    "video/rav",
                    "video/wmv",
                    "video/hd",
                    "video/avchd",
                    "video/mpeg",
                    "video/mpeg-1",
                    "video/mpeg-2",
                    "video/mpeg-4",
                    "video/dv",
                    "video/pal",
                    "video/ntsc",
                    "video/mpegps",
                    "video/mpeg4",
                    "video/flv",
                    "video/quicktime"
                ];

                $thumbPrev = null;

                foreach ($vl as $key => $v) {

                    $mimeType = mime_content_type($v);

                    if(in_array($mimeType, $videoFormats))
                    {
                        $thumbPrev = str_replace(ABSPATH,"https://youinroll.com", $v);
                    }
                }

                $video->mime = $mimeType;
                $video->preview = $thumbPrev;
            }
            $user = $cachedb->get_row("SELECT name,avatar from vibe_users WHERE id = $video->user_id");

            $video->userName = $user->name;
            $video->userAvatar = thumb_fix($user->avatar, 50,50);

            $video->comments = intval($db->get_var("SELECT COUNT(*) FROM vibe_em_comments WHERE object_id = 'video_$video->id'"));
        }

    }
}

echo json_encode($videos, true);

class VideoSrc {}
?>