<?php
// Site
$_['site_base']            = '';
$_['site_ssl']             = false;

// Language
$_['language_default']     = 'en-gb';
$_['language_autoload']    = array('en-gb');

// Database
$_['db_autostart']         = false;
$_['db_type']              = 'mysqli'; // mpdo, mssql, mysql, mysqli or postgre
$_['db_hostname']          = 'localhost';
$_['db_username']          = 'root';
$_['db_password']          = '';
$_['db_database']          = '';
$_['db_port']              = 3306;

// Database TDM
$_['db_tdm_autostart']     = true;
$_['db_tdm_type']          = 'mysqli'; // mpdo, mssql, mysql, mysqli or postgre
$_['db_tdm_hostname']      = 'localhost';
$_['db_tdm_username']      = 'root';
$_['db_tdm_password']      = '';
$_['db_tdm_database']      = '';
$_['db_tdm_port']          = 3306;

// Database TD
$_['db_td_autostart']      = true;
$_['db_td_type']           = 'mysqli'; // mpdo, mssql, mysql, mysqli or postgre
$_['db_td_hostname']       = '178.32.148.174';
$_['db_td_username']       = 'larriat0906';
$_['db_td_password']       = 'wocky25423';
$_['db_td_database']       = 'TD';
$_['db_td_port']           = 3306;
$_['media_td_path']        = '77.120.224.229/';

// Mail
$_['mail_protocol']        = 'mail'; // mail or smtp
$_['mail_from']            = ''; // Your E-Mail
$_['mail_sender']          = ''; // Your name or company name
$_['mail_reply_to']        = ''; // Reply to E-Mail
$_['mail_smtp_hostname']   = '';
$_['mail_smtp_username']   = '';
$_['mail_smtp_password']   = '';
$_['mail_smtp_port']       = 25;
$_['mail_smtp_timeout']    = 5;
$_['mail_verp']            = false;
$_['mail_parameter']       = '';

// Cache
$_['cache_type']           = 'file'; // apc, file or mem
$_['cache_expire']         = 3600;

// Session
$_['session_autostart']    = true;
$_['session_name']         = 'PHPSESSID';

// Template
$_['template_type']        = 'basic';

// Error
$_['error_display']        = true;
$_['error_log']            = true;
$_['error_filename']       = 'error.log';

// Reponse
$_['response_header']      = array('Content-Type: text/html; charset=utf-8');
$_['response_compression'] = 0;

// Autoload Configs
$_['config_autoload']      = array();

// Autoload Libraries
$_['library_autoload']     = array();

// Autoload Libraries
$_['model_autoload']       = array();

// Actions
$_['action_default']       = 'common/home';
$_['action_router']        = 'startup/router';
$_['action_error']         = 'error/not_found';
$_['action_pre_action']    = array();
$_['action_event']         = array();