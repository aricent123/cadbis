<?php
global $MDL,$GV,$DIRS;
?>
<div align=center><b>root menu:</b></div>
<div align=center>
<a href="?act=root&id=pass">пароль</a><br>
<a href="?act=root&id=modules">модули</a><br>
<a href="?act=root&id=vars">настройки</a><br>
<? if($MDL->IsModuleExists("menu")){ ?><a href="?act=root&id=menu">пункты меню</a><br><? }?>
<?  if($MDL->IsModuleExists("pager")){ ?><a href="?act=root&id=pager">странички</a><br><? } ?>
<?  if($MDL->IsModuleExists("users")){ ?><a href="?act=root&id=users">пользователи</a><br><? } ?><br>
<a href="?act=root&id=logout&fwdto=<? OUT($FLTR->DirectProcessURL(getfullurl())) ?>">logout root</a><br>
</div> 