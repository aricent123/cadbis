<?php
require_once(dirname(__FILE__)."/../../../../../modules_conf/smadbis.conf.php");
require_once(dirname(__FILE__)."/../../DrClass.php");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
require_once(dirname(__FILE__)."/cadbis_statistic_backend.php");
require_once(dirname(__FILE__)."/cadbis_statistic_view.php");
