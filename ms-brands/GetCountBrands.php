<?php
require "config.local.php";

$params=json_decode($_GET['sort'],true);
//$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//$sql=" SELECT  count(akey1) FROM" . " " . TD_DB_TABLE_TDM_LINKS ;
//тестовая таблица
$sql=" SELECT  count(gob__id) FROM" . " " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS ;


$result = pg_query($db,$sql);

while ($row = pg_fetch_row($result)){

    $count=['count'=>$row['0']];
  
}


echo(json_encode($count));
pg_close($db);


