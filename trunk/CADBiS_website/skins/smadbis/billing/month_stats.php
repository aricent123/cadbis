<?php
 if($BILLEVEL<2)return;

$cur=$BILL->GetMonthTotalAccts();
$mon=$BILL->GetMonthMaxAccts();
$prc_tr=((float)$cur["traffic"])/((float)$mon["traffic"])*100.0;  

$year=date("Y");
$month=date("m"); 
$day=date("d");
$daycount=date("t");
$maxtraf=1;
$daynorm=NULL;
$daynorm[0]=$GV["max_month_traffic"]/($daycount);

$rest=$mon["traffic"]-$cur["traffic"];   
$dnrm=$rest/($daycount-$day+1);
$k=($dnrm<0)?-90000:100;
$prc_tr2=((float)$daynorm[0])/((float)$dnrm)*100.0-$k;  

 $resttext="Остаток";
if($prc_tr2<0)$prc_tr2=0;
if($prc_tr2<100)
 {
 $colorr="00";
 $colorg="FF";
 $restclr="FFFFFF"; 
 }
 elseif($prc_tr2>0 && $dnrm>0)
 {
 if($prc_tr2>150)$prc_tr2=150;
 $color=(int)((16.0/150.0)*$prc_tr2);
 $colorr=dechex($color).dechex($color);
 
 $color=(int)((16.0/150.0)*(150-$prc_tr2));
 $colorg=dechex($color).dechex($color);
 $restclr="FFFF00"; 
 }
 else
 {
 $resttext="Превышение";
  $colorr="00";
 $colorg="00";
 $restclr="FF0000";
 }

for($i=1;$i<=$daycount;++$i)          
  {
   $d=($i<10)?("0".($i)):$i;
  $day=date("D",makeunixtime($year,$month,$i+1,0,0,0));
    $fdate=$year."-".$month."-1 00:00:00";
    $tdate=$year."-".$month."-".$d." 23:59:59";
    $accts=$BILL->GetPeriodTotalAccts($fdate,$tdate);
    //$fdate=$year."-".$month."-".($d)." 00:00:00";
    //$tdate=$year."-".$month."-".($d)." 23:59:59";    
    //$test=$BILL->GetPeriodTotalAccts($fdate,$tdate);
    $rest=$mon["traffic"]-$accts["traffic"];
    $m=$daycount-$i+1;   
    $daynorm[$i-1]=$rest/$m;
  //echo(($d).")($day) остаток: ".bytes2mb($rest)."мб норма: ".bytes2mb($daynorm[$i-1])."мб потреблено всего: ".bytes2mb($accts["traffic"])."мб за этот день:".bytes2mb($test["traffic"])."<br>");
  }

for($i=0;$i<$daycount;++$i)
 {
  $d=($i+1<10)?("0".($i+1)):$i+1;
 $fdate=$year."-".$month."-".($d)." 00:00:00";
 $tdate=$year."-".$month."-".($d)." 23:59:59";
 $accts=$BILL->GetPeriodTotalAccts($fdate,$tdate);
 if($accts["traffic"]>$maxtraf)$maxtraf=$accts["traffic"];
 $history[$i]["traffic"]=$accts["traffic"]; 
 $history[$i]["time"]=$accts["time"];
 }

?>
<div align=center style="font-size:11px"><b>Месячное потребление трафика:</b></div>
<table width=80% class=tbl1 align=center>
<td width=50%>
 <table align=center height=180px width=200px >
  <tr><td width=60px bgcolor="#<? OUT($restclr) ?>" height="<? OUT("".(100-$prc_tr)) ?>%"></td>
  <td  class=tbl1><? OUT($resttext) ?>:<? OUT(bytes2mb(abs($mon["traffic"]-$cur["traffic"]))) ?> Мб<br> (<? OUT(round(abs(100-$prc_tr),3)) ?>%)</td>
  </tr>
  <tr><td width=60px bgcolor="#<? OUT($colorr.$colorg) ?>00" height="<? OUT($prc_tr) ?>%"></td>
  <td class=tbl1>Скачано:<? OUT(bytes2mb($cur["traffic"])) ?> Мб<br> (<? OUT(round($prc_tr,3)) ?>%)</td>
  </tr>
 </table>
</td>
<td width=50% height=100% valign=bottom class=tbl1><? OUT($months[date("n")-1]) ?> по дням (в Мб) (норма <? OUT(bytes2mb($daynorm[0])) ?> Мб):
<table width=100% height=150px cellspacing=0 cellpadding=0 valign=bottom border=0>
<? 
for($i=0;$i<$daycount;++$i)
 {
 $prc=((float)$history[$i]["traffic"]/(float)$maxtraf)*100.0;
 $k=($daynorm[$i]<0)?-90000:0;
 $prci=((float)$history[$i]["traffic"]/(float)$daynorm[$i])*100.0-$k;
  
 if($prci<100)
  {
  $colorr="00";
  $colorg="FF";
  }
  else
  {
  if($prci>200)$prci=200.0;
  $color=(int)((15.0/200.0)*($prci));
  $colorr=dechex($color).dechex($color);
  $color=(int)((15.0/200.0)*(200.0-$prci));
  $colorg=dechex($color).dechex($color);
  }
 ?><td width=10px height=100% valign=bottom>             
 <font style="font-size:7px;font-color:#000000"><? OUT(bytes2mb($history[$i]["traffic"])) ?></font><br>   
 <table align=center bgcolor="#<? OUT($colorr) ?><? OUT($colorg) ?>00" height="<? OUT($prc) ?>px" width=10px valign=bottom border=0>
 <td class=tbl1 style="font-size:7px;font-color:#000000"></td>
 </table><font style="font-size:7px;font-color:#000000"><br>
 <? OUT($i+1) ?></font>
 </td>
 <?
 }
?>
</table>  
</td>
<tr><td class=tbl1 colspan=2 width=100%>
Цветовое обозначение: <br>                                                                                                                                                    
<table style="font-size:9px"><td><table width=10px height=10px bgcolor=#00FF00 align=left><td></td></table></td><td> - Норма (траффик потреблялся в соответствии с нормой дня)</td></table>
<table style="font-size:9px"><td><table width=10px height=10px bgcolor=#778800 align=left><td></td></table></td><td> - Незначительное превышение нормы (траффик незначительно превысил норму)</td></table>
<table style="font-size:9px"><td><table width=10px height=10px bgcolor=#995500 align=left><td></td></table></td><td> -  Превышение нормы (превышение, на которое стоит обратить внимание)</td></table>          
<table style="font-size:9px"><td><table width=10px height=10px bgcolor=#DD2200 align=left><td></td></table></td><td> -  Значительное превышение нормы (сильное превышение)</td></table>
<table style="font-size:9px"><td><table width=10px height=10px bgcolor=#FF0000 align=left><td></td></table></td><td> -  Многократное превышение нормы (критическое превышение нормы)</td></table>

</td></tr>
</table>

<?

?>