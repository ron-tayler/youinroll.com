<?php

// Site
$_['site_url']              = HTTP_SERVER;
$_['site_ssl']              = HTTPS_SERVER;
$_['url_autostart']         = true;

$_['date_timezone']         = 'Europe/Moscow';

$_['debug_filename']        = 'debug.log';
$_['error_filename']        = 'error.log';
$_['error_display']         = false;
$_['error_log']             = true;

// Template
$_['template_engine']      = 'twig';
$_['template_cache']       = false;
$_['template_bootstrap']   = true;
$_['template_jquery']      = true;

// Autoload Configs
$_['config_autoload']      = array();

// Autoload Libraries
$_['library_autoload']     = array(
    'rtf'
);

// Autoload model
$_['model_autoload']       = array();

// Language
$_['language_directory']   = 'ru-ru';
$_['language_autoload']    = array('ru-ru');

// Cache
$_['cache_engine']         = 'file'; // apc, file, mem or memcached
$_['cache_expire']         = 3600;

// Session
$_['session_engine']       = 'db';
$_['session_autostart']    = true;
$_['session_name']         = 'SESSION_ID';
$_['default_user_group_name'] = 1;

// Database
$_['db_autostart']         = true;
$_['db_engine']            = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']          = DB_HOSTNAME;
$_['db_username']          = DB_USERNAME;
$_['db_password']          = DB_PASSWORD;
$_['db_database']          = DB_DATABASE;
$_['db_port']              = DB_PORT;

// Router
$_['route_default']       = 'common/home';
$_['route_error']         = 'error/not_found';
$_['routes_pre_action']    = array(
    'startup/alias',
    'user/permission'
);
$_['routes_post_action'] = array();
$_['route_event']         = array();

/*/ Action Events
$_['route_event'] = array(
    'controller/* /before' => array(
        'event/language/before'
    ),
    'view/* /before' => array(
        1 => 'event/theme/override',
        2 => 'event/language',
        3 => 'event/theme'
    )
);
//*/