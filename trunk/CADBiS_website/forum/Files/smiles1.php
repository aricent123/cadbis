<?php 
$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Gecko")==false)&&(strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
{
$user_browser="Internet Explorer";
}
elseif((strstr($Browser,"Gecko")==true)&&(strstr($Browser,"Netscape")==false))
{
$user_browser="Mozilla";
}
elseif((strstr($Browser,"Netscape")==true)&&(strstr($Browser,"Gecko")==false))
{
$user_browser="Netscape";
}
elseif(strstr($Browser,"Opera")==true)
{
$user_browser="Opera";
}
elseif((strstr($Browser,"Netscape")==true)&&(strstr($Browser,"Gecko")==true))
{
$user_browser="Netscape/Mozilla";
}
echo("<TITLE>Смайлы!</TITLE>");

if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Gecko")==false)&&(strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
{
//include "vars.php";


for($i=$vars['smls_count']+1;$i<=$vars['smiles_count'];$i++)
	{
	echo("
	<a href=\"JavaScript:window.opener.do_text2('".$smiles[$i]['code']."');\"><img border=0 src='".$vars['dir_smiles']."/".$smiles[$i]['img']."' style='position:relative;top:2px;cursor:hand;'></a>
	");
	}
}
else
{
echo("<font size=2>
<SMALL>Для вашего браузера ($user_browser) JavaScript отключён...</SMALL><Br>
</table><CENTER><table><td><CENTER><SMALL><font size=2>
");
echo("<br>Чтобы использовать смайлы пишите следующее:<br><table><td><table><Td><SMALL><font size=2>");
for($i=$vars['smls_count']+1;$i<=$vars['smiles_count'];$i++)
	{echo("
	".$smiles[$i]['code']."<td><img src='".$vars['dir_smiles']."/".$smiles[$i]['img']."'><td><SMALL><font size=2>
	");
	if($i/7==round($i/7))echo("</table><tr><td><table><td><SMALL><font size=2>");
	}
	echo("</table></table>");
}
?>
