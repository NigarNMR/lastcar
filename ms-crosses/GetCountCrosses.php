<?php
require "config.local.php";
$params=json_decode($_GET['sort'],true);

//Подключение бд
//$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Запрос на получение количества строк в выборке
$sql=" SELECT  count(akey1) FROM" . " " . TD_DB_TABLE_TDM_LINKS ;

//тестовая таблица
//$sql=" SELECT  count(akey1) FROM links_tmp" ;
$result = pg_query($db,$sql);

//формирование ответа
while ($row = pg_fetch_row($result)){

    $count=['count'=>$row['0']];
  
}

//вывод ответа
echo(json_encode($count));
//закрытие бд
pg_close($db);


