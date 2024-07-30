<?php


require "config.local.php";
//Получение и парсинг данных
$b_id=$_GET['b_id'];
$b_id=json_decode($b_id);

//подключение к бд
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


//формирование и выполнение запроса на поиск указанного id бренда
$sql="SELECT * FROM ". " " . TD_DB_TABLE_TDM_BRANDS . " " . "where b__id = $b_id ";

$result = pg_query($db,$sql);

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