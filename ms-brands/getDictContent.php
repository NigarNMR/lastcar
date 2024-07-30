<?php

require "config.local.php";

//подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение и парсинг параметров
$_GET['sort']=urldecode($_GET['sort']);

$params = json_decode($_GET['sort'],true);
$dict_name = $params['dict_name'];
$status = $params['status'];
$page =$params['page'] ;


// формирование запроса
if ($status==1) {
    $sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "d" . " " . "inner join" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "g" . " " . " on d.brand_id=g.b__id inner join" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "t" . " " . "ON d.brand_id=t.b__id where d.dict_code='$dict_name' and t.b__status=$status order by b__name";
    //$sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "d" . " " . " inner join" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "t" . " " . "ON d.brand_id=t.b__id where d.dict_code='$dict_name' and t.b__status=$status";
}

else
{
    //  $sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "d" . " " . "inner join" . " " . TD_DB_TABLE_TDM_GROUP_BRAND . " " . "g" . " " . " on d.brand_id=g.b__id inner join" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "t" . " " . "ON d.brand_id=t.b__id where d.dict_code='$dict_name' and t.b__status=$status order by b__name";
    $sql = " SELECT  * FROM" . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " . "d" . " "." inner join" . " " . TD_DB_TABLE_TDM_BRANDS . " " . "t" . " " . "ON d.brand_id=t.b__id where d.dict_code='$dict_name' and t.b__status=$status order by b__name";
}


//если параметр страницы =1 то выводим первые 20 строк без учета offset
if ($page == 1 ) {
    $a .= $sql . "  limit 20";

}

//иначе  формируем запрос исходя из параметра страницы
else
{
    $page=$page - 1;
    if ($page < 0) $page =0;
    $page=$page * 20;

    $a .= $sql  . "  OFFSET" . " " . $page  . " " . "limit 20";

}

//echo $a;
//выполняем запрос
$result = pg_query($dbClient,$a);
$count= pg_num_rows($result);

$count_sql="SELECT count(*) from " . " " . TD_DB_TABLE_TDM_DICTIONARIES . " " ." where dict_code='$dict_name'";
$count=pg_query($dbClient,$count_sql);
while ($row = pg_fetch_row($count)){
    $counter=$row[0];
}
//формируем массив общих групп
$allGroups=array();

//По каждой строке
while ($row = pg_fetch_assoc($result)){

    $b_name = json_encode($row['gob__id']);
    $ch = curl_init();
    $aa = GET_GROUP_NAME_BY_ID."?gob_id=".$b_name;
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $b_name = json_decode(curl_exec($ch));
    //Заполняем массив
    $allGroups[]=['repl_brand'=>$row['repl_id'],'desc_brand'=> $row['desc_brand'], 'brand_id'=>$row['brand_id'], 'dict_code'=>$row['dict_code'],'gob_id'=>$row['gob__id'],'gob_name'=>$b_name,'count'=>$counter];

}
//вывод данных
echo(json_encode($allGroups));

//закрытие бд
pg_close($dbClient);




