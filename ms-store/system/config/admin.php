<?php
// Site
$_['site_base']         = substr(HTTP_SERVER, 7);
$_['site_ssl']          = false;

// Database
$_['db_autostart']      = true;
$_['db_type']           = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']       = DB_HOSTNAME;
$_['db_username']       = DB_USERNAME;
$_['db_password']       = DB_PASSWORD;
$_['db_database']       = DB_DATABASE;
$_['db_port']           = DB_PORT;

// Database TDM
$_['db_tdm_autostart']     = true;
$_['db_tdm_type']          = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_tdm_hostname']      = MODULE_DB_SERVER;
$_['db_tdm_username']      = MODULE_DB_LOGIN;
$_['db_tdm_password']      = MODULE_DB_PASS;
$_['db_tdm_database']      = MODULE_DB_NAME;
$_['db_tdm_port']          = MODULE_DB_PORT;

// Database TD
$_['db_td_autostart']      = true;
$_['db_td_type']           = DB_DRIVER; // mpdo, mssql, mysql, mysqli or postgre
$_['db_td_hostname']       = TD_DB_HOSTNAME;
$_['db_td_username']       = TD_DB_USERNAME;
$_['db_td_password']       = TD_DB_PASSWORD;
$_['db_td_database']       = TD_DB_DATABASE;
$_['db_td_port']           = TD_DB_PORT;
$_['media_td_path']        = TD_MEDIA_PATH;

// Session
$_['session_autostart'] = true;

// Actions
$_['action_pre_action']  = array(
	'startup/startup',
	'startup/error',
	'startup/event',
	'startup/sass',
	'startup/login',
	'startup/permission'
);

// Actions
$_['action_default']     = 'common/dashboard';

// Action Events
$_['action_event'] = array(
    'view/*/before' => 'event/theme',
);