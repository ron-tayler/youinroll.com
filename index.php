<?php
set_time_limit(30);

/* Start API */
$v_api = $_REQUEST['api']??false;
if($v_api){
    $res = preg_match('/v?[1-9]+[0-9]*\.[0-9]+/',$v_api);
    if($res===1){
        require_once __DIR__.'/index_api.php';
    }else{
        header("Content-Type: text/html; charset=utf-8");
        echo json_encode([
            'error'=>[
                'code'=>'1',
                'message'=>'Ошибка в переданном параметре версии api'
            ]
        ],JSON_UNESCAPED_UNICODE);
    }
    exit();
}
/* End API */
if($_COOKIE['landing']!='visited' and $_SERVER['REQUEST_URI']=='/'){
    echo file_get_contents(__DIR__.'/land/index.html');
    exit();
}

/* Start YouInRoll Vibe */
error_reporting(E_ALL);

// Debugging?
$sttime = microtime(true); 

// Security
if( !defined( 'in_phpvibe' ) )
    define( 'in_phpvibe', true);

// Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', __DIR__);

//Include configuration
require_once( ABSPATH.'/vibe_config.php' );


//Check session start
if (!isset($_SESSION)) { session_start(); }

/*** Start static serving from cache ***/
/* Kill static cache for users */
if(isset($_SESSION['user_id']) || isset($_GET['action']))
    $killcache = true;

/* Serve static pages for visitors */
if (isset($killcache) && !$killcache) {
    $a = strip_tags($_SERVER['REQUEST_URI']);
    if(($a === '/') || ($a === '/index.php')) { $a = '/index.php'; }
        /* Exclude specific pages */
        if((strpos($a,'register') == false) && (strpos($a,'dashboard') == false) && (strpos($a,'login') == false) && (strpos($a,'moderator') == false) && (strpos($a,'setup') == false) && !isset($_GET['clang'])) {
        require_once( ABSPATH.'/lib/fullcache.php' );
        /* Create cache token for this page */
        $token = (isset($_SESSION['phpvibe-language'])) ? $a.$_SESSION['phpvibe-language'] : $a;
        /* Run static caching and serving */
        FullCache::Encode($token);
        FullCache::Live();
    }
}

/** End static serving from cache **/
//Vital file include
require_once("load.php");
ob_start();
// Login, maybe?
if (!is_user()) {
    //action = login, logout ; type = twitter, facebook, google
    if (!empty($_GET['action']) && $_GET['action'] == "login") {
        switch ($_GET['type']) {
            case 'facebook':
			if(get_option('allowfb') == 1 ) {
                require_once( INC.'/facebook/autoload.php' );
                $fb = new Facebook\Facebook([
                'app_id'  => Fb_Key,
                'app_secret' => Fb_Secret,
                'default_graph_version' => 'v2.8',
                ]);
                $helper = $fb->getRedirectLoginHelper();
                $permissions = explode (",", $conf_facebook['permissions']); // Optional permissions
                $facebookLoginUrl = $helper->getLoginUrl($conf_facebook['redirect_uri'], $permissions);
                //Send user to login
                redirect($facebookLoginUrl);
			}	
                break;
            case 'google':
			if(get_option('allowg') == 1 ) {
                //Initialize google login
                
				require_once(INC.'/google/Google/Client.php');
				
				$client = new Google_Client();
                $client->setClientId(trim(get_option('GClientID')));
                $client->setClientSecret(trim(get_option('GClientSecret')));
                $client->setRedirectUri($conf_google['return_url']);
                $client->setScopes(array('https://www.googleapis.com/auth/userinfo.email','https://www.googleapis.com/auth/userinfo.profile'));
                $authUrl = $client->createAuthUrl();

                if (!empty($authUrl)) {
                        
                       redirect($authUrl);
                }
             } 
			  break;
        
            default:
                //If any login system found, warn user
                echo _lang('Invalid Login system');
        }
    }
} else {
    if (!empty($_GET['action']) && $_GET['action'] == "logout") {
        //If action is logout, kill sessions
        user::clearSessionData();
        //var_dump($_COOKIE);exit;
       redirect(site_url()."index.php");
    }
}

