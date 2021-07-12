<?php ini_set('max_execution_time', 800);
error_reporting(E_ALL);
ini_set('display_errors', 1);
$rtf_debug = 1;


//Vital file include
require_once(__DIR__ . "/../load.php");

function admin_url($sk = null)
{
    if (is_null($sk)) {
        return site_url() . ADMINCP . '/';
    } else {
        return site_url() . ADMINCP . '/?sk=' . $sk;
    }
}


function checkThumbFile($id)
{
    /* Test smallest thumbnail */
    $url = 'https://img.youtube.com/vi/' . $id . '/1.jpg';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // don't download content
    curl_setopt($ch, CURLOPT_NOBODY, 1);
    curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($ch);
    curl_close($ch);
    if ($result !== FALSE) {
        return true;
    } else {
        return false;
    }
}

$vq = "select id,views,source,thumb,duration,date,title,disliked,liked from " . DB_PREFIX . "videos where pub > 0 and source like '%youtube%' " . this_limit();

$videos = $db->get_results($vq);
$i = 0;
if ($videos) {
    foreach ($videos as $video) {
        /* Get id */
        preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $video->source, $match);
        if (isset($match[1])) {
            $ytId = $match[1];
            /* Test every thumbnail */
            if (!checkThumbFile($ytId)) {
                ?>
                <li class="list-group-item">
                    <div class="row">
                        <div class="inline-block img-hold">
                            <div class="inline-block right20 img-checker">
    <span class="pull-left mg-t-xs mg-r-md top20">
	<input type="checkbox" name="checkRow[]" value="<?php echo $video->id; ?>" class="styled"/>
	</span>
                                <span class="pull-left mg-t-xs mg-r-md">
	<img class="row-image" src="<?php echo thumb_fix($video->thumb); ?>">
	</span>
                            </div>
                            <div class="inline-block right20 img-txt">
                                <h4><a target="_blank"
                                       href="<?php echo video_url($video->id, $video->title); ?>"><?php echo _html($video->title); ?> </a>
                                </h4>
                                <div class="img-det-text">
                                    <i class="material-icons">timer</i> <?php echo video_time($video->duration); ?>
                                    <i class="material-icons">&#xE192;</i> <?php echo time_ago($video->date); ?>
                                    <i class="material-icons">&#xE8DC;</i><?php echo intval($video->liked); ?>
                                    <span class="couldhide"><i
                                                class="material-icons">&#xE8DB;</i><?php echo intval($video->disliked); ?></span>
                                    <i class="material-icons">&#xE417;</i> <?php echo _html($video->views); ?>
                                </div>

                            </div>
                        </div>

                        <div class="btn-group btn-group-vertical pull-right">
                            <a target="_blank" class="btn btn-sm btn-outline btn-default confirm"
                               href="<?php echo admin_url('videos'); ?>&delete-video=<?php echo $video->id; ?>">
                                <i class="material-icons mright10"> delete </i> unlist
                            </a>
                            <a target="_blank" class="btn btn-sm btn-raised btn-primary"
                               href="<?php echo admin_url('edit-video'); ?>&vid=<?php echo $video->id; ?>">
                                <i class="material-icons mright10"> edit </i> modify
                            </a>
                            <a target="_blank" class="btn btn-sm btn-outline btn-danger confirm"
                               href="<?php echo admin_url('unvideos'); ?>&delete-video=<?php echo $video->id; ?>">
                                <i class="material-icons mright10"> delete </i> erase
                            </a>

                        </div>
                    </div>

                </li>
                <?php
                $i++;
            }
        }
    }
}
?>