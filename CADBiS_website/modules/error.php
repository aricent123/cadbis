<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for errors                                       //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//

$MDL_TITLE="Errors";
$MDL_DESCR="ERROR";
$MDL_UNIQUEID="error";
$MDL_MAKER="SMStudio";

if(!$_GETINFO)
{

switch($page)
 {
  case "404":
 ?>
 <font size=20px>404</font>
 <?
 break;
 case "403":
 ?>
 <font size=20px>403</font> 
 <?
 break;
 default:
 ?>
 <font style="font-size:20px">Some errors</font> 
 <?
 
 };
}
?>