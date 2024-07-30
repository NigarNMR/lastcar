<?php
require "config.local.php";
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//$result = pg_query($db, "SELECT b__name, CASE WHEN b__parent_id > 0 THEN b__parent_id WHEN b__parent_id < 0 THEN b__id WHEN b__parent_id = 0 THEN b__id END FROM tdm_brand");

$result = pg_query($dbClient,"Select * FROM " . " ". TD_DB_TABLE_TDM_GROUPS_OF_BRANDS. " " . "ORDER BY gob__name");
if (!$dbClient) {
    echo "Произошла ошибка.\n";
   // exit;
}
while ($row = pg_fetch_row($result)) {
    $gob__id=$row[0];
    $gob__name=$row[1];
    $allBrands[] = ['b__id' => $gob__id, 'b__name' => $gob__name];
}
    echo(json_encode($allBrands));

pg_close($dbClient);


