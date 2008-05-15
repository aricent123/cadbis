<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete/comment links               //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//

$MDL_TITLE="Links";
$MDL_DESCR="For links";
$MDL_UNIQUEID="links";
$MDL_MAKER="SMStudio";

if(!$_GETINFO)
{

if(!class_exists("CLinks"))
{class CLinks
 { 
 function GetLinks()
  {
  global $DIRS;
  include $DIRS["links_list"];
  $list=array();
  for($i=0;$i<count($LNKS);++$i)
    $list[$i]=$LNKS[$i];
  return $list;
  }
};}

?>
<?php
  if($_INSTALL)
  {
  //установка модуля
  }
  elseif($_UNINSTALL)
  {
  //удаление модуля
  }                 
  elseif($_CONFIGURE)
  {
  //настройка модуля
  } 
  elseif($_MENU)
  {
  //меню администратора
  }  
elseif($_MODULE)
{
if($_NOTBAR)
  if(!file_exists(SK_DIR."/links.php"))
    $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
  else
    include(SK_DIR."/links.php");
else
  if(!file_exists(SK_DIR."/linksbar.php"))
    $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
  else
    include(SK_DIR."/linksbar.php");
}
}
?>