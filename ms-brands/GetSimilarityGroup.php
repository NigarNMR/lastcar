<?php

require "config.local.php";
set_time_limit(0);

//Подключение к бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$name=urldecode($_GET['name']);


if (!$dbClient) {
    echo "Произошла ошибка.\n";
    // exit;
}

function CyrillicTransliteration($st)
{
    $st = urldecode($st);
    $cyr = [
        'а', 'б', 'в', 'г', 'д', 'е', 'ё', 'ж', 'з', 'и', 'й', 'к', 'л', 'м', 'н', 'о', 'п',
        'р', 'с', 'т', 'у', 'ф', 'х', 'ц', 'ч', 'ш', 'щ', 'ъ', 'ы', 'ь', 'э', 'ю', 'я',
        'А', 'Б', 'В', 'Г', 'Д', 'Е', 'Ё', 'Ж', 'З', 'И', 'Й', 'К', 'Л', 'М', 'Н', 'О', 'П',
        'Р', 'С', 'Т', 'У', 'Ф', 'Х', 'Ц', 'Ч', 'Ш', 'Щ', 'Ъ', 'Ы', 'Ь', 'Э', 'Ю', 'Я'
    ];
    $lat = [
        'a', 'b', 'v', 'g', 'd', 'e', 'io', 'zh', 'z', 'i', 'y', 'k', 'l', 'm', 'n', 'o', 'p',
        'r', 's', 't', 'u', 'f', 'h', 'ts', 'ch', 'sh', 'sht', 'a', 'i', 'y', 'e', 'yu', 'ya',
        'A', 'B', 'V', 'G', 'D', 'E', 'Io', 'Zh', 'Z', 'I', 'Y', 'K', 'L', 'M', 'N', 'O', 'P',
        'R', 'S', 'T', 'U', 'F', 'H', 'Ts', 'Ch', 'Sh', 'Sht', 'A', 'I', 'Y', 'e', 'Yu', 'Ya'
    ];
    $st = str_replace($cyr, $lat, $st);
    return $st;
}


function CyrillicMetaphone($st)
{
    $st = mb_strtoupper(urldecode($st));
    $cyr = [
        'ЭЙ', 'БИ', 'СИ', 'ДИ', 'ЭФ', 'ДЖИ', 'ЭЙЧ', 'АЙ', 'ДЖЕЙ', 'КЕЙ', 'ЭЛ', 'ЭМ', 'ЭН', 'ОУ', 'ПИ', 'КЬЮ', 'ЭС', 'ТИ', 'ВИ', 'ЭКС', 'УАЙ', 'ЗЕД'
    ];
    $lat = [
        'A', 'B', 'C', 'D', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'S', 'T', 'V', 'X', 'Y', 'Z',
    ];
    $st = str_replace($cyr, $lat, $st);
    return $st;
}



    $ch = curl_init();
    $aa = GET_ALL_GROUPS;

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_URL, $aa);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
    $arGroups = json_decode(curl_exec($ch),true);




$strBrand=$name;

$arGrBrSmlr = array();
foreach ($arGroups as $arGrData)
{
    $strGroup = $arGrData['b__name'];

    $strBrandCmp = strtoupper(CyrillicTransliteration($strBrand));
    $strGroupCmp = strtoupper(CyrillicTransliteration($strGroup));

    similar_text($strBrandCmp,$strGroupCmp,$percent);
    $percent = ceil($percent);
    $maxRelevance = $percent;

    if ($maxRelevance<55)
    {
        $strOrig = $strBrandCmp;
        $strSrchRusM = CyrillicTransliteration(CyrillicMetaphone(mb_strtoupper($strGroup)));
        $strSrchRus = mb_strtoupper(CyrillicTransliteration($strGroup));

        $arRusM = preg_split("/[\s,-]+/", $strSrchRusM);
        $abbrRusM = '';
        foreach ($arRusM as $wordRusM)
        {
            $abbrRusM .= $wordRusM[0];
        }

        $arRus = preg_split("/[\s,-]+/", $strSrchRus);
        $abbrRus = '';
        foreach ($arRus as $wordRus)
        {
            $abbrRus .= $wordRus[0];
        }

        similar_text($strOrig, $strSrchRus, $percentR);
        $percentR = ceil($percentR);
        similar_text($strOrig, $abbrRus, $percentRA);
        $percentRA = ceil($percentRA);
        similar_text($strOrig, $strSrchRusM, $percentRM);
        $percentRM = ceil($percentRM);
        similar_text($strOrig, $abbrRusM, $percentRMA);
        $percentRMA = ceil($percentRMA);

        if ($percentR>$maxRelevance)
        {
            $maxRelevance = $percentR;
        }
        if ($percentRA>$maxRelevance)
        {
            $maxRelevance = $percentRA;
        }
        if ($percentRM>$maxRelevance)
        {
            $maxRelevance = $percentRM;
        }
        if ($percentRMA>$maxRelevance)
        {
            $maxRelevance = $percentRMA;
        }
    }

    if ($maxRelevance<55)
    {
        $strOrig = CyrillicMetaphone($strBrand);
        $strSrchRusM = CyrillicTransliteration(CyrillicMetaphone(mb_strtoupper($strGroup)));
        $strSrchRus = mb_strtoupper(CyrillicTransliteration($strGroup));

        $arRusM = preg_split("/[\s,-]+/", $strSrchRusM);
        $abbrRusM = '';
        foreach ($arRusM as $wordRusM)
        {
            $abbrRusM .= $wordRusM[0];
        }

        $arRus = preg_split("/[\s,-]+/", $strSrchRus);
        $abbrRus = '';
        foreach ($arRus as $wordRus)
        {
            $abbrRus .= $wordRus[0];
        }

        similar_text($strOrig, $strSrchRus, $percentR);
        $percentR = ceil($percentR);
        similar_text($strOrig, $abbrRus, $percentRA);
        $percentRA = ceil($percentRA);
        similar_text($strOrig, $strSrchRusM, $percentRM);
        $percentRM = ceil($percentRM);
        similar_text($strOrig, $abbrRusM, $percentRMA);
        $percentRMA = ceil($percentRMA);

        if ($percentR>$maxRelevance)
        {
            $maxRelevance = $percentR;
        }
        if ($percentRA>$maxRelevance)
        {
            $maxRelevance = $percentRA;
        }
        if ($percentRM>$maxRelevance)
        {
            $maxRelevance = $percentRM;
        }
        if ($percentRMA>$maxRelevance)
        {
            $maxRelevance = $percentRMA;
        }
    }

    $arGrBrSmlr[$strGroup] = $maxRelevance;
}

arsort($arGrBrSmlr);


$counter = 1;
foreach ($arGrBrSmlr as $grName => $arData)
{
    if ($counter>15)
    {
        break;
    }
    else
    {

        $result[]=['group'=>$grName,'arData'=>$arData];


    }
}

$result=array_slice($result,0,15);
echo json_encode($result);