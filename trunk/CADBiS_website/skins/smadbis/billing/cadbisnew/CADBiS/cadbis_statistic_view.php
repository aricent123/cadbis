<style type="text/css">
    #chart_channel{
    	float: left;
        border: 1px solid #C5E4EC;
        position:relative;
        height:200px;
        width:100%;
        overflow-x:auto; 
        overflow-y:hidden;
      } 
    #chart_memory{
    	float: left;
    	border: 1px solid #C5E4EC;
        position:relative;
        height:200px;
        width:100%;
        overflow-x:auto; 
        overflow-y:hidden;
      } 
	.indicator{
    	background-color: #85A4AC; 
        position: absolute;
        bottom: 0px;
        width: 13px; 
        border: 1px solid black;
        font-size: 9px;
        font-family: serif;
        color: white;
	}      
</style>        
<? 
	$left = $channel_count->get_value()*14;            
?>
<table width="100%">
<tr>
<td width="50%">
	Нагрузка на канал (потоков):
</td>
<td width="50%">
	Использование памяти (Мб):
</td>
</tr>
<tr>
<td width="50%">
<div id="chart_channel">        
        <? 

        ?>
        <? $channelbuf->start(); ?>
                <div class="indicator" style="left: <?=$left?>px; height: <?=calc_height($channel_loading,10,180) ?>px">
                	<?=$channel_loading ?>
                </div>                       
        <? $channelbuf->end(); ?>
</div>
</td>
<td width="50%">
        <? 
			$left = $memory_count->get_value()*14;            
        ?>
<div id="chart_memory">
        <? $memorybuf->start(); ?>
                <div class="indicator" style="left: <?=$left?>px; height: <?=calc_height($memory_usage,20,180) ?>px">
                	<?=$memory_usage ?>
                </div>                       
        <? $memorybuf->end(); ?>
</div>
</td>
</tr>
</table>
<label><input type="checkbox" id="chbScroll" checked/>Scroll graph</label>
<label><input type="checkbox" onclick="OnOffGraphics(this.checked)" id="chbTurnOn"/>Включить/выключить графики</label>
<script type="text/javascript"> 
	var int = null;
	function OnOffGraphics(on)
	{
		if(!on)
		{
			clearInterval(int);
		}
		else
		{
			int = setInterval('statistic_update()', 1500);
		}
	}
	function update_buffer(buffer,cntvarname,chartid)
	{
		buffer.set_var(cntvarname, parseInt(buffer.get_var(cntvarname)) + 1);
		buffer.onSuccess=  function(){
        	if($('chbScroll').checked){
				jQuery(chartid).scrollTo(parseInt(buffer.get_var(cntvarname))*14,0, {axis:'x'});
			}
		}
        buffer.update();		
	}
    function statistic_update()
    {
    	if(typeof(<?=$channelbuf->client_id()?>) != 'undefined' && <?=$channelbuf->client_id()?>!=null)
        {
        	var buffer = <?=$channelbuf->client_id() ?>;
            var cntvarname = '<?=$channel_count->client_id() ?>';
			update_buffer(buffer,cntvarname,'#chart_channel');
		}
    	if(typeof(<?=$memorybuf->client_id()?>) != 'undefined' && <?=$memorybuf->client_id()?>!=null)
        {
        	var buffer = <?=$memorybuf->client_id() ?>;
            var cntvarname = '<?=$memory_count->client_id() ?>';			
			update_buffer(buffer,cntvarname,'#chart_memory');
		}		
	}
	
</script>