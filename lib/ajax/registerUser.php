<?php
include_once('../../load.php');
require_once(INC.'/recaptchalib.php');
ini_set('display_errors', 0);// TODO Этой файл генерит предупреждения, нужно проверить

$result = '';
$email = '';
$errors = [];

$role = (intval(_post('category')) === 4 || intval(_post('category')) === 3 || intval(_post('category')) === 10 || intval(_post('category')) === 8) ? _post('category') : '4' ;

//If submited
if(get_option('allowlocalreg') == 1 ) {
    if(_post('name') && _post('password') && _post('email') && !isset($_POST['mobile'])){
        $recaptcha = new \ReCaptcha\ReCaptcha(get_option('recap-secret'));
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) {
            // What happens when the CAPTCHA was entered incorrectly
            $errors['captcha']['text'] = 'The reCAPTCHA wasn\'t entered correctly. Go back and try it again
                    reCAPTCHA error: ';
                    foreach ($resp->getErrorCodes() as $code) {
                            $errors['captcha']['codes'] = $code;
                        }
            } else {
                if (filter_var(_post('email'), FILTER_VALIDATE_EMAIL)) {
        
                    if(_post('password') == _post('password2')) {
                        $avatar = 'uploads/def-avatar.jpg';
                        if(isset($_FILES['avatar']) && $_FILES['avatar']){
                            $formInputName   = 'avatar';							# This is the name given to the form's file input
                            $savePath	     = ABSPATH.'/storage/uploads';								# The folder to save the image
                            $saveName        = md5(time()).'-'.user_id();									# Without ext
                            $allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
                            $imageQuality    = 100;
                            $uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
                            if ($uploader->getIsSuccessful()) {
                                $uploader -> resizeImage(180, 180, 'crop');
                                $uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
                                $thumb  = $uploader->getTargetPath();
                                $avatar = str_replace(ABSPATH.'/' ,'',$thumb);
                            } 
                        }
                            if(user::CheckMail(_post('email')) < 1) {
                                $keys_values = array(   "passKey"=> 'uinactive-'.md5(uniqid()),
                                                        "avatar"=> $avatar,
                                                        "local"=> _post('city'),
                                                        "country"=> _post('country'),
                                                        "name"=> _post('name'),								
                                                        "email"=> _post('email'),
                                                        "group_id" => $role,
                                                        "chatRoom"=> generateRandomString(),
                                                        "password"	 => sha1(_post('password')),							
                                                        "type"=> "core"  );
                                $id = user::AddUser($keys_values);
                            
                                if(user::CheckMail(_post('email')) > 0) {	    
                                    
                                    user::loginbymail(_post('email'),_post('password'));

                                    $email = urlencode(_post('email'));
                    
                                } else {
                                    $errors['unknown'] = _lang('Something went wrong, try again!');
                                }
                            
                            } else {
                                $errors['email'] = _lang('Email already in use');
                            }						
                        
                        } else {
                            $errors['password'] = _lang('Passwords are not the same');
                        }
                    } else {
                        $errors['email'] = _lang('Invalid e-mail detected.');
                    }
                if(is_user()){
                    $result = 'success';
                }
            }
    }

    if(_post('name') && _post('password') && _post('email') && isset($_POST['mobile'])){
        if (filter_var(_post('email'), FILTER_VALIDATE_EMAIL)) {
        
        if(_post('password') == _post('password2')) {
            $avatar = 'uploads/def-avatar.jpg';
            if(isset($_FILES['avatar']) && $_FILES['avatar']){
                $formInputName   = 'avatar';							# This is the name given to the form's file input
                $savePath	     = ABSPATH.'/storage/uploads';								# The folder to save the image
                $saveName        = md5(time()).'-'.user_id();									# Without ext
                $allowedExtArray = array('.jpg', '.png', '.gif');	# Set allowed file types
                $imageQuality    = 100;
                $uploader = new FileUploader($formInputName, $savePath, $saveName , $allowedExtArray);
                if ($uploader->getIsSuccessful()) {
                    $uploader -> resizeImage(180, 180, 'crop');
                    $uploader -> saveImage($uploader->getTargetPath(), $imageQuality);
                    $thumb  = $uploader->getTargetPath();
                    $avatar = str_replace(ABSPATH.'/' ,'',$thumb);
                } 
            }
                if(user::CheckMail(_post('email')) < 1) {
                    $keys_values = array(   "passKey"=> 'uinactive-'.md5(uniqid()),
                                            "avatar"=> $avatar,
                                            "local"=> _post('city'),
                                            "country"=> _post('country'),
                                            "name"=> _post('name'),
                                            "chatRoom"=> generateRandomString(),								
                                            "email"=> _post('email'),
                                            "group_id" => $role,
                                            "password"	 => sha1(_post('password')),							
                                            "type"=> "core"  );
                    $id = user::AddUser($keys_values);
                
                    if(user::CheckMail(_post('email')) > 0) {	    
                        
                        user::loginbymail(_post('email'),_post('password'));

                        $email = urlencode(_post('email'));
        
                    } else {
                        $errors['unknown'] = _lang('Something went wrong, try again!');
                    }
                
                } else {
                    $errors['email'] = _lang('Email already in use');
                }						
            
            } else {
                $errors['password'] = _lang('Passwords are not the same');
            }
        } else {
            $errors['email'] = _lang('Invalid e-mail detected.');
        }

        if(is_user()){
            $user = $db->get_row("SELECT * FROM vibe_users where id = '".toDb(user_id())."'");
            $result = $user;
        }
    }
}

echo(json_encode(
    ['result' => $result, 'errors' => $errors, 'email' => $email]
));
?>