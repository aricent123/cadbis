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
  0=>"����� ������ �� ��� ������, � ������� ������",
  1=>"����� ������ �� ��� ������, � ������� ������",
  2=>"����� ������ ������� ������",  
  3=>"����� ������ ������� ������",
  4=>"����� ������",
  5=>"����� ������",
  6=>"����� ������",
  7=>"����� ������",
  8=>"����� ������",    
  9=>"�����, �� ������ ���������", 
  10=>"�����, �� ������ ���������",
  11=>"�����, �� ������ ����� ���������",
  12=>"������ ���� �������� �� ������", 
  13=>"������ ����� ���������", 
  14=>"�����, ������ �� �������������� � ��������", 
  15=>"�����, ������ �� �������������� � ��������",
  16=>"�����, ������ �������", 
  17=>"�����, ������ �������",
  18=>"�����, ������ �������",  
  19=>"�����!!!");

 $imgnum+=1;
 if($imgnum>20)$imgnum=20;
?>
<div align=center>
<img width=35 src="<? OUT(SK_DIR."/billing/state_img.php") ?>"><br>
<? OUT($decrs[$imgnum-1]."<br>(".bytes2gb($cur["traffic"])." �� ".bytes2gb($mon["traffic"])." ��)") ?>
</div>     