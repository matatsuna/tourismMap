<?php
header("Content-Type: application/json; charset=utf-8");
mb_internal_encoding("UTF-8");

if($_REQUEST["category"]){
    $category = htmlspecialchars($_REQUEST["category"]);
}else{
    $category = "イベント";
}

$mysqli=new mysqli ("mysql","matatsuna","matatsuna");
$mysqli -> select_db("tourism_db");
$mysqli -> set_charset("utf8");
if(!$_REQUEST["prefecture"]){

$results=$mysqli->query("select prefecture,name,count(name) from tourism_db where category ='".$category."' group by prefecture");
$json = array();
$jsonchild = array();
array_push($jsonchild,"都道府県");
array_push($jsonchild,"カテゴリー数");
array_push($json,$jsonchild);
while ($line = $results-> fetch_array(MYSQLI_BOTH)) {
    $jsonchild = array();
    $prefecture = str_replace("県","",$line["prefecture"]);
    $prefecture = str_replace("府","",$prefecture);
    $prefecture = str_replace("都","",$prefecture);
    array_push($jsonchild, $prefecture );
    array_push($jsonchild,intval($line["count(name)"]));
    array_push($json,$jsonchild);
}
echo json_encode($json);

}else{
$prefecture =htmlspecialchars($_REQUEST["prefecture"]);
$results=$mysqli->query("select name,city,address from tourism_db where category ='".$category."' and prefecture LIKE '".$prefecture."%'");
$json = array();

while ($line = $results-> fetch_array(MYSQLI_BOTH)) {
    $jsonchild = array();
    $jsonchild["name"] = $line["name"];
    $jsonchild["address"] = $line["city"].$line["address"];
    array_push($json,$jsonchild);
}
echo json_encode($json);
    
}

$mysqli->close();

?>