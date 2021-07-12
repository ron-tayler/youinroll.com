<?php
set_time_limit(30);

/**********************************************************************************************************************/
/* Перевод на API или Landing                                                                                         */

$v_api = $_REQUEST['api'] ?? false;
if ($v_api) {
    $res = preg_match('/v?[1-9]+[0-9]*\.[0-9]+/', $v_api);
    if ($res === 1) {
        require_once __DIR__ . '/index_api.php';
    } else {
        header("Content-Type: text/html; charset=utf-8");
        echo json_encode([
            'error' => [
                'code' => '1',
                'message' => 'Ошибка в переданном параметре версии api'
            ]
        ], JSON_UNESCAPED_UNICODE);
    }
    exit();
}

/* Перевод на лендинг */
if ($_COOKIE['landing'] != 'visited' and $_SERVER['REQUEST_URI'] == '/') {
    setcookie('landing', 'visited',);
    echo file_get_contents(__DIR__ . '/land/index.html');
    exit();
}

/**********************************************************************************************************************/
/* Различная инициализация                                                                                            */

error_reporting(E_ALL);
$sttime = microtime(true);
if (!defined('in_phpvibe')) {
    define('in_phpvibe', true);
}
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__);
}
require_once(ABSPATH . '/vibe_config.php');
if (!isset($_SESSION)) {
    session_start();
}
if (isset($_SESSION['user_id']) || isset($_GET['action'])) {
    $killcache = true;
}
if (isset($killcache) && !$killcache) {
    $a = strip_tags($_SERVER['REQUEST_URI']);
    if (($a === '/') || ($a === '/index.php')) {
        $a = '/index.php';
    }
    if ((strpos($a, 'register') == false) && (strpos($a, 'dashboard') == false) && (strpos($a, 'login') == false) && (strpos($a, 'moderator') == false) && (strpos($a, 'setup') == false) && !isset($_GET['clang'])) {
        require_once(ABSPATH . '/lib/fullcache.php');
        $token = (isset($_SESSION['phpvibe-language'])) ? $a . $_SESSION['phpvibe-language'] : $a;
        FullCache::Encode($token);
        FullCache::Live();
    }
}
require_once("load.php");
ob_start();
if (!is_user()) {
    if (!empty($_GET['action']) && $_GET['action'] == "login") {
        switch ($_GET['type']) {
            case 'facebook':
                if (get_option('allowfb') == 1) {
                    require_once(INC . '/facebook/autoload.php');
                    $fb = new Facebook\Facebook([
                        'app_id' => Fb_Key,
                        'app_secret' => Fb_Secret,
                        'default_graph_version' => 'v2.8',
                    ]);
                    $helper = $fb->getRedirectLoginHelper();
                    $permissions = explode(",", $conf_facebook['permissions']); // Optional permissions
                    $facebookLoginUrl = $helper->getLoginUrl($conf_facebook['redirect_uri'], $permissions);
                    redirect($facebookLoginUrl);
                }
                break;
            case 'google':
                if (get_option('allowg') == 1) {
                    require_once(INC . '/google/Google/Client.php');
                    $client = new Google_Client();
                    $client->setClientId(trim(get_option('GClientID')));
                    $client->setClientSecret(trim(get_option('GClientSecret')));
                    $client->setRedirectUri($conf_google['return_url']);
                    $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email', 'https://www.googleapis.com/auth/userinfo.profile'));
                    $authUrl = $client->createAuthUrl();
                    if (!empty($authUrl)) {
                        redirect($authUrl);
                    }
                }
                break;
            default:
                echo _lang('Invalid Login system');
        }
    }
} else {
    if (!empty($_GET['action']) && $_GET['action'] == "logout") {
        user::clearSessionData();
        redirect(site_url() . "index.php");
    }
}
$id_pos = null;
$router = new Router();
$aFolder = parse_url(site_url());
if (isset($aFolder['path']) && not_empty($aFolder['path']) && ($aFolder['path'] !== '/')) {
    $router->setBasePath('/' . ltrim($aFolder['path'], '/'));
}
do_action('VibePermalinks');

/**********************************************************************************************************************/
/* Настройка маршрутизации                                                                                            */

