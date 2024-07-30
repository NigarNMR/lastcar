<?php

require "config.local.php";


//подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение и парсинг параметров
$_GET['b_id']=urldecode($_GET['b_id']);
$params = json_decode($_GET['b_id'],true);

$sql="SELECT * FROM" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . " WHERE b__id = $params";

$result = pg_query($dbClient,$sql);
$count= pg_num_rows($result);

//По каждой строке
while ($row = pg_fetch_row($result)) {

    $gob_id = $row[0];


    $sql = "SELECT gob__name FROM " . " " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . " " . "WHERE gob__id=$gob_id";

    $result = pg_query($dbClient, $sql);

    while ($row1 = pg_fetch_row($result)) {

       $group[]=['gob__id'=>$row[0],'gob__name'=>$row1[0]];

    }

}
//вывод данных
$group= json_encode($group);
echo  $group;

//закрытие бд
pg_close($db);




