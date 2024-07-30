<?php
require "config.local.php";
//Подключение к бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
if (!$dbClient) {
    echo "Произошла ошибка.\n";
    // exit;
}
else {
    $sql = "Select DISTINCT dict_code FROM" . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "ORDER BY dict_code";
    $result = pg_query($dbClient, $sql);
    if(!$result){
        echo pg_last_error($dbClient);
    }
    else {
		while ($row = pg_fetch_row($result)) {
			$dict_code = $row[0];
			$dictionary[] = ['dict_code' => $dict_code];
		}
		echo(json_encode($dictionary));
    }

}

