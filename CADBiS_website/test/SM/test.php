<?php
session_start();
require_once("../../classes.php");
require_once("./SMPHPToolkit/SMAjax.php");
$ajaxbuf = new ajax_buffer("update_buffer");
$ajaxbuf->set_method(ajax_buffer_method::APPEND_AFTER);
$ind_count = new ajax_var('ind_count',0);
$ajaxbuf->register_var($ind_count);
error_reporting(E_PARSE);

?>
<html>
<head>
<style type="text/css">
	@IMPORT url("../../skins/smadbis/css/ajax.css");
	.indicator{
		background-color: red; 
		position: absolute;
		bottom: 0px;
		width: 10px; 
		border: 1px solid black;
	}
      #chart{
        position:relative;
        height:200px;
        width:300px;
        overflow-x:auto; 
        overflow-y:hidden;
      }	
</style>
  <script type="text/javascript" src="../../js/scriptaculous/prototype.old.js"></script>
   <script type="text/javascript" src="../../js/jquery/jquery.js"></script>
  <script type="text/javascript" src="../../js/jquery/jquery.scrollTo-min.js"></script>
  <script type="text/javascript">
  	jQuery.noConflict();
  </script>
  <script type="text/javascript" src="../../js/ajax/engine.js"></script>
</head>
<body>
	Channel loading (Mbps):
	<? 
			$left = $ind_count->get_value()*11;
			$height = rand(30,100);
	?>
<div id="chart">
	<? $ajaxbuf->start(); ?>
		<div class="indicator" style="left: <?=$left?>px; height: <?=$height ?>px"></div>			
	<? $ajaxbuf->end(); ?>
</div>
<label><input type="checkbox" id="chbScroll"/>Scroll graph</label>
<script type="text/javascript">	
	function update()
	{
		var buffer = <?=$ajaxbuf->client_id() ?>;
		var ind_count = '<?=$ind_count->client_id() ?>';
		buffer.set_var(ind_count, parseInt(buffer.get_var(ind_count)) + 1);
		buffer.onSuccess=  function(){
			if($('chbScroll').checked)
				jQuery('#chart').scrollTo(parseInt(buffer.get_var(ind_count))*10,0, {axis:'x'});
		}
		buffer.update();
	}
	setInterval('update()', 1000);
</script>
</body>
</html>