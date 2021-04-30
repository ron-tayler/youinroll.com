<?php //security check
if( !defined( 'in_phpvibe' ) || (in_phpvibe !== true) ) {
die();
}
/* This is your phpVibe config file.
* Edit this file with your own settings following the comments next to each line
*/

/*
** MySQL settings - You can get this info from your web host
*/

/** MySQL database username */
define( 'DB_USER', 'xatikont_youinro' );

/** MySQL database password */
define( 'DB_PASS', 'Cvbn636153' );

/** The name of the database */
define( 'DB_NAME', 'xatikont_youinro' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** MySQL tables prefix */
define( 'DB_PREFIX', 'vibe_' );

/** MySQL cache timeout */
/** For how many hours should queries be cached? **/
define( 'DB_CACHE', '12' );

/*
** Site options
*/
/** License key 
Create it in the store, under "My Licenses" **/
define( 'phpVibeKey', '1486h5589ad' );

/** Site url (with end slash, ex: http://www.domain.com/ ) **/
define( 'SITE_URL', 'https://youinroll.com/' );

/** Admin folder, rename it and change it here **/
define( 'ADMINCP', 'moderator' );

/* Choose between mysqli (improved) and (old) mysql */
 define( 'cacheEngine', 'mysqli' ); 
 
/** Timezone (set your own) **/
date_default_timezone_set('Europe/Moscow');

/** Your Paypal email **/
define( 'PPMail', 'xatiko540@yandex.ru' );
/*
 ** Mail settings.
 */  
$adminMail = 'smtp.beget.com';
$mvm_useSMTP = false; /* Use smtp for mails? */
/* true: Use smtp | false : uses's PHP's sendmail() function */
$mvm_host = 'smtp.beget.com';  /* Main SMTP server */
$mvm_user = 'youinroll@youinroll.com'; /* SMTP username */
$mvm_pass = 'lCkQ&1zw'; /* SMTP password */
$mvm_secure = 'tls'; /* Enable TLS encryption, `ssl` also accepted */
$mvm_port = '465';  /* TCP port to connect to	*/
/*
 ** Full cache settings.
 */  
$killcache = true; /* true: disabled full cache (recommended for starters); false : enabled full cache */
$cachettl = 10200; /* $ttl = Expiry time in seconds for cache's static html pages */ 
/* 1 day = 86400; 1 hour = 3600; */ 
/*
** Custom settings would go after here.
*/
function is_licensed_true() {return true;}?>