<?
if($BILLEVEL<5)return;

 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 $BILL->KillInactiveUsers();
 $list=$BILL->GetOnlineUsersData();
 
 
 if(!isset($action))$action="";
 if($action=="save")
  {
  $error="";  
  $GV['max_month_traffic']=mb2bytes($max_month_traffic);
  $GV['start_year']=$start_year;
  $res="<?php\r\n";
  $res.="\$GV['start_year']=".$GV['start_year'].";\r\n";
  $res.="\$GV['max_month_traffic']=".$GV['max_month_traffic'].";\r\n";
  $res.="\$GV['max_month_time']=".$GV['max_month_time'].";\r\n";
  $res.="?>";
  $fp=fopen($GV["modules_conf_dir"]."/smadbisext.conf.php","w+");  
  if(!$fp)$error.="<b>Ошибка:</b> Невозможно открыть файл!";
  if(!$error) {
    fwrite($fp,$res);
    fclose($fp);
    }
  if(!$error){OUT("<div align=center>Сохранено!</div><br><div align=center><a href=\"?p=$p&act=$act\">назад</a></div>");}
  else{OUT("<div align=center>$error</div><br><div align=center><a href=\"?p=$p&act=$act\">назад</a></div>");}
  }
  else
  {
 
 include SK_DIR."/billing/month_stats.php"; 
?>

<form action="<? OUT("?p=$p&act=$act&action=save") ?>" method=post>
<div align=center style="font-size:11px"><b>Настройки системы:</b></div>
<table width=80% class=tbl1 align=center>
<tr><td width=70%>Предельное месячное количество траффика (Мб):</td><td><input value="<? OUT(bytes2mb($GV["max_month_traffic"])) ?>" type=text name=max_month_traffic class=inputbox style="width:100%"></td></tr>
<tr><td width=70%>Начальный год отсчёта:</td><td><input value="<? OUT($GV["start_year"]) ?>" type=text name=start_year class=inputbox style="width:100%"></td></tr>
</table>
<div align=center><input class=button type=submit value="Сохранить"></div>
</form>
<div align=center><a href="<? OUT("?p=$p") ?>">назад</a></div>
<? 
  }
?>