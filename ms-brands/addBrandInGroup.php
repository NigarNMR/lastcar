
<?php

require "config.local.php";
// Получение и парсинг данных
$json = file_get_contents('php://input');
$json=urldecode($json);

$obj = json_decode($json,true);
$name=$obj['b_name'];
$gob_id=$obj['gob_id'];

//Подключение к бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
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

    $sqlST="SELECT b.b__id,b.b__parent_id,g.gob__id FROM". " " . TD_DB_TABLE_TDM_BRANDS  . "  b  " . "INNER JOIN " . TD_DB_TABLE_TDM_GROUP_BRAND . " g  on b.b__id=g.b__id where g.gob__id=$gob_id";

    $resultST = pg_query($dbClient, $sqlST);
    while ($row = pg_fetch_row($resultST)) {
        if($row[1]==0)
        {
            $parent_id=$row[0];

        }

    }
    echo "Бренд уже существует";
    $sqlDel="DELETE FROM " .  TD_DB_TABLE_TDM_GROUP_BRAND . " WHERE b__id=$brand_id ";
    $result = pg_query($dbClient,$sqlDel);

    $sql1 = "INSERT INTO" . " " . TD_DB_TABLE_TDM_GROUP_BRAND  . " " . " VALUES($gob_id,$brand_id,0,0)";
    $result = pg_query($dbClient, $sql1);
    if($parent_id !==NULL) {
        $sql2 = "UPDATE" . " " . TD_DB_TABLE_TDM_BRANDS . " " . " SET b__status=1,b__parent_id = $parent_id  WHERE b__id=$brand_id";
    }
    else {
        $sql2 = "UPDATE" . " " . TD_DB_TABLE_TDM_BRANDS . " " . " SET b__status=1 WHERE b__id=$brand_id";
    }
    $result1 = pg_query($dbClient, $sql2);

    exit();
}

//Иначе
else {

/*
            //Из-за неработающего или отсутсвующего автоинкремента делаем что-то подобное
        $sql="SELECT MAX(b__id) FROM" . " " . TD_DB_TABLE_TDM_BRANDS ;
        $result = pg_query($dbClient, $sql);
        while ($row = pg_fetch_row($result)) {
            $b__id=$row[0]+1;
        }
*/
        $sqlST="SELECT b.b__id,b.b__parent_id,g.gob__id FROM". " " . TD_DB_TABLE_TDM_BRANDS  . "  b  " . "INNER JOIN " . TD_DB_TABLE_TDM_GROUP_BRAND . " g  on b.b__id=g.b__id where g.gob__id=$gob_id";
        $resultST = pg_query($dbClient, $sqlST);
        while ($row = pg_fetch_row($resultST)) {
            if($row[1]==0)
             {
                $parent_id=$row[0];
             }

         }

        $name=pg_escape_string($name);
        //Формирование запроса на добавление новой группы
        $sql = "INSERT INTO" . " " . TD_DB_TABLE_TDM_BRANDS  . "(b__name,b__parent_id,b__status)" . " " . " VALUES('$name',$parent_id,1)";
        $result = pg_query($dbClient, $sql);



        $sql="SELECT * FROM". " " . TD_DB_TABLE_TDM_BRANDS  . " " ."where b__name = '$name'";
        $result = pg_query($dbClient, $sql);
         while ($row = pg_fetch_row($result)) {
             $id=$row[0];
         }


    $sqlDel="DELETE FROM " .  TD_DB_TABLE_TDM_GROUP_BRAND . " WHERE b__id=$b__id ";
    $result1 = pg_query($dbClient,$sqlDel);
    $sql1 = "INSERT INTO" . " " . TD_DB_TABLE_TDM_GROUP_BRAND  . " " . " VALUES($gob_id,$b__id,0,0)";
    $result1 = pg_query($dbClient, $sql1);

  
}
    //Закрытие бд
//    pg_close($dbGen);
    pg_close($dbClient);



