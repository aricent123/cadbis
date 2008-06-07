<?php
require_once(dirname(__FILE__).'/cadbisnew/SMPHPToolkit/common.inc.php');
//include("restore_confs.php");
if($BILLEVEL<=1)
 {
 ?>
 Эта страница запрещена для вас!
 <?
 return;
 }
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
 ?>
<html>
<head>
<title><? OUT($GV["site_title"]) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251;">
<meta http-equiv="Keywords" content="<? OUT($GV["site_keywds"]) ?>">
<meta http-equiv="Author" content="<? OUT($GV["site_owner"]) ?>">
<meta http-equiv="Description" content="<? OUT($GV["site_descr"]) ?>">
</head>
 <style>
 .tbl1 {border-style:solid;border-width:1px;border-color:#000000;}
 </style>
<body>
 <?

if(!isset($action))$action="";
if(!isset($act))$act="";
if(!isset($sort))$sort=">traffic";
 if(!isset($gid))$gid="all";

if($action && $act=="smadbisrept")
 {
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
   $head="Статистика по пользователям за текущую неделю: (".date_dmy(strtotime($b))." - ".date_dmy(strtotime($a)).")";
   break;
   case "sessions":
    $head="Статистика по сессиям за период ".$fdate." - ".$tdate;
    if(isset($user) && $user!="")
      $accts=$BILL->GetUserSessions($user,$fdate,$tdate);
    elseif($groupby=="users")
      $accts=$BILL->GetUsersSessions($fdate,$tdate);
    else
      $accts=$BILL->GetSessions($fdate,$tdate);
   break;
   case "tarifs":
    $head="Статистика по тарифам за период ".$fdate." - ".$tdate;
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
     break;
     
     
    case "ctry":     
//------------------------------------------------------------------------------

if(!isset($sort))
 $sort=">count";
if(!isset($limit) || !$limit)
	$limit=25;
if(!strstr($sort,"count") && !strstr($sort,"url")&& !strstr($sort,"length")&& !strstr($sort,"ctry"))
    $sort=">count";
if(!isset($gid) || $gid=='all')
	$gid=null;
if(!isset($groupby))
	$groupby=null;

// FUCKEN SHIT HACK 
// P.S. I HATE ALL OF THIS CODE I'VE WRITTEN EVER !!!!!!!!!! :P
// hideother - this is "year/month" definer
// urls - ctrys
switch($hideother){
 case "true":
   $month = null;
   $year = date("Y");
   $ymtitle = "Статистика за текущий год";
  break;
 case "false":
   $year = date("Y");
   $month = date("n");
   $ymtitle = "Статистика за текущий месяц";   
  break;
 default:
   $year = null;
   $month = null;
   $ymtitle = "Общая статистика";      
  break;
}
$urls = $BILL->GetCtryPopularity($sort,$uid,$limit,$gid,$year,$month);

 ?>
 <div align=center><h3>Популярность стран</h3></div>
 <h4><?=$ymtitle?></h4>
 <?


//line1=RU:1:0x00FF00#EU:1:0x0000FF&line2=RU:1:0x00FF00#DZ:1:0x0000FF&line3=RU:1:0x00FF00#AU:1:0x0000FF&line4=RU:1:0x00FF00#US:1:0x0000FF
$flparams = "";
$i=1;
foreach($urls as $url)
 {
 //$flparams .= (($flparams)?"&":"")."line$i=RU:1:0x00FF00#".$url['ctry'].":".ctry_calc_linesize($url).":".ctry_calc_linecolor($url);
 $flparams .= (($flparams)?"&":"")."line$i=RU:".ctry_calc_linesize($url).":".ctry_calc_linecolor($url)."#".$url['ctry'].":".ctry_calc_linesize($url).":".ctry_calc_linecolor($url);
 $i++;
 }

 if(isset($graf) && $graf=="true"){?>
   <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="200" height="100" id="map4" align="middle">
     <param name="allowScriptAccess" value="sameDomain" />
     <param name="movie" value="map4.swf?<?=$flparams?>" />
     <param name="quality" value="high" />
     <param name="bgcolor" value="#ffffff" />
     <embed src="map4.swf?<?=$flparams?>" quality="high" bgcolor="#ffffff" width="800" height="400" name="map4" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
   </object> <br>
 <div align=right>
 <?}

?></div><?

  $tarlist=$BILL->GetTarifs();
 

   $total_count = 0;
   $total_length =  0;
   $total_ucount=0;
 $cats = $BILL->GetUrlCategories();
  ?>
  <table border=0 width=100% class=tbl1>
   <tr>
   	<td class=tbl1>№</td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"ctry","Страна");?></td>
   	<? if($groupby!="cid" && !$uid){?><td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"ucount","Число пользователей");?></td><?}?>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"count","Число пакетов");?></td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"length","Трафик");?></td>
   </tr>

 <?
  $i=0;
  if(count($urls))
  foreach($urls as $url)
   {
   $total_count +=(int)$url['count'];
   $total_length += (int)$url['length'];
   $total_ucount =($url['ucount']>$total_ucount)?$url['ucount']:$total_ucount;
    ?>
     <tr>
       <td class=tbl1><?=++$i ?></td>
       <td class=tbl1><?=format_ctry($url['ctry'])?></td>
       <? if(!$uid){?><td class=tbl1><?=$url['ucount'] ?></td><?}?>
       <td class=tbl1><?=$url['count'] ?></td>
       <td class=tbl1><?=make_fsize_str($url['length']) ?></td>
     </tr>
    <?

   }
