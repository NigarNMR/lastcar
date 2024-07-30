<?php

require "config.local.php";

//Получение и парсинг данных
$json = file_get_contents('php://input');

$brands = json_decode($json,true);

//Подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$id = $brands['b_id'];
$gob_id=$brands['gob_id'];

// Формирование и выполнение запроса на удаление
    $sql="DELETE FROM" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "where b__id = $id and gob__id = $gob_id";

    $result = pg_query($dbClient, $sql);
   // $result = pg_query($dbGen, $sql);
   //  echo "Кросс успешно удален";

//закрытие бд
//pg_close($dbGen);
pg_close($dbClient);


