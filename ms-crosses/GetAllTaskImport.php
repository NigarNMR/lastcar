<?php
require "config.local.php";

$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_API ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$client_id=$_GET['d'];
$a=1;
$result = pg_query($db," SELECT * FROM" . " ".  TD_DB_TABLE_TASK_IMPORT . " " . "WHERE client_id= $a  ORDER BY create_time DESC");
while ($row = pg_fetch_assoc($result)) {
    echo "id-ипорта:";
    echo  $row['import_id'] ;
    echo "<br />\n";
    echo "id-клиента:";
    echo  $row['client_id'] ;
    echo "<br />\n";
    echo "Статус задачи:";
    echo  $row['status'] ;
    echo "<br />\n";
    echo "Время начала:";
    echo  $row['create_time'] ;
    echo "<br />\n";
    echo "Последнее время обновления:";
    echo  $row['update_time'] ;
    echo "<br />\n";
    echo "Данные по задачи(json):";
    echo  $row['data'] ;
    echo "<br />\n";
    echo "Параметры:";
    echo  $row['param'] ;
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
    echo "<br />\n";
}


