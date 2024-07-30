<?php

require "config.local.php";


//подключение к бд
$handler = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


//$result = pg_query($db, "SELECT b__name, CASE WHEN b__parent_id > 0 THEN b__parent_id WHEN b__parent_id < 0 THEN b__id WHEN b__parent_id = 0 THEN b__id END FROM tdm_brand");
$sql = "SELECT TGB.gob__id,TGoB.gob__name,TGB.b__id, TB.b__parent_id,TB.b__name,TGB.smlr_percent,TGB.smlr_checked FROM TDM_GROUP_BRAND TGB INNER JOIN TDM_BRANDS TB ON TB.b__id=TGB.b__id INNER JOIN TDM_GROUPS_OF_BRANDS TGoB ON TGoB.gob__id=TGB.gob__id";
$result = pg_query($handler,"Select * FROM tdm_brands ORDER BY b__name");

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


pg_close($db);
pg_close($handler);

