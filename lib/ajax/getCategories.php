<?php
include_once('../../load.php');

$result = '';
$errors = [];

if(isset($_GET['type']) && $_GET['type'] === 'premium')
{
    $result = $db->get_results("SELECT id,name FROM ".DB_PREFIX.'users_groups where access_level IS NULL AND is_bussines = '.toDb(1).' ORDER BY id');
} else
{
    $result = $db->get_results("SELECT id,name FROM ".DB_PREFIX.'users_groups where is_simple = '.toDb(1).' ORDER BY id asc');
}

echo(json_encode(
    ['result' => $result, 'errors' => $errors]
));
?>