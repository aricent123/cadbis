<html>
<head>
	<title><?=$title ?></title>
	<link href="css/grid.css" rel="stylesheet" type="text/css"/>
	<link href="css/ajax.css" rel="stylesheet" type="text/css"/>
	<script type="text/javascript" src="js/common/prototype.js"></script>
	<script type="text/javascript" src="js/common/scriptaculous.js"></script>	
	<script type="text/javascript" src="js/common/window/window.js"></script>	
	<script type="text/javascript" src="js/smphptoolkit/ajaxbuffer.js"></script>
	<script type="text/javascript" src="js/smphptoolkit/entitymanager.js"></script>
</head>
<body>
<? $ajaxbuffer->start(); ?>
	<?=$grid->render(); ?><br/>
	Updated at: <?=date("H:i:s") ?>
<? $ajaxbuffer->end(); ?>
</body>
</html>