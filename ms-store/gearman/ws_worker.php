<?php

echo "Starting\n";

/*
require 'gearmanConfig.php';
$conHandler = mysqli_connect($host, $user, $pass, $database);
*/

function TDMPriceArray($arPart = array()) {
    $Arr = array(
        "BKEY" => "", 
        "AKEY" => "", 
        "ARTICLE" => "", 
        "ALT_NAME" => "", 
        "BRAND" => "", 
        "PRICE" => 0, 
        "TYPE" => 1, 
        "CURRENCY" => "", 
        "DAY" => "", 
        "AVAILABLE" => "", 
        "SUPPLIER" => "", 
        "STOCK" => "", 
        "OPTIONS" => "", 
        "CODE" => "", 
        "DATE" => time()
    );
    //Указание ссылок на AKEY, BKEY
    $Arr["LINK_TO_BKEY"] = $arPart["BKEY"];
    $Arr["LINK_TO_AKEY"] = $arPart["AKEY"];
    return $Arr;
}

# Создание обработчика.
$gmworker= new GearmanWorker();
//$gmworker->setId('test');

# Указание сервера по умолчанию  (localhost).
$gmworker->addServer();

# Регистрация функции "reverse" на сервере. Изменение функции обработчика на
# "reverse_fn_fast" для более быстрой обработки без вывода.
$gmworker->addFunction("get_price", "getPrices");
$gmworker->addFunction("get_price_orig", "getPricesNoAnalogs");
$gmworker->addFunction("test_ws_search", "testSearch");
//$gmworker->addFunction("update_price", "updatePrices");
$gmworker->addFunction("search_article_only", "searchArtOnly");
$gmworker->addFunction("get_analogs_list", "getAnalogsList");

print "Waiting for job...\n";
while($gmworker->work())
{
  if ($gmworker->returnCode() != GEARMAN_SUCCESS)
  {
    echo "return_code: " . $gmworker->returnCode() . "\n";
    break;
  }
}
/*
function reverse_fn($job)
{
  echo "Received job: " . $job->handle() . "\n";

  $workload = $job->workload();
  $workload_size = $job->workloadSize();

  echo "Workload: $workload ($workload_size)\n";

  # Этот цикл не является необходимым, но показывает как выполняется работа
  for ($x= 0; $x < $workload_size; $x++)
  {
    echo "Sending status: " . ($x + 1) . "/$workload_size complete\n";
    $job->sendStatus($x+1, $workload_size);
    $job->sendData(substr($workload, $x, 1));
    sleep(1);
  }

  $result= strrev($workload);
  echo "Result: $result\n";

  # Возвращаем, когда необходимо отправить результат обратно клиенту.
  return $result;
}
*/
/*
function updatePrices($job)
{
    $workload = $job->workload();
    
    global $conHandler;
    $conHandler->ping();
    $conHandler->query($workload, MYSQLI_ASYNC);
    echo($workload);
    echo("\r\n");
}
*/
function getPrices($job)
{
    $workload = $job->workload();
    
    $arRecData = json_decode($workload, true);
    $exclAnalogs = FALSE;
    
    $arPrices = array();
    var_dump($arRecData);
    $arPart = (array)$arRecData['PARTS'];
    $arWS = (array)$arRecData['WS'];
    $arParams = (array)$arRecData['PARAMS'];
    
    $WSPath = (__DIR__) . "/ws/" . $arWS["SCRIPT"] . ".php";
        
    if (file_exists($WSPath))
    {
      require($WSPath);
    }
    else
    {
      //echo "file not found\n";
      //echo "file path: $WSPath\n\n";
    }
    echo($WSPath."</br>");
        
    $arResData = array('PARAMS' => $arParams, 'PRICES' => $arPrices, 'WS' => $arWS);
    echo(json_encode($arResData) . "\n");
    
    //$fHandler = fopen($arPart['BRAND'].$arPart['ARTICLE'].'_'.$arWS['ID'].'.txt', 'a+');
    //fwrite($fHandler, json_encode($arResData) . "\r\n\r\n");
    //fclose($fHandler);
    
    $job->sendData(json_encode($arResData));
}

