<?php
include_once( INC.'/paymaster.class.php' );

if(true)
{
    $data = file_get_contents('php://input');
    $object = json_decode($data,true);
    $file = fopen(__DIR__ . '/test.txt', 'w');
    fwrite($file, $data);
    fclose($file);

    $order = $cachedb->get_row('SELECT * FROM '.DB_PREFIX."user_subscriptions WHERE txn_id = '".toDb($object['OrderId'])."'");

    $db->query('UPDATE '.DB_PREFIX."user_subscriptions SET payment_status = 'ready', rebill_id = '".toDb($object['RebillId'])."' WHERE txn_id = '".toDb($object['OrderId'])."'");
    //$db->query('INSERT INTO '.DB_PREFIX."premium_users (`user_id`) VALUES ('".toDb(user_id())."')");

    //$user = $cachedb->get_row('SELECT * FROM vibe_users WHERE id = '.toDb(user_id()));

    //$cardList = $YNRpayment->getCard($user);

    /* if($cardList !== []){

        $selectedCard = $cardList[0];

        $db->query('UPDATE '.DB_PREFIX."user_subscriptions SET rebill_id = '".toDb($selectedCard['RebillId'])."' WHERE txn_id = '".toDb($_GET['OrderId'])."'");
    }

    $_SESSION['premium-valid-until'] = $order->valid_to;

    header('Location: '.site_url().'dashboard'); */

    print_r('OK');
}
?>