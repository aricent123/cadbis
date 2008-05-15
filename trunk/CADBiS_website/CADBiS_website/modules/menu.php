<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for get menu                                     //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Menu";
$MDL_DESCR="For menu";
$MDL_UNIQUEID="menu";
$MDL_MAKER="SMStudio";

if(!$_GETINFO)
{

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//*********************** class for menu ******************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CMenu"))
{class CMenu
{   
 function GetItems($parent=0)
  {
  global $DIRS;
  include $DIRS["menu_list"];
  $k=0;
  $items=NULL;
  for($i=0;$i<count($MENU);++$i) 
   {   
   //echo("$i)".$MENU[$i]["parent"]."==".$parent."<br>\n\r");
   if($MENU[$i]["parent"]==$parent)
    {    
    //echo("match!<br>\r\n");
    $items[$k]["title"]=$MENU[$i]["title"];
    $items[$k]["ulevel"]=$MENU[$i]["ulevel"];
    $items[$k]["link"]=$MENU[$i]["link"];
    $items[$k]["parent"]=$MENU[$i]["parent"];
    $items[$k]["group"]=$MENU[$i]["group"];    
    $items[$k]["number"]=$i+1;
    $k++;
   }
   }
  return $items;
  } 
  
 function GetItemsCount($parent)
  {
  global $DIRS;
  include $DIRS["menu_list"];
  $res=0;
  for($i=0;$i<count($MENU);++$i) 
    {
    if($MENU[$i]["parent"]==$parent)$res++;
    }
  return $res;
  }
};}


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
 elseif($_ADMIN)
  {
  global $MNU; 
  $MNU= new CMenu();
  }
 elseif($_INSTALL)
  {
  }
 elseif($_MODULE)
  {
    if($_NOTBAR)
      if(!file_exists(SK_DIR."/menu.php"))
        $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
      else
        include(SK_DIR."/menu.php");
    else
      if(!file_exists(SK_DIR."/menubar.php"))
        $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
      else
        include(SK_DIR."/menubar.php");
  }
}