?>
     <tr>
       <td class=tbl1><b>TOTAL:</b></td>
       <td class=tbl1></td>
       <? if(!$uid){?><td class=tbl1>MAX: <?=$total_ucount?></td><?}?>
       <td class=tbl1><?=$total_count?></td>
       <td class=tbl1><?=make_fsize_str($total_length) ?></td>
     </tr>
</table>
<?














// -----------------------------------------------------------------------------
     break;
     
     case "urls":

// -----------------------------------------------------------------------------


if(!isset($uid) || $uid=="null" || !$uid)
	$uid = null;
if($uid)
	$user = $BILL->GetUserData($uid);
else
	$user = null;
if(!isset($modurl))
	$modurl = "";


if($modurl=="stats" && $user){

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

    $head="Посещённые сайты в период  ".$daysel1."/".$monsel1."/".$yearsel1." - ".$daysel2."/".$monsel2."/".$yearsel2;

    if(isset($user) && $user!="")
      $accts=$BILL->GetUserUrlsByPeriod($uid,$fdate,$tdate,$sort);
    }


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


   ?>
<h4>Сайты посещённые пользователем '<? OUT($user['fio']) ?>':</h4>
<br>

   <div align=center><b><? OUT($head) ?></b></div>
   <?
   if(isset($mod)&& $mod=="show")
   {
   if(!isset($groupby))$groupby="";

   if(count($accts))
   {
   $par=	"modurl=stats&uid=$uid&mod=show".
   			"&monsel[]=".$monsel[0]."&monsel[]=".$monsel[1].
   			"&daysel[]=".$daysel[0]."&daysel[]=".$daysel[1].
   			"&yearsel[]=".$yearsel[0]."&yearsel[]=".$yearsel[1];

   ?>
      <table width=100% align=center class=tbl1>
      <tr>
       <td class=tbl1>№</td>
       <td class=tbl1><?=template_header_sort($par,$sort,"url","URL");?></td>
       <td class=tbl1><?=template_header_sort($par,$sort,"count","Число пакетов");?></td>
       <td class=tbl1><?=template_header_sort($par,$sort,"date","Последний визит");?></td>
       <td class=tbl1>Даты визитов</td>
       <td class=tbl1><?=template_header_sort($par,$sort,"length","Трафик");?></td>
      </tr>
      <?
       $sumtra=0;
       $sumnum=0;
      for($i=0;$i<count($accts);++$i)
        {
        $sumtra+=$accts[$i]["length"];
        $sumnum+=$accts[$i]["count"];
        ?>
         <tr>
         <td class=tbl1><? OUT($i+1) ?></td>
       <td class=tbl1><?=make_url_str($accts[$i]['url'])?></td>
       <td class=tbl1><?=$accts[$i]['count']?></td>
       <td class=tbl1><?=norm_date($accts[$i]['date'])?></td>
       <td class=tbl1><? foreach($accts[$i]['dates'] as $date)OUT(norm_date($date).", "); ?></td>
       <td class=tbl1><?=make_fsize_str($accts[$i]['length'])?></td>
         </tr>
        <?
        }
        ?>
         <tr>
          <td class=tbl1><b>Итог</b></td>
          <td class=tbl1></td>
          <td class=tbl1><b><? OUT($sumnum) ?></b></td>
          <td class=tbl1></td>
          <td class=tbl1></td>
          <td class=tbl1><b><? OUT(bytes2mb($sumtra,3)) ?> Мб</b></td>
         </tr>
        </table>
        <?
    }
    if(!count($accts))OUT("<div align=center>Ничего не найдено</div>");
    } //end of mod==""
}
else
{
if($uid && $showdenied=="true"){
         ?>
<h4>Запрещённые попытки посещения сайтов пользователем '<? OUT($user['fio']) ?>':</h4>
  <table border=0 width=100% class=tbl1>
   <tr>
   	<td class=tbl1>№</td>
   	<td class=tbl1>URL</td>
   	<td class=tbl1>Дата</td>
   </tr>
         <?
           $denied = $BILL->GetDeniedLog($uid);
          $i=0;
           foreach($denied as $url)
           {
			?>
			   <tr>
			   	<td class=tbl1><?=$i++?></td>
			   	<td class=tbl1><?=make_url_str($url['url'])?></td>
			   	<td class=tbl1><?=norm_date($url['date'])?></td>
			   </tr>

			<?
           }

		?>
		</table>
		<?
		}


if(!isset($sort))
 $sort=">count";
if(!isset($limit) || !$limit)
	$limit=25;
if(!strstr($sort,"count") && !strstr($sort,"url")&& !strstr($sort,"length"))
    $sort=">count";
if(!isset($gid) || $gid=='all')
	$gid=null;
if(!isset($groupby))
	$groupby=null;
if(!isset($hideother) || $hideother=="false")

$urls = $BILL->GetUrlsPopularity($sort,$uid,$limit,$gid,$groupby,$hideother);
$urls = $BILL->GetUrlsPopularity($sort,$uid,$limit,$gid,$groupby,$hideother);

 ?>
 <div align=center><h3>Популярность сайтов</h3></div>
 <?
 if($uid)
 	{
 		?>
 		<h4>Сайты посещённые пользователем '<? OUT($user['fio']) ?>':</h4>
 		<?
 	}
 if(isset($graf) && $graf=="true"){?>
 <div align=center><img align=center src="<? OUT(SK_DIR) ?>/billing/admin_draw.php?action=topurl<? OUT("&sort=$sort&limit=$limit&uid=$uid&gid=$gid&groupby=$groupby&hideother=$hideother") ?>"></div><br>
 <div align=right>
 <?}?></div>
  <table border=0 width=100% class=tbl1>
   <tr>
   	<td class=tbl1>№</td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied",$sort,"url","URL");?></td>
   	<? if($groupby!="cid" && !$uid){?><td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied",$sort,"ucount","Число пользователей");?></td><?}?>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied",$sort,"count","Число пакетов");?></td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied",$sort,"length","Трафик");?></td>
   	<? if($groupby!="cid"){?><td class=tbl1>Category</td><?}?>
   </tr>

 <?
     $total_count = 0;
   $total_length =  0;
   $total_ucount=0;
    $cats = $BILL->GetUrlCategories();
  foreach($urls as $url)
   {
   $total_count +=(int)$url['count'];
   $total_length += (int)$url['length'];
   $total_ucount =($url['ucount']>$total_ucount)?$url['ucount']:$total_ucount;
    ?>
     <tr>
         <td class=tbl1><?=++$i ?></td>
       <td class=tbl1><?=(($groupby=="cid")?utils::utf2cp($url['cattitle']):make_url_str($url['url'],true))?></td>
       <? if($groupby!="cid" && !$uid){?><td class=tbl1><?=$url['ucount'] ?></td><?}?>
       <td class=tbl1><?=$url['count'] ?></td>
       <td class=tbl1><?=make_fsize_str($url['length']) ?></td>
       <? if($groupby!="cid"){?><td class=tbl1><?=utils::utf2cp($url['cattitle']) ?></td><?}?>       
     </tr>
    <?

   }
?>
     <tr>
       <td class=tbl1><b>TOTAL:</b></td>
       <td class=tbl1></td>
       <? if($groupby!="cid"){?><td class=tbl1>MAX: <?=$total_ucount?></td><? } ?>
       <td class=tbl1><?=$total_count?></td>
       <td class=tbl1><?=make_fsize_str($total_length) ?></td>
     </tr>
</table>
<?
}

