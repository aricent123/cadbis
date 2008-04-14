<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for billing based on FreeRADIUS                  //
//    MAKER: SMStudio                                                   //
//    specially for SM&DBiS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="SM&DBiS";
$MDL_DESCR="For billng";
$MDL_UNIQUEID="smadbis";
$MDL_MAKER="SMStudio";

if(!class_exists("CBilling"))
{
if(file_exists(SK_DIR."/billing/DrClass.php"))
  include SK_DIR."/billing/DrClass.php";
}

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
  //if($CURRENT_USER["level"]<)return;
  if($_NOTBAR)
   if(file_exists(SK_DIR."/billing") && is_dir(SK_DIR."/billing"))
    include(SK_DIR."/billing/index.php");
   else
     $ERR->Warning("skin '".$GV['skin']." doesn't support module 'billing!'");
  else
   if(file_exists(SK_DIR."/billing") && is_dir(SK_DIR."/billing"))
    include(SK_DIR."/billing/bar.php");
   else
     $ERR->Warning("skin '".$GV['skin']." doesn't support module 'billing!'");
    
  }
                         
}

?>