<?
require_once('../../load.php');

$allSubs = $cachedb->get_results("SELECT * FROM vibe_user_subscriptions WHERE valid_to > CURRENT_TIMESTAMP AND payment_status <> 'waiting'");

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

    if( strtotime($newValid) <= time() )
    {
        $YNRpayment->charge($sub);
    }
}

?>