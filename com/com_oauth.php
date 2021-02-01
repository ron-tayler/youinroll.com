<?php
$path = token();

$oauthClass = new OAuth();
$method = $_SERVER['REQUEST_METHOD'];

$params = [];

switch ($method)
{
    case 'GET':
        foreach ($_GET as $key => $value)
        {
           $params[$key] = $value;
        }
        break;

    case 'POST':
        foreach ($_POST as $key => $value)
        {
            $params[$key] = $value;
        }
        break;
    
    default:
        $params = [];
        break;
}


print_r($oauthClass->init($path, $method, $params));

class OAuth {

    private $path; // Path of route
    private $method; // Method (GET, POST and etc.)
    private $params; //Array with params of request
    private $result = []; // Returned data

    /* Rows for returned user info */
    const userRows = [
        'id',
        'group_id',
        'email',
        'name',
        'avatar',
        'cover',
        'type',
        'gid',
        'fid',
        'oauth_token',
        'local',
        'country',
        'bio',
        'views',
        'fblink',
        'twlink',
        'glink',
        'iglink',
        'gender',
        'lastlogin',
        'lastNoty'
    ];

    public function init(string $path, string $method, array $params)
    {
        $this->path = $path;
        $this->method = $method;
        $this->params = $params;

        if($this->checkClient())
        {
            $this->checkPath();
        }

        return json_encode($this->result);
    }

    /* Проверка доступа к апи у приложения */
    private function checkClient() :bool
    {
        $isActive = false;

        if( isset($this->params['client_secret']) )
        {
            global $db;
            $row = $db->get_row("SELECT * from ".DB_PREFIX."api_clients where client_secret='". toDB($this->params['client_secret']) ."' AND is_active='1'");

            if($row !== null)
            {
                $isActive = true;
            }
        }

        return $isActive;
    }

    /* Проверка роута */
    private function checkPath()
    {
        switch ($this->path)
        {
            case 'login':

                //if($this->method === 'POST')
                //{
                    $this->result = $this->routeLogin();
                //}
                
                break;
            
            default:
                
                break;
        }
    }

    private function routeLogin()
    {
        $result = [];

        if( isset($this->params['login']) && isset($this->params['password']) )
        {
            $passwordValid = user::loginbymail($this->params['login'], $this->params['password']);
            if($passwordValid)
            {
                global $db;
                $userId = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE email ='" . toDB($this->params['login']) . "'");
                
                if($userId !== null)
                {
                    $token = $this->generateOauth();
                    
                    $db->query("UPDATE  ".DB_PREFIX."users SET oauth_token='". toDB($token) ."' WHERE id ='" . toDB($userId) . "'");

                    $result = $db->get_row("SELECT ". implode(',', self::userRows) ." FROM ".DB_PREFIX ."users WHERE id = '". toDB($userId) ."'");
                }
            }
        }

        if( isset($this->params['login']) && isset($this->params['gid']) )
        {
            global $db;
            $userId = $db->get_var("SELECT id FROM ".DB_PREFIX."users WHERE type ='google' and gid ='" . toDB($this->params['gid']) . "'");
            
            if($userId !== null)
            {
                $token = $this->generateOauth();
                
                $db->query("UPDATE  ".DB_PREFIX."users SET oauth_token='". toDB($token) ."' WHERE id ='" . toDB($userId) . "'");

                $result = $db->get_row("SELECT ". implode(',', self::userRows) ." FROM ".DB_PREFIX ."users WHERE id = '". toDB($userId) ."'");
            }

        }

        /* echo "<pre>";
        var_dump($result);
        die(); */

        return $result;
    }

    private function generateOauth()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return md5($randomString);
    }
}
?>