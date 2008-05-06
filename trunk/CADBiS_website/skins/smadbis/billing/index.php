<?php
include "config.php";
require_once(SK_DIR."/billing/funcs.php");



if(!isset($act))$act="";
if(!isset($action))$action="";
 global $BILLEVEL,$CURRENT_USER;
  	authenticate();
  	$BILLEVEL=0;
  	$BILLEVEL=getbillevel($CURRENT_USER["level"]);
 
// hack for the new system
if($action == "cadbisnew")
	exit(require_once "cadbisnew.php");
elseif($act=="noskin")
  {
	$act="smadbisrept";
//  authenticate();
//  $BILLEVEL=0;
//  $BILLEVEL=getbillevel($CURRENT_USER["level"]);
  include"stats_rept.php";
  exit;
  }

$BILLEVEL=0;
$BILLEVEL=getbillevel($CURRENT_USER["level"]);


if($act=="stats")
{
 ?>
 <div align=center><b>����������</b></div><br>
 <?
 if($BILLEVEL>2)
  {
  include SK_DIR."/billing/admin_stats.php";
  }
  elseif($BILLEVEL>1)
  {
  ?>
  ��� ��������� �������� ���� ���������� � ��������� ������ �������
  <?
  }
  elseif($BILLEVEL>0)
  {
  ?>
  ��� �������� ���� ����������, �� ��������� ������� �������������
  <?

  }
  ?>
  <?
}
elseif($act=="users")
{
if($BILLEVEL<3)return;
 ?>
 <div align=center><b>������������</b></div><br>
 <?
 if(isset($action) && $action=="add")
  {
  include SK_DIR."/billing/admin_add_user.php";
  }
  elseif(isset($action) && $action=="delete")
  {
  include SK_DIR."/billing/admin_delete_user.php";
  }
  elseif(isset($action) && $action=="edit")
  {
  include SK_DIR."/billing/admin_edit_user.php";
  }
  elseif(isset($action) && $action=="block")
  {
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  if($BILL->IsUserActivated($uid))
    {
    $BILL->BlockUser($uid);
    ?>
    <div align=center><b>������������ ������������!</b></div>
    <?
    }
    else
    {
    $BILL->ActivateUser($uid);
    ?>
    <div align=center><b>������������ �����������!</b></div>
    <?

    }
    ?>
  <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>
  <?
  }
  else
  {
  include SK_DIR."/billing/admin_users.php";
  }

}
elseif(isset($act) && $act=="tarifs")
{
 ?>
 <div align=center><b>������</b></div><br>
 <?
 if(isset($action) && $action=="add" && $BILLEVEL>3)
  {
  include SK_DIR."/billing/admin_add_tarif.php";
  }
  elseif(isset($action) && $action=="delete" && $BILLEVEL>3)
  {
  include SK_DIR."/billing/admin_delete_tarif.php";
  }
  elseif(isset($action) && $action=="edit" && $BILLEVEL>3)
  {
  include SK_DIR."/billing/admin_edit_tarif.php";
  }
  elseif(isset($action) && $action=="view")
  {
  include SK_DIR."/billing/tarifs_list.php";
  }
  elseif(isset($action) && $action=="denied_urls")
  {
  include SK_DIR."/billing/admin_tarifs_denied_urls.php";
  }
  elseif(isset($action) && $action=="expenses")
  {
  include SK_DIR."/billing/admin_tarifs_expenses.php";
  }  
  elseif($BILLEVEL>3)
  {
  include SK_DIR."/billing/admin_tarifs.php";
  }


}
elseif($act=="online" && $BILLEVEL>=3)
{
 ?>
 <div align=center><b>�� �����</b></div><br>
 <?
  include SK_DIR."/billing/online.php";
}
elseif($act=="log_url" && $BILLEVEL>=3)
{
 ?>
 <div align=center><b>���������� �����</b></div><br>
 <?
  include SK_DIR."/billing/log_url.php";
}
elseif($act=="options")
{
 ?>
 <div align=center><b>��������� �������-�������</b></div><br>
 <?
  include SK_DIR."/billing/options.php";
}
elseif($act=="countries")
{
 ?>
 <div align=center><b>��������� �����</b></div><br>
 <?
  include SK_DIR."/billing/countries.php";
}
else
{
switch($BILLEVEL)
 {
 case 5:
   ?>
   <div align=center><b><font class=fontheader>�������� ��������������:</font></b></div><br>
   <table width=100%>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats">����������</a></b></div><br>
               �������� ��������� ���������� �������-�������. ��������� ������� �� ������� � ������� �� ���������
               ���������� �������, �������������� � ��������� �����.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=users';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_edituser.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=users">������������</a></b></div><br>
               ���������� ��������������. ����������, ��������, �������������� ������������� �������-�������.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=tarifs';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_edit_tarif.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=tarifs">������</a></b></div><br>
               ���������� �������� �������-�������. ����������, ��������, �������������� �������.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=online';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_online.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=online">��-���� ������������</a></b></div><br>
               �������� �������������, ������������ � ���� �������� � ������ ���������� ������.
               ���������� ��-���� ��������������, ����������, �������� ��������������.
      </td>
      </table>
     </td></tr>

     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='<?=cadbisnewurl('admin_tarifs_rangs') ?>';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_options.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="<?=cadbisnewurl('admin_tarifs_rangs') ?>">��������� � ������</a></b></div><br>
              ���������� � ����������� ����������, ������� ����� � �������. ��������� ���������� �������-�������. ��������� ����������� �������� ������� � ������� �� ���������� �������.
      </td>
      </table>
     </td></tr> 

     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=countries';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_edit_countries.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=countries">������ ����</a></b></div><br>
               ���������� �������� � ����������� IP-�������.
      </td>
      </table>
     </td></tr>     
   </table>
   <?
 break;
 case 4:
   ?>
   <div align=center><b><font class=fontheader>�������� ����������:</font></b></div>
   <table width=100%>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats">����������</a></b></div><br>
               �������� ��������� ���������� �������-�������. ��������� ������� �� ������� � ������� �� ���������
               ���������� �������, �������������� � ��������� �����.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=users';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_edituser.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=users">������������</a></b></div><br>
               ���������� ��������������. ����������, ��������, �������������� ������������� �������-�������.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=online';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_online.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=online">��-���� ������������</a></b></div><br>
               �������� �������������, ������������ � ���� �������� � ������ ���������� ������.
               ���������� ��-���� ��������������, ����������, �������� ��������������.
      </td>
      </table>
     </td></tr>

   </table>
   <?
 break;
 case 3:
   ?>
   <div align=center><b><font class=fontheader>�������� ���������:</font></b></div>
   <table width=100%>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=stats';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_statistic.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=stats">����������</a></b></div><br>
               �������� ��������� ���������� �������-�������. ��������� ������� �� ������� � ������� �� ���������
               ���������� �������, �������������� � ��������� �����.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=users';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_edituser.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=users">������������</a></b></div><br>
               ���������� ��������������. ����������, ��������, �������������� ������������� �������-�������.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=online';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_online.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=online">��-���� ������������</a></b></div><br>
               �������� �������������, ������������ � ���� �������� � ������ ���������� ������.
               ���������� ��-���� ��������������, ����������, �������� ��������������.
      </td>
      </table>
     </td></tr>

   </table>
   <?
 break;
 case 2:
   $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
   ?>
   <div align=center><b><font class=fontheader>������ �������:</font></b></div>
   <?
   switch($act){   	
   case "topofurl":
   ?>
   <a href="<?OUT("?p=$p")?>">�������� ����������� �������</a>
   <?
   include SK_DIR."/billing/admin_urls.php";
   break;
   default:
   ?>
   <a href="<?OUT("?p=$p&act=topofurl")?>">Top ���������� �������� ��������</a>
   <?
   include SK_DIR."/billing/month_stats.php";
   include SK_DIR."/billing/user_stats.php";
   }
 break;
 case 1:
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
   ?>
   <div align=center><b><font class=fontheader>���� ����������:</font></b></div>
   <?
   include SK_DIR."/billing/user_stats.php";
 break;
 default:
   ?>
   <div align=center><b>��������, �� ������ �������� ��� ����������!</b></div>
   <?
 break;
 };
}
?>
