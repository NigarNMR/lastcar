<?php

/*
  // HTTP
  define('HTTP_SERVER', 'http://eijen.localhost/');

  // HTTPS
  define('HTTPS_SERVER', 'http://eijen.localhost/');

  // DIR
  define('DIR_APPLICATION', 'C:/OpenServer/domains/eijen.localhost/catalog/');
  define('DIR_SYSTEM', 'C:/OpenServer/domains/eijen.localhost/system/');
  define('DIR_IMAGE', 'C:/OpenServer/domains/eijen.localhost/image/');
  define('DIR_LANGUAGE', 'C:/OpenServer/domains/eijen.localhost/catalog/language/');
  define('DIR_TEMPLATE', 'C:/OpenServer/domains/eijen.localhost/catalog/view/theme/');
  define('DIR_CONFIG', 'C:/OpenServer/domains/eijen.localhost/system/config/');
  define('DIR_CACHE', 'C:/OpenServer/domains/eijen.localhost/system/storage/cache/');
  define('DIR_DOWNLOAD', 'C:/OpenServer/domains/eijen.localhost/system/storage/download/');
  define('DIR_LOGS', 'C:/OpenServer/domains/eijen.localhost/system/storage/logs/');
  define('DIR_MODIFICATION', 'C:/OpenServer/domains/eijen.localhost/system/storage/modification/');
  define('DIR_UPLOAD', 'C:/OpenServer/domains/eijen.localhost/system/storage/upload/');

  // DB
  define('DB_DRIVER', 'mysqli');
  define('DB_HOSTNAME', 'localhost');
  define('DB_USERNAME', 'root');
  define('DB_PASSWORD', 'rootpwd');
  define('DB_DATABASE', 'web_eijen_ru');
  define('DB_PORT', '3306');
  define('DB_PREFIX', 'oc_'); */

/* Подгрузка локального конфигурационного файла */
if (is_file($local_config = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.local.php')) {
  include $local_config;
}

/* Подгрузка локального конфигурационного файла TDM */

//define("TDM_PROLOG_INCLUDED", true);
if (is_file($local_config_tdm = dirname(__FILE__) . DIRECTORY_SEPARATOR . 'autoparts' . DIRECTORY_SEPARATOR . 'config.local.php')) {
  if(!defined('TDM_PROLOG_INCLUDED')) define('TDM_PROLOG_INCLUDED', true);
  include $local_config_tdm;
  if (isset($arTDMConfig) && !empty($arTDMConfig) && is_array($arTDMConfig)) {
    foreach ($arTDMConfig as $key => $value) {
      if (is_scalar($value)) {
        define($key, $value);
        //echo 'key = ' . $key . '; value = ' . $value . '<br>';
      }
    }
  }
  //цикл по $arTDMConfig
  // и сохранение всего в константы
}  

