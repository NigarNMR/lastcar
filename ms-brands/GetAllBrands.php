<?php
require "config.local.php";

$handler = pg_connect("host=82.200.17.36 port=5432 dbname=brands_client1 user=postgres password=25022015admindb");
$dbb = pg_connect("host=82.200.17.36 port=5432 dbname=general_brands user=postgres password=25022015admindb");

//$result = pg_query($db, "SELECT b__name, CASE WHEN b__parent_id > 0 THEN b__parent_id WHEN b__parent_id < 0 THEN b__id WHEN b__parent_id = 0 THEN b__id END FROM tdm_brand");

$result = pg_query($handler,"Select * FROM " . TD_DB_TABLE_TDM_BRANDS . " ORDER BY b__name");
if (!$handler) {
    echo "Произошла ошибка.\n";
   // exit;
}
while ($row = pg_fetch_row($result)) {
    $b__id=$row[0];
    $b__name=$row[1];
    $b__parent_id=$row[2];
    $b__status=$row[3];
    $allBrands[] = ['b__id' => $b__id, 'b__name' => $b__name, 'b__parent_id' => $b__parent_id, 'b__status' => $b__status];
}
    echo(json_encode($allBrands));




