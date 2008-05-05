<?php
require_once("../../classes.php");

if(!check_auth() || $CURRENT_USER['level']<7){
	die("Access denied!");
}

require_once("../../skins/smadbis/billing/DrClass.php");
require_once("./SMPHPToolkit/SMAjax.php");
$ajaxbuf = new ajax_buffer("update_buffer");
error_reporting(E_PARSE);

$DBAccess = new CBilling();

?>
<html>
<head>
<style type="text/css">
	@IMPORT url("../../skins/smadbis/css/ajax.css");
</style>
  <script type="text/javascript" src="../../js/scriptaculous/prototype.old.js"></script>
  <script type="text/javascript" src="../../js/ajax/engine.js"></script>
</head>
<body>

</body>
</html>