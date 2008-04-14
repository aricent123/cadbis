<?php

$BILLEVEL=getbillevel($CURRENT_USER["level"]);
if($BILLEVEL>2)include SK_DIR."/billing/admin_stats_rept.php";
else include SK_DIR."/billing/user_stats_rept.php";

?>