// Let's start the site
//$page = com();
$id_pos = null;
$router = new Router();
/* Check if it is installed in a folder */
$aFolder  =  parse_url(site_url());
if(isset($aFolder['path']) && not_empty($aFolder['path']) && ($aFolder['path'] !== '/')) {
$router->setBasePath('/'.ltrim($aFolder['path'],'/'));
}
/* End folder check */
do_action('VibePermalinks');
$router->map('/', 'home', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/module/:section/:id', 'module', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map('/faq', 'faq', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/notify', 'notify', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/success', 'success', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/noty', 'noty', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/fail', 'fail', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/payment/:section', 'payment', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map(get_option('profile-seo-url','/profile/:name/:id/'), 'profile', array('methods' => 'GET,PUT,POST', _makeUrlArgs(get_option('profile-seo-url','/profile/:name/:id/'))));
$router->map('/'.premiumhub.'/:section', 'premiumhub', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.videos.'/:section', 'videolist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
//$router->map('/'.lessons.'/:section', 'lessonslist', array('methods' => 'GET, POST', 'filters' => array('section' => '(.*)')));
$router->map('/lessons/:section', 'streams', array('methods' => 'GET, POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.lessonsettings.'/:id', 'lessonsettings', array('methods' => 'GET, POST', 'filters' => array('section' => '(.*)')));
$router->map('/images/:section', 'imageslist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/music/:section', 'musiclist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('channel-seo-url','/category/:name/:id/'), 'category', array('methods' => 'GET', 'filters' => _makeUrlArgs(get_option('channel-seo-url','/category/:name/:id/'))));
$router->map('/playlist/:name/:id/:section', 'playlist', array('methods' => 'GET', 'filters' => array('id' => '(\d+)','section' => '(.*)')));
$router->map(get_option('page-seo-url','/read/:name/:id'), 'page', array('methods' => 'GET', 'filters' => _makeUrlArgs(get_option('page-seo-url','/read/:name/:id'))));
$router->map('/'.me.':section', 'manager', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.blog, 'blog', array('methods' => 'GET'));
$router->map('/'.members.'/:section', 'members', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.playlists.'/:section', 'playlists', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.albums.'/:section', 'albums', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.album.'/:section', 'album', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.blogcat.'/:name/:id/:section', 'blogcat', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map(get_option('article-seo-url','/read/:name/:id'), 'post', array('methods' => 'GET', 'filters' => _makeUrlArgs(get_option('article-seo-url','/read/:name/:id'))));
$router->map('/forward/:section',  'forward', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/msg/:section',  'msg', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/conversation/:id',  'conversation', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/login/:section', 'login',  array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/register/:section', 'register', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/resetpassword/:section', 'resetpassword', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.buzz.'/:section', 'buzz', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.show.'/:section', 'search', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.imgsearch.'/:section', 'searchimages', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.pplsearch.'/:section', 'searchppl', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/'.playlistsearch.'/:section', 'searchpaylist', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/api/:section', 'api', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/oauth/:section', 'oauth', array('methods' => 'GET,POST', 'filters' => array('section' => '(.*)')));
$router->map('/embed/:section', 'embed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/feed/:section', 'feed', array('methods' => 'GET', 'filters' => array('section' => '(.*)')));
$router->map('/share/:section', 'share', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upimage.'/:section', 'addimage', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.upmusic.'/:section', 'addmusic', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.add.'/:section', 'add', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/'.addconf.'/:section', 'addconf', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/dashboard/:section', 'dashboard', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));

$router->map('/testmail', 'testmail', array('methods' => 'GET', 'filters' => array('id' => '(\d+)')));
$router->map('/studio/:section', 'studio', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));
$router->map('/landing/:section', 'landing', array('methods' => 'GET,PUT,POST', 'filters' => array('section' => '(.*)')));

/* Single video or song */
$router->map(get_option('video-seo-url','/video/:id/:name'), 'video', array('methods' => 'GET', 'filters' => _makeUrlArgs(get_option('video-seo-url','/video/:id/:name'))));
/* Single conference or song */
$router->map('/lesson/:id/:name', 'stream', array('methods' => 'GET', 'filters' => _makeUrlArgs('/conference/:id/:name')));
$router->map('/rtf/:section/:id', 'rtf_test', array('methods' => 'GET,POST', 'filters' => _makeUrlArgs('/rtf/:section/:id')));
$router->map('/conference/:id/:name', 'conference', array('methods' => 'GET', 'filters' => _makeUrlArgs('/conference/:id/:name')));
//$router->map('/stream/:id/:name', 'stream', array('methods' => 'GET', 'filters' => _makeUrlArgs('/stream/:id/:name')));
$router->map('/createstream', 'createstream', array('methods' => 'GET'));
/* Single image */
$router->map(get_option('image-seo-url','/image/:id/:name'), 'image', array('methods' => 'GET', 'filters' => _makeUrlArgs(get_option('image-seo-url','/image/:id/:name'))));
//Match
$route = $router->matchCurrentRequest();
//end routing
/* include the theme functions / filters */
//Global tpl
if($route) {	
    /* Assign page from route */
    $page = $route->getTarget();
    //print_r($route);
}

include_once(TPL.'/tpl.globals.php');
//If offline
if(!is_admin() && (get_option('site-offline', 0) == 1 )) { 
    layout('offline');
    exit();
}
//Include com
if($route) {
    include_once(ABSPATH."/com/com_".$route->getTarget().".php");
} else {
    include_once(ABSPATH."/com/com_404.php");
}


//end sitewide
ob_end_flush();
//Debugging 
/*
if(is_admin()) {
echo "<pre class=\"footerdebug\" style='text-align:center'>Time Elapsed: ".(microtime(true) - $sttime)."s</pre> <br />
".$db->debug();
}
*/


//That's all folks!
?>