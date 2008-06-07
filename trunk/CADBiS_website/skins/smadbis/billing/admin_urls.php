<?php
require_once(dirname(__FILE__).'/cadbisnew/graph/charts.php');
require_once(dirname(__FILE__).'/cadbisnew/SMPHPToolkit/common.inc.php');

//include("restore_confs.php");
if($BILLEVEL<=1)
 {
 ?>
 Эта страница запрещена для вас!
 <?
 return;
 }
if(!isset($uid) || $uid=="null" || !$uid)
	$uid = null;
if($uid)
	$user = $BILL->GetUserData($uid);
else
	$user = null;
if(!isset($modurl))
	$modurl = "";
if($BILLEVEL>=3){
?>
<a target=_blank href="<? OUT("?act=noskin&page=$p&noskinact=smadbisrept&action=$action&sort=$sort&modurl=$modurl&hideother=$hideother&limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&modurl=$modurl&gid=$gid&groupby=$groupby&uid=$uid&mod=show&monsel[]=".$monsel[0]."&monsel[]=".$monsel[1]."&daysel[]=".$daysel[0]."&daysel[]=".$daysel[1]."&yearsel[]=".$yearsel[0]."&yearsel[]=".$yearsel[1]) ?>">Версия для печати</a><br>
<?
}

if($modurl=="stats" && $user){

?>
<a href="<? OUT("?p=$p&act=$act&action=$action&uid=$uid") ?>">Просмотр общей статистики</a><br>
<?
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
   <form action="?p=smadbis&act=stats&action=urls&modurl=stats&uid=<?=$uid?>&mod=show" method=post>
   <div align=center>Параметры выборки:</div>
   <table width=100% align=center class=tbl1>
   <td width=50% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center>Начиная с:</td></tr>
    <tr><td>   <? OUT($daysel1) ?><? OUT($monsel1) ?><? OUT($yearsel1) ?></td></tr>
    </table>
   </td>
   <td width=50% height=100%>
    <table width=100% align=center class=tbl1>
    <tr><td align=center height=100%>И до:</td></tr>
    <tr><td>   <? OUT($daysel2) ?><? OUT($monsel2) ?><? OUT($yearsel2) ?></td></tr>
    </table>
   </td>
   </table>
   <div align=center><input type=submit class=button value="Показать"></div>
   </form>
   <?
   if(isset($mod)&& $mod=="show")
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
		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&showdenied=false&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Убрать запреты на посещения</a><br>
		<?
	}
	elseif($uid)
	{
		?>
		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&showdenied=true&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Показать запреты на посещения</a><br>
		<?
	}
if($user)
	{
	?>
	<a href="<? OUT("?p=$p&act=$act&action=$action&modurl=stats&uid=$uid") ?>">Просмотр статистики по датам</a><br>
	<?
	}

if(!isset($sort))
 $sort=">count";
if(!isset($limit) || !$limit)
	$limit=25;
if(!strstr($sort,"count") && !strstr($sort,"url")&& !strstr($sort,"length")&& !strstr($sort,"cid"))
    $sort=">count";
if(!isset($gid) || $gid=='all')
	$gid=null;
if(!isset($groupby))
	$groupby=null;
 if(isset($savecats) && $savecats=="true" && $BILLEVEL>=3)
 		$BILL->SetCidsForUrls($cids);

if(!isset($hideother) || $hideother=="false")
	$hideother = false;	
$urls = $BILL->GetUrlsPopularity($sort,$uid,$limit,$gid,$groupby,$hideother);

 ?>
 <div align=center><h3>Популярность сайтов</h3></div>
 <?
 if($uid)
 	{
 		?>
 		<h4>Сайты посещённые пользователем '<a href="<?OUT("?p=users&act=userinfo&id=".$user['uid'])?>"><? OUT($user['fio']) ?></a>':</h4>
 		<?
 	}


 if(isset($graf) && $graf=="true"){ ?>
 <div>
 <? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=topurl&sort=$sort&limit=$limit&uid=$uid&gid=$gid&groupby=$groupby&hideother=$hideother",600, 500 );?>
 </div><br>
 <div align=right>
 <?}
 if($graf!="true")
 	{?><a href="<? OUT("?p=$p&act=$act&action=$action&sort=$sort&limit=$limit&graf=true&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Показать график</a><br><?
 	}
 	else
 	{
 	?><a href="<? OUT("?p=$p&act=$act&action=$action&sort=$sort&limit=$limit&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Без графика</a><br><?
 		}
