
<?php
require "config.local.php";
$dbClient = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_CROSS_CLIENT ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);
$dbGeneral = pg_connect("host=". TD_DB_HOSTNAME .' '."port=" . TD_DB_PORT . " ". "dbname=" . TD_DB_DATABASE_GENERAL_CROSSES ." " . "user=".TD_DB_USERNAME." "."password=" .TD_DB_PASSWORD);

$cross=json_decode($_GET['cross'],true);
$brand_name=$cross['b_name'];
$akey=$cross['akey'];

$ch = curl_init();
$a = "http://139.168.33.11/brand/GetBrandIdbyName.php?b_name='$brand_name'";
curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1);
curl_setopt($ch, CURLOPT_URL, $a);
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS,"param=param1");
$brand_id=curl_exec($ch);
curl_close($ch);


$sql="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$brand_id AND akey1='$akey' or brand_id2=$brand_id AND akey1='$akey'";

$result=pg_query($dbGeneral,$sql);
$crosses=array();
while ($row = pg_fetch_assoc($result)) {
    $crosses[]=['brand_id1'=>$row['brand_id1'],'akey1'=> $row['akey1'],'brand_id2'=>$row['brand_id2'],'akey2'=>$row['akey2'],'side'=>$row['side'],'code'=> $row['code']];

}

$sqlClient="SELECT * FROM" . " ". TD_DB_TABLE_TDM_LINKS . " ". "WHERE brand_id1=$brand_id AND akey1='$akey' or brand_id2=$brand_id AND akey1='$akey'";

$result1=pg_query($dbClient,$sql);

while ($row1 = pg_fetch_assoc($result1)) {
    $crosses[]=['brand_id1'=>$row1[0],'akey1'=> $row1['akey1'],'brand_id2'=>$row1['brand_id2'],'akey2'=>$row1['akey2'],'side'=>$row1['side'],'code'=> $row1['code']];
   // var_dump($crosses);
}

$countRowsGen = pg_num_rows($result);
$countRowsClient = pg_num_rows($result1);
if (($countRowsGen == 0) && ($countRowsClient == 0)) {
   echo "Аналогов не найдено";
}
else {
    foreach ($crosses as $cross) {

        $brand_id1 = $cross['brand_id1'];
        $brand_id2 = $cross['brand_id2'];

        $ch = curl_init();
        $a = "http://139.168.33.11/brand/GetBrandNamebyId.php?b_id='$brand_id1'";
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_URL, $a);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
        $brand_name = curl_exec($ch);
        $cross['brand_id1'] =$brand_name;
        curl_close($ch);

        $ch = curl_init();
        $a = "http://139.168.33.11/brand/GetBrandNamebyId.php?b_id='$brand_id2'";
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_URL, $a);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "param=param1");
        $brand_name = curl_exec($ch);

        $cross['brand_id2'] =$brand_name;
        //var_dump($cross);
        curl_close($ch);
        printf(json_encode($cross));
    }
pg_close($dbClient);
pg_close($dbGeneral);
}