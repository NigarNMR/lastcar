<?php
//переменная get-запроса должна называеться brands. json должен быть перечеслением названий брендов через запятую. Пример http://139.168.33.11/brand/GetBrandForSearch.php?brands=[%22AUDI%22,%22NISSAN%22,%22OPEL%22]

require "config.local.php";
$db = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);


//Получение и парсинг данных
$brands=$_GET['brands'];
$brands=json_decode($brands);

foreach ($brands as $brand) {

    $sql = "SELECT * FROM " . " " . TD_DB_TABLE_TDM_BRANDS . " " . "where b__name = '$brand' ";
    $result = pg_query($db, $sql);

//если запрос не прошел, выдат причину
    if (!$result) {
        exit;
    }

    else {

        while ($row = pg_fetch_row($result)) {
            $id = $row[0];
            $name= $row[1];
            $parent_id=$row[2];

            $resourses[]=['b__name'=>$name, 'b__id'=>$id, 'b__parent_id'=>$parent_id];
        }

    }
}
if(!empty($resourses)){

    $result=['code'=>200,'status'=>'success', 'data'=>array('brands'=>$resourses)];
    printf(json_encode($result));
}

pg_close($db);