<?php


require "config.local.php";
//Получение и парсинг данных
$b_id=$_GET['b_id'];

//подключение к бд
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


//формирование и выполнение запроса на поиск указанного id бренда
$sql="SELECT count(*) FROM ". " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "where brand_id = $b_id ";
echo $sql;
$result = pg_query($db,$sql);

while ($row=pg_fetch_row($result)) {
//если запрос не прошел, выдат причину
    if ($row[0] == 0) {

        $message = "False";
    } else {

        $message = "True";
    }
    print_r($message);
}





