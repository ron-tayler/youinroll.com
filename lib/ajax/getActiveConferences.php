<?php

include_once('../../load.php');

/** @lang MySQL */
$sql = "SELECT
    conf.*,
    users.name AS userName,
    users.chatRoom as userCharRoom,
    users.avatar AS userImage,
    cat.cat_name
FROM PREFIX_conferences AS conf
LEFT JOIN PREFIX_channels AS cat ON conf.category = cat.cat_id
INNER JOIN PREFIX_users AS users ON conf.moderator_id = users.id
WHERE conf.on_air = '1'  AND conf.type = 'stream' AND conf.tags <> 'dev'
ORDER BY conf.views DESC
LIMIT 0, 5";
$sql = str_replace('PREFIX_',DB_PREFIX,$sql);
$activeConferences = $db->get_results($sql);

$data = [];
foreach ($activeConferences as $conf) {
    $stream = new stdClass();

    //$stream->id = $conf->id;
    $stream->name = $conf->name;
    $stream->url = conference_url($conf->id, $conf->name);
    $stream->cover = thumb_fix(
        ($conf->cover === null || $conf->cover === '')?'/storage/uploads/def-avatar.jpg':$conf->cover,
        480, 270
    );
    $stream->description = $conf->description;
    $stream->user_url = profile_url($conf->moderator_id,$conf->userName);
    $stream->user_name = $conf->userName;
    $stream->userImage = $conf->userImage;
    $stream->views = $conf->views;
    $stream->tags = explode(',',$conf->tags);
    $token = md5($conf->userCharRoom);
    $stream->stream_url = "https://smartfooded.com:8443/hls/{$token}.m3u8";
    $stream->status = 'on_air';

    $data []= $stream;
}

$result = json_encode($data);

echo $result;
?>
