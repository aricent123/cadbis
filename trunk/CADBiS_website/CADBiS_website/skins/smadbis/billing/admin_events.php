<?php

//include("restore_confs.php");
if($BILLEVEL<=3)
 {
 ?>
 Эта страница запрещена для вас!
 <?
 return;
 }

$events = $BILL->GetEvents("","", "date");
 ?>
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