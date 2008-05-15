<?php

$i=0;
while($all_themes[$i]!="$topic")
{
$i++;
}
if(($CURRENT_USER["id"]!=$themes_data[$i]['nick'])&&($user_rights<5))exit;
$usr_id=0;
for($g=0;$g<count($all_users);$g++)
	{
	if($users_info['login'][$g]==$themes_data[$i]['nick'])
		{
		$usr_id=$g;
		$reg=1;
		}
	}
if($reg==1)
	{
	$mes_user_rights=$users_info['rights'][$usr_id];
	}
	else
	{
	$mes_user_rights=-1;
	}
if($page==0)$page=1;
$error=true;
if(($themes_data[$i]['nick']==$CURRENT_USER["id"])||($user_rights>4))
	{
	if(($user_rights>$mes_user_rights)||($themes_data[$i]['nick']==$CURRENT_USER["id"]))
	{
	$error=false;
	}
	}

if($page==0)$page=1;

include $file_read_forums;
if(($user_rights>4)&&($themes_data[$i]['nick']!=$CURRENT_USER["id"]))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($m=0;$m<count($admins_f);$m++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
		}
	if($okk==false)$error=true;
	}

if($error==false)
{
//echo($button);
if($button=="".$language['addvoting_activate'].""){$act="add";}
elseif($button=="+ ".$language['addvoting_variant']){if($count<50)$count=$count+1;}
elseif($button=="- ".$language['addvoting_variant']){if($count>1)$count=$count-1;}
if($act=="add")
	{
	echo("<CENTER>");
	$title=htmlspecialchars($title);
	$title=str_replace("$smb","",$title);
	$title=str_replace("$smb1","",$title);
	$title=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($title)))));
		if($vars['mat_filter']=="true")
			{
			include "$file_mat_filter";
			$title=str_replace($unsymb,$vars['mat_filter_word'],$title);
			}
	for($s=0;$s<count($variant);$s++)
		{
		$variant[$s]=htmlspecialchars($variant[$s]);
		$variant[$s]=str_replace("$smb","",$variant[$s]);
		$variant[$s]=str_replace("$smb1","",$variant[$s]);
		$variant[$s]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($variant[$s])))));
		$variant[$s]=str_replace($unsymb,$vars['mat_filter_word'],$variant[$s]);
		}
		
	
	$file2=$vars['dir_voting']."/".$topic.".".$vars['voting_ext'];
	if(!($fp=fopen($file2,"w+"))){echo("<CENTER>Error AV01! Невозможно добавить голосование!</CENTER>");}
		else
		{
		$zapis="";
		$zapis=$title.$smb1;
		for($d=0;$d<count($variant);$d++)
			{
			$zapis.=$variant[$d].$smb."0";
			if($d<count($variant)-1)$zapis.=$smb; 
			}
		$zapis.=$smb1;
		if(!(fwrite($fp,$zapis))){echo("<CENTER>Error AV02! Невозможно добавить голосование!</CENTER>");}
			else
			{
			fclose($fp);
			echo("".$language['addvoting_success']."");
			}
		}
	echo("<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['addvoting_back']."</a></CENTER>");
	}
	else
	{
	if($count==0)$count=1;
	echo("<CENTER>".$language['addvoting_addvoting']." \"".$themes_data[$i]['title']."\" ?<table cellspacing=0 cellpadding=0><td>
	<table><td>
	<form action=\"?p=$p\" method=post>
	<input type=hidden name=forum_ext value=\"$forum_ext\">
	<input type=hidden name=id value=add_voting>
	<input type=hidden name=topic value=$topic>
	<input type=hidden name=page value=$page>
	<input type=hidden name=count value=$count>
	".$language['addvoting_question']."<td><input type=text name=title value=\"$title\" style=\"width:300\"><tr><td align=center>");
	for($s=0;$s<$count;$s++)
		{
		echo("".$language['addvoting_variantn']."$s:<td><input type=text name=variant[] value=\"".$variant[$s]."\" style=\"width:300\"><tr><td align=center>");
		}
	echo("</table><tr><td align=center><input type=submit name=button value=\"".$language['addvoting_activate']."\">");
	echo("<tr><td align=center>");
	$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		echo("
		<input type=submit value=\"+ ".$language['addvoting_variant']."\" name=button>
		<input type=submit value=\"- ".$language['addvoting_variant']."\" name=button>
		</FORM>");

		echo("
	</form></table>
	<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['addvoting_back']."</a></CENTER>");

	}
	}
?>