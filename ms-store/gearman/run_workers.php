<?php
require_once 'Process.php';
$usleep = 900000;
$process_id = getmypid();
$fp = fopen('log/run_workers.txt', 'w+');
$fp2 = fopen('log/kill_workers.txt', 'w+');
$pids = array();
$process_count = 15;

for($i=0; $i<$process_count; $i++){
  $command = 'php -f ws_worker.php';
  $parser_posts = new Process($command);
  $parser_posts_id = $parser_posts->getPid();
  $text = "№ $i worker_process ID: $parser_posts_id \n";
  echo $text;
  fwrite($fp, $text);
  $ids[] = $parser_posts_id;
  usleep($usleep);
}

fclose($fp);

$text = 'kill '.implode(' ', $ids);
fwrite($fp2, $text);
fclose($fp2);
?>