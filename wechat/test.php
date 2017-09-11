<?php
$keyword='淘宝';

$dsn="mysql:host=bdm242626193.my3w.com;dbname=bdm242626193_db";
$pdo=new PDO($dsn,'bdm242626193','rootroot');
$pdo->query("set names utf8");

if($keyword=='first'){
    $sql = "select * from wx_auto_replay where re_id=1 or keyword='first'";
}else{
    $sql = "select * from wx_auto_replay where keyword like '%".$keyword."%'";
}
$rs=$pdo->query($sql);
$row=$rs->fetchALl();

print_r($row);die;



?>