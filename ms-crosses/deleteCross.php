<?php

require "config.local.php";

//Получение данных
$json = urldecode(file_get_contents('php://input'));
$obj = json_decode($json,true);

//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//$cross=json_decode($_GET['cross'],true);


//Парсинг данных
$brand_name1=json_encode($obj['brand_name1']);
$akey1=$obj['akey1'];
$brand_name2=json_encode($obj['brand_name2']);
$akey2=$obj['akey2'];
$side=$obj['side'];
$code=$obj['code'];

//Получение id бренда1 по имени
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($brand_name1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id1=json_decode(curl_exec($ch));
curl_close($ch);

//Получение id бренда2 по имени
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($brand_name2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id2=json_decode(curl_exec($ch));
curl_close($ch);


//Формирование запроса на удаление и его выполнение
$sql="Delete from " . " " . TD_DB_TABLE_TDM_LINKS . " WHERE brand_id1=$brand_id1 and akey1='$akey1' and brand_id2=$brand_id2 and akey2='$akey2'";
if(pg_query($dbClient,$sql))
    echo "Удаление завершено";

pg_close($dbClient);

















