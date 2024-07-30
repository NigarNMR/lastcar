<?php
  
    $rediska = new Redis();
    $rediska->connect('127.0.0.1', 6379);
    
    //$rediska->del('aliasTable');
    //var_dump($rediska->hGetAll('SearchAliases'));
    //$rediska->hDel('SearchAliases','3765_2');
    //$alData = json_decode($rediska->get('aliasTable'), true);
    //echo ($alData['Complex']);
    
echo(is_null(null) && empty(null));