
<?php

require "config.local.php";
// Получение и парсинг данных
$json = file_get_contents('php://input');
$json=urldecode($json);

$obj = json_decode($json,true);
$name=$obj['b_name'];

//Подключение к бд
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$brand_name= urlencode(json_encode($name));

//Получение id группы по имени
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME ."?b_name=$brand_name";
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id = json_decode(curl_exec($ch));

//Если id найден, то уведомление, что группа уже существует
if(!$brand_id == NULL) {
    exit();
}

//Иначе
else {
         

        $name=pg_escape_string($name);
        //Формирование запроса на добавление новой группы
        $sql = "INSERT INTO" . " " . TD_DB_TABLE_TDM_BRANDS  . "(b__name,b__parent_id,b__status)" . " " . " VALUES('$name',0,0)";
        $result = pg_query($dbClient, $sql);



}
    //Закрытие бд
//    pg_close($dbGen);
    pg_close($dbClient);



