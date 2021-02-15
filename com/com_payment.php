<?php
include_once( INC.'/paymaster.class.php' );


$user = $db->get_row('SELECT * FROM '.DB_PREFIX.'users WHERE id = '.toDb(user_id()));
$type = null;


if(isset($_POST['buyCourse']))
{	
	if($_POST['agree'] === 'on' && $_POST['agree2'] === 'on')
	{
		if($_POST['payment'] === 'card')
		{	

			header('Location: '. $YNRpayment->buyCourse($_POST, user_id()));
			die();
		}

		if($_POST['payment'] === 'paymaster')
		{
			header('Location: '. $YNRpayment->buyCourse($_POST, user_id(), 'paymaster'));
			die();
		}
	}
	
	die();
}

if(isset($_POST['buySubscribe']))
{	
	if($_POST['agree'] === 'on' && $_POST['agree2'] === 'on')
	{
		if($_POST['payment'] === 'card')
		{
			header('Location: '. $YNRpayment->init($_POST, user_id()));
			die();
		}

		if($_POST['payment'] === 'paymaster')
		{
			header('Location: '. $YNRpayment->init($_POST, user_id(), 'paymaster'));
			die();
		}
	}	
}

if(token() === "success") {
	$id = user_id();
	user::Update('group_id', 6, $id);
	user::RefreshUser($id);	

	$type = 'success';
	
	the_header();
	include_once(TPL.'/pay-form.php');
	the_footer();
	
}
if(token() === "noty") {

	try {
		$paymaster = new PayMaster;

		if($paymaster->savePremium())
		{
			$subscr_id = $_POST['LMI_SYS_PAYMENT_ID'];
            $payment_status = 'success';
            $txn_id = $_POST['LMI_PAYMENT_NO'];
            $currency_code = 'RUB';
            $unitPrice = get_option("monthlyprice", "1");
            $subscr_days = 30;
            $subscr_date_from = date("Y-m-d H:i:s");
			$subscr_date_to = date("Y-m-d H:i:s", strtotime($subscr_date_from. ' + '.$subscr_days.' days'));

			$description = mb_convert_encoding($_POST['LMI_PAYMENT_DESC'], 'CP1251', 'UTF-8');

			$description = strstr($description, ':');

			$description = str_replace(': ','', $description);

			$payer_email = filter_var($description, FILTER_VALIDATE_EMAIL);

            $user = $db->get_row("SELECT id FROM ".DB_PREFIX."users WHERE email = '".$payer_email."'");

            $user = $user->id;
        
            $insert = $db->query("INSERT INTO ".DB_PREFIX."user_subscriptions(user_id,payment_method,validity,valid_from,valid_to,item_number,txn_id,payment_gross,currency_code,subscr_id,payment_status,payer_email) VALUES('".$user."','paymaster','1','".$subscr_date_from."','".$subscr_date_to."','1','".$txn_id."','159','".$currency_code."','".$subscr_id."','".$payment_status."','".$payer_email."')");
            $premium = premium_group();
            if($premium > 0) {
                $db->query ("UPDATE ".DB_PREFIX."users SET group_id='".toDb($premium)."' WHERE id ='" . sanitize_int($user) . "'");
            }
		}

		
	} catch (\Throwable $th) {
		echo "<pre>";
		print_r($th);
		die();
	}
}

if(token() === "test") {

	$link = 'https://youinroll.com/payment/noty';

	$curl = curl_init();

	$postData = "LMI_MERCHANT_ID=4cae70bd-2415-4dc8-bb55-e4c3c9439d66&LMI_PAYMENT_SYSTEM=503&LMI_CURRENCY=RUB&LMI_PAYMENT_AMOUNT=159.00&LMI_PAYMENT_DESC=%d0%9e%d0%bf%d0%bb%d0%b0%d1%82%d0%b0+%d0%bf%d1%80%d0%b5%d0%bc%d0%b8%d1%83%d0%bc%d0%b0+%d0%b4%d0%bb%d1%8f%3a+tumanych17498%40yandex.ru&LMI_SYS_PAYMENT_DATE=2021-01-05T09%3a51%3a02&LMI_SYS_PAYMENT_ID=277061553&LMI_PAID_AMOUNT=159.00&LMI_PAID_CURRENCY=RUB&LMI_SIM_MODE=0&LMI_PAYER_IDENTIFIER=410000XXXXXX0010&LMI_PAYMENT_METHOD=Test&LMI_PAYER_IP_ADDRESS=109.201.202.150&LMI_HASH=KDziSzzUSVm0ooDS%2b3KqWw%3d%3d";

	curl_setopt($curl, CURLOPT_URL, $link);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_VERBOSE, true);
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);

	$result = curl_exec($curl);

	print_r($result);
	die();
}

$YNRtemplate->include('/pay-form.php', [
	'/tpl/main_n/styles/pages/payment.css'
], [
	'/tpl/main_n/styles/js/pages/payment.js'
])

/* the_header();
include_once(TPL.'/pay-form.php');
the_footer();	 */
?>