<?php
session_start();
require("classes.php");
require("conf/globals.php");
require("conf/passwd.php");
require("conf/modules.php");
 $timer = new CTimer();
 $timer->start();
 $MDL=new CModules();
 $ERR=new CErrors();
 $PDIV=new CPageDivider(10);
 $FLTR=new CFiltration();
require("config.php");


//reading all configs for all modules
$list= read_dir_ext($GV["modules_conf_dir"],$GV["module_ext"]);
foreach($list as $lst){require($GV["modules_conf_dir"]."/$lst");}

   define("SK_DIR",$GV["skin_dir"]);
//AUTHORIZATION:
if(!isset($p))
switch($act)
{
  case "noskin":
  $p=$page;
  $act=$noskinact;
  $GLOBALS['act']=$noskinact;
  $GLOBALS['p']=$page;
  $_GET['act']=$noskinact;
  $_GET['p']=$page;
  //Authenticate current user
  $CURRENT_USER=NULL;
  authenticate();

  //Deny blocked accounts
  if($CURRENT_USER["level"]<0)
  $ERR->Forbidden("your account is blocked");

  $MDL->LoadModule($p);
  exit;
  break;
  case "auth":

    if(!_login($login,$passwd))
     {
     ($MDL->IsModuleExists('error'))?$p="error":$ERR->Forbidden("account isn't valid");
     $page="403"; //forbidden
     break;
     }
     resetpage($fwdto);
  break;
  case "logout":
    {
    _logout();
    resetpage($fwdto);
    }
    break;
  case "root":
    if(isset($id) && $id=="logout")
      {_logoutroot();resetpage($fwdto);break;}
    $p="user_page";
  break;
  case "smadbisrept":
  if($MDL->IsModuleExists('smadbis'))
    $MDL->LoadModule('smadbis',0);
  exit;
  break;
  default:
    setpage("?p=".$GV["default_page"]);
};


//Authenticate current user
$CURRENT_USER=NULL;
authenticate();

//Deny blocked accounts
if($CURRENT_USER["level"]<0)
  $ERR->Forbidden("your account is blocked");

//Set current skin
if(!defined("SK_DIR")){
$GV["skin_dir"]=$GV["skins_dir"]."/".$GV["skin"];
define("SK_DIR",$GV["skin_dir"]);
}

//ENABLE SKIN
require(SK_DIR."/index.php");
?>