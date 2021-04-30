<?php
include_once('../../../load.php');

$vidId = $_POST['id'];

$sql = 'SELECT * FROM '.DB_PREFIX."videos WHERE id = '".toDb($vidId)."' AND user_id = '".user_id()."' LIMIT 0,1";

$video = $db->get_row($sql);

if($video)
{   
    $secondsStart = $_POST['start'];
    $secondsEnd = $_POST['end'];

    $db->query("UPDATE  ".DB_PREFIX."videos SET started_at = '".toDb($secondsStart)."', end_at = '".toDb($secondsEnd)."'  WHERE id = '".intval($video->id)."'");
}
?>