<?php
require "config.local.php";

//Подключение бд
//$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Парсинг полученных данных
$_GET['sort']=urldecode($_GET['sort']);

$params=json_decode($_GET['sort'],true);
$GetBrand_name1=$params['filter']['brand1'];
$GetBrand_name2=$params['filter']['brand2'];
$akey1= $params['filter']['akey1'];
$akey2= $params['filter']['akey2'];
$side= $params['filter']['side'];
$code= $params['filter']['code'];
$page= $params['page'];

//Формирование запроса
$sql=" SELECT  * FROM" . " " . TD_DB_TABLE_TDM_LINKS ;


//если артикул1 не передался, то запрос формируется без него
if ($akey1 == NULL)
{
    $sqlAkey1='akey1 IS NOT NULL';
}

//иначе включаем артикул1 в запрос
else
{
    $sqlAkey1="akey1 = '$akey1'"  ;
}

//если бренд1 не передался, то запрос формируется без него
if ($GetBrand_name1 == NULL)
{
    $sqlBrand_id1=' AND brand_id1 IS NOT NULL';
}
//иначе получаем id бренда по имени
else
{
    $ch = curl_init();
    $aa = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode(json_encode($GetBrand_name1));

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $GetBrand_id1 = json_decode(curl_exec($ch));

    //если бренд не найден, то формируем запрос без него
    if( $GetBrand_id1==15065) $sqlBrand_id1=' AND brand_id1 IS NOT NULL';

    //иначе включаем его в запрос
    else $sqlBrand_id1=" and brand_id1 = '$GetBrand_id1'" ;

}


//если бренд2 не передался, то запрос формируется без него
if ($GetBrand_name2 == NULL)
{
    $sqlBrand_id2=' AND brand_id2 IS NOT NULL';
}
//иначе получаем id бренда по имени
else
{
    $ch = curl_init();
    $aa = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode(json_encode($GetBrand_name2));


    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $GetBrand_id2 = json_decode(curl_exec($ch));


    //если бренд не найден, то формируем запрос без него
    if( $GetBrand_id2==15065) {
        $sqlBrand_id2=' AND brand_id2 IS NOT NULL';
    }

    //иначе включаем его в запрос
    else
    {
        $sqlBrand_id2=" and brand_id2 = '$GetBrand_id2'"  ;
    }
}

//если артикул1 не передался, то запрос формируется без него
if ($akey2 == NULL)
{
    $sqlAkey2=' AND akey2 IS NOT NULL';
}
//иначе включаем его в запрос
else
{
    $sqlAkey2=" AND akey2 ='$akey2'"  ;
}

//если сторона не передалась, то запрос формируется без него
if ($side == NULL)
{
    $sqlSide=' AND side IS NOT NULL';
}
//иначе включаем ее в запрос
else
{
    $sqlSide=" AND side = $side"  ;
}
//если код не передался, то запрос формируется без него
if ($code == NULL)
{
    $sqlCode=' AND code IS NOT NULL';
}
//иначе включаем его в запрос
else
{
    $sqlCode=" AND code = '$code'"  ;
}
//если параметры сортировки  не передались, то запрос формируется без них
if ((($params['sort'] == NULL) || ($params['order']== NULL)))
{
    $sortSql='';
}

//иначе включаем его в запрос
else
{
    $sortSql="ORDER BY" . " " . $params['sort'] . " " . $params['order'] ;
}


// если передалась первая страница
if ($page == 1 ) {
    //формируем запрос без offset
    $a .= $sql . " " . "WHERE" . " " . $sqlAkey1 . " " . $sqlBrand_id1 . " " . " " . $sqlBrand_id2 . " " . $sqlAkey2 . " " . $sqlSide . " " . $sqlCode . " " . $sortSql . " " . "limit 20";

}

//Иначе, формируем вывод 20 строк, исходя из страницы
else
    {
        $page=$page - 1;
        if ($page < 0) $page =0;
        $page=$page * 20;

        $a .= $sql . " " . "WHERE" . " " . $sqlAkey1 . " " . $sqlBrand_id1 . " " . " " . $sqlBrand_id2 . " " . $sqlAkey2 . " " . $sqlSide . " " . $sqlCode . " " . $sortSql . " " . " OFFSET" . " " . $page  . " " . "limit 20";

        //echo  $a;
    }
//echo $a;

//выполняем запрос
$result = pg_query($db,$a);
$allCrosses=array();

//по каждой строке
while ($row = pg_fetch_assoc($result)){
//формируем id-брендов для отправки
    $brand_id1=json_encode($row['brand_id1']);
    $brand_id2=json_encode($row['brand_id2']);

    //Получение имени бренда по id
    $ch = curl_init();
    $a = GET_BRAND_NAME_BY_ID_ADRESS."?b_id=$brand_id1";
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $a);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $brand_name1 = json_decode(curl_exec($ch));
    curl_close($ch);

    //Получение имени бренда по id
    $ch = curl_init();
    $a = GET_BRAND_NAME_BY_ID_ADRESS."?b_id=$brand_id2";
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $a);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $brand_name2 = json_decode(curl_exec($ch));
    curl_close($ch);

    //формирование общего массива кроссов
    $allCrosses[]=['brand_name1'=>$brand_name1,'akey1'=> $row['akey1'],'brand_name2'=>$brand_name2,'akey2'=>$row['akey2'],'side'=>$row['side'],'code'=> $row['code']];

}
//вывод кроссов
$a=json_encode($allCrosses);
echo $a;
pg_close($db);