foreach ([
     ['url'=>'/',                            'target'=>'categories',     'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)']]],
     ['url'=>'/albums/:section',             'target'=>'albums',         'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/articles/:name/:id/:section', 'target'=>'blogcat',        'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/album/:section',              'target'=>'album',          'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/add-image/:section',          'target'=>'addimage',       'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/add-music/:section',          'target'=>'addmusic',       'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/activity/:section',           'target'=>'buzz',           'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/api/:section',                'target'=>'api',            'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/add-video/:section',          'target'=>'add',            'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/addconf/:section',            'target'=>'addconf',        'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/blog',                        'target'=>'blog',           'args'=>['methods' => 'GET']],
     ['url'=>'/conference/:id/:name',        'target'=>'conference',     'args'=>['methods' => 'GET',            'filters' => _makeUrlArgs('/conference/:id/:name')]],
     ['url'=>'/createstream',                'target'=>'createstream',   'args'=>['methods' => 'GET']],
     ['url'=>'/chat',                        'target'=>'chat',           'args'=>['methods' => 'GET']],
     ['url'=>'/conversation/:id',            'target'=>'conversation',   'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)']]],
     ['url'=>'/categories',                  'target'=>'categories',     'args'=>['methods' => 'GET']],
     ['url'=>'/dashboard/:section',          'target'=>'dashboard',      'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/embed/:section',              'target'=>'embed',          'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/faq',                         'target'=>'faq',            'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)']]],
     ['url'=>'/fail',                        'target'=>'fail',           'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/feed/:section',               'target'=>'feed',           'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/forward/:section',            'target'=>'forward',        'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/images/:section',             'target'=>'imageslist',     'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/imgsearch/:section',          'target'=>'searchimages',   'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/lessons/:section',            'target'=>'streams',        'args'=>['methods' => 'GET, POST',      'filters' => ['section' => '(.*)']]],
     ['url'=>'/lessonsettings/:id',          'target'=>'lessonsettings', 'args'=>['methods' => 'GET, POST',      'filters' => ['section' => '(.*)']]],
     ['url'=>'/lists/:section',              'target'=>'playlists',      'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/login/:section',              'target'=>'login',          'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/landing/:section',            'target'=>'landing',        'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/lesson/:id/:name',            'target'=>'stream',         'args'=>['methods' => 'GET',            'filters' => _makeUrlArgs('/conference/:id/:name')]],
     ['url'=>'/me:section',                  'target'=>'manager',        'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/module/:section/:id',         'target'=>'module',         'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)', 'section' => '(.*)']]],
     ['url'=>'/music/:section',              'target'=>'musiclist',      'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/msg/:section',                'target'=>'msg',            'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/notify',                      'target'=>'notify',         'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/noty',                        'target'=>'noty',           'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/oauth/:section',              'target'=>'oauth',          'args'=>['methods' => 'GET,POST',       'filters' => ['section' => '(.*)']]],
     ['url'=>'/payment/:section',            'target'=>'payment',        'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/premiumhub/:section',         'target'=>'premiumhub',     'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/playlist/:name/:id/:section', 'target'=>'playlist',       'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)', 'section' => '(.*)']]],
     ['url'=>'/pplsearch/:section',          'target'=>'searchppl',      'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/playlistsearch/:section',     'target'=>'searchpaylist',  'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/register/:section',           'target'=>'register',       'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/resetpassword/:section',      'target'=>'resetpassword',  'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/rtf/:section/:id',            'target'=>'rtf_test',       'args'=>['methods' => 'GET,POST',       'filters' => _makeUrlArgs('/rtf/:section/:id')]],
     ['url'=>'/success',                     'target'=>'success',        'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/show/:section',               'target'=>'search',         'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/share/:section',              'target'=>'share',          'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/studio/:section',             'target'=>'studio',         'args'=>['methods' => 'GET,PUT,POST',   'filters' => ['section' => '(.*)']]],
     ['url'=>'/testmail',                    'target'=>'testmail',       'args'=>['methods' => 'GET',            'filters' => ['id' => '(\d+)']]],
     ['url'=>'/users/:section',              'target'=>'members',        'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>'/videos/:section',             'target'=>'videolist',      'args'=>['methods' => 'GET',            'filters' => ['section' => '(.*)']]],
     ['url'=>get_option('profile-seo-url', '/profile/:name/:id/'),'target'=>'profile','args'=>['methods' => 'GET,PUT,POST', _makeUrlArgs(get_option('profile-seo-url', '/profile/:name/:id/'))]],
     ['url'=>get_option('channel-seo-url', '/category/:name/:id/'),'target'=>'category','args'=>['methods' => 'GET', 'filters' => _makeUrlArgs(get_option('channel-seo-url', '/category/:name/:id/'))]],
     ['url'=>get_option('page-seo-url', '/read/:name/:id'),'target'=>'page','args'=>['methods' => 'GET', 'filters' => _makeUrlArgs(get_option('page-seo-url', '/read/:name/:id'))]],
     ['url'=>get_option('article-seo-url', '/read/:name/:id'),'target'=>'post','args'=>['methods' => 'GET', 'filters' => _makeUrlArgs(get_option('article-seo-url', '/read/:name/:id'))]],
     ['url'=>get_option('video-seo-url', '/video/:id/:name'),'target'=>'video','args'=>['methods' => 'GET', 'filters' => _makeUrlArgs(get_option('video-seo-url', '/video/:id/:name'))]],
     ['url'=>get_option('image-seo-url', '/image/:id/:name'),'target'=>'image','args'=>['methods' => 'GET', 'filters' => _makeUrlArgs(get_option('image-seo-url', '/image/:id/:name'))]]
] as $route_map_el){
    $router->map($route_map_el['url'],$route_map_el['target'],$route_map_el['args']);
}

/**********************************************************************************************************************/
/* Запуск контроллеров и шаблонов                                                                                     */

$route = $router->matchCurrentRequest();
if ($route) {
    $page = $route->getTarget();
}
include_once(TPL . '/tpl.globals.php');
if (!is_admin() && (get_option('site-offline', 0) == 1)) {
    layout('offline');
    exit();
}
if ($route) {
    include_once(ABSPATH . "/com/com_" . $route->getTarget() . ".php");
} else {
    include_once(ABSPATH . "/com/com_404.php");
}

ob_end_flush();

?>
