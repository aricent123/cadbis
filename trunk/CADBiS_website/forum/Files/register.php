<?php

$inf1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($inf1)))));
$sig1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($sig1)))));
$inf1=str_replace("<br />","\n",$inf1);
$sig1=str_replace("<br />","\n",$sig1);
$log1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($log1)))));
$pas1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($pas1)))));
$nic1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($nic1)))));
$cit1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($cit1)))));
$ema1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($ema1)))));
$url1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url1)))));
?>

<FORM action="?p=$p&forum_ext=$forum_ext&id=reg" method=post>
<table align=center><td><table>
<td><?php echo("".$language['register_login'].""); ?><td><input type=text name=login maxlength="<?php w($vars['login_len']); ?>" value="<?php w($log1); ?>" style="width:300;"><tr>
<td><?php echo("".$language['register_nick'].""); ?><td><input type=text name=nick maxlength="<?php w($vars['nick_len']); ?>"  value="<?php w($nic1); ?>" style="width:300;" ><tr>
<td><?php echo("".$language['register_password'].""); ?><td><input type=text name=password maxlength="<?php w($vars['pass_len']); ?>"  value="<?php w($pas1); ?>" style="width:300;"><tr>
<td><?php echo("".$language['register_city'].""); ?><td><input type=text name=city maxlength="<?php w($vars['city_len']); ?>"  value="<?php w($cit1); ?>" style="width:300;"><tr>
<td><?php echo("".$language['register_email'].""); ?><td><input type=text name=email maxlength="<?php w($vars['email_len']); ?>"  value="<?php w($ema1); ?>" style="width:300;"><tr>
<td><?php echo("".$language['register_url'].""); ?><td><input type=text value="
<?php
if($url1!="")echo($url1); else echo("http://");
echo("\" name=url style=\"width:300;\" maxlength=\"".$vars['url_len']."\" ><tr>");
?>
<td><?php echo("".$language['register_sex'].""); ?><td><table><td>
<input type=radio name=sex value="<?php echo(""."мужской".""); ?>" 
<?php if ($sex1!="женский") w("checked"); ?> >
<td><?php echo("".$language['sex_man'].""); ?><tr><td><input type=radio name=sex value="<?php echo(""."женский".""); ?>" <?php if ($sex1=="женский") w("checked"); ?> >
<td><?php echo("".$language['sex_woman'].""); ?></table><tr>
<td><?php echo("".$language['register_sign'].""); ?><td><textarea name=sign maxlength="<?php w($vars['sign_len']); ?>"   style="width:300; height=50"><?php w($sig1); ?></textarea><tr>
<td><?php echo("".$language['register_info'].""); ?><td><textarea name=info maxlength="<?php w($vars['info_len']); ?>"  style="width:300; height=50"><?php w($inf1); ?></textarea></table>
<tr><td><center><input type=submit value="<?php echo("".$language['register_butreg'].""); ?>"></center></table></form>

<?php



?>