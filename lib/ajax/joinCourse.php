<?php
/* ini_set('display_errors', 1); */
include_once('../../load.php');

$senderMail = $_GET['mail'];

$senderName = toDb($_GET['name']);

if(isset($_GET['course']))
{
    $courseId = intval($_GET['course']);

    $course = $db->get_row("SELECT
            course.*,
            USER.name AS NAME,
            USER.avatar AS avatar
        FROM
            ".DB_PREFIX."landings AS course
        INNER JOIN ".DB_PREFIX."users AS USER
        ON
            course.user_id = USER.id AND course.id = '$courseId'
        LIMIT 0,1");

    if($course !== null && $senderMail !== null && $senderName !== null)
    {
        $userInfo = json_decode($course->userInfo);

        $mail = new PHPMailer;
        $message = "
            <div>
                <h1>$course->title</h1>
                <h2>$userInfo->name</h2>
                <br>
                <h3>Новый участник курса: </h3>
                <h2>$senderName, $senderMail</h2>
            </div>
        ";

        $mail->isSMTP();                                      // Set mailer to use SMTP
        $mail->Host = $mvm_host;  
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = $mvm_user;
        $mail->Sender = $mvm_user;
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';        
        $mail->Password = $mvm_pass;                           // SMTP password
        $mail->SMTPSecure = $mvm_secure;                            
        $mail->Port = $mvm_port;
        $mail->FromName = mb_convert_encoding('YouInRoll', "UTF-8", "auto");                                    
        $mail->From = 'youinroll@youinroll.com';

        /* if(isset($adminMail) && not_empty($adminMail)) {
            $mail->From = $adminMail;
        } else {
            $mail->From = "noreply@".ltrim(cookiedomain(),".");
        }

        $mail->FromName ='YouInRoll'; */	
        //$mail->addAddress('tumanych17498@yandex.ru', toDb($senderName));
        $mail->addAddress($userInfo->email, toDb($userInfo->name));
        $mail->WordWrap = 50;
        $mail->Subject =  mb_convert_encoding($course->title, 'UTF-8', 'auto');
        $mail->Body    = $message;
        $mail->AltBody = $message;

        $db->query('INSERT INTO '.DB_PREFIX."course_forms(course_id, email, name, ip, uagent, source)
             VALUES ('$courseId', '$senderMail', '$senderName', '".$_SERVER['REMOTE_ADDR']."', '".$_SERVER['HTTP_USER_AGENT']."', '".$_SERVER['HTTP_REFERER']."')"
        );

        $mail->send();
    }

    header('Location: '. playlist_url($course->playlist_id, $course->title));
}
?>