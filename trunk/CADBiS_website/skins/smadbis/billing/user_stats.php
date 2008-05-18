<?
if($BILLEVEL<1)return;

require_once(dirname(__FILE__)."/cadbisnew/SMPHPToolkit/ajax/buffer.inc.php");
$ud=$BILL->GetUserData($CURRENT_USER["id"]);

?>
<div align=center><b>Ваша статистика по сессиям за этот месяц:</b></div>
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
        $year=date("Y");
        $month=date("m"); 
        $day=date("d");
		$daycount=(date('d')<date("t"))?date("d"):date("t");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";
		$accts=$BILL->GetUserSessions($CURRENT_USER["login"],$bdate,$adate);
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
<br>
<? 
$ajaxbuf = new ajax_buffer("update_buffer");
$ajaxbuf->start(); 
?>
<table width=100% align=center class=tbl1><tr><td>
  Today is <?=date("Y/m/d H:i:s")?> (updated by ajax)
<td><tr></table>
<? $ajaxbuf->end(); ?>