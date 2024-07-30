<?php

/*
  Documentation:
  http://ws.armtek.ru/?page=service&alias=search

  Result array sample:
  stdClass Object
  (
  [STATUS] => 200
  [MESSAGES] => Array
  (
  )

  [RESP] => Array
  (
  [0] => stdClass Object
  (
  [PIN] => 9036945003
  [BRAND] => TOYOTA
  [NAME] => Подшипник ступицы передний
  [ARTID] => 177178
  [PARNR] => 144311
  [KEYZAK] => 0000011610
  [RVALUE] => 3
  [RDPRF] => 1
  [MINBM] => 1
  [VENSL] => 95.00
  [PRICE] => 2921.09
  [WAERS] => RUB
  [DLVDT] => 20160802200000
  [ANALOG] =>
  )
  [1] => stdClass Object
  (
  [PIN] => DAC4584W1CS81
  [BRAND] => KOYO
  [NAME] => Подшипник ступичный KOYO 75058
  [ARTID] => 177177
  [PARNR] => 111753
  [KEYZAK] => 0000011117
  [RVALUE] => 50
  [RDPRF] => 1
  [MINBM] => 1
  [VENSL] => 94.00
  [PRICE] => 2283.30
  [WAERS] => RUB
  [DLVDT] => 20160802200000
  [ANALOG] => X
  )
  )
  )
 */
/**
 * настройка подключения к веб-сервисам
 * 
 * @var array
 */
$user_settings = array(
    'user_login' => $arWS['LOGIN']   // логин 
    , 'user_password' => $arWS['PASSW']  // пароль
);


/**
 * настройки по умолчанию
 * 
 * @param VKORG - сбытовая организация.
 * @param KUNNR_RG - код покупателя
 * @param KUNNR_WE - код грузополучателя
 * @param KUNNR_ZA - код адреса доставки
 * @param INCOTERMS - самовывоз
 * @param PARNR - код контактного лица
 * @param VBELN - номер договора
 * 
 * @var array
 */
$ws_default_settings = array(
    'VKORG' => ''
    , 'KUNNR_RG' => ''
    , 'KUNNR_WE' => ''
    , 'KUNNR_ZA' => ''
    , 'INCOTERMS' => ''
    , 'PARNR' => ''
    , 'VBELN' => ''
    , 'format' => 'json'
);
require_once dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'ArmtekRestClient_v1.0.1' . DIRECTORY_SEPARATOR . 'autoloader.php';

//echo '<pre>'; print_r($arWsParts); echo '</pre>'; 
//echo '<pre>'; print_r($arWS); echo '</pre>';

use ArmtekRestClient\Http\Exception\ArmtekException as ArmtekException;
use ArmtekRestClient\Http\Config\Config as ArmtekRestClientConfig;
use ArmtekRestClient\Http\ArmtekRestClient as ArmtekRestClient;

// init configuration 
$armtek_client_config = new ArmtekRestClientConfig($user_settings);

// init client
global $armtek_client;
if (empty($armtek_client)) {
    $armtek_client = new ArmtekRestClient($armtek_client_config);
}

// получение VKORG
$vkorg = '';

// requeest params for send
$request_params = [
    'url' => 'user/getUserVkorgList',
];

// send data
global $response;
if (empty($response)) {
    $response = $armtek_client->get($request_params);
}

// in case of json
global $getUserVkorgList;
if (empty($getUserVkorgList)) {
    $getUserVkorgList = $response->json();
}



if (isset($getUserVkorgList->RESP) && !empty($getUserVkorgList->RESP)){
    foreach ($getUserVkorgList->RESP as $key => $value) {
  //@note тут нужно продумать получение и использование полного списка VKORG
            $vkorg = $value->VKORG;
}
}

