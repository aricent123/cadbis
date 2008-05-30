<?php
require_once(dirname(__FILE__).'/cadbisnew/graph/charts.php');
if($BILLEVEL<=2)
 {
 ?>
 Эта страница запрещена для вас!
 <?
 return;
 }



if(!isset($action))$action="";
if(!isset($mod))$mod="";
if(!isset($sort))$sort=">traffic";

  

if($action)
 {     
 if(!isset($gid))$gid="all";         
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 switch($action)
   {            
   case "today":
   $accts=$BILL->GetTodayUsersAccts($sort,0,$gid);
   $wday=date("w");
   if($wday==0)$wday=6;   
   $head="Статистика по пользователям за ".$wdaysto[$wday-1].", ".date("d")." ".$monthsof[date("n")-1]."".date(" Y года");
   break;
   case "month":
   $accts=$BILL->GetMonthUsersAccts($sort,0,$gid);
   $head="Статистика по пользователям за ".$months[date("n")-1]." ".date("Y")." года:<br>
   <small>(по состоянию на ".date("d")." ".$monthsof[date("n")-1].")</small>";
   break;
   case "week":
   $accts=$BILL->GetWeekUsersAccts($sort,0,$gid);
   get_current_week(&$b,&$a,0);
   $head="Статистика по пользователям за текущую неделю:<br><small>(".date_dmy(strtotime($b))." - ".date_dmy(strtotime($a)).")</small>";
   break;
   case "sessions":
   if($mod=="show")
    {

      if(!isset($groupby))$groupby="";
      if($daysel[0]=="null")$daysel1=1;else $daysel1=$daysel[0];     
      if($monsel[0]=="null")$monsel1=1;else $monsel1=$monsel[0];       
      if($yearsel[0]=="null")$yearsel1=$GV["start_year"];else $yearsel1=$yearsel[0];     
      if($daysel[1]=="null")$daysel2=31;else $daysel2=$daysel[1];         
      if($monsel[1]=="null")$monsel2=12;else $monsel2=$monsel[1];       
      if($yearsel[1]=="null")$yearsel2=date("Y")+1;else $yearsel2=$yearsel[1];     

    
    $fdate= $yearsel1."-".$monsel1."-".$daysel1." 00:00:00";
    $tdate= $yearsel2."-".$monsel2."-".$daysel2." 23:59:59";

    $head="Статистика по сессиям за период ".$daysel1."/".$monsel1."/".$yearsel1." - ".$daysel2."/".$monsel2."/".$yearsel2;   

    if(isset($user) && $user!="")
      $accts=$BILL->GetUserSessions($user,$fdate,$tdate);
    elseif($groupby=="users")                                        
      $accts=$BILL->GetUsersSessions($fdate,$tdate);
    else
      $accts=$BILL->GetSessions($fdate,$tdate);    
     }
    else
    {
    $head="Статистика по сессиям пользователей";
    }
   break;  
   case "tarifs":
  if($mod=="show")
    {
    
      if(!isset($tarif))$tarif="";
      if($daysel[0]=="null")$daysel1=1;else $daysel1=$daysel[0];     
      if($monsel[0]=="null")$monsel1=1;else $monsel1=$monsel[0];       
      if($yearsel[0]=="null")$yearsel1=$GV["start_year"];else $yearsel1=$yearsel[0];     
      if($daysel[1]=="null")$daysel2=31;else $daysel2=$daysel[1];         
      if($monsel[1]=="null")$monsel2=12;else $monsel2=$monsel[1];       
      if($yearsel[1]=="null")$yearsel2=date("Y")+1;else $yearsel2=$yearsel[1];     

    
    $fdate= $yearsel1."-".$monsel1."-".$daysel1." 00:00:00";;
    $tdate= $yearsel2."-".$monsel2."-".$daysel2." 23:59:59";;
    $head="Статистика по тарифам за период ".$daysel1."/".$monsel1."/".$yearsel1." - ".$daysel2."/".$monsel2."/".$yearsel2;    
    if($tarif=="!all!")
      $accts=$BILL->GetTarifsAccts($fdate,$tdate);
     else
       {
       $data=$BILL->GetTarifAccts($tarif,$fdate,$tdate);
       $tdata=$BILL->GetTarifData($tarif);
       $accts=NULL;
       $accts[0]["traffic"]=$data["traffic"];
       $accts[0]["time"]=$data["time"];       
       $accts[0]["packet"]=$tdata["packet"];
       }         
     }
    else
    {
    $head="Статистика по тарифам";
    }
   break;    
   };  
   ?>
    <br><br><div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div> 
   <? 
if($action=="today" || $action=="week" || $action=="month")
{

  $tarlist=$BILL->GetTarifs();
  $tarsel="<select name=gid style=\"width:70%\"  class=inputbox><option value=\"all\"></option>";
  for($i=0;$i<count($tarlist);++$i)
   {
   if($gid==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
   $tarsel.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
   }
  $tarsel.="</select>";    
   ?>
   <form action="<? OUT("?p=$p&act=$act&action=$action&draw=$draw&sort=$sort") ?>" method=post>
   <Table width=100%><td class=tbl1>Выбрать только тариф:<br>    
   <? OUT($tarsel) ?><input style="width:30%" class=button value="Просмотр" type=submit> 
    </td></table>
    </form>
   <?  
   if(count($accts)){ 
   ?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div><br>   
   <div align=center><b><? OUT($head) ?></b></div>
  
   
   <table width=100% align=center class=tbl1>
   <tr>
    <td class=tbl1>№</td>
    <td class=tbl1><a href="<? ($sort==">user")?$ssort="<user":$ssort=">user"; OUT("?p=$p&act=$act&action=$action&sort=$ssort&draw=$draw&gid=$gid") ?>">Ник</a><? if($sort=="<user")OUT("<img src=\"".SK_DIR."/img/asc.gif\">");elseif($sort==">user")OUT("<img src=\"".SK_DIR."/img/desc.gif\">"); ?></td>
    <td class=tbl1><a href="<? ($sort==">fio")?$ssort="<fio":$ssort=">fio"; OUT("?p=$p&act=$act&action=$action&sort=$ssort&draw=$draw&gid=$gid") ?>">ФИО</a>     <? if($sort=="<fio")OUT("<img src=\"".SK_DIR."/img/asc.gif\">");elseif($sort==">fio")OUT("<img src=\"".SK_DIR."/img/desc.gif\">"); ?></td>
    <td class=tbl1><a href="<? ($sort==">traffic")?$ssort="<traffic":$ssort=">traffic"; OUT("?p=$p&act=$act&action=$action&sort=$ssort&draw=$draw&gid=$gid") ?>">Траффик (Кб)</a> <? if($sort=="<traffic")OUT("<img src=\"".SK_DIR."/img/asc.gif\">");elseif($sort==">traffic")OUT("<img src=\"".SK_DIR."/img/desc.gif\">"); ?></td>
    <td class=tbl1><a href="<? ($sort==">time")?$ssort="<time":$ssort=">time"; OUT("?p=$p&act=$act&action=$action&sort=$ssort&draw=$draw&gid=$gid") ?>">Время (чч:мм:сс)</a> <? if($sort=="<time")OUT("<img src=\"".SK_DIR."/img/asc.gif\">");elseif($sort==">time")OUT("<img src=\"".SK_DIR."/img/desc.gif\">"); ?></td>        
   </tr>
   <?
   
   
   $cnt=count($accts);
   $sumtra=0;
   $sumtim=0;
   for($i=0;$i<$cnt;++$i)
     {
     $sumtra+=$accts[$i]["traffic"];
     $sumtim+=$accts[$i]["time"];
      ?>
      <tr>
       <td class=tbl1><? OUT($i+1) ?></td>
       <td class=tbl1><a href="?p=users&act=userinfo&id=<? OUT($accts[$i]["uid"]) ?>"><? OUT($accts[$i]["nick"]) ?></a></td>    
       <td class=tbl1><a href="?p=users&act=userinfo&id=<? OUT($accts[$i]["uid"]) ?>"><? OUT($accts[$i]["fio"]) ?></a></td>
       <td class=tbl1><? OUT(bytes2kb($accts[$i]["traffic"])." (".bytes2mb($accts[$i]["traffic"],3)." Mb)") ?></td>
       <td class=tbl1><? OUT(gethours($accts[$i]["time"]).":".getmins($accts[$i]["time"]).":".getsecs($accts[$i]["time"])) ?></td>        
      </tr>      
      <?
     }   
     
   ?>
      <tr>
       <td class=tbl1><b>Итого</b></td>
       <td class=tbl1></td>    
       <td class=tbl1></td>
       <td class=tbl1><b><? OUT(bytes2kb($sumtra)." (".bytes2mb($sumtra,3)." Mb)") ?></b></td>
       <td class=tbl1><b><? OUT(gethours($sumtim).":".getmins($sumtim).":".getsecs($sumtim)) ?></b></td>        
      </tr>      
   </table>
   <? } else {OUT("<div align=center>Нет результатов</div>");} ?>
   <br>
   <? 
   if($cnt)
   { 
   ?>

   <table width=100%><td width=50% align=center>
   <?
   if(isset($draw) && $draw=="graph")
    { ?><div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&gid=$gid&sort=$sort") ?>">Без графика</a></div><? } 
    else
    { ?><div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&draw=graph&gid=$gid&sort=$sort") ?>">Показать график</a></div><? } ?>
   </td><td width=50% align=center>
    <div align=center><a target=_blank href="<? OUT("?act=noskin&page=smadbis&noskinact=smadbisrept&action=$action&sort=$sort&gid=$gid") ?>">Версия для печати</a></div>
   </td></table>
   <br>
   <?
    }
   if(isset($draw) && $draw=="graph" && count($accts)){
  ?>   
   <div align=center>
   <? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=$action&gid=$gid",600, 500 );?>
   </div>
   <? } 
}
elseif($action=="sessions")
{
   $year=date("Y");
   $month=date("m"); 
   $day=date("d");   

   if(!isset($user))$user="";
   
   if(isset($mod) && $mod=="show")
    {
    $daysel1=getdaysel($daysel[0]);
    $monsel1=getmonthsel($monsel[0],$monthsof);
    $yearsel1=getyearsel($GV["start_year"],$year,$yearsel[0]);                                     
    $daysel2=getdaysel($daysel[1]);
    $monsel2=getmonthsel($monsel[1],$monthsof);
    $yearsel2=getyearsel($GV["start_year"],$year,$yearsel[1]);
    }
    else
    {
    $daysel1=getdaysel(1);
    $monsel1=getmonthsel($month,$monthsof);
    $yearsel1=getyearsel($GV["start_year"],$year,$year);                                     
    $daysel2=getdaysel($day);
    $monsel2=getmonthsel($month,$monthsof);
    $yearsel2=getyearsel($GV["start_year"],$year,$year);   
    }
   
   if(isset($groupby) && $groupby!="null")
     if($groupby=="users"){$selu="selected";$seld="";}
      else {$seld="selected";$selu="";}    
   $groupbysel="<select style=\"width:100%\" name=groupby>
   <option value=\"null\">не группировать</option>
   <option value=\"users\" $selu>пользователь</option>  
   <option value=\"date\" $seld>дата</option></select>";
   ?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div><br><br>   
   <div align=center><b><? OUT($head) ?></b></div>
   <form action="?p=smadbis&act=stats&action=sessions&mod=show" method=post>    
   <div align=center>Параметры выборки:</div> 
   <table width=100% align=center class=tbl1>
   <td width=14% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center>Начиная с:</td></tr>   
    <tr><td>   <? OUT($daysel1) ?><? OUT($monsel1) ?><? OUT($yearsel1) ?></td></tr>
    </table>
   </td>
   <td width=14% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center height=100%>И до:</td></tr>   
    <tr><td>   <? OUT($daysel2) ?><? OUT($monsel2) ?><? OUT($yearsel2) ?></td></tr>
    </table>
   </td>   
   <td width=22% height=100%  valign=top>
    <table width=100% align=center class=tbl1  valign=top>
    <tr><td align=center height=100% valign=top>Группировать по:</td></tr>   
    <tr><td>   <? OUT($groupbysel) ?></td></tr>
    </table>
   </td>     
   <td width=18% height=100%  valign=top>
    <table width=100% align=center class=tbl1  valign=top>
    <tr><td align=center height=100% valign=top>Пользователь:</td></tr>   
    <tr><td><input type=text class=inputbox style="width:100%" name=user value="<? OUT($user) ?>"></td></tr>
    </table>
   </td>  
   </table>
   <div align=center><input type=submit class=button value="Показать"></div>     
   </form>
   <?
   if(isset($mod)&& $mod=="show")
   {
   if(!isset($groupby))$groupby="";
   
   if(count($accts))
   {
   ?>
     <div align=center><a target=_blank href="<? OUT("?act=noskin&page=smadbis&noskinact=smadbisrept&action=$action&groupby=$groupby&fdate=$fdate&tdate=$tdate&user=$user") ?>">Версия для печати</a></div>
   <?   
     switch($groupby)
      {
      case "users":

      if($user)
       {
        ?>
        <div align=center><? OUT($accts[0]["user"]) ?>(<? OUT($accts[0]["fio"]) ?>)</div>        
       <table width=100% align=center class=tbl1>
       <tr>
        <td class=tbl1>№</td>
        <td class=tbl1>IP адрес</td>       
        <td class=tbl1>Начало сеанса</td>
        <td class=tbl1>Конец сеанса</td>        
        <td class=tbl1>Траффик</td>
        <td class=tbl1>Причина завершения</td>
        <td class=tbl1>Протокол сессии</td>
       </tr>
       <?
       $sumtra=0;
       for($i=0;$i<count($accts);++$i)
        {
        $sumtra+=$accts[$i]["out_bytes"];
        ?>
         <tr>
         <td class=tbl1><? OUT($i+1) ?></td>
         <td class=tbl1><? OUT($accts[$i]["call_from"]) ?></td>       
         <td class=tbl1><? OUT($accts[$i]["start_time"]) ?></td>
         <td class=tbl1><? OUT($accts[$i]["stop_time"]) ?></td>        
         <td class=tbl1><? OUT(bytes2mb($accts[$i]["out_bytes"],3)) ?> Мб</td>
         <td class=tbl1><? OUT(get_terminate_cause_str($accts[$i]["terminate_cause"])) ?></td>
         <td class=tbl1>
         <? if($BILL->IsProtocolExists($accts[$i]["unique_id"]))
        	OUT("<a href=\"?p=$p&act=log_url&action=session_protocol&unique_id=".$accts[$i]["unique_id"]."\">просмотр</a>");
			else
         	OUT("N/A");
         ?></td>
         </tr>        
         <?
         }  
         ?>
         <tr>
          <td class=tbl1><b>Итог</b></td>
          <td class=tbl1></td>       
          <td class=tbl1></td>
          <td class=tbl1></td>        
          <td class=tbl1><b><? OUT(bytes2mb($sumtra,3)) ?> Мб</b></td>
          <td class=tbl1></td>
          <td class=tbl1></td>
         </tr>         
         </table>
         <?    
       break;
       }
     
      for($i=0;$i<count($accts);++$i)
        {
        ?>
        <div align=center><? OUT($accts[$i][0]["user"]) ?>(<? OUT($accts[$i][0]["fio"]) ?>)</div>
      <table width=100% align=center class=tbl1>
      <tr>
       <td class=tbl1>№</td>
       <td class=tbl1>IP адрес</td>       
       <td class=tbl1>Начало сеанса</td>
       <td class=tbl1>Конец сеанса</td>        
       <td class=tbl1>Траффик</td>
       <td class=tbl1>Причина завершения</td>
       <td class=tbl1>Протокол сессии</td>
      </tr>
        <?
       $sumtra=0;          
        for($k=0;$k<count($accts[$i]);++$k)
        {
        $sumtra+=$accts[$i][$k]["out_bytes"];        
        ?>
         <tr>
         <td class=tbl1><? OUT($k+1) ?></td>
         <td class=tbl1><? OUT($accts[$i][$k]["call_from"]) ?></td>       
         <td class=tbl1><? OUT($accts[$i][$k]["start_time"]) ?></td>
         <td class=tbl1><? OUT($accts[$i][$k]["stop_time"]) ?></td>        
         <td class=tbl1><? OUT(bytes2mb($accts[$i][$k]["out_bytes"],3)) ?> Мб</td>
         <td class=tbl1><? OUT(get_terminate_cause_str($accts[$i][$k]["terminate_cause"])) ?></td>
         <td class=tbl1>
		<? if($BILL->IsProtocolExists($accts[$i][$k]["unique_id"]))
        	OUT("<a href=\"?p=$p&act=log_url&action=session_protocol&unique_id=".$accts[$i][$k]["unique_id"]."\">просмотр</a>");
			else
         	OUT("N/A");
         ?></td>
         </tr>
         <?
         }
        ?>
         <tr>
          <td class=tbl1><b>Итог</b></td>
          <td class=tbl1></td>       
          <td class=tbl1></td>
          <td class=tbl1></td>        
          <td class=tbl1><b><? OUT(bytes2mb($sumtra,3)) ?> Мб</b></td>
          <td class=tbl1></td>
          <td class=tbl1></td>
         </tr>            
        </table><br>
        <?    
        }  
      break;
      default:
      ?>
      <table width=100% align=center class=tbl1>
      <tr>
       <td class=tbl1>№</td>
       <td class=tbl1>Ник</td>    
       <td class=tbl1>ФИО</td>
       <td class=tbl1>IP адрес</td>       
       <td class=tbl1>Начало сеанса</td>
       <td class=tbl1>Конец сеанса</td>        
       <td class=tbl1>Траффик</td>
       <td class=tbl1>Причина завершения</td>
       <td class=tbl1>Протокол сессии</td>
      </tr>
      <?
       $sumtra=0;      
      for($i=0;$i<count($accts);++$i)
        {
        $sumtra+=$accts[$i]["out_bytes"];        
        ?>
         <tr>
         <td class=tbl1><? OUT($i+1) ?></td>
         <td class=tbl1><? OUT($accts[$i]["user"]) ?></td>    
         <td class=tbl1><? OUT($accts[$i]["fio"]) ?></td>
         <td class=tbl1><? OUT($accts[$i]["call_from"]) ?></td>       
         <td class=tbl1><? OUT($accts[$i]["start_time"]) ?></td>
         <td class=tbl1><? OUT($accts[$i]["stop_time"]) ?></td>        
         <td class=tbl1><? OUT(bytes2mb($accts[$i]["out_bytes"],3)) ?> Мб</td>
         <td class=tbl1><? OUT(get_terminate_cause_str($accts[$i]["terminate_cause"])) ?></td>
         <td class=tbl1>
         	<? if($BILL->IsProtocolExists($accts[$i]["unique_id"]))
         			OUT("<a href=\"?p=$p&act=log_url&action=session_protocol&user=$user&unique_id=".$accts[$i]["unique_id"]."\">просмотр</a>");
         		else
         			OUT("N/A");
         	?>
         </tr>        
        <?
        }  
        ?>
         <tr>
          <td class=tbl1><b>Итог</b></td>
          <td class=tbl1></td>
          <td class=tbl1></td>
          <td class=tbl1></td>                      
          <td class=tbl1></td>
          <td class=tbl1></td>        
          <td class=tbl1><b><? OUT(bytes2mb($sumtra,3)) ?> Мб</b></td>
          <td class=tbl1></td>
          <td class=tbl1></td>
         </tr>            
        </table>
        <?    
      break;
      };
    }
    if(!count($accts))OUT("<div align=center>Ничего не найдено</div>");
    else{
    ?>
     <div align=center><a target=_blank href="<? OUT("?act=noskin&page=smadbis&noskinact=smadbisrept&action=$action&groupby=$groupby&fdate=$fdate&tdate=$tdate&user=$user") ?>">Версия для печати</a></div>
     <?  
     } 
    } //end of mod==""    
   } //end of action=="sessions" 
   elseif($action=="tarifs")
   {
   $year=date("Y");
   $month=date("m"); 
   $day=date("d");   

   if(!isset($user))$user="";
   
   if(isset($mod) && $mod=="show")
    {
    $daysel1=getdaysel($daysel[0]);
    $monsel1=getmonthsel($monsel[0],$monthsof);
    $yearsel1=getyearsel($GV["start_year"],$year,$yearsel[0]);                                     
    $daysel2=getdaysel($daysel[1]);
    $monsel2=getmonthsel($monsel[1],$monthsof);
    $yearsel2=getyearsel($GV["start_year"],$year,$yearsel[1]);
    }
    else
    {
    $daysel1=getdaysel(1);
    $monsel1=getmonthsel($month,$monthsof);
    $yearsel1=getyearsel($GV["start_year"],$year,$year);                                     
    $daysel2=getdaysel($day);
    $monsel2=getmonthsel($month,$monthsof);
    $yearsel2=getyearsel($GV["start_year"],$year,$year);   
    }
    
  $tarlist=$BILL->GetTarifs();
  $tarsel="<select name=tarif style=\"width:100%;\" class=inputbox><option value=\"!all!\">Сравнительная</option>";
  for($i=0;$i<count($tarlist);++$i)
   {
   if($tarif==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
   $tarsel.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
   }
  $tarsel.="</select>"; 
  ?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div><br><br>
   <div align=center><b><? OUT($head) ?></b></div>
   <form action="?p=smadbis&act=stats&action=tarifs&mod=show" method=post>    
   <div align=center>Параметры выборки:</div> 
   <table width=100% align=center class=tbl1>
   <td width=14% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center>Начиная с:</td></tr>   
    <tr><td>   <? OUT($daysel1) ?><? OUT($monsel1) ?><? OUT($yearsel1) ?></td></tr>
    </table>
   </td>
   <td width=14% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center height=100%>И до:</td></tr>   
    <tr><td>   <? OUT($daysel2) ?><? OUT($monsel2) ?><? OUT($yearsel2) ?></td></tr>
    </table>
   </td>   
   <td width=18% height=100%  valign=top>
    <table width=100% align=center class=tbl1  valign=top>
    <tr><td align=center height=100% valign=top>Тариф:</td></tr>   
    <tr><td><? OUT($tarsel) ?></td></tr>
    <tr><td><input type=checkbox name=draw value="graph" checked>С графиками</td></tr>
    </table>
   </td>  
   </table>
   <div align=center><input type=submit class=button value="Показать"></div>     
   </form>
   <?
   if(isset($mod)&& $mod=="show")
     {
      $cnt=count($accts);    
       $sumtra=0;  
       $sumtim=0;                         
       ?>      
      <table width=100% align=center class=tbl1>
      <tr>
       <td class=tbl1>№</td>
       <td class=tbl1>Тариф</td>    
       <td class=tbl1>Траффик</td>
       <td class=tbl1>Время</td>
      </tr>
      <?       
      for($k=0;$k<$cnt;++$k)
        {
        $sumtra+=$accts[$k]["traffic"];        
        $sumtim+=$accts[$k]["time"];  
        ?>
         <tr>
         <td class=tbl1><? OUT($k+1) ?></td>
         <td class=tbl1><? OUT($accts[$k]["packet"]) ?></td>    
         <td class=tbl1><? OUT(bytes2mb($accts[$k]["traffic"],3)) ?> Мб</td>
         <td class=tbl1><? OUT(gethours($accts[$k]["time"]).":".getmins($accts[$k]["time"]).":".getsecs($accts[$k]["time"])) ?></td>
         </tr>
        <?
       }
       ?>
         <tr>
         <td class=tbl1><b>Всего</b></td>
         <td class=tbl1></td>    
         <td class=tbl1><b><? OUT(bytes2mb($sumtra,3)." Mb") ?></b></td>
         <td class=tbl1><b><? OUT(gethours($sumtim).":".getmins($sumtim).":".getsecs($sumtim)) ?></b></td>    
         </tr>
        </table>       
       <?
   if($cnt)
   { 
   ?>    
   <table width=100%><td width=50% align=center>
   <td width=50% align=center>
    <div align=center><a target=_blank href="<? OUT("?act=noskin&page=smadbis&noskinact=smadbisrept&action=$action&tarif=$tarif&fdate=$fdate&tdate=$tdate") ?>">Версия для печати</a></div>
   </td></table>
   <br>
   <? 
   if(isset($draw) && $draw=="graph" && $tarif=="!all!"){
        ?>      
       <div align=center>По траффику:<br>
       <? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=$action&fdate=$fdate&tdate=$tdate&tarif=$tarif&param=traffic",600, 500 );?>
       </div>
       <div align=center>По времени:<br>
       <? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=$action&fdate=$fdate&tdate=$tdate&tarif=$tarif&param=time",600, 500 );?>
       </div>
       <? }    
   
    }

      
    
    
    if(!count($accts))echo("<br><br><div align=center>ничего не найдено</div>");
    
     }//eof $mod=="show"     
  
   }//end of $action=="tarifs"
   elseif($action=="events")
   {
   include SK_DIR."/billing/admin_events.php";
   }//end of $action=="events"
   elseif($action=="urls")
   {
   include SK_DIR."/billing/admin_urls.php";
   }   
   elseif($action=="ctry")
   {
   include SK_DIR."/billing/admin_ctry.php";
   }      
   ?>
    <br><br><div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div> 
   <?    
 } //end of isset(action)                                                                      
 else
 {
 ?>
   <table width=100%>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=today';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats&action=today">Общая сегодня</a></b></div><br>
               Просмотр подробной статистики за сегодня по времени и трафику.
      </td> 
      </table>
     </td></tr>     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=week';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=stats&action=week">Общая за неделю</a></b></div><br>
               Просмотр подробной статистики за неделю по времени и трафику.
      </td> 
      </table>
     </td></tr>     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=month';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats&action=month">Общая за месяц</a></b></div><br>
               Просмотр подробной статистики за месяц по времени и трафику.
      </td> 
      </table>
     </td></tr>     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=sessions';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=stats&action=sessions">По сессиям</a></b></div><br>
               Просмотр подробной статистики по сессиям за определённую дату.
      </td> 
      </table>
     </td></tr>     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=tarifs';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats&action=tarifs">По тарифам</a></b></div><br>
               Просмотр подробной статистики по тарифам за определённую дату.
      </td> 
      </table>
     </td></tr>


     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=events';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=stats&action=events">Просмотр событий</a></b></div><br>
               Просмотр административных событий web-интерфейса биллинг системы.
      </td> 
      </table>
     </td></tr>
     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=urls';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats&action=urls">Популярность сайтов</a></b></div><br>
               Просмотр посещаемых сайтов и их популярность.
      </td> 
      </table>
     </td></tr>     
     
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats&action=ctry';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=stats&action=urls">Популярность стран</a></b></div><br>
               Просмотр посещаемых серверов по странам и их популярность.
      </td> 
      </table>
     </td></tr>        
     
    </table>     
    <div align=center><a href="<? OUT("?p=$p") ?>">назад</a></div>
 <?
 }
?>

