
<?php

require "config.local.php";
// Получение и парсинг данных
$json = file_get_contents('php://input');
$json=urldecode($json);
$obj = json_decode($json,true);
 $name=$obj['gob_name'];


//Подключение к бд
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$brand_group_name= json_encode($obj['gob_name']);

//Получение id группы по имени
$ch = curl_init();
$a = GET_GROUP_ID_BY_NAME ."?gob_name=".urlencode($brand_group_name);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_group_id = json_decode(curl_exec($ch));
echo $brand_group_id;
//Если id найден, то уведомление, что группа уже существует
if(!$brand_group_id == NULL) {
    echo "Группа брендов уже существует";
}

//Иначе
else {

  
    $name=pg_escape_string($name);
    //Формирование запроса на добавление новой группы
    if ($name !==" " || $name !==NULL) {
        $sql = "INSERT INTO" . " " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . "(gob__name)" . " " . " VALUES('$name')";
        $result = pg_query($dbClient, $sql);
    }
         //$result = pg_query($dbGen, $sql);
        //  echo "Кросс успешно добавлен";
}
    //Закрытие бд
  //  pg_close($dbGen);
    pg_close($dbClient);



