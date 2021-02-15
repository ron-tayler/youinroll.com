<?php

include_once('../../load.php');

$activeConferences = $db->get_results(
"
    SELECT
    conference.*,
    USER.name as author,
    USER.avatar as authorImage
FROM
   ".DB_PREFIX."conferences AS conference
INNER JOIN ".DB_PREFIX."users AS USER
ON
    conference.moderator_id = USER.id AND (conference.on_air = '1' OR conference.started_at > CURRENT_TIMESTAMP) AND conference.type = 'stream'
ORDER BY
    conference.views
DESC
LIMIT 0, 5
");

if($activeConferences)
{
    foreach ($activeConferences as $conf) {
        $conf->url = conference_url($conf->id, $conf->name);
    }
}

$result = json_encode($activeConferences);

echo $result;
?>