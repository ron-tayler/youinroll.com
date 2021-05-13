<?php
/**
 * @file /lib/ajax/registerUser.php - Регистрация пользователя
 * @author Ron_Tayler
 * @copyright 11.05.2021
 * @todo Этот файл генерировал предупреждения до код-ревью, нужно проверить
 */

include_once('../../load.php');
require_once(INC.'/recaptchalib.php');

class RegisterException extends Exception{
    private string $type;

    /**
     * RegisterException constructor.
     * @param string $type
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct(string $type = '', string $message = '', int $code = 0, Throwable $previous = null){
        $this->type = $type;
        parent::__construct($message, $code, $previous);
    }

    public function getType(): string{
        return $this->type;
    }
}

$result = '';
$errors = [];
$email = '';

try{
    $valid_category = [4,3,10,8];
    $cat = intval(_post('category'));
    $role = in_array($cat,$valid_category,true)?$cat:4;

    // todo false
    if(false && !isset($_POST['mobile'])) {
        // Проверка по рекапче
        $recaptcha = new \ReCaptcha\ReCaptcha(get_option('recap-secret'));
        $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
        if (!$resp->isSuccess()) throw new RegisterException('captcha', 'The reCAPTCHA wasn\'t entered correctly. Go back and try it again reCAPTCHA error: '.$resp->getErrorCodes()[0]);
    }

    if(get_option('allowlocalreg') != 1 ) throw new RegisterException('forbidden','Локальная регистрация запрещена');
    if(!_post('name') || !_post('password') || !_post('email')) throw new RegisterException('param','Отсутствуют один или несколько входных параметров');

    // Проверка email и password
    if(!filter_var(_post('email'), FILTER_VALIDATE_EMAIL)) throw new RegisterException('email',_lang('Invalid e-mail detected.'));
    if(user::CheckMail(_post('email')) != 0) throw new RegisterException('email',_lang('Email already in use'));
    if(_post('password') != _post('password2')) throw new RegisterException('password',_lang('Passwords are not the same'));

    // Загрузка аватара
    $avatar = 'uploads/def-avatar.jpg';
    if(isset($_FILES['avatar']) && !empty($_FILES['avatar'])){
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

    // добавление нового пользователя
    $keys_values = array(
        'passKey'  =>'uinactive-'.md5(uniqid()),
        'avatar'   => $avatar,
        'local'    => _post('city'),
        'country'  => _post('country'),
        'name'     => _post('name'),
        'email'    => _post('email'),
        'group_id' => $role,
        'chatRoom' => generateRandomString(),
        'password' => sha1(_post('password')),
        'type'     => 'core');
    $id = user::AddUser($keys_values);

    // Проверка после
    if(user::CheckMail(_post('email')) == 0) throw new RegisterException('unknown',_lang('Something went wrong, try again!'));

    // Авторизация нового пользователя
    user::loginbymail(_post('email'),_post('password'));
    $email = urlencode(_post('email'));

    if(is_user()){
        $result = user_id();
    }else{
        $result = '0';
    }
}catch(RegisterException $ex){
    $result = 'error';
    $errors[$ex->getType()]['text'] = $ex->getMessage();
    $errors[$ex->getType()]['code'] = $ex->getCode();
}catch(Exception $ex){
    $result = 'error';
    $errors['exception']['text'] = $ex->getMessage();
    $errors['exception']['code'] = $ex->getCode();
}finally {
    echo(json_encode([
        'result' => $result,
        'errors' => $errors,
        'email' => $email
    ]));
    exit();
}
