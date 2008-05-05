<?php
error_reporting(E_PARSE);
include("restore_confs.php");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

$cur=$BILL->GetMonthTotalAccts();
$mon=$BILL->GetMonthMaxAccts();

$imgnum=20.0;
$prc_tr=((float)$cur["traffic"])/((float)$mon["traffic"])*100.0;
$perimg=100.0/$imgnum;


$year=date("Y");
$month=date("m"); 
$day=date("d");
$daycount=date("t");
$maxtraf=0;
$daynorm=$GV["max_month_traffic"]/($daycount);
$rest=$mon["traffic"]-$cur["traffic"];   
$dnrm=$rest/($daycount-$day+1);
$k=($dnrm<0)?-90000:100;
$prc_tr2=((float)$daynorm)/((float)$dnrm)*100.0-$k;  
if($prc_tr2>100)$prc_tr2=100;
if($prc_tr2<0)$prc_tr2=0;
$imgnum=(int)((float)$prc_tr2/$perimg);

$imgnum+=1;
if($imgnum>20)$imgnum=20;
header("Location: ../img/semafor/".($imgnum).".gif");
?>     