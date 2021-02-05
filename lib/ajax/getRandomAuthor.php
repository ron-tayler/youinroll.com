<?php

include_once('../../load.php');


$randomAuthors = $cachedb->get_results("SELECT avatar from ".DB_PREFIX."users where avatar is not NULL AND avatar NOT LIKE '%def-avatar.%' ORDER BY RAND() LIMIT 20");

$result = json_encode($randomAuthors);

echo $result;
return $result;
?>