<?php
include_once('../../load.php');
ini_set('display_errors', 1);

$video = [];
$id = isset($_GET['id']) ? intval($_GET['id']) : null;

if(is_user())
{
    $video = $db->get_row("SELECT * from ".DB_PREFIX."videos where id='".intval($id)."'");
    if($video) {

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
            $video->seconds = range(0,$video->duration);
        }

        if(($video->end_at !== null && $video->started_at !== null) && ($video->end_at !== '00:00:00' && $video->started_at !== '00:00:00'))
        {
            $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $video->started_at);

            sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

            $video->started_at = $hours * 3600 + $minutes * 60 + $seconds;

            $str_time = preg_replace("/^([\d]{1,2})\:([\d]{2})$/", "00:$1:$2", $video->end_at);

            sscanf($str_time, "%d:%d:%d", $hours, $minutes, $seconds);

            $video->end_at = $hours * 3600 + $minutes * 60 + $seconds;

            $video->seconds = range($video->started_at,$video->end_at);
        }
    }
       
}

echo json_encode($video, true);

class VideoSrc
{
    
}

?>