<?php
require "config.local.php";
require "functions.php";

//Получаем данные
$json = urldecode(file_get_contents('php://input'));
$obj = json_decode($json,true);
//Подключение бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
//$cross=json_decode($_GET['cross'],true);

//Парсинг полученных данных
$brand_name1=json_encode($obj['brand_name1']);
$akey1=Akey($obj['akey1']);
$brand_name2=json_encode($obj['brand_name2']);
$akey2=Akey($obj['akey2']);
$side=$obj['side'];
$code=$obj['code'];


//Замена имени бренда на id
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($brand_name1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id1 = json_decode(curl_exec($ch));



curl_close($ch);

//Замена имени бренда на id
$ch = curl_init();
$a = GET_BRAND_ID_BY_NAME_ADRESS."?b_name=".urlencode($brand_name2);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id2=json_decode(curl_exec($ch));
curl_close($ch);


//Просмотр кросса в general бд
//$sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$brand_id1 AND akey1='$akey1' AND brand_id2=$brand_id2 AND akey2='$akey2'";
$sql="SELECT * FROM" . " ". "test_tdm_links" . " ". "WHERE brand_id1=$brand_id1 AND akey1='$akey1' AND brand_id2=$brand_id2 AND akey2='$akey2'";
$result=pg_query($dbGen,$sql);
$rows = pg_num_rows($result);


//если кросс найден, то уведомление, что кросс уже существует
if ((!$rows ==0))
{

   // $qw=0;
    echo "Кросс уже присутствует";
}
// иначе проверка в клиентской бд
else
    {
        $sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$brand_id1 AND akey1='$akey1' AND brand_id2=$brand_id2 AND akey2='$akey2'";
        $result=pg_query($dbClient,$sql);
        $rows = pg_num_rows($result);

        //если кросс найден то уведомление, что кросс уже существует и добавление его в general
        if ((!$rows ==0))
        {
            $qw=0;
            //echo "Кросс уже присутствует";
            //  $sql1="INSERT INTO" . " ". TD_DB_TABLE_TDM_LINKS_TMP . " ". "VALUES($brand_id1,'$akey1',$brand_id2,'$akey2',0,'-',0)";
            $sql1="INSERT INTO" . " ". "test_tdm_links" . " ". "VALUES($brand_id1,'$akey1',$brand_id2,'$akey2',$side,'$code',0)";
            $result=pg_query($dbGen,$sql1);


        }

        //иначе добавление кросса в обе таблицы
        else
        {
            //тестовые таблицы
            $sql="INSERT INTO" . " ". TD_DB_TABLE_TDM_LINKS  . " ". "VALUES($brand_id1,'$akey1',$brand_id2,'$akey2',$side,'$code',0)";
            $result=pg_query($dbClient,$sql);
           // $result=pg_query($dbGen,$sql);
          //  echo "Кросс успешно добавлен";

        }


    }

pg_close($dbGen);
pg_close($dbClient);

