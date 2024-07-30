<?php

require "config.local.php";

//подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение и парсинг параметров

$sql=" SELECT  * FROM" . " ".  TD_DB_TABLE_TDM_GROUP_BRAND . " " ."LEFT JOIN" . " " . TD_DB_TABLE_TDM_BRANDS . " " ."ON" . " " . TD_DB_TABLE_TDM_GROUP_BRAND .".b__id=" .TD_DB_TABLE_TDM_BRANDS . ".b__id  inner join  " . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . " on  " .  TD_DB_TABLE_TDM_GROUP_BRAND .".gob__id=" . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . ".gob__id" ;
$params = json_decode($_GET['name'],true);


$res=pg_query($dbClient,"SELECT COUNT(b__id) FROM" . TD_DB_TABLE_TDM_BRANDS);
while($row=pg_fetch_row($res))
{
    $count=$row[0];
}

if(isset($params['gob_name'])) {
    $gob_name = $params['gob_name'];
    $gob_name = urlencode(json_encode($gob_name));

    $ch = curl_init();
    $aa = GET_GROUP_ID_BY_NAME . "?gob_name=$gob_name";
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $gob_id = json_decode(curl_exec($ch));
    $sql.="  WHERE  " . TD_DB_TABLE_TDM_GROUP_BRAND . ".gob__id= $gob_id" ;
}
else{
    $sql.=" WHERE " .  TD_DB_TABLE_TDM_GROUP_BRAND.".smlr_percent BETWEEN   " .  $params['filter']['from'] . "  AND  " . $params['filter']["before"] ;
    }

//echo $sql;
$result = pg_query($dbClient,$sql);
$count= pg_num_rows($result);

$sql.="  ORDER BY b__name";

if (isset($params['page'])) {
    $page = $params['page'];

//если параметр страницы =1 то выводим первые 20 строк без учета offset
    if ($page == 1) {
        $sql .= "  limit 20";

    } //иначе  формируем запрос исходя из параметра страницы
    else {
        $page = $page - 1;
        if ($page < 0) $page = 0;
        $page = $page * 20;

        $sql .= " OFFSET" . " " . $page . " " . "limit 20";

    }
}


$result = pg_query($dbClient,$sql);



//По каждой строке
while ($row = pg_fetch_row($result)){


    $sql1="SELECT count(*) FROM ". " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "where brand_id = $row[4]";

    $result1 = pg_query($dbClient,$sql1);

    while ($row1=pg_fetch_row($result1)) {

        if ($row1[0] == 0) {

            $a = "FALSE";
        } else {

            $a = "TRUE";
        }

    }


$gob_name=$row[9];


    if($row[6] == 0)
    {
        $parent_id='Основной';

    }

    else{

        $parent_id = " ";

    }

    $main=false;

    //Заполняем массив
    $allGroups[]=['gob_name'=>$gob_name, 'gob_id'=> $row[0], 'b__id'=> $row[1], 'b__parent_id'=>$parent_id, 'b__name'=>$row[5], 'b__status'=>$row[7], 'smlr_percent'=>$row[2], 'smlr_checked'=>$row[3],'main'=>$main,'count'=>$count,'dict'=>$a];

}

//вывод данных
$result= json_encode($allGroups);
echo $result;

//закрытие бд
pg_close($db);