function getPricesNoAnalogs($job)
{
  $workload = $job->workload();
  
  $arRecData = json_decode($workload, true);
  $exclAnalogs = TRUE;
  
  $arPrices = array();
  var_dump($arRecData);
  $arPart = (array)$arRecData['PARTS'];
  $arWS = (array)$arRecData['WS'];
  $arParams = (array)$arRecData['PARAMS'];
  $arParams['PKEY'] = md5("article=" . $arPart['ARTICLE'] . "brand=" . $arPart['BRAND']);
  
  $WSPath = (__DIR__) . "/ws/" . $arWS["SCRIPT"] . ".php";
  
  if (file_exists($WSPath))
  {
    require($WSPath);
  }
  
  echo("NO-ANALOGS " . $arWS['NAME'] . "\r\n");
  
  $arResData = array('PARAMS' => $arParams, 'PRICES' => $arPrices, 'WS' => $arWS);
  echo(json_encode($arResData) . "\n");
  
  $job->sendData(json_encode($arResData));
}

function testSearch($job)
{
  $workload = $job->workload();
    
  $arRecData = json_decode($workload, true);
  
  $exclAnalogs = false;
  if ($arRecData['orig_only'] === 'true' OR $arRecData['orig_only'] === true)
  {
    $exclAnalogs = true;
  }

  $arPrices = array();
  $arPart = array();
  $arPart['ARTICLE'] = $arRecData['article'];
  $arPart['BRAND'] = $arRecData['brand'];
  $arPart['AKEY'] = '';
  $arPart['BKEY'] = '';
  $arPart['BKeyTmp'] = '';
  $arWS = array();
  $arWS['SCRIPT'] = $arRecData['ws_script'];
  $arWS['LOGIN'] = $arRecData['ws_login'];
  $arWS['PASSW'] = $arRecData['ws_pass'];
  $arWS['CURRENCY'] = $arRecData['ws_curr'];

  $WSPath = (__DIR__) . "/ws/" . $arWS["SCRIPT"] . ".php";

  if (file_exists($WSPath))
  {
    require($WSPath);
  }

  $arResData = $arPrices;

  $job->sendData(json_encode($arResData));
}

