<?php
set_time_limit(0);
# curl "http://192.168.33.10/index.php?route=search/prices/collect&article=art&brand=bra"
$exec_string = 'curl "http://' . urlencode($argv[3]) . '/index.php?route=search/prices/collect&article=' . urlencode($argv[1]) . '&brand=' . urlencode($argv[2]) . '"';
exec($exec_string);
