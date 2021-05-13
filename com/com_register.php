<?php if (is_user()) {
    redirect(site_url() . 'dashboard/');
}
$error = '';
require_once(INC . '/recaptchalib.php');
//If submited
if (get_option('allowlocalreg') == 1) {
    if (_post('name') && _post('password') && _post('email')) {
        $recaptcha = new \ReCaptcha\ReCaptcha(get_option('recap-secret'));
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) {
            // What happens when the CAPTCHA was entered incorrectly
            $error = '<div class="msg-warning">The reCAPTCHA wasn\'t entered correctly. Go back and try it again
         reCAPTCHA error: ';
            foreach ($resp->getErrorCodes() as $code) {
                $error .= '' . $code . '; ';
            }
            $error .= '</div>';
        } else {
            if (filter_var(_post('email'), FILTER_VALIDATE_EMAIL)) {

                if (_post('password') == _post('password2')) {
                    $avatar = 'uploads/def-avatar.jpg';
                    if (isset($_FILES['avatar']) && $_FILES['avatar']) {
                        $formInputName = 'avatar';                            # This is the name given to the form's file input
                        $savePath = ABSPATH . '/storage/uploads';                                # The folder to save the image
                        $saveName = md5(time()) . '-' . user_id();                                    # Without ext
                        $allowedExtArray = array('.jpg', '.png', '.gif');    # Set allowed file types
                        $imageQuality = 100;
                        $uploader = new FileUploader($formInputName, $savePath, $saveName, $allowedExtArray);
                        if ($uploader->getIsSuccessful()) {
                            $uploader->resizeImage(180, 180, 'crop');
                            $uploader->saveImage($uploader->getTargetPath(), $imageQuality);
                            $thumb = $uploader->getTargetPath();
                            $avatar = str_replace(ABSPATH . '/', '', $thumb);
                        }
                    }
                    if (user::CheckMail(_post('email')) < 1) {
                        $keys_values = array("passKey" => 'uinactive-' . md5(uniqid()),
                            "avatar" => $avatar,
                            "local" => _post('city'),
                            "country" => _post('country'),
                            "name" => _post('name'),
                            "email" => _post('email'),
                            "password" => sha1(_post('password')),
                            "type" => "core");
                        $id = user::AddUser($keys_values);

                        if (user::CheckMail(_post('email')) > 0) {

                            //Create user in Tinode
                            $post_data = array("name" => _post('name'),
                                "nick" => 't' . _post('nick'),
                                "email" => _post('email'),
                                "password" => _post('password'));

                            $url = "https://video-library-service.com/user_add.php";
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, $url);
                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                            curl_exec($ch);
                            curl_close($ch);

                            redirect(site_url() . 'login/?verifyemail=1&mail=' . urlencode(_post('email')));

                        } else {
                            $error = '<div class="msg-warning">' . _lang('Something went wrong, try again!') . '</div>';
                        }

                    } else {
                        $error = '<div class="msg-warning">' . _lang('Email already in use') . '</div>';
                    }

                } else {
                    $error = '<div class="msg-warning">' . _lang('Passwords are not the same') . '</div>';
                }

            } else {
                $error = '<div class="msg-warning">' . _lang('Invalid e-mail detected.') . '</div>';
            }
            var_dump($error);
            if (is_user()) {
                redirect(site_url() . 'dashboard/');
            }

        }
    }
}
//if (is_user()) { redirect(site_url().me);}
if (get_option('allowlocalreg') == 0) {
    redirect(site_url());
}

// SEO Filters
function modify_title($text)
{
    return strip_tags(stripslashes($text . ' ' . _lang('registration')));
}

function modify_content($text)
{
    global $error, $captcha, $socials;
    return $error . '
<div class="row text-center clearfix odet mbot20 mtop20">
<h2>' . _lang("Register") . '</h2>
' . _lang("Thank you for choosing to register, just one step...") . ' 
 <a class="btn btn-primary btn-block mtop20" href="javascript:void(0)" data-toggle="modal" data-target="#register-now">' . _lang("Create account") . '</a>
</div>
';
}

add_filter('phpvibe_title', 'modify_title');
add_filter('the_defaults', 'modify_content');

//Time for design
if (!isset($_GET['dev'])) {
    if (token() === 'busines') {
        include_once(TPL . '/register_business.php');

    } else {
        include_once(TPL . '/register.php');
    }

} else {
    the_header();
    include_once(TPL . '/default-full.php');
    the_footer();
}
?>
