<?php
global $MDL,$GV,$DIRS;
?>
<div align=center><b>root menu:</b></div>
<div align=center>
<a href="?act=root&id=pass">������</a><br>
<a href="?act=root&id=modules">������</a><br>
<a href="?act=root&id=vars">���������</a><br>
<? if($MDL->IsModuleExists("menu")){ ?><a href="?act=root&id=menu">������ ����</a><br><? }?>
<?  if($MDL->IsModuleExists("pager")){ ?><a href="?act=root&id=pager">���������</a><br><? } ?>
<?  if($MDL->IsModuleExists("users")){ ?><a href="?act=root&id=users">������������</a><br><? } ?><br>
<a href="?act=root&id=logout&fwdto=<? OUT($FLTR->DirectProcessURL(getfullurl())) ?>">logout root</a><br>
</div> 