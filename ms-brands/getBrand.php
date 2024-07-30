<?php

require "config.local.php";

//подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение и парсинг параметров
$_GET['sort']=urldecode($_GET['sort']);
$params = json_decode($_GET['sort'],true);

$b_name = $params['name'];
$st = $params['st'];

if($st==0) {
// формирование запроса
    $sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "WHERE b__name LIKE '%$b_name%' AND b__status=0";
}

elseif($st==1)
    {
        $sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "tb " . " " . "INNER JOIN" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "tg" . " " . "ON tb.b__id=tg.b__id WHERE b__name LIKE '%$b_name%' AND b__status=1";
    }

    file_put_contents("12.txt",$sql);

//выполняем запрос
$resultClient = pg_query($dbClient,$sql);
//$resultGeneral = pg_query($dbClient,$sql);
$countClient= pg_num_rows($resultClient);
//$countGeneral=pg_num_rows($resultGeneral);


if ($countClient==0 ){
    echo "ничего не найдено";
}
else
    {
        while ($row = pg_fetch_row($resultClient)) {

            if($st==1) {
                $gob_id = json_encode($row[4]);
                $ch = curl_init();
                $aa = GET_GROUP_NAME_BY_ID . "?gob_id=$gob_id";
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
                curl_setopt($ch, CURLOPT_URL, $aa);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
                $gob_name = json_decode(curl_exec($ch));
            }

            elseif($st==0)
            {
                $gob_name=" ";
            }

            //$allGroups[]=['gob_name'=>$gob_name, 'gob_id'=> $row[0], 'b__id'=> $row[1], 'b__parent_id'=>$parent_id, 'b__name'=>$row[5], 'b__status'=>$row[7], 'smlr_percent'=>$row[2], 'smlr_checked'=>$row[3]];
            $brands[] = ['b__name' => $row[1], 'b__parent_id' => $parent_id, 'b__status' => $row[3], 'gob__name'=>$gob_name];
        }
    }




//вывод данных
 $result= json_encode($brands);
echo $result;

//закрытие бд
pg_close($dbClient);




