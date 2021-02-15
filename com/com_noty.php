<?php
include_once( INC.'/paymaster.class.php' );

/* GcY3csuWsjs9UQVnnYdea409zhHxkE */

$order = $cachedb->get_row('SELECT * FROM '.DB_PREFIX."user_subscriptions WHERE txn_id = '".toDb($_POST['OrderId'])."'");

if($order !== null)
{
    $db->query('UPDATE '.DB_PREFIX."user_subscriptions SET rebill_id = '".toDb($_POST['RebillId'])."' WHERE txn_id = '".toDb($_POST['OrderId'])."'");
}

header("HTTP/1.1 200 OK");
print_r('OK');
?>