<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once(getcwd().'/cron_load.php');

$allSubs = $db->get_results("SELECT * FROM vibe_user_subscriptions WHERE renewed = '0'");

foreach ($allSubs as $sub) {

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

    $newValid = date("Y-m-d h:i:s", strtotime("+$month month"));

    echo "\n";
    /* print_r(strtotime($sub->valid_to));
    echo "\n";
    print_r(time());
    echo "\n";
    die(); */

    $YNRpayment->charge($sub, $newValid);
}
?>