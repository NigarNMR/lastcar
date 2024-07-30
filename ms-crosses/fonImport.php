<?php

require "config.local.php";
set_time_limit(0);

$file = urldecode($argv[1]);
$start= urldecode($argv[2]);
$end= urldecode($argv[3]);
$arFtoN= array();
$arFtoN=["BRAND_ID1"=> $argv[6], "BRAND_ID2"=> $argv[7],"AKEY1"=>$argv[4],"AKEY2"=>$argv[5]];


//Проверка начальной и конечной строки
if (isset($start))
{
    if (!empty($start))
    {
        $start = intval($start - 1);
    }
    else
    {
        $start = 0;
    }
    echo $start;
}

else $start = 0;


if (isset($end))
{
    if (!empty($end))
    {
        $end = intval($end);
    }
    else
    {
        $end = 0;
    }
    echo $end;
}

else $end=0;

if ($end < $start ){
    $start = $end;
    $end =0;
}

$file = file($file);

if ($end == 0)
{
    $end = count($file);
}




// Выборка и замена названий брендов на id
$mysqli = new mysqli(TD_DB_HOSTNAME, TD_DB_USERNAME, TD_DB_PASSWORD,TD_DB_DATABASE);

$sel_query = "SELECT b__name, IF (TDM_BRANDS.b__parent_id > 0, TDM_BRANDS.b__parent_id,TDM_BRANDS.b__id) AS BRAND FROM `tdm_web_eijen_ru`.`TDM_BRANDS`";
$result = $mysqli->query($sel_query);

while( $row = $result->fetch_array())
{

    $brands[$row[0]] = $row[1];

}

$file = array_slice($file,$start,$end);
$countfile=count($file);
$startpoz = 0; $endpoz= 500; $trigger= true; $counter = 0;

while($trigger != false){

    $fileslize = array_slice($file,$startpoz,$endpoz);
    $mysqli = new mysqli('localhost', 'root', 'root', 'tdm_web_eijen_ru');

    foreach ($fileslize as $line =>$strLine) {


        $arCSVrow = explode(';', $strLine);
        $arFields = array();

        foreach ($arFtoN as $FIELD => $NUM) {
            $VALUE = str_replace("\"", "", trim($arCSVrow[$NUM]));
            $VALUE = str_replace("'", "", $VALUE);
            $arFields[$FIELD] = $VALUE;

        }


        $brandsName2= $arFields["BRAND_ID2"];
        $brandsName1 = $arFields["BRAND_ID1"];

        $arFields["BRAND_ID2"] = $brands[$arFields["BRAND_ID2"]];
        $arFields["BRAND_ID1"] = $brands[$arFields["BRAND_ID1"]];

     if ($arFields["BRAND_ID2"] == 0)
        {
           $mysqli->query("INSERT INTO `tdm_web_eijen_ru`.`TDM_BRANDS`(`b__name` ,`b__parent_id` ,`b__status`) VALUES ('$brandsName2','0','0') ");
           $brand = $mysqli->query(" SELECT `b__name`, `b__id` FROM `tdm_web_eijen_ru`.`TDM_BRANDS` where b__name='$brandsName2' ");
           while( $row1 = $brand->fetch_array())
            {
                $brands[$row1[0]] = $row1[1];
            }

          $arFields["BRAND_ID2"] = $brands[0];


        }

        if ($arFields["BRAND_ID1"] == 0)
        {
            $mysqli->query("INSERT INTO `tdm_web_eijen_ru`.`TDM_BRANDS`(`b__name` ,`b__parent_id` ,`b__status`) VALUES ('$brandsName1','0','0') ");
            $brand = $mysqli->query(" SELECT `b__name`, `b__id` FROM `tdm_web_eijen_ru`.`TDM_BRANDS` where b__name='$brandsName1' ");
            while( $row1 = $brand->fetch_array())
            {
                $brands[$row1[0]] = $row1[1];
            }

            $arFields["BRAND_ID1"] = $brands[0];


        }

        $arUKeys = array();
        $arUValue = array();

        foreach ($arFields as $key => $value) {
            $arUKeys[] = $key;
            $arUValue[] = "'" . $mysqli->real_escape_string($value) . "'";
        }

        $qKeys = implode(",", $arUKeys);
        $qValues = implode(",", $arUValue);


       $str .="(";
       $str .= $qValues;
       $str .="),";


    }

    $str = substr($str,0,-1);
    $SQL = "INSERT IGNORE INTO `tdm_web_eijen_ru`.`tdm_link_tmps` \n(" . $qKeys . ") \nVALUES \n $str \n";
    $counter = $counter + 200;
   

    $query = $mysqli->query($SQL);

    unset($fileslize);
    $startpoz  = $startpoz + 500;
    unset ($str);
   // sleep(1);

    if (($counter == $countfile) || $counter > $countfile)
    {
        $trigger = false;
        $query = $mysqli->close();

    }

}






