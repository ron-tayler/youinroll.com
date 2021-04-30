<?php

include_once('../../load.php');

$sql = "SELECT
    conf.*,
    users.name AS author,
    users.avatar AS authorImage,
    cat.cat_name
FROM PREFIX_conferences AS conf
LEFT JOIN PREFIX_channels AS cat ON conf.category = cat.cat_id
INNER JOIN PREFIX_users AS users ON conf.moderator_id = users.id
WHERE (conference.on_air = '1' OR conference.started_at > CURRENT_TIMESTAMP) AND conference.type = 'stream' AND conference.tags <> 'dev'
ORDER BY conference.views DESC
LIMIT 0, 5";
$sql = str_replace('PREFIX_',DB_PREFIX,$sql);
$activeConferences = $db->get_results($sql);

foreach ($activeConferences as $conf) {
    $conf->url = conference_url($conf->id, $conf->name);

    if($conf->cover === null || $conf->cover === '')
    {
        $conf->cover = '/storage/uploads/def-avatar.jpg';
    }

    $conf->cover = thumb_fix($conf->cover, 400, 400);
}

$result = json_encode($activeConferences??[]);

echo $result;
?>