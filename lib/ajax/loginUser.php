<?php
include_once('../../load.php');
require_once(INC.'/recaptchalib.php');

$result = '';
$errors = [];

/** Actual login **/
if(_post('password') && _post('email')){
    if(user::loginbymail(_post('email'),_post('password') )) {

        $jitsiParams = $db->get_row('SELECT haveJitsi FROM '.DB_PREFIX.'users WHERE id = '.toDb(user_id()));

        $result = $jitsiParams;

    } else {
        if(user::UserIsBanned(_post('email'))) {	
            $errors['email'] = _lang('This account is banned for infringing our rules!');
        } else {
            $errors['email'] = _lang('Wrong username or password.');
        }
    }
}
echo(json_encode(
    ['result' => $result, 'errors' => $errors]
));
?>