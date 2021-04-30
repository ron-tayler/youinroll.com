<?php
include_once('../../load.php');
require_once(INC.'/recaptchalib.php');

$result = '';
$errors = [];

$check = user::CheckMail(_post('email'));
if($check > 0 ) {
    $omail = toDb(_post('email'));
    $result = $db->get_row("SELECT pass, name FROM ".DB_PREFIX."users WHERE email ='" . toDb($omail) . "'");
    if($result) {
        $key = base64_encode($result->pass);
        $link = site_url().'login?do=autologin&m='.base64_encode($omail).'&k='.$key;
        if(_get('verifyemail')) {
            $message = _lang('In order to activate your account please follow this link:');
        } else {
            $message = _lang('In order to change your password please follow this link:');	
        }
        $message .= '<br /> <a href="##link##">##link##</a> <br />';
        $message .= _lang('Regards, Webmaster');
        $message .= '<br> '.site_url();
        $message = str_replace("##link##",$link,$message);	

        //echo $link;
        $mail = new PHPMailer;
        if(isset($mvm_useSMTP) && $mvm_useSMTP  ) {
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = $mvm_host;  
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = $mvm_user;   
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
            $mail->addAddress($omail, toDb($result->name));
            $mail->WordWrap = 50;  
        if(_get('verifyemail')) {
            $mail->Subject = 'YouInRoll';//$result->name.' '._lang('Activate your account');	
        } else {
            $mail->Subject = 'YouInRoll';//_lang('Password change for'). ' '.$result->name;
        }
        $mail->Body    = $message;
        $mail->AltBody = $message;
        if(!$mail->send()) {
            $errors['email'] = _lang('Message could not be sent.');
        } else {
            $result = 'Ссылка с восстановлением пароля отправлена на почту';					
        }
    }
} else {
    $errors['email'] = _lang('is not registered to any account.');
}

echo(json_encode(
    ['result' => $result, 'errors' => $errors]
));
?>