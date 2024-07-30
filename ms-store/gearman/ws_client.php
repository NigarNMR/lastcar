<?php

global $arNewPrices;
$arNewPrices = array();
$arBrandGroups = array();
$arBrandGroups['ASVA'] = '312';
$arBrandGroups['SAT'] = '4919';

$gmc = new GearmanClient();
$gmc->addServer();

# регистрация функций обратного вызова
$gmc->setCreatedCallback("reverse_created");
$gmc->setDataCallback("reverse_data");
$gmc->setStatusCallback("reverse_status");
$gmc->setCompleteCallback("reverse_complete");
$gmc->setFailCallback("reverse_fail");

$arOnlineWs = array();
$arOnlineWs[] = array("LOGIN" => "397882", "PASSW" => "12649a54", "CURRENCY" => "RUB", "SCRIPT" => "emex.ru", "QLIMIT" => "20", "OrOnly" => FALSE);

$arWs = array();
$arWs[] = array("LOGIN" => "mashkovsky_ab@mail.ru", "PASSW" => "27072016armtek", "CURRENCY" => "RUB", "SCRIPT" => "armtek.ru", "QLIMIT" => "3", "OrOnly" => TRUE);
$arWs[] = array("LOGIN" => "397882", "PASSW" => "12649a54", "CURRENCY" => "RUB", "SCRIPT" => "emex.ru", "QLIMIT" => "20", "OrOnly" => FALSE);

# указание неких произвольных данных
//$data['foo'] = 'bar';

$arOrgPart = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");

$arParts = array();
$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");

$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");
$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");
$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");

$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");
$arParts[] = array("PKEY" => "ASVANS34", "BKEY" => "ASVA", "AKEY" => "NS34", "BRAND" => "ASVA", "ARTICLE" => "NS34");
$arParts[] = array("PKEY" => "ASVAMZIRM3", "BKEY" => "ASVA", "AKEY" => "MZIRM3", "BRAND" => "ASVA", "ARTICLE" => "MZIRM3");
$arParts[] = array("PKEY" => "SATSTBMX52061", "BKEY" => "SAT", "AKEY" => "STBMX52061", "BRAND" => "SAT", "ARTICLE" => "STBMX52061");
# добавление двух заданий
//$task= $gmc->addTask("reverse", "foo", 'bar');

require_once 'test.php';

$tasksCompleted = false;


$arPosition = array();
foreach ($arWs as $wsKey => $arWsData) {
    $arPosition[$wsKey] = 0;
    $arWsComplete[$wsKey] = FALSE;
}
$partsCount = count($arParts);

$startTime = microtime(true);
while (TRUE) {
    $arTasks = array();
    $exitKey = 1;
    
    foreach ($arOnlineWs as $wsKey => $arWsData) {
        //Поиск по всем запчастям
        if (!$arWsData['OrOnly']) {
            for ($i = 0; $i < intval($arWsData["QLIMIT"]); $i++) {
                if ($arPosition[$wsKey] >= $partsCount) {
                    $exitKey *= 1;
                }
                else {
                    $exitKey *= 0;
                    $arDataToOp["WS"] = $arWsData;
                    $arDataToOp["PART"] = $arParts[$arPosition[$wsKey]];
                    if (isset($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)]) && !empty($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)])) {
                        $arDataToOp["PART"]["BRAND"] = $arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)];
                    }
                    $arPosition[$wsKey]++;

                    $arTasks[] = $gmc->addTaskHigh("reverse", json_encode($arDataToOp), NULL);
                }
            }
        }
        //Поиск лишь по запрашиваемой запчасти
        else {
            if (!$arWsComplete[$wsKey]) {
                $exitKey *= 0;
                $arDataToOp["WS"] = $arWsData;
                $arDataToOp["PART"] = $arOrgPart;
                if (isset($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)]) && !empty($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)])) {
                    $arDataToOp["PART"]["BRAND"] = $arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)];
                }
                    
                $arTasks[] = $gmc->addTaskHigh("reverse", json_encode($arDataToOp), NULL);
                $arWsComplete[$wsKey] = TRUE;
            }
            else {
                $exitKey *= 1;
            }
        }
    }
    
    if ($exitKey) {
        $tasksCompleted = true;
        break;
    }
    
    $gmc->runTasks();
    time_nanosleep(0, 340000000);
}
$arOnlinePrices = $arNewPrices;
$arNewPrices = array();
$endTime = microtime(true);
echo("\n\n");
var_dump(count($arOnlinePrices));
echo "\nTIME: ". ($endTime - $startTime);
echo("\n\n");

$startTime = microtime(true);
while (TRUE) {
    $arTasks = array();
    $exitKey = 1;
    
    foreach ($arWs as $wsKey => $arWsData) {
        //Поиск по всем запчастям
        if (!$arWsData['OrOnly']) {
            for ($i = 0; $i < intval($arWsData["QLIMIT"]); $i++) {
                if ($arPosition[$wsKey] >= $partsCount) {
                    $exitKey *= 1;
                }
                else {
                    $exitKey *= 0;
                    $arDataToOp["WS"] = $arWsData;
                    $arDataToOp["PART"] = $arParts[$arPosition[$wsKey]];
                    if (isset($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)]) && !empty($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)])) {
                        $arDataToOp["PART"]["BRAND"] = $arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)];
                    }
                    $arPosition[$wsKey]++;

                    $arTasks[] = $gmc->addTaskHigh("reverse", json_encode($arDataToOp), NULL);
                }
            }
        }
        //Поиск лишь по запрашиваемой запчасти
        else {
            if (!$arWsComplete[$wsKey]) {
                $exitKey *= 0;
                $arDataToOp["WS"] = $arWsData;
                $arDataToOp["PART"] = $arOrgPart;
                if (isset($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)]) && !empty($arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)])) {
                    $arDataToOp["PART"]["BRAND"] = $arSrchAlias[$arGroupsId[$arDataToOp["PART"]["BRAND"]].'.'.($wsKey+1)];
                }
                    
                $arTasks[] = $gmc->addTaskHigh("reverse", json_encode($arDataToOp), NULL);
                $arWsComplete[$wsKey] = TRUE;
            }
            else {
                $exitKey *= 1;
            }
        }
    }
    
    if ($exitKey) {
        $tasksCompleted = true;
        break;
    }
    
    $gmc->runTasks();
    time_nanosleep(0, 340000000);
}
$endTime = microtime(true);
echo("\n\n");
$arCachePrices = $arNewPrices;
$arNewPrices = array();
var_dump(count($arCachePrices));
echo("\n\n");

echo "DONE\n";
echo "TIME: ". ($endTime - $startTime) . "\n";

function reverse_created($task)
{
    echo "CREATED: " . $task->jobHandle() . "\n";
}

function reverse_status($task)
{
    echo "STATUS: " . $task->jobHandle() . " - " . $task->taskNumerator() . 
         "/" . $task->taskDenominator() . "\n";
}

function reverse_complete($task)
{
    echo "COMPLETE: " . $task->jobHandle() . ", " . $task->data() . "\n";
}

function reverse_fail($task)
{
    echo "FAILED: " . $task->jobHandle() . "\n";
}

function reverse_data($task)
{
    //echo "DATA: " . $task->data() . "\n";
    $arPrice = json_decode($task->data());
    global $arNewPrices;
    $arNewPrices[] = $arPrice;
}