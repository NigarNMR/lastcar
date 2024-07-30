<?php

require "config.local.php";

$bid=$_GET['gob_id'];

$bid=json_decode($bid,true);
$b_id=$bid['gob_id'];
//echo $bid;


//подключение к бд
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//формирование и выполнение запроса на поиск указанного id группы

$sql="SELECT * FROM " ." " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . " " . "where gob__id = $bid";
$result = pg_query($db, $sql);

//если запрос не прошел, выдат причину
if (!$result) {

    exit;
}

//сформировать имя
while ($row = pg_fetch_row($result)) {
   $name=$row[1];
}

pg_close($db);
//вывод имени

printf(json_encode($name));
