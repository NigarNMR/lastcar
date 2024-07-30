<?php

require "config.local.php";
//Получение и парсинг данных
$json = file_get_contents('php://input');
$json=urldecode($json);
$brands = json_decode($json,true);
$groupName=$brands['gob_name'];
$oldGobName=json_encode($brands['gob_nameO']);
$newGobName=json_encode($brands['gob_name']);

//Подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение id по имени
$ch = curl_init();
$a = GET_GROUP_ID_BY_NAME."?gob_name=".urlencode($oldGobName);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$oldGroupId=json_decode(curl_exec($ch));
curl_close($ch);

//Получение id по имени
$ch = curl_init();
$a = GET_GROUP_ID_BY_NAME."?gob_name=".urlencode($newGobName);
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
    $groupName=pg_escape_string($groupName);
    //формирование и выполнение запроса на обновление
    $sql="UPDATE" . " " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . " " . " set gob__name='$groupName' where gob__id=$oldGroupId";
    $result = pg_query($dbClient, $sql);
    //$result = pg_query($dbGen, $sql);
    //  echo "Кросс успешно добавлен";

}

//pg_close($dbGen);
pg_close($dbClient);

