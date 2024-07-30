<?php

require "config.local.php";
set_time_limit(0);
//Подключение к бд
$dbGen = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" .TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_BRANDS_CLIENT1 ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
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


$brandId = '';


$sqlQuery = 'SELECT TGB.gob__id,TGoB.gob__name,TGB.b__id,TB.b__name,TGB.smlr_percent,TGB.smlr_checked FROM ' . TD_DB_TABLE_TDM_GROUP_BRAND . ' TGB INNER JOIN ' . TD_DB_TABLE_TDM_BRANDS . ' TB ON TB.b__id=TGB.b__id INNER JOIN ' . TD_DB_TABLE_TDM_GROUPS_OF_BRANDS . ' TGoB ON TGoB.gob__id=TGB.gob__id';
file_put_contents("1.txt",$sqlQuery);
if (!empty($brandId)) {
    $sqlQuery .= ' WHERE TGB.b__id=\'' . $brandId . '\'';
}
$sqlQuery .= ';';

$result=pg_query($dbClient, $sqlQuery);




while ($arResult = pg_fetch_assoc($result))
{
    if (intval($arResult['smlr_percent'])==0 && intval($arResult['smlr_checked'])==0) {
        $strGroup = $arResult['gob__name'];
        $strBrand = $arResult['b__name'];


        $strBrandCmp = strtoupper(CyrillicTransliteration($strBrand));
        $strGroupCmp = strtoupper(CyrillicTransliteration($strGroup));

        similar_text($strGroupCmp, $strBrandCmp, $percent);
        echo $percent;
        $percent = ceil($percent);
        $maxRelevance = $percent;
       echo $maxRelevance;


        if ($maxRelevance<55)
        {
            $strOrig = $strGroupCmp;
            $strSrchRusM = CyrillicTransliteration(CyrillicMetaphone(mb_strtoupper($strBrand)));
            $strSrchRus = mb_strtoupper(CyrillicTransliteration($strBrand));

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
            $strOrig = CyrillicMetaphone($strGroup);
            $strSrchRusM = CyrillicTransliteration(CyrillicMetaphone(mb_strtoupper($strBrand)));
            $strSrchRus = mb_strtoupper(CyrillicTransliteration($strBrand));

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
            $arGroupW = preg_split("/[\s,-]+/", $strGroupCmp);
            $strOrig = '';
            foreach ($arGroupW as $word)
            {
                $strOrig .= $word[0];
            }
            $strSrchRusM = CyrillicTransliteration(CyrillicMetaphone(mb_strtoupper($strBrand)));
            $strSrchRus = mb_strtoupper(CyrillicTransliteration($strBrand));

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

        $updSql = 'UPDATE ' . TD_DB_TABLE_TDM_GROUP_BRAND . ' SET ';
        $updSql .= 'smlr_percent=\'' . $maxRelevance . '\'';
        /*
        if ($percent>=55)
        {
          $updSql .= ', smlr_checked=\'1\'';
        }
        */
        $updSql .= ' WHERE gob__id=\'' . $arResult['gob__id'] . '\' AND b__id=\'' . $arResult['b__id'] . '\';';
        pg_query($updSql);
    }
}

