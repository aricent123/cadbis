<html>
<head>
<title><? OUT($GV["site_title"]) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=Windows-1251;">
<meta http-equiv="Keywords" content="<? OUT($GV["site_keywds"]) ?>">
<meta http-equiv="Author" content="<? OUT($GV["site_owner"]) ?>">
<meta http-equiv="Description" content="<? OUT($GV["site_descr"]) ?>">
<link href="<? OUT(SK_DIR) ?>/styles.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor=#F0F6F8 text=#000000 alink=#000000 vlink=#000000 hlink=#000000 link=#000000> 
<script>
log_sel=false;
pas_sel=false;
</script>
 
<table width=100% height=100% cellspacing=0 cellpadding=0>
 <td width=207px valign=top>
 <table width=207px height=100%  cellspacing=0 cellpadding=0>
  <tr><td width=100%><img src="<? OUT(SK_DIR) ?>/img/left_up_corner.gif"></td></tr>
  <tr><td width=100% height=100% background="<? OUT(SK_DIR) ?>/img/left_middle.gif" valign=top>

<!-- STARTOF MENU -->
<?php
$MDL->LoadModule('menu',true);
?>
<!-- ENDOF MENU -->
<br>
<img src="<? OUT(SK_DIR) ?>/img/separator.gif">
<br>
    <? if(!check_auth())
    { 
    ?>
    <div align=center>Вход:</div>
    <form action=?act=auth method=post>
    <table width=100%>
    <tr><td width=30%>
    логин
    </td><td width=50% align=left>
    <input type=text name=login class=inputbox style="width:70%;" value="login" onfocus="if(!log_sel)this.value='';log_sel=true;" style="width:90px">  
    </td></tr>
    <tr><td width=30%>
    пароль
    </td><td width=50% align=left>
    <input type=password name=passwd class=inputbox style="width:70%;"value="password" onfocus="if(!pas_sel)this.value='';pas_sel=true;" style="width:90px">
    </td></tr>    
    </table>
    <div align=center><input type=submit class=button value="войти">
    </form>
    <? if($MDL->IsModuleExists('users'))
      {
      }
      ?> 
    <?php 
    }
    else 
       $MDL->LoadMenu('users');    
    ?>   

  </td></tr>
  <tr><td width=100%><img src="<? OUT(SK_DIR) ?>/img/left_down_corner.gif"></td></tr>
  </table>
  </td><td width=100%>
   <table width=100% height=100% cellspacing=0 cellpadding=0 valign=top align=left border=0>
    <tr><td width=100% height=121px>
	<table width=100% height=121px cellspacing=0 cellpadding=0 valign=top align=left border=0>
	 <td width=60px background="<? OUT(SK_DIR) ?>/img/top_um.gif" valign=middle>
         <img width=60px src="<? OUT(SK_DIR) ?>/billing/state_img.php">
         </td>
         <td width=398px background="<? OUT(SK_DIR) ?>/img/top_um.gif"><img src="<? OUT(SK_DIR) ?>/img/top_left.gif"></td>
         <td background="<? OUT(SK_DIR) ?>/img/top_um.gif" width=100%></td>
	 <td width=375px><img src="<? OUT(SK_DIR) ?>/img/top_right.gif"></td>
	</table>
    </td></tr>
    <tr><td width=100% height=100%>



  	<table width=100% height=100% border=0px cellspacing=0 cellpadding=0>
	<td width=100% height=100% align=center>

	<table height=40px><td></td></table>
	<table width=97% height=80% cellspacing=0 cellpadding=0 border=0px align=center>
	<tr>
          <td width=100% height=72px>
	  <table width=100% height=72px cellspacing=0 cellpadding=0 border=0px>
	  <td width=14px height=100%>
 	    <table width=100% height=100% cellspacing=0 cellpadding=0 border=0px>
	    <tr><td width=16px height=17px><img src="<? OUT(SK_DIR) ?>/img/content_luc.gif"></td></tr>
	    <tr><td width=100%>
		<table cellspacing=0 cellpadding=0 width=14px height=100% width=100%>
		  <td width=14px height=100% background="<? OUT(SK_DIR) ?>/img/content_lm.gif"><table cellspacing=0 cellpadding=0 width=14px><td></td></table></td>
	          <td width=100% bgcolor=#F0F6F8></td>
		</table>
	    </table>
	  </td>	
  	  <td width=100% height=72px>
	    <table width=100% height=72px cellspacing=0 cellpadding=0 border=0px>
	    <tr><td width=100% height=17px background="<? OUT(SK_DIR) ?>/img/content_um.gif"></td></tr>
	    <tr><td bgcolor=#F0F6F8 width=100% height=55px align=center>
	    
	    </td></tr>
  	    </table></td>
	  <td><img src="<? OUT(SK_DIR) ?>/img/content_ruc.gif"></td>
	  </table>
	</tr><tr>
	<td width=100% height=100%>
 	    <table width=100% height=100% cellspacing=0 cellpadding=0 border=0px>
	    <td width=14px background="<? OUT(SK_DIR) ?>/img/content_lm.gif" height=100%><table cellspacing=0 cellpadding=0 width=14px><td></td></table></td>
	    <td width=20px><table width=20px><td></td></table></td>
	    <td width=100% bgcolor=#F0F6F8 valign=top>


