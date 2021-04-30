<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('/home/x/xatikont/youinroll.com/public_html/cron_load.php');

$currentMonth = date("Y-m-d H:i:s", time());
print($currentMonth);

$allSubs = $db->get_results("SELECT * FROM vibe_user_subscriptions WHERE renewed = '0' AND payment_status = 'ready' AND valid_to < '$currentMonth' ");

foreach ($allSubs as $sub) {

    $user = $cachedb->get_row("SELECT * FROM vibe_users WHERE id = $sub->user_id");

    switch ($sub->validity) {
        case '3':

            $month = 3;

            break;

        case '6':

            $month = 6;

            break;
        
        default:

            $month = 1;

            break;
    }

    $test = $YNRpayment->tinkoffPaySecond($sub, $user, $month);
}
?>