?></div><?
 if($limit!=10){?><a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=10&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Первые 10</a><?}else{?>Первые 10<?}?><br>
 <?if($limit!=25){?><a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=25&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Первые 25</a><?}else{?>Первые 25<?}?><br>
 <?if($limit!=50){?><a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=50&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Первые 50</a><?}else{?>Первые 50<?}?><br>
 <?if($limit!=100){?> <a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=100&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Первые 100</a><?}else{?>Первые 100<?}?><br>
 <?if($limit!="all"){?> <a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=all&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">Все</a><?}else{?>Все<?}?><br>
 <?if($groupby!="cid"){?> <a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&showdenied=$showdenied&gid=$gid&groupby=cid&hideother=$hideother") ?>">По категориям</a>
 <?}else{?>
 	<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied") ?>">По URL</a><br><br>
 	<?if($hideother!="true"){?>
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=true") ?>">Скрыть неназначенную группу</a>
 		<?}else{?>
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=false") ?>">Показать неназначенную группу</a>
 		<?}?>
 	<?}
  if($uid){?><br><a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother") ?>">ДЛЯ ВСЕХ ПОЛЬЗОВАТЕЛЕЙ</a><?}?> 
 <?
  $tarlist=$BILL->GetTarifs();
  $tarsel="<select name=gid style=\"width:70%\"  class=inputbox><option value=\"all\"></option>";
  for($i=0;$i<count($tarlist);++$i)
   {
   if($gid==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
   $tarsel.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
   }
  $tarsel.="</select>";
   if(!$uid){
   ?>
   <form action="<? OUT("?p=$p&act=$act&action=$action&sort=$sort&limit=$limit&graf=true&uid=$uid&hideother=$hideother&showdenied=$showdenied&groupby=$groupby") ?>" method=post>
   Выбрать только тариф:<br>
   <? OUT($tarsel) ?><input style="width:30%" class=button value="Просмотр" type=submit>
   </form>

<?}
     $total_count = 0;
   $total_length =  0;
   $total_ucount=0;
 $cats = $BILL->GetUrlCategories();
  ?>
  <form action="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby&savecats=true")?>"  method=post>
  
  <table border=0 width=100% class=tbl1>
   <tr>
   	<td class=tbl1>№</td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"url","URL");?></td>
   	<? if($groupby!="cid" && !$uid){?><td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"ucount","Число пользователей");?></td><?}?>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"count","Число пакетов");?></td>
   	<td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"length","Трафик");?></td>
   	<? if($groupby!="cid"){?><td class=tbl1><?=template_header_sort("limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&hideother=$hideother&gid=$gid&groupby=$groupby",$sort,"cid","Категория");?></td><?}?>
   </tr>

 <?
  $i=0;
  if(count($urls))
  foreach($urls as $url)
   {
   $sel_cat = utils::utf2cp($url['cattitle']);
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
       <? if($groupby!="cid"){?><td class=tbl1><?=$sel_cat?></td><?}?>
     </tr>
    <?

   }
?>
     <tr>
       <td class=tbl1><b>TOTAL:</b></td>
       <td class=tbl1></td>
       <? if($groupby!="cid" && !$uid){?><td class=tbl1>MAX: <?=$total_ucount?></td><?}?>
       <td class=tbl1><?=$total_count?></td>
       <td class=tbl1><?=make_fsize_str($total_length) ?></td>
       <? if($groupby!="cid" && $BILLEVEL>=3){?><td align=center></td><?}?>
     </tr>
</table>
</form>
<?
}
if($BILLEVEL>=3){
?>

<a target=_blank href="<? OUT("?act=noskin&page=$p&noskinact=smadbisrept&action=$action&sort=$sort&modurl=$modurl&hideother=$hideother&limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&modurl=$modurl&gid=$gid&groupby=$groupby&uid=$uid&mod=show&monsel[]=".$monsel[0]."&monsel[]=".$monsel[1]."&daysel[]=".$daysel[0]."&daysel[]=".$daysel[1]."&yearsel[]=".$yearsel[0]."&yearsel[]=".$yearsel[1]) ?>">Версия для печати</a><br>
<?
}
?>