// -----------------------------------------------------------------------------






   break;
   };


   ?>
   <div align=center><font style="font-size:16px"><b>Биллинг-система кафедры САПРиУ</b></font></div>
   <div align=center><BIG><? OUT($head) ?></BIG></div>
  <?

  if($action=="today" || $action=="week" || $action=="month")
  {
  ?>
   <table width=70% align=center class=tbl1>
   <tr>
    <td class=tbl1><B>№</B></td>
    <td class=tbl1><B>Логин</B></td>
    <td class=tbl1><B>ФИО</B></td>
    <td class=tbl1><B>Траффик (Кб)</B></td>
    <td class=tbl1><B>Время (ЧЧ:ММ:СС)</B></td>
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
       <td class=tbl1><? OUT($accts[$i]["user"]) ?></td>
       <td class=tbl1><? OUT($accts[$i]["fio"]) ?></td>
       <td class=tbl1><? OUT(bytes2kb($accts[$i]["traffic"],3)." (".bytes2mb($accts[$i]["traffic"],3)." Mb)") ?></td>
       <td class=tbl1><? OUT(gethours($accts[$i]["time"]).":".getmins($accts[$i]["time"]).":".getsecs($accts[$i]["time"])) ?></td>
      </tr>
      <?
     }

   ?>
      <tr>
       <td class=tbl1><b>Итого</b></td>
       <td class=tbl1></td>
       <td class=tbl1></td>
       <td class=tbl1><b><? OUT(bytes2kb($sumtra,3)." (".bytes2mb($sumtra,3)." Mb)") ?></b></td>
       <td class=tbl1><b><? OUT(gethours($sumtim).":".getmins($sumtim).":".getsecs($sumtim)) ?></b></td>
       </tr>
  </table>
  <?
  }
  elseif($action=="sessions")
  {
   if(!isset($groupby))$groupby="";

   if(count($accts))
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
         </tr>
        </table>
        <?
      break;
      };

    if(!count($accts))OUT("<div align=center>Ничего не найдено</div>");

  }
  elseif($action=="tarifs")
  {
  ?>
      <table width=100% align=center class=tbl1>
      <tr>
       <td class=tbl1>№</td>
       <td class=tbl1>Тариф</td>
       <td class=tbl1>Траффик</td>
       <td class=tbl1>Время</td>
      </tr>
      <?
      $cnt=count($accts);
       $sumtra=0;
       $sumtim=0;
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
  }
}
?>
</body>
</html>