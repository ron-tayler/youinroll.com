<?php
include_once('../../load.php');
require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';

use Google_Client;

try {
    
    if(user_id() !== null)
    {
        $user = $db->get_results("SELECT * FROM ".DB_PREFIX."users where id = ".toDb(user_id()));

    } else
    {
        layout(404);
        die();
    }
    if(isset($_POST['code']))
    {
        $code = $_POST['code'];

        $client = new Google_Client();
        $client->setApplicationName('Quickstart');
        $client->setScopes(Google_Service_Calendar::CALENDAR_READONLY);
        $client->setAuthConfig(__DIR__.'/credentionals.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');
        
        try {

            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            $client->setAccessToken($accessToken);

            print_r(json_encode($client->getAccessToken(), true));
            $db->query("UPDATE ".DB_PREFIX."users SET gcalendar = '".toDb(json_encode($client->getAccessToken()))."' WHERE id = '" . sanitize_int(user_id()) . "'");

        } catch (\Throwable $th) {
            echo "<pre>";
            print($th);
            die();
        }
    }

} catch (\Throwable $th) {
    //throw $th;
    echo "<pre>";
    print_r($th);
    die();
}


?>