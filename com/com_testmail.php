<?php
$result = '';
$errors = [];

$omail = toDb($_GET['email']);
	
$message = "Тестер";

//echo $link;
$mail = new PHPMailer;
if(isset($mvm_useSMTP) && $mvm_useSMTP  ) {
	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = $mvm_host;  
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = $mvm_user;  
	$mail->CharSet = 'Windows-1252';
	$mail->AddReplyTo = $mvm_user;              
	$mail->Password = $mvm_pass;                           // SMTP password
	$mail->SMTPSecure = $mvm_secure;                            
	$mail->Port = $mvm_port;                                    
}
	if(isset($adminMail) && not_empty($adminMail)) {
		$mail->From = "youinroll@youinroll.com";
	} else {
		$mail->From = "youinroll@youinroll.com";	
	}
	$mail->FromName = 'YouInRoll';	
	$mail->addAddress($omail, 'Test');
	$mail->WordWrap = 50;  
	$mail->Subject = 'Бреед';
$mail->Body    = $message;
$mail->AltBody = $message;
if(!$mail->send()) {
	$errors['email'] = _lang('Message could not be sent.');
} else {
	$result = 'Ссылка с восстановлением пароля отправлена на почту';					
}

echo(json_encode(
    ['result' => $result, 'errors' => $errors]
));
?>
