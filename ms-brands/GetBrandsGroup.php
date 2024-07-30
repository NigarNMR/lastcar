<?php

require "config.local.php";

//подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_BRANDS ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

//Получение и парсинг параметров
$_GET['sort']=urldecode($_GET['sort']);
$params = json_decode($_GET['sort'],true);
$gob_name = $params['filter']['gob_name'];
$page =$params['page'] ;

// формирование запроса
$sql=" SELECT  * FROM" . " ".  TD_DB_TABLE_TDM_GROUPS_OF_BRANDS ;

//если имя группы отсутствует, то выбираем их все
if ($gob_name == NULL)
{
    $sqlGob='  gob__id IS NOT NULL';
}

//иначе получаем id группы и добавляем параметр в запрос
else
{   $gob_name = json_encode($gob_name);
    $ch = curl_init();

    $aa = GET_GROUP_ID_BY_NAME."?gob_name=".urlencode($gob_name);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $gob_id = json_decode(curl_exec($ch));
if($gob_id ==NULL){
    exit();
}
else{
    $sqlGob=' gob__id ='. $gob_id ;
}

}


//если нет параметров сортировки, то пропускаем их
if ((($params['sort'] == NULL) || ($params['order']== NULL)))
{
    $sortSql='';
}

//иначе добавлям параметры сортировки в запрос
else
{
    $sortSql="ORDER BY" . " " . $params['sort'] . " " . $params['order'] ;
}

//если параметр страницы =1 то выводим первые 20 строк без учета offset
if ($page == 1 ) {
    $a .= $sql . " " . "WHERE" . " " .  $sqlGob  . " " . $sortSql . " " . "limit 20";

}

//иначе  формируем запрос исходя из параметра страницы
else
{
    $page=$page - 1;
    if ($page < 0) $page =0;
    $page=$page * 20;

    $a .= $sql . " " . "WHERE" . " " . $sqlGob   . " " . $sortSql . " " . " OFFSET" . " " . $page  . " " . "limit 20";

}

//echo $a;
//выполняем запрос
$result = pg_query($dbClient,$a);
$count= pg_num_rows($result);

//формируем массив общих групп
$allGroups=array();

//По каждой строке
while ($row = pg_fetch_assoc($result)){

    //Получаем имя группы по id
    $gobId=json_encode($row['gob__id']);
    $ch = curl_init();
    $a = GET_GROUPNAME_BY_ID."?gob_id=$gobId";
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $a);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $brand_name1 = json_decode(curl_exec($ch));
    curl_close($ch);
    //Заполняем массив
    $allGroups[]=['gob_id'=>$gobId,'gob_name'=> $row['gob__name']];

}
//вывод данных
echo(json_encode($allGroups));

//закрытие бд
pg_close($dbClient);




