<?php

require "config.local.php";

$handler = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


$_GET['sort']=urldecode($_GET['sort']);
$params = json_decode($_GET['sort'],true);
$b_name = $params['filter']['b_name'];
$page =$params['page'] ;

//$result = pg_query($db, "SELECT b__name, CASE WHEN b__parent_id > 0 THEN b__parent_id WHEN b__parent_id < 0 THEN b__id WHEN b__parent_id = 0 THEN b__id END FROM tdm_brand");


if (!$handler) {
    echo "Произошла ошибка.\n";
   // exit;
}
$sql="Select * FROM" . " ". TD_DB_TABLE_TDM_BRANDS. " ".  "WHERE b__status=0 ";

//если имя группы отсутствует, то выбираем их все
if ($b_name == NULL)
{
    $b_name='  b__id IS NOT NULL';
    $sqlCount="Select count(*) FROM" . " ". TD_DB_TABLE_TDM_BRANDS. " ".  "WHERE b__status=0 ";
    $result = pg_query($handler,$sqlCount);
    while ($row = pg_fetch_row($result)){

        $count=$row[0];

    }

}

    else
        {
            $b_name = json_encode($b_name);
            $ch = curl_init();
            $aa = GET_BRAND_ID_BY_NAME."?b_name=".urlencode($b_name);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_URL, $aa);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
            $b_id = json_decode(curl_exec($ch));

            $b_name=' b__id ='. $b_id ;

            $sqlCount="Select count(*) FROM" . " ". TD_DB_TABLE_TDM_BRANDS. " ".  "WHERE b__status=0 and b__id= $b_id ";
            $result = pg_query($handler,$sqlCount);
            while ($row = pg_fetch_row($result)){

                $count=$row[0];

            }
        }


//если нет параметров сортировки, то пропускаем их
if ((($params['sort'] == NULL) || ($params['order']== NULL)))
{
    $sortSql='ORDER BY b__name';
}

//иначе добавлям параметры сортировки в запрос
else
{
    $sortSql="ORDER BY" . " " . $params['sort'] . " " . $params['order'] ;
}

//если параметр страницы =1 то выводим первые 20 строк без учета offset
if ($page == 1 ) {
    $a .= $sql .  " "  . "AND" . " " . $b_name  . " " . $sortSql . " " . "limit 20";

}

//иначе  формируем запрос исходя из параметра страницы
else
{
    $page=$page - 1;
    if ($page < 0) $page =0;
    $page=$page * 20;

    $a .= $sql . " " . "AND" . " " . $b_name . " " . $sortSql . " " . " OFFSET" . " " . $page  . " " . "limit 20";

}

$result = pg_query($handler,$a);
while ($row = pg_fetch_row($result)) {


    //Получаем имя группы по id
    $bId=json_encode($bid);
    $ch = curl_init();
    $a = GET_BRAND_NAME_BY_ID."?b_id=$bid";
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $a);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $brand_name1 = json_decode(curl_exec($ch));
    curl_close($ch);

    $b__id=$row[0];
    $b__name=$row[1];
    $b__parent_id=$row[2];
    $b__status=$row[3];
    $allBrands[] = ['b__id' => $b__id, 'b__name' => $b__name, 'b__parent_id' =>false,'count'=>$count];
}


    echo(json_encode($allBrands));




