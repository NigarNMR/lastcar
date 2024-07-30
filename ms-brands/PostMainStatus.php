
<?php

require "config.local.php";
// Получение и парсинг данных
$json = file_get_contents('php://input');
$id = json_decode($json,true);



//Подключение к бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$brand_group_name= json_encode($obj['gob_name']);
$sql="UPDATE" ." " . TD_DB_TABLE_TDM_BRANDS . " " . "set b__parent_id=0 where b__id =$id";
$result = pg_query($dbClient, $sql);
$sql = "SELECT *, b.b__parent_id FROM" . " " . TD_DB_TABLE_TDM_BRANDS  . " " . "b" . " " . "INNER JOIN" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "g" . " " . " ON b.b__id=g.b__id WHERE gob__id in (SELECT gob__id FROM" ." " . TD_DB_TABLE_TDM_GROUP_BRAND. " " . "where b__id=$id)";
$result1 = pg_query($dbClient, $sql);
while($row=pg_fetch_row($result1)){
if($row[0]==$id)
{
    continue;
}
else
    {
        $sql="UPDATE" ." " . TD_DB_TABLE_TDM_BRANDS . " " . "set b__parent_id=$id where b__id =$row[0]";
        $result = pg_query($dbClient, $sql);
    }

}

    //Закрытие бд
  //  pg_close($dbGen);
    pg_close($dbClient);



