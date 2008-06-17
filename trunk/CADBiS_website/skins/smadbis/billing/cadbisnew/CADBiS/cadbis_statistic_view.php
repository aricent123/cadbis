<?
require_once(dirname(__FILE__).'/../graph/charts.php');
?>
<style type="text/css">
    #chart_channel{
    	float: left;
        border: 1px solid #C5E4EC;
        position:relative;
        height:300px;
        width:100%;
        overflow-x:auto; 
        overflow-y:hidden;
      } 
    #chart_memory{
    	float: left;
    	border: 1px solid #C5E4EC;
        position:relative;
        height:300px;
        width:100%;
        overflow-x:auto; 
        overflow-y:hidden;
      }     
</style>        
<table width="100%">
<tr>
<td width="50%">
	Нагрузка на канал (потоков):<br>
	<b>Оптимальная: 200<br>
	Пиковая: 500</b>
</td>
<td width="50%">
	Использование памяти (Мб):<br>
	<b>Оптимальное: 128Мб<br>
	Пиковое: 512Мб</b>
</td>
</tr>
<tr>
<td width="50%">
<div id="chart_channel">        
 
<? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=loading",333, 300 );?>


</div>
</td>
<td width="50%">
<div id="chart_memory">
<? echo InsertChart ("./skins/smadbis/billing/cadbisnew/graph/charts.swf", "./skins/smadbis/billing/cadbisnew/graph/charts_library", "./skins/smadbis/billing/chart_data.php?chart_type=memory",333, 300 );?>

</div>
</td>
</tr>
</table>