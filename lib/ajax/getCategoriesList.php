<?php
include_once('../../load.php');

$result = [];

$result = $db->get_results("SELECT cat_id,cat_name FROM ".DB_PREFIX."channels where type = '1' AND child_of = '0' ORDER BY cat_id asc");

echo(json_encode($result));
?>