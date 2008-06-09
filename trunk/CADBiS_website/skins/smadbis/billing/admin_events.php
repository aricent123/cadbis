<?php

//include("restore_confs.php");
if($BILLEVEL<=3)
 {
 ?>
 Эта страница запрещена для вас!
 <?
 return;
 }

 
 /**
  * Primitive paging
  * @param integer $page
  * @param CPageDivider $PGR
  */
 function admin_events_pager($page, $totalcount, $psize)
 { 
 	global $p,$act,$action;
 	$pcount = $totalcount/$psize;
 	?><div id="pager"><?
 	for($i=0;$i<$pcount;++$i)
 	{
 		if($page != $i+1)
 		{
 			?><a href="<?=("?p=$p&act=$act&action=$action&page=".($i+1)."&pagesize=$psize")?>"><?=($i+1) ?></a><?			
		}
		else
		{
			echo ($i+1);
		}
		if($i<$pcount-1)
			echo ",";
	}	
	?>
	</div>
	<?
 } 
if(!isset($page))
	$page=1;
if(!isset($pagesize))
	$pagesize = 150;
$events = $BILL->GetEvents($page,$pagesize, "date");
$evcount= $BILL->GetRowsCount('events');
 ?>
 <?=admin_events_pager($page,$evcount,$pagesize); ?>
  <table border = 0 width=100% class=tbl1>
   <tr> <td class=tbl1>Пользователь</td><td class=tbl1>Системное событие</td><td class=tbl1>Время</td></tr>  
 <? 
  foreach($events as $event)
   {
    ?>
     <tr>
       <td class=tbl1><?=$event['login'] ?></td>
       <td class=tbl1><?=$event['event'] ?></td>
       <td class=tbl1><?=norm_date(strtotime($event['date'])) ?></td>
     </tr>
    <?    
   }
?>
</table>
<?=admin_events_pager($page,$evcount,$pagesize); ?>