<?php
include_once('../../load.php');
ini_set('display_errors', 1);

$result = '';

if($_POST['agree'] === 'true' && $_POST['agree2'] === 'true')
{
    if($_POST['payment'] === 'card')
    {
        $result = $YNRpayment->init($_POST, user_id());
    }

    if($_POST['payment'] === 'paymaster')
    {
        $result = $YNRpayment->init($_POST, user_id(), 'paymaster');
    }
}

echo(json_encode($result));

?>