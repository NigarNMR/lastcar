<?php
require "config.local.php";
require "functions.php";

function Akey($val)
{
    $VAL =strtoupper($val);
    $VAL=trim($VAL);
    $VAL = str_replace("\xc3\x8b", "E", $VAL);
    $VAL = str_replace("\xc3\x96", "O", $VAL);
    $VAL = str_replace("\xc3\x92", "O", $VAL);
    $VAL = str_replace("\xc3\x84", "A", $VAL);
    $VAL = str_replace("\xc3\x9c", "U", $VAL);
    $VAL = str_replace("O'", "O", $VAL);
    $VAL = str_replace("\xe2\x84\x96", "", $VAL);

    //Удаление неподходящих символов из $VAL
    $VAL = preg_replace("/[^A-Z\xd0\x90-\xd0\xaf0-9a-z\xd0\xb0-\xd1\x8f]/", "", $VAL);

    return trim($VAL);
}
