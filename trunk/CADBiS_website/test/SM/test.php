<?php
session_start();
require_once("../../classes.php");
require_once("./SMPHPToolkit/SMAjax.php");
$ajaxbuf = new ajax_buffer("update_buffer");
error_reporting(E_PARSE);


$height = rand(30,100);
?>
<html>
<head>
<style type="text/css">
	@IMPORT url("../../skins/smadbis/css/ajax.css");
	.indicator{
		background-color: red; 
		width: 80px; 
		position: relative;
		float: left;
		border: 1px solid black;
	}
</style>
  <script type="text/javascript" src="../../js/scriptaculous/prototype.old.js"></script>
  <script type="text/javascript" src="../../js/ajax/engine.js"></script>
</head>
<body>
	Channel loading: 
	<? $ajaxbuf->start(); ?>
		<div class="indicator" style="top:<?=100-$height ?>px; height: <?=$height ?>px">
			<?=$height ?> %
		</div>			
	<? $ajaxbuf->end(); ?>
<script type="text/javascript">
	function update()
	{
		var buffer = <?=$ajaxbuf->client_id() ?>;
		buffer.update();
	}
	setInterval('update()', 500);
</script>
</body>
</html>