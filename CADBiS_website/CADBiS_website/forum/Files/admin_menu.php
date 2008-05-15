<?php
echo("
<table width=100%>


<td width=100%>

$table_all  $td_all <center>
<b><u>".$language['version_2.0'][39]."</u></b>
<tr> $td_all  <center>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin1\">".$language['admin_menu01']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2\">".$language['admin_menu02']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin3\">".$language['admin_menu03']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=statistic\">".$language['version_2.0'][53]."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9\">".$language['admin_menu11']."</a>
</table>

<tr><td width=100%>

$table_all  $td_all <center>
<b><u>".$language['version_2.0'][40]."</u></b>
<tr> $td_all <center>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4\">".$language['admin_menu04']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin5\">".$language['admin_menu05']."</a>
</table>

<tr><td width=100%>

$table_all  $td_all <center>
<b><u>".$language['version_2.0'][41]."</u></b>
<tr> $td_all  <center>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6\">".$language['admin_menu06']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs\">".$language['admin_menu07']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=auth_logs\">".$language['admin_menu08']."
</table>

<Tr><td width=100%>

$table_all $td_all <center>
<b><u>".$language['version_2.0'][42]."</u></b>
<tr> $td_all  <center>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin7\">".$language['admin_menu09']."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin8\">".$language['admin_menu10']."</a>
</table>

<tr><td width=100%>

$table_all  $td_all <center>
<b><u>".$language['version_2.0'][43]."</u></b>
<tr> $td_all  <center>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10\">".$language['version_1.6'][5]."</a><br>
<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin11\">".$language['version_1.6'][7]."</a>
</table>

<Tr><td width=100%>

$table_all $td_all <center>
<b><u>".$language['version_2.0'][44]."</u></b>
<tr> $td_all  <center>");
if($user_rights>7)echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12\">".$language['version_1.7'][10]."</a>");
echo("</table></table>");

?>
 
