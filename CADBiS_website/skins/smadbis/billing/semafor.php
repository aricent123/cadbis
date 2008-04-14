<?php
global $_funcsPHP;
if(!isset($_funcsPHP) || $_funcsPHP!="defined")include("funcs.php");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

$cur=$BILL->GetMonthTotalAccts();
$mon=$BILL->GetMonthMaxAccts();

$imgnum=20.0;
$prc_tr=((float)$cur["traffic"])/((float)$mon["traffic"])*100.0;
$perimg=100.0/$imgnum;
$imgnum=(int)((float)$prc_tr/$perimg);

 $decrs=array(
  0=>"Можно качать всё что угодно, и сколько угодно",
  1=>"Можно качать всё что угодно, и сколько угодно",
  2=>"Можно качать сколько угодно",  
  3=>"Можно качать сколько угодно",
  4=>"Можно качать",
  5=>"Можно качать",
  6=>"Можно качать",
  7=>"Можно качать",
  8=>"Можно качать",    
  9=>"Можно, но только осторожно", 
  10=>"Можно, но только осторожно",
  11=>"Можно, но только очень осторожно",
  12=>"Только чуть полазить по сайтам", 
  13=>"Только почту проверить", 
  14=>"Можно, только по договоренности с админами", 
  15=>"Можно, только по договоренности с админами",
  16=>"Можно, только админам", 
  17=>"Можно, только админам",
  18=>"Можно, только админам",  
  19=>"НИЗЯЯ!!!");

 $imgnum+=1;
 if($imgnum>20)$imgnum=20;
?>
<div align=center>
<img width=35 src="<? OUT(SK_DIR."/billing/state_img.php") ?>"><br>
<? OUT($decrs[$imgnum-1]."<br>(".bytes2gb($cur["traffic"])." из ".bytes2gb($mon["traffic"])." Гб)") ?>
</div>     