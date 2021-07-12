<?php //Check session start

// Дебагинг
define('DEBUG_PRIVATE',1);
define('DEBUG_TRACE',1<<1);
define('DEBUG_ERROR',1<<2);
define('DEBUG_REQUEST',1<<3);
define('DEBUG_GET',1<<4);
define('DEBUG_POST',1<<5);
$debug_types =[
    '0'         => 0,
    'private'   => DEBUG_PRIVATE,
    'trace'     => DEBUG_TRACE,
    'error'     => DEBUG_ERROR,
    'request'   => DEBUG_REQUEST,
    'get'       => DEBUG_GET,
    'post'      => DEBUG_POST,
];
$debug_types['all'] = array_sum($debug_types);
$debug_text = (($_GET['dev_token']??'')=='953C54')?($_GET['debug']??''):'';
$debug_type = 0;
foreach (explode(',',$debug_text) as $el) $debug_type |= ($debug_types[$el]??0);
define('DEBUG_TYPE',$debug_type??0);
unset($debug_types,$debug_type,$debug_text);

if(DEBUG_TYPE & DEBUG_ERROR)
    ini_set('display_errors', 1);
else
    ini_set('display_errors', 0);

// Log
require(__DIR__.'/lib/class.log.php');
$err_log = new Log(__DIR__.'/logs/error');

set_error_handler(function($code, $message, $file, $line) use($err_log) {
    // error suppressed with @
    if (error_reporting() === 0) {
        return false;
    }
    switch ($code) {
        case E_NOTICE:
        case E_USER_NOTICE:
            $error = 'Notice';
            break;
        case E_WARNING:
        case E_USER_WARNING:
            $error = 'Warning';
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $error = 'Fatal Error';
            break;
        default:
            $error = 'Unknown';
            break;
    }
    if (false) {
        echo '<b>' . $error . '</b>: ' . $message . ' in <b>' . $file . '</b> on line <b>' . $line . '</b>';
    }
    if (true) {
        $err_log->write('PHP ' . $error . ':  ' . $message . ' in ' . $file . ' on line ' . $line);
    }
    return true;
});

if (!isset($_SESSION)) { @session_start(); }
// Root
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', __DIR__  );
// Includes
if( !defined( 'INC' ) )
	define( 'INC', ABSPATH.'/lib' );
// Security
if( !defined( 'in_phpvibe' ) )
	define( 'in_phpvibe', true);
// Configs
require_once( ABSPATH.'/vibe_config.php' );
require_once( ABSPATH.'/vibe_setts.php' );
// Sql db classes
require_once( INC.'/ez_sql_core.php' );

if( !defined( 'cacheEngine' ) || (cacheEngine == "mysql") ) {
    require_once( INC.'/ez_sql_mysql.php' );

    /* Define live db for MySql */
    $db = new ezSQL_mysql(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');

    /* Define cached db for MySql */
    $cachedb = new ezSQL_mysql(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');
} else {
    require_once( INC.'/ez_sql_mysqli.php' );

    /* Define live db for MySql Improved */
    $db = new ezSQL_mysqli(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');

     /* Define cached db for MySql Improved */
    $cachedb = new ezSQL_mysqli(DB_USER,DB_PASS,DB_NAME,DB_HOST,'utf8');
}

if( !defined( 'DB_CACHE' ) ) {
    $cachedb->cache_timeout = 6; /* Note: this is hours */
} else { $cachedb->cache_timeout = DB_CACHE; }

$cachedb->cache_dir = ABSPATH.'/storage/cache';
$cachedb->use_disk_cache = true;
$cachedb->cache_queries = true;
// Include functions
require_once( INC.'/Router.php' );
require_once( INC.'/Route.php' );
require_once( INC.'/HashGenerator.php');
require_once( INC.'/functions.permalinks.php');
require_once( INC.'/Hashids.php');
require_once( INC.'/functions.plugins.php' );
require_once( INC.'/functions.html.php' );
require_once( INC.'/functions.php' );
require_once( INC.'/functions.videoads.php' );
require_once( INC.'/functions.user.php' );
require_once( INC.'/functions.kses.php' );
require_once( INC.'/functions.templates.php' );
require_once( INC.'/functions.payment.php' );
require_once( INC.'/comments.php' );

$YNRtemplate = new YNRTemplate();
$YNRpayment = new YNRPayment();

// Theme
if( !defined( 'THEME' ) )
	define( 'THEME', get_option('theme','main') );
// Themes directory
if( !defined( 'TPL' ) )
	define( 'TPL', ABSPATH.'/tpl/'.THEME);
// Site options
$all_options = get_all_options();
// Global classes
require_once( INC.'/class.upload.php' );
require_once( INC.'/class.providers.php' );
require_once( INC.'/class.pagination.php' );
require_once( INC.'/class.phpmailer.php' );
require_once( INC.'/class.images.php' );
require_once( INC.'/class.youtube.php' );
// Fix some slashes
if ( /*get_magic_quotes_gpc() устарело с версии 7.4*/false ) {
    $_POST      = array_map( 'stripslashes_deep', $_POST );
    $_GET       = array_map( 'stripslashes_deep', $_GET );
    $_COOKIE    = array_map( 'stripslashes_deep', $_COOKIE );
    $_REQUEST   = array_map( 'stripslashes_deep', $_REQUEST );
}
// Current translation
$trans = init_lang();
// Plugins
if(!is_null(get_option('activePlugins',null))) {
//Plugins array
$Plugins = explode(",",get_option('activePlugins',null));
if(!empty($Plugins) && is_array($Plugins)){
// Plugins loop
foreach ($Plugins as $plugin) {
if(file_exists(plugin_inc($plugin))) { include_once(plugin_inc($plugin)); }
}
}
}
// Twitter Login
define( 'Tw_Key', get_option('Tw_Key') ); define( 'Tw_Secret', get_option('Tw_Secret') );
//Facebook API Login
define( 'Fb_Key', get_option('Fb_Key') ); define( 'Fb_Secret', get_option('Fb_Secret'));
// OnSite Login
define('COOKIEKEY', get_option('COOKIEKEY') ); define('SECRETSALT', get_option('SECRETSALT')); define( 'COOKIESPLIT', get_option('COOKIESPLIT') );
// Cookie logins
authByCookie(); validate_session();
if(is_user()) {$killcache = true;}
?>