<!-- STARTOF CONTENT -->
<?php
$MDL->LoadModule($p,false);
?>
<!-- ENDOF CONTENT -->


	    </td>
	    <td width=20px><table width=20px><td></td></table></td>
	    <td width=62px background="<? OUT(SK_DIR) ?>/img/content_rm.gif" height=100%><table cellspacing=0 cellpadding=0 width=62px><td></td></table></td>
	    </table>
   	</td></tr>
	<tr>
	<td width=100% height=10px>
 	    <table width=100% height=100% cellspacing=0 cellpadding=0 border=0px>
	    <td width=14px background="<? OUT(SK_DIR) ?>/img/content_lm.gif" height=100%><table cellspacing=0 cellpadding=0 width=14px><td></td></table></td>
	    <td width=100% bgcolor=#F0F6F8 align=center>
 

	    </td>
	    <td width=20px><table width=10px><td></td></table></td>
	    <td width=62px background="<? OUT(SK_DIR) ?>/img/content_rm.gif" height=100%><table cellspacing=0 cellpadding=0 width=62px><td></td></table></td>
	    </table>
	</td></tr>
	<tr>
	<td width=100% height=65px>
 	    <table width=100% height=100% cellspacing=0 cellpadding=0 border=0px>
	    <td width=66px height=100%><img src="<? OUT(SK_DIR) ?>/img/content_ldc.gif"></td>
	    <td width=100% background="<? OUT(SK_DIR) ?>/img/content_dm.gif"></td>
	    <td width=64px><img src="<? OUT(SK_DIR) ?>/img/content_rdc.gif"></td>
	    </table>
   	</td></tr> 
          </table>
	  
	   <div align=center><small>© SMStudio, 2006</small></div>

	</td>
	<td width=100%>
	  <table width=100% height=100% cellspacing=0 cellpadding=0 border=0px>
	  <tr><td width=100% height=53px>
	   <table width=228px height=100% cellspacing=0 cellpadding=0 valign=top align=left border=0>
	   <td width=88px height=53px><img src="<? OUT(SK_DIR) ?>/img/right_luc.gif"></td>
	   <td bgcolor=#F0F6F8 width=149px></td>
	   </table>	
	  </tr></td>
 	  <tr><td width=100% height=100%>
	   <table width=228px height=100% cellspacing=0 cellpadding=0 valign=top align=left border=0>
	    <td width=45px height=100% background="<? OUT(SK_DIR) ?>/img/right_lm.gif"></td>
	    <td bgcolor=#F0F6F8 width=183px valign=top>


<!-- STARTOF BARS -->

	   <table width=178px cellspacing=0 cellpadding=0 class=tblbar>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/bar_title.gif">
	     <table width=178px height=32px><td align=center valign=bottom width=100% height=100%>
		<font class=bartitle>
		Пользователи	
		</font>
	     </td></table>
	    </td></tr>
	    <tr><td width=100%>
             <? $MDL->LoadModule('users',true);   ?>
	    </td></tr>
	   </table><br>

	   <table width=178px cellspacing=0 cellpadding=0 class=tblbar>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/bar_title.gif">
	     <table width=178px height=32px><td align=center valign=bottom width=100% height=100%>
		<font class=bartitle>
                Новости
                </font>
	     </td></table>
	    </td></tr>
	    <tr><td width=100% class=tblbar>
             <? $MDL->LoadModule('news',true);   ?>
	    </td></tr>
	   </table><br>
	      
	   <table width=178px cellspacing=0 cellpadding=0 class=tblbar>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/bar_title.gif">
	     <table width=178px height=32px><td align=center valign=bottom width=100% height=100%>
		<font class=bartitle>
                Последние статьи
                </font>
	     </td></table>
	    </td></tr>
	    <tr><td width=100%>
	   <? $MDL->LoadModule('articles',true);   ?>
	    </td></tr>
	   </table><br>

	   <table width=178px cellspacing=0 cellpadding=0 class=tblbar>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/bar_title.gif">
	     <table width=178px height=32px><td align=center valign=bottom width=100% height=100%>
		<font class=bartitle>
                Объявления      
                </font>
	     </td></table>
	    </td></tr>
	    <tr><td width=100%>
             <? $MDL->LoadModule('annoucements',true);   ?>

	    </td></tr>
	   </table><br>
	   
	   
	   <table width=178px cellspacing=0 cellpadding=0 class=tblbar>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/bar_title.gif">
	     <table width=178px height=32px><td align=center valign=bottom width=100% height=100%>
		<font class=bartitle>
                Состояние     
                </font>
	     </td></table>
	    </td></tr>
	    <tr><td width=100%>
             <? $MDL->LoadModule('smadbis',true);   ?>

	    </td></tr>
	   </table><br>	   



<!-- ENDOF BARS -->
	


	    </td>
	   </table>
	  </td></tr>
 	  <tr><td width=100% height=100%>
	   <table width=228px height=100% cellspacing=0 cellpadding=0 valign=top align=left border=0>
	    <td width=46px height=100%><img src="<? OUT(SK_DIR) ?>/img/right_ldc.gif"></td>
	    <td bgcolor=#F0F6F8 width=100% background="<? OUT(SK_DIR) ?>/img/right_dm.gif"></td>
	   </table>
	  </td></tr>
	  </table>
	</td>
	</table>
	
    </td></tr>
   </table>  
  </td>
  </table>
<div align=center>
<SMALL style="font-size:9px;">Page generated by <? $timer->stop();OUT($timer->elapsed()); ?> ms.<br>
</div>  
</body>
</html>