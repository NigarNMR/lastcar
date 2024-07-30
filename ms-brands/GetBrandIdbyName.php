<?php
require "config.local.php";
//Получение и парсинг данных
$b_name=urldecode($_GET['b_name']);
$b_name=json_decode($b_name);

//подключение к бд
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


//формирование и выполнение запроса на поиск указанного имени бренда
$sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_BRANDS . " " . "where b__name = '$b_name'";

$result = pg_query($db, $sql);
//если запрос не прошел, выдат причину
if (!$result) {
    echo pg_last_error($db);
    exit;
}

//сформировать id
while ($row = pg_fetch_row($result)) {
   $id=$row[0];
}

pg_close($db);
//вывод id
printf(json_encode($id));