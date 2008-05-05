<?php   
session_start(); 
error_reporting(E_ALL);    
extract($_GET); 
extract($_POST); 
extract($_SESSION);

//if(!isset($action))die("no image");
$pdir="../../../";
require_once($pdir."classes.php");
require_once($pdir."conf/globals.php");
require_once("DrClass.php");
require_once("funcs.php");

//reading all configs for all modules
$list= read_dir_ext($pdir.$GV["modules_conf_dir"],$GV["module_ext"]);
foreach($list as $lst){require($pdir.$GV["modules_conf_dir"]."/$lst");}
//$GV["modules_dir"]=$pdir.$GV["modules_dir"];
foreach($DIRS as &$value) 
  $value=$pdir.$value;
$GV["modules_dir"]=$pdir.$GV["modules_dir"];

$MDL=new CModules();
$ERR=new CErrors();

$CURRENT_USER=NULL;
authenticate();

$BILLEVEL=getbillevel($CURRENT_USER["level"]);
?>
