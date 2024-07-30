<?php

require "config.local.php";
//Получение и парсинг данных
$json = urldecode(file_get_contents('php://input'));
$brands = json_decode($json,true);
$bName=$brands['b_name'];
$oldbName=json_encode($brands['b__nameO']);
$newbName=json_encode($brands['b_name']);

//Подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение id по имени
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME."?b_name=".urlencode($oldbName);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$oldbId=json_decode(curl_exec($ch));

curl_close($ch);

//Получение id по имени
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME."?b_name=".urlencode($newbName);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$newGroupId=json_decode(curl_exec($ch));
curl_close($ch);
//Если id уже существует, то уведомление об этом
if(!$newGroupId == NULL) {
    echo "Группа брендов уже существует";

}
//иначе
else {
	$bName=pg_escape_string($bName);
    //формирование и выполнение запроса на обновление
    $sql="UPDATE" . " " . TD_DB_TABLE_TDM_BRANDS . " " . " set b__name='$bName' where b__id=$oldbId";
    $result = pg_query($dbClient, $sql);
    //$result = pg_query($dbGen, $sql);
    //  echo "Кросс успешно добавлен";

}

//pg_close($dbGen);
pg_close($dbClient);

