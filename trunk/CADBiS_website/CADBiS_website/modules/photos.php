<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete/comment photos              //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//

$MDL_TITLE="Photos";
$MDL_DESCR="For photos";
$MDL_UNIQUEID="photos";
$MDL_MAKER="SMStudio";


if(!$_GETINFO)
{


/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for photos   ****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CPhotos"))
{class CPhotos{


};}
?>
<?php

  if($_INSTALL)
  {
  //��������� ������
  }
  elseif($_UNINSTALL)
  {
  //�������� ������
  }                 
  elseif($_CONFIGURE)
  {
  //��������� ������
  } 
  elseif($_MENU)
  {
  //���� ��������������
  }  
 elseif($_ADMIN)
  {
  //�������� ��������������
  } 
 elseif($_NOTBAR)
  if(!file_exists(SK_DIR."/photos.php"))
    $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
  else
    include(SK_DIR."/photos.php");
else
  if(!file_exists(SK_DIR."/photosbar.php"))
    $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
  else
    include(SK_DIR."/photosbar.php");

}
?>