if ($vkorg) {
  // получение KUNNR_RG
  $kunnr_rg = '';

  $params = [
      'VKORG' => $vkorg
      , 'STRUCTURE' => 1
      , 'FTPDATA' => ''
  ];

  // requeest params for send
  $request_params = [

      'url' => 'user/getUserInfo',
      'params' => [
          'VKORG' => !empty($params['VKORG']) ? $params['VKORG'] : (isset($ws_default_settings['VKORG']) ? $ws_default_settings['VKORG'] : '')
          , 'STRUCTURE' => isset($params['STRUCTURE']) ? $params['STRUCTURE'] : ''
          , 'FTPDATA' => isset($params['FTPDATA']) ? $params['FTPDATA'] : ''
          , 'format' => 'json'
      ]
  ];

  // send data
  $response = $armtek_client->post($request_params);

  // in case of json
  $getUserInfo = $response->json();


  //var_dump($getUserInfo->RESP);
  //var_dump($getUserInfo->RESP->STRUCTURE);
    if (isset($getUserInfo->RESP->STRUCTURE->RG_TAB)){
        foreach ($getUserInfo->RESP->STRUCTURE->RG_TAB as $value) {
            //@note тут также сделать использование полного списка KUNNR
            //var_dump($value);
            $kunnr_rg = $value->KUNNR;
        }
    }

  if ($kunnr_rg) {
    //var_dump($arWsParts);
    //foreach ($arWsParts as $arPart) {
      $params = [
          'VKORG' => $vkorg
          , 'KUNNR_RG' => $kunnr_rg
          , 'PIN' => $arPart['ARTICLE']
          , 'BRAND' => $arPart['BRAND']
          , 'QUERY_TYPE' => ''
          , 'KUNNR_ZA' => ''
          , 'INCOTERMS' => ''
          , 'VBELN' => ''
      ];
      //if ($exclAnalogs) {
      //    $params['QUERY_TYPE'] = 1;
      //} else {
          $params['QUERY_TYPE'] = 2;
      //}
      if ($exclAnalogs) {
        $params['QUERY_TYPE'] = 1;
      }
      // requeest params for send
      $request_params = [

          'url' => 'search/search',
          'params' => [
              'VKORG' => !empty($params['VKORG']) ? $params['VKORG'] : (isset($ws_default_settings['VKORG']) ? $ws_default_settings['VKORG'] : '')
              , 'KUNNR_RG' => isset($params['KUNNR_RG']) ? $params['KUNNR_RG'] : (isset($ws_default_settings['KUNNR_RG']) ? $ws_default_settings['KUNNR_RG'] : '')
              , 'PIN' => isset($params['PIN']) ? $params['PIN'] : ''
              , 'BRAND' => isset($params['BRAND']) ? $params['BRAND'] : ''
              , 'QUERY_TYPE' => isset($params['QUERY_TYPE']) ? $params['QUERY_TYPE'] : ''
              , 'KUNNR_ZA' => isset($params['KUNNR_ZA']) ? $params['KUNNR_ZA'] : (isset($ws_default_settings['KUNNR_ZA']) ? $ws_default_settings['KUNNR_ZA'] : '')
              , 'INCOTERMS' => isset($params['INCOTERMS']) ? $params['INCOTERMS'] : (isset($ws_default_settings['INCOTERMS']) ? $ws_default_settings['INCOTERMS'] : '')
              , 'VBELN' => isset($params['VBELN']) ? $params['VBELN'] : (isset($ws_default_settings['VBELN']) ? $ws_default_settings['VBELN'] : '')
              , 'format' => 'json'
          ]
      ];

      // send data
      $response = $armtek_client->post($request_params);

      // in case of json
      $prices = $response->json();
      //var_dump($prices);
      if (is_array($prices->RESP)) {
        foreach ($prices->RESP as $obRes) {
          /*
           * RETURN ARRAY
            Параметр	Наименование	Тип(макс.-размер)	Обязательный	Примечание
            PIN	Номер артикула	строка ( <40 )	Нет	ПИН (строка поиска)
            BRAND	Бренд	строка ( <18 )	Нет	Наименование бренда
            NAME	Наименование	строка ( 100 )	Нет
            ARTID	Уникальный идентификационный номер	строка ( 20 )	Нет
            PARNR	Код склада партнера	строка ( 20 )	Нет
            KEYZAK	Код склада	строка ( 10 )	Нет
            RVALUE	Доступное количество	строка ( 20 )	Нет
            RDPRF	Кратность	строка ( 10 )	Нет
            MINBM	Минимальное количество	строка ( 10 )	Нет
            VENSL	Вероятность поставки	строка ( 10 )	Нет
            PRICE	Цена	строка ( 20 )	Нет
            WAERS	Валюта	строка ( 4 )	Нет
            DLVDT	Дата поставки	строка ( 20 )	Нет	Формат даты YYYYMMDDHHIISS
            Пример заполнения DLVDT=20151005120000
            ANALOG	Признак аналога	строка ( 1 )	Нет
          */

          $arPrice = TDMPriceArray($arPart);
          //Webservice data
          
          $arPrice["LINK_TO_BKEY"] = $arPart['BKeyTmp'];
          $arPrice["LINK_TO_AKEY"] = $arPart['AKEY'];
          $arPrice["ARTICLE"] = (string) $obRes->PIN;
          $arPrice["ALT_NAME"] = (string) $obRes->NAME;
          $arPrice["BRAND"] = (string) $obRes->BRAND;
          $arPrice["PRICE"] = (string) $obRes->PRICE;
          $arPrice["PRICE_ORIG"] = (string) $obRes->PRICE;
          $arPrice["CURRENCY"] = $arWS['CURRENCY'];
          $date_now = new DateTime("now");
          $year = substr($obRes->DLVDT, 0, 4);
          $month = substr($obRes->DLVDT, 4, 2);
          $day = substr($obRes->DLVDT, 6, 2);
          $date_delivery = new DateTime($year . '-' . $month . '-'. $day);
          $interval = date_diff($date_delivery, $date_now);
          //echo $interval->format('%R%a дней');
          $arPrice["DAY"] = (string) $interval->format('%a');
          
          $arPrice["AVAILABLE"] = $obRes->RVALUE;
          //"под заказ"
          $arPrice["STOCK"] = (string) $obRes->KEYZAK;
          $arPrice["OPTIONS"] = '';
          //Price options
          $arOps = Array();
          $MINIMUM = (string) $obRes->MINBM;
          if ($MINIMUM > 1) {
            $arOps['MINIMUM'] = $MINIMUM;
          }
          $PERCENTGIVE = (string) $obRes->VENSL; //Вероятность поставки товара поставщика
          if ($PERCENTGIVE > 0) {
            $arOps['PERCENTGIVE'] = $PERCENTGIVE;
          }
          //$arPrice["OPTIONS"] = TDMOptionsImplode($arOps, $arPrice);
          $arPrice["SUPPLIER_OPTIONS"] = json_encode(
                  array(
                      "PIN" => (string)$obRes->PIN,
                      "BRAND" => (string)$obRes->BRAND,
                      "NAME" => (string)$obRes->NAME,
                      "ARTID" => (string)$obRes->ARTID,
                      "PARNR" => (string)$obRes->PARNR,
                      "KEYZAK" => (string)$obRes->KEYZAK,
                      "RVALUE" => (string)$obRes->RVALUE,
                      "RDPRF" => (string)$obRes->RDPRF,
                      "MINBM" => (string)$obRes->MINBM,
                  )
              );
          //Add new record
          $arPrices[] = $arPrice;
        }
      }
    //}
  }
}
?>
