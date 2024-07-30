<?php

require "config.local.php";
//Получение данных



$json = urldecode(file_get_contents('php://input'));

$cross = json_decode($json,true);


//Подключение к бд
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$cross=json_decode($_GET['cross'],true);
//$crossUpd=json_decode($_GET['crossUpd'],true);

//Парсинг старых значений кросса
$oldBrandName1=json_encode($cross['brand_name1O']);
$oldAkey1=$cross['akey1O'];
$oldBrandName2=json_encode($cross['brand_name2O']);
$oldAkey2=$cross['akey2O'];
////Парсинг обновленных значений кросса
$newBrandName1=json_encode($cross['brand_name1']);
$newAkey1=Akey($cross['akey1']);
$newBrandName2=json_encode($cross['brand_name2']);
$newAkey2=Akey($cross['akey2']);
$side=$cross['side'];
$code=$cross['code'];



//Получение id по имени бренда
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($oldBrandName1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$oldBrand_id1=json_decode(curl_exec($ch));
curl_close($ch);
//echo $oldBrand_id1;

//Получение id по имени бренда
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($oldBrandName2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$oldBrand_id2=json_decode(curl_exec($ch));
curl_close($ch);


//Получение id по имени бренда
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($newBrandName1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$newBrand_id1=json_decode(curl_exec($ch));
curl_close($ch);

//Заглушка. если id нового бренда нет, то обращение к микросервису брендов на добавление нового бренда с переданным именем,
// а также выборка его id



//Получение id по имени бренда
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($newBrandName2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$newBrand_id2=json_decode(curl_exec($ch));
curl_close($ch);

//Заглушка. если id нового бренда нет, то обращение к микросервису брендов на добавление нового бренда с переданным именем,
// а также выборка его id


//Просмотр есть ли в general бд обновленный кросс
$sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$newBrand_id1 AND akey1='$newAkey1' AND brand_id2=$newBrand_id2 AND akey2='$newAkey2'";
$result=pg_query($dbGen,$sql);
$rows = pg_num_rows($result);
//Если есть , то уведомление что кросс уже существует
if ((!$rows ==0))
{
    //$qw=0;
    //echo "Кросс уже присутствует";
}

//Иначе Просмотр есть ли в клиентской бд обновленный кросс
else{

    $sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$newBrand_id1 AND akey1='$newAkey1' AND brand_id2=$newBrand_id2 AND akey2='$newAkey2'";
    $result=pg_query($db,$sql);
    $rows = pg_num_rows($result);

//Если есть , то уведомление что кросс уже существует и занесение его в general бд
    if ((!$rows ==0))
    {
        $sql="UPDATE" . " " . TD_DB_TABLE_TDM_LINKS . " " . " set brand_id1= $newBrand_id1 ,akey1='$newAkey1',brand_id2=$newBrand_id2,akey2='$newAkey2',side=$side,code='$code' where brand_id1=$oldBrand_id1 and akey1='$oldAkey1' and brand_id2=$oldBrand_id2 and akey2='$oldAkey2'";
        pg_query($dbGen,$sql);
        //echo "Кросс уже присутствует";
    }

//Иначе запрос на обновление данных в кроссе в обоих таблицах
    else{

        $sql="UPDATE" . " " . TD_DB_TABLE_TDM_LINKS . " " . " set brand_id1= $newBrand_id1 ,akey1='$newAkey1',brand_id2=$newBrand_id2,akey2='$newAkey2',side=$side,code='$code' where brand_id1=$oldBrand_id1 and akey1='$oldAkey1' and brand_id2=$oldBrand_id2 and akey2='$oldAkey2'";

         pg_query($dbClient,$sql);
       // pg_query($dbGen,$sql);

    }
}


//Закрытие бд
pg_close($dbClient);
pg_close($dbGen);