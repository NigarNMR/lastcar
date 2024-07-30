<?php
set_time_limit(0);
$exec_string = 'curl "http://' . urlencode($argv[2]) . '/index.php?route=search/parts/collect&article=' . urlencode($argv[1]) . '"';
exec($exec_string);