function searchArtOnly($job)
{
  $workload = $job->workload();

  $arRecData = json_decode($workload, true);
  //var_dump($arRecData);
  $arPrices = array();
  $arParts = array();
  
  $sConType = (string)$arRecData['conType'];
  $arConOptions = (array)$arRecData['conOptions'];
  $sSrchData = (string)$arRecData['srchData'];
  
  $arResData = array();
  
  switch ($sConType)
  {
    //Выбор данных из БД TecDoc
    case 'tecdoc':
      {
        var_dump($arConOptions);
        if (empty($arConOptions['PORT']))
        {
          $arAddress = explode(':', $arConOptions['SERVER']);
          $arConOptions['SERVER'] = $arAddress[0];
          if (isset($arAddress[1]))
          {
            $arConOptions['PORT'] = $arAddress[1];
          }
        }
        
        $hSQL = new mysqli($arConOptions['SERVER'],$arConOptions['LOGIN'],$arConOptions['PASS'],$arConOptions['NAME'],(int)$arConOptions['PORT']);
        
        $hSQL->query("SET CHARSET utf8");
        
        $sSQL = "SELECT DISTINCT " . "IF (ART_LOOKUP.ARL_KIND IN (3, 4), BRANDS.BRA_BRAND, SUPPLIERS.SUP_BRAND) AS BRAND, " . "ART_LOOKUP.ARL_SEARCH_NUMBER AS ARTICLE, " . "ART_LOOKUP.ARL_KIND AS KIND,  " . "ARTICLES.ART_ID AS AID, " . "DES_TEXTS.TEX_TEXT AS TD_NAME " . "FROM ART_LOOKUP " . "LEFT JOIN BRANDS ON BRANDS.BRA_ID = ART_LOOKUP.ARL_BRA_ID " . "INNER JOIN ARTICLES ON ARTICLES.ART_ID = ART_LOOKUP.ARL_ART_ID " . "INNER JOIN SUPPLIERS ON SUPPLIERS.SUP_ID = ARTICLES.ART_SUP_ID " . "INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = ARTICLES.ART_COMPLETE_DES_ID " . "INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID " . "WHERE " . "ART_LOOKUP.ARL_SEARCH_NUMBER = '" . $sSrchData . "' AND " . "ART_LOOKUP.ARL_KIND IN (1, 2, 3, 4, 5) AND  " . "DESIGNATIONS.DES_LNG_ID = 16 " . "ORDER BY ARL_KIND DESC;";
        
        $obRes = $hSQL->query($sSQL);
        if (!empty($obRes) && !is_null($obRes)) {
            while ($arArts = $obRes->fetch_assoc()) {
              $arParts[] = array("BKEY" => urlencode($arArts["BRAND"]), "AKEY" => urlencode($arArts["ARTICLE"]), "NAME" => urlencode($arArts["TD_NAME"]), "BKEY_TYPE" => urlencode("NAME"));
            }
        }
        var_dump($arParts);
        $hSQL->close();
        echo 'tecdoc';
      }
      break;
    //Выбор данных из БД Модуля
    case 'tdmprices':
      {
        $hSQL = new mysqli($arConOptions['SERVER'],$arConOptions['LOGIN'],$arConOptions['PASS'],$arConOptions['NAME'],(int)$arConOptions['PORT']);
        
        $sSQL = "SELECT BRAND_ID, AKEY, ALT_NAME FROM TDM_PRICES WHERE AKEY=\"" . $sSrchData . "\";";

        $obRes = $hSQL->query($sSQL);
        if (!empty($obRes) && !is_null($obRes)) {
            while ($arSArts = $obRes->fetch_assoc()) {
              /*
              $arSArts["PKEY"] = TDMSingleKey($arSArts["BKEY"], true) . $arSArts["AKEY"];
              if (is_array($arPARTS_noP[$arSArts["PKEY"]]))
              {
                continue;
              }
              $arPARTS_noP[$arSArts["PKEY"]] = array("BKEY" => $arSArts["BKEY"], "AKEY" => $arSArts["AKEY"], "NAME" => $arSArts["ALT_NAME"]);
              continue;
              */
              $arParts[] = array("BKEY" => urlencode($arSArts["BRAND_ID"]), "AKEY" => urlencode($arSArts["AKEY"]), "NAME" => urlencode($arSArts["ALT_NAME"]), "BKEY_TYPE" => urlencode("ID"));
            }
        }
        $hSQL->close();
        echo 'tdmprice';
      }
      break;
    //Выбор данных от web-поставщика
    case 'ws':
      {
        //$exclAnalogs = TRUE;
        $exclAnalogs = FALSE;
        $arWS = $arConOptions;
        $arPart = array("PKEY" => $sSrchData, "BKEY" => "", "AKEY" => $sSrchData, "ARTICLE" => $sSrchData, "BRAND" => "", "BKeyTmp" => "");
        $WSPath = (__DIR__) . "/ws/" . $arWS["SCRIPT"] . ".php";

        if (file_exists($WSPath)) {
            require($WSPath);
        }
        
        foreach ($arPrices as $arPriceData)
        {
          if ($arPriceData['ARTICLE']==$sSrchData)
          {
            $arParts[] = array("BKEY" => urlencode($arPriceData['BRAND']), "AKEY" => urlencode($sSrchData), "NAME" => urlencode($arPriceData['ALT_NAME']), "BKEY_TYPE" => urlencode("NAME"));
          }
        }        
        echo 'ws';
      }
      break;
    default:
      break;
  }
  
  $arResData = array('PARTS' => $arParts);
  $job->sendData(json_encode($arResData));
  
}

function getAnalogsList($job)
{
  $workload = $job->workload();

  $arRecData = json_decode($workload, true);
  //var_dump($arRecData);
  
  $arPrices = array();
  $arParts = array();
  $arPAIDs = array();
  
  $sConType = (string)$arRecData['conType'];
  $arConOptions = (array)$arRecData['conOptions'];
  $arSrchData = (array)$arRecData['srchData'];
  
  $arResData = array();
  
  switch ($sConType)
  {
    //Выбор аналогов из БД TecDoc
    case 'tecdoc':
      {
        var_dump($arConOptions);
        if (empty($arConOptions['PORT']))
        {
          $arAddress = explode(':', $arConOptions['SERVER']);
          $arConOptions['SERVER'] = $arAddress[0];
          if (isset($arAddress[1]))
          {
            $arConOptions['PORT'] = $arAddress[1];
          }
        }
        
        $hSQL = new mysqli
            (
              $arConOptions['SERVER'],
              $arConOptions['LOGIN'],
              $arConOptions['PASS'],
              $arConOptions['NAME'],
              (int)$arConOptions['PORT']
            );
        
        $hSQL->query("SET CHARSET utf8");
        
        $sSQL = "SELECT DISTINCT ARTICLES.ART_ID AS AID, \r\n\t\t\t\tIF (ART_LOOKUP2.ARL_KIND = 3, BRANDS2.BRA_BRAND, SUPPLIERS2.SUP_BRAND) AS BRAND,\r\n\t\t\t\tIF (ART_LOOKUP2.ARL_KIND IN (2, 3), ART_LOOKUP2.ARL_DISPLAY_NR, ARTICLES2.ART_ARTICLE_NR) AS ARTICLE,\r\n\t\t\t\tART_LOOKUP2.ARL_KIND AS KIND, \r\n\t\t\t\tDES_TEXTS.TEX_TEXT AS TD_NAME\r\n\t\t\tFROM\r\n\t\t\t\tART_LOOKUP\r\n\t\t\t\tLEFT JOIN BRANDS ON BRANDS.BRA_ID = ART_LOOKUP.ARL_BRA_ID \r\n\t\t\t\tINNER JOIN ARTICLES ON ARTICLES.ART_ID = ART_LOOKUP.ARL_ART_ID \r\n\t\t\t\tINNER JOIN SUPPLIERS ON SUPPLIERS.SUP_ID = ARTICLES.ART_SUP_ID \r\n\t\t\t\tINNER JOIN ART_LOOKUP AS ART_LOOKUP2 FORCE KEY (PRIMARY) ON ART_LOOKUP2.ARL_ART_ID = ART_LOOKUP.ARL_ART_ID \r\n\t\t\t\tLEFT JOIN BRANDS AS BRANDS2 ON BRANDS2.BRA_ID = ART_LOOKUP2.ARL_BRA_ID \r\n\t\t\t\tINNER JOIN ARTICLES AS ARTICLES2 ON ARTICLES2.ART_ID = ART_LOOKUP2.ARL_ART_ID \r\n\t\t\t\tINNER JOIN SUPPLIERS AS SUPPLIERS2 FORCE KEY (PRIMARY) ON SUPPLIERS2.SUP_ID = ARTICLES2.ART_SUP_ID \r\n\t\t\t\tINNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = ARTICLES.ART_COMPLETE_DES_ID \r\n\t\t\t\tINNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID\r\n\t\t\tWHERE\r\n\t\t\t\tART_LOOKUP.ARL_SEARCH_NUMBER = '" . $arSrchData['article'] . "' AND\r\n\t\t\t\t(ART_LOOKUP.ARL_KIND IN (3, 4) AND BRANDS.BRA_BRAND = '" . $arSrchData['brand'] . "' OR\r\n\t\t\t\tART_LOOKUP.ARL_KIND IN (1, 2) AND SUPPLIERS.SUP_BRAND = '" . $arSrchData['brand'] . "') AND \r\n\t\t\t\t(ART_LOOKUP.ARL_KIND, ART_LOOKUP2.ARL_KIND) IN\r\n\t\t\t\t((1, 1), (1, 2), (1, 3),\r\n\t\t\t\t(2, 1), (2, 2), (2, 3),\r\n\t\t\t\t(3, 1), (3, 2), (3, 3),\r\n\t\t\t\t(4, 1)) \r\n\t\t\t\tAND DESIGNATIONS.DES_LNG_ID = " . 16 /*$LNG_ID*/. " \r\n\t\t\tORDER BY KIND, BRAND, ARTICLE;";
        
        $obRes = $hSQL->query($sSQL);
        if (!empty($obRes) && !is_null($obRes)) {
            while ($arArts = $obRes->fetch_assoc()) {
              $arParts[] = array(
                  "PKEY" => "",
                  "BKEY" => "",
                  "AKEY" => "",
                  "KIND" => $arArts["KIND"],
                  "AID" => $arArts["AID"],
                  "ARTICLE" => $arArts["ARTICLE"],
                  "BRAND" => $arArts["BRAND"],
                  "TD_NAME" => $arArts["TD_NAME"],
                  "NAME" => $arArts["TD_NAME"],
                  "IMG_SRC" => "/autoparts/media/images/nopic.jpg"
                );
              $arPAIDs[] = $arArts["AID"];
            }
        }
        $hSQL->close();
      }
      break;
    //Выбор аналогов из таблицы кроссов БД TDM
    case 'tdmlinks':
      {
        $hSQL = new mysqli
          (
            $arConOptions['SERVER'],
            $arConOptions['LOGIN'],
            $arConOptions['PASS'],
            $arConOptions['NAME'],
            (int)$arConOptions['PORT']
          );
        
        //Проход по левой стороне кроссов
        $sSQL = "SELECT BRAND_ID1, AKEY1, BRAND_ID2, AKEY2, SIDE, CODE FROM TDM_LINKS WHERE BRAND_ID1=\"" . $arSrchData['brand'] . "\" AND AKEY1=\"" . $arSrchData['article'] . "\" AND SIDE IN (0,1) ";
        $obRes = $hSQL->query($sSQL);
        
        if (!empty($obRes) && !is_null($obRes)) {
            while ($arLink = $obRes->fetch_assoc()) {
              $arParts[] = array(
                  "PKEY" => $arLink["BRAND_ID2"] . $arLink["AKEY2"],
                  "BKEY" => $arLink["BRAND_ID2"],
                  "BRAND" => $arLink["BRAND_ID2"],
                  "AKEY" => $arLink["AKEY2"],
                  "ARTICLE" => $arLink["AKEY2"],
                  "IMG_SRC" => "/autoparts/media/images/nopic.jpg"
                );
            }
        }
        
        //Проход по правой стороне кроссов
        $sSQL = "SELECT BRAND_ID1, AKEY1, BRAND_ID2, AKEY2, SIDE, CODE FROM TDM_LINKS WHERE BRAND_ID2=\"" . $arSrchData['brand'] . "\" AND AKEY2=\"" . $arSrchData['article'] . "\" AND SIDE IN (0,2) ";
        $obRes = $hSQL->query($sSQL);
        
        while ($arLink = $obRes->fetch_assoc())
        {
          $arParts[] = array
            (
              "PKEY" => $arLink["BRAND_ID1"] . $arLink["AKEY1"],
              "BKEY" => $arLink["BRAND_ID1"],
              "BRAND" => $arLink["BRAND_ID1"],
              "AKEY" => $arLink["AKEY1"],
              "ARTICLE" => $arLink["AKEY1"],
              "IMG_SRC" => "/autoparts/media/images/nopic.jpg",
              "TYPE" =>"ID"
            );
        }
      }
      break;
    default:
      break;
  }
  
  $arResData = array
    (
      'TYPE' => 'ANALOGS',
      'DATA' => array
        (
          'PARTS' => $arParts,
          'PAIDS' => $arPAIDs
        )
    );
  //var_dump($arResData);
  $job->sendData(json_encode($arResData));
}
?>