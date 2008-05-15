<?php
          /*
          
SELECT * FROM url_popularity u where url='www.google-analytics.com';
delete from ctry_popularity;
SELECT * FROM url_log u;
SELECT * FROM url_popularity u where uid=12 and url;
SELECT * FROM protocols p where unique_id='CADBiS_v_1.2_test';
insert into url_log values('CADBiS_v_1.2_test','SM','www.google-analytics.com',1000,'2007-05-19 21:03:00','216.239.59.104');

select * from `ip2country` where country = 'BRAZIL';
SELECT * FROM ctry_popularity c;
delete from ctry_popularity;

insert into ctry_popularity SELECT distinct ctry,CAST((ROUND(RAND()*2)+2005) AS CHAR(4)),CAST((ROUND(RAND()*2)+5)AS CHAR(2)),ROUND(RAND()*1000),ROUND(RAND()*1000000),12 FROM ip2country group by ctry limit 1000;
*/          
      
          
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
  break;
 case "false":
   $year = date("Y");
   $month = date("n");
  break;
 default:
   $year = null;
   $month = null;
  break;
}
$urls = $BILL->GetCtryPopularity($sort,$uid,$limit,$gid,$year,$month);

 ?>
 <div align=center><h3>Популярность стран</h3></div>
 <?


//line1=RU:1:0x00FF00#EU:1:0x0000FF&line2=RU:1:0x00FF00#DZ:1:0x0000FF&line3=RU:1:0x00FF00#AU:1:0x0000FF&line4=RU:1:0x00FF00#US:1:0x0000FF
$flparams = "";
$i=1;
if(count($urls)){
$max = 0;
foreach($urls as $url)
 if($max<$url['length'] && $url['ctry']!="RU")
   $max = $url['length'];
      
foreach($urls as $url)
 {
 /*?>
 <table width=100 bgcolor=<?=strtoupper(genmapcol($url['length'],$max))?>><tr><td><?=$url['length']?></td></tr></table>
 <?*/
 $flparams .= (($flparams)?"&":"")."line$i=RU:".ctry_calc_linesize($url, $max).":".ctry_calc_linecolor($url, $max)."#".$url['ctry'].":".ctry_calc_linesize($url, $max).":".ctry_calc_linecolor($url, $max);
 $i++;
 }}else
 OUT("No results");
   
 if(isset($graf) && $graf=="true"){?>
   <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="800" height="500" id="map4" align="middle">
     <param name="allowScriptAccess" value="sameDomain" />
     <param name="movie" value="map4.swf?<?=$flparams?>" />
     <param name="quality" value="high" />
     <param name="bgcolor" value="#ffffff" />
     <embed src="map4.swf?<?=$flparams?>" quality="high" bgcolor="#ffffff" width="800" height="500" name="map4" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
   </object> <br>
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


 	<?if($hideother=="false"){?>
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=true") ?>">За текущий год</a> |
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=none") ?>">За всё время исследования</a> 		
        <?}elseif($hideother=="true"){?>
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=false") ?>">За текущий месяц</a> |
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=none") ?>">За всё время исследования</a>
        <?}else{?>
 		<a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=false") ?>">За текущий месяц</a> | 
                <a href="<? OUT("?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&uid=$uid&gid=$gid&showdenied=$showdenied&groupby=$groupby&hideother=true") ?>">За текущий год</a>
 		<?}
 if($uid)
    OUT("<br><a href=\"?p=$p&act=$act&graf=$graf&sort=$sort&action=$action&limit=$limit&showdenied=$showdenied&gid=$gid&groupby=$groupby&hideother=$hideother\">ДЛЯ ВСЕХ ПОЛЬЗОВАТЕЛЕЙ</a>");   
     

  $tarlist=$BILL->GetTarifs();
  $tarsel="<select name=gid style=\"width:70%\"  class=inputbox><option value=\"all\"></option>";
  for($i=0;$i<count($tarlist);++$i)
   {
   if($gid==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
   $tarsel.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
   }
  $tarsel.="</select>";
  
if(!$uid)
   OUT("<form action=\"?p=$p&act=$act&action=$action&sort=$sort&limit=$limit&graf=true&uid=$uid&hideother=$hideother&showdenied=$showdenied&groupby=$groupby\" method=post>
   Выбрать только тариф:<br>
   $tarsel<input style=\"width:30%\" class=button value=\"Просмотр\" type=submit>
   </form>");
   
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
if($BILLEVEL>=3){
?>

<a target=_blank href="<? OUT("?act=noskin&page=$p&noskinact=smadbisrept&action=$action&sort=$sort&modurl=$modurl&hideother=$hideother&limit=$limit&graf=$graf&uid=$uid&showdenied=$showdenied&modurl=$modurl&gid=$gid&groupby=$groupby&uid=$uid&mod=show&monsel[]=".$monsel[0]."&monsel[]=".$monsel[1]."&daysel[]=".$daysel[0]."&daysel[]=".$daysel[1]."&yearsel[]=".$yearsel[0]."&yearsel[]=".$yearsel[1]) ?>">Версия для печати</a><br>
<?
}
?>