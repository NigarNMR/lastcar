<?php

require "config.local.php";

//Получение и парсинг данных
$json = file_get_contents('php://input');
$json=urldecode($json);
$brands = json_decode($json,true);

//Подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$groupName=json_encode($brands['gob_name']);

// Получение id по имени
$ch = curl_init();
$a = GET_GROUP_ID_BY_NAME."?gob_name=".urlencode($groupName);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$groupId = json_decode(curl_exec($ch));
curl_close($ch);

// Формирование и выполнение запроса на удаление
    $sql="DELETE FROM" . " " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . " " . "where gob__id=$groupId";
    $result = pg_query($dbClient, $sql);
    $sql="DELETE FROM" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "where gob__id=$groupId";
    $result = pg_query($dbClient, $sql);
   // $result = pg_query($dbGen, $sql);
   //  echo "Кросс успешно удален";

//закрытие бд
//pg_close($dbGen);
pg_close($dbClient);


