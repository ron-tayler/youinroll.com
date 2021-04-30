<?php
include_once( INC.'/paymaster.class.php' );

$order = $cachedb->get_row('SELECT * FROM '.DB_PREFIX."users_courses WHERE order_id = '".toDb($_GET['OrderId'])."' LIMIT 0,1");

if($order !== null)
{
    $db->query('UPDATE '.DB_PREFIX."users_courses SET status = 'ready' WHERE order_id = '".toDb($_GET['OrderId'])."'");

    $playlist = $cachedb->get_row('SELECT id,title FROM '.DB_PREFIX.'playlists WHERE id = '.toDb($order->playlist_id));

    $db->query('DELETE FROM  '.DB_PREFIX."users_courses WHERE playlist_id = '".toDb($playlist->id)."' AND user_id = '".toDb(user_id())."' AND order_id <> '".toDb($_GET['OrderId'])."'");

    header('Location: '.playlist_url($playlist->id, $playlist->title));
} else
{
    $order = $cachedb->get_row('SELECT * FROM '.DB_PREFIX."user_subscriptions WHERE txn_id = '".toDb($_GET['OrderId'])."'");

    $db->query('UPDATE '.DB_PREFIX."user_subscriptions SET payment_status = 'ready' WHERE txn_id = '".toDb($_GET['OrderId'])."'");
    $db->query('INSERT INTO '.DB_PREFIX."premium_users (`user_id`) VALUES ('".toDb(user_id())."')");

    $user = $cachedb->get_row('SELECT * FROM vibe_users WHERE id = '.toDb(user_id()));

    $cardList = $YNRpayment->getCard($user);

    if($cardList !== []){

        $selectedCard = $cardList[0];

        $db->query('UPDATE '.DB_PREFIX."user_subscriptions SET rebill_id = '".toDb($selectedCard['RebillId'])."', payment_status = 'ready' WHERE txn_id = '".toDb($_GET['OrderId'])."'");
    }

    $_SESSION['premium-valid-until'] = $order->valid_to;

    header('Location: '.site_url().'dashboard');
}
?>