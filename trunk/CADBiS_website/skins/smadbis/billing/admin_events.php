<?php

//include("restore_confs.php");
if($BILLEVEL<=3)
 {
 ?>
 ��� �������� ��������� ��� ���!
 <?
 return;
 }

$events = $BILL->GetEvents("","", "date");
 ?>
  <table border = 0 width=100% class=tbl1>
   <tr> <td class=tbl1>������������</td><td class=tbl1>��������� �������</td><td class=tbl1>�����</td></tr>
  
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