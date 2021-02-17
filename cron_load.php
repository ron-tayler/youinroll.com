<?php //Check session start

if (!isset($_SESSION)) { @session_start(); }
// Root 
if( !defined( 'ABSPATH' ) )
	define( 'ABSPATH', str_replace( '\\', '/',  dirname( __FILE__ ) )  );
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
require_once( INC.'/functions.payment.php' );

$YNRpayment = new YNRPayment();
?>