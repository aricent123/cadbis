<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for FORUM                                        //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//


$MDL_TITLE="Forum";
$MDL_DESCR="Forum";
$MDL_UNIQUEID="smdsforum";
$MDL_MAKER="SMStudio";

if(!$_GETINFO)
{

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
  //страница администратора
  } 
 elseif($_MODULE)
  {
  include "config.php";
    if($_NOTBAR)
        {
        include($DIRS["smdsforum_forumdir"]."/index.php");
        }
    else
      if(!file_exists(SK_DIR."/smdsforum.php"))
        {
        // FORUM BAR
        }
      else
        include(SK_DIR."/smdsforumbar.php");
  }
}
?>