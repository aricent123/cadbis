<?php
$i=0;
while($all_themes[$i]!="$topic"){
$i++;
}
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

if(($themes_data[$i]['nick']==$HTTP_SESSION_VARS['forum_login'])||($user_rights>4))
	{
	if(($user_rights>$mes_user_rights)||($themes_data[$i]['nick']==$CURRENT_USER["id"]))
	{
	$error=false;
	}
	}
if(($themes_data[$i]['count']>0)&&($user_rights<5))
{
echo("<CENTER>".$language['topic_delete_cant']."</CENTER>");
$error=true;
}

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
if($act=="delete_page")
	{
	echo("<centeR>");
	if($sure=="yes")
		{
		$reg=0;
		for($r=0;$r<count($all_users);$r++)
			{
			if($users_info['login'][$r]==$all_messages[$i]['nick'][$mes])$reg=1;
			}
		if($reg==1)
		{
		$user_id=0;
		while($users_info['login'][$user_id]!=$all_messages[$i]['nick'][$mes])$user_id++;
		}
		$error=false;
		if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"w")))
			{
			echo("<center>Ошибка ME01! Невозможно удалить сообщение</center>");	
			$error=true;
			}
			else
			{
			//echo($del_to." / ".$themes_data[$f]['count']);
			for($f=0;$f<$del_from;$f++)
				{
				if($all_messages[$i]['nick'][$f]!="")
					{
					//echo($zapis."[$f]<hr>");
					$zapis=$all_messages[$i]['nick'][$f]."$smb".$all_messages[$i]['email'][$f]."$smb".$all_messages[$i]['url'][$f]."$smb".$all_messages[$i]['date'][$f]."$smb".$all_messages[$i]['time'][$f]."$smb".$all_messages[$i]['text'][$f]."$smb".$all_messages[$i]['user_id'][$f]."$smb1";
					fwrite($fp,$zapis);
					}
				}
			for($f=$del_from;$f<$del_to+1;$f++)
				{
				$usr_id=-1;
				for($r=0;$r<count($all_users);$r++)
					{
					if($all_messages[$i]['nick'][$f]==$users_info['login'][$r]){$usr_id=$r; break;}
					}
				if(($user_rights<=$users_info['rights'][$usr_id])&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login']))
					{
					$zapis=$all_messages[$i]['nick'][$f]."$smb".$all_messages[$i]['email'][$f]."$smb".$all_messages[$i]['url'][$f]."$smb".$all_messages[$i]['date'][$f]."$smb".$all_messages[$i]['time'][$f]."$smb".$all_messages[$i]['text'][$f]."$smb".$all_messages[$i]['user_id'][$f]."$smb1";
					fwrite($fp,$zapis);
					}
				}
						
			for($f=$del_to+1;$f<$themes_data[$i]['count'];$f++)
				{
				//echo($zapis."[$f]<hr>");
				if($all_messages[$i]['nick'][$f]!="")
					{
					$zapis=$all_messages[$i]['nick'][$f]."$smb".$all_messages[$i]['email'][$f]."$smb".$all_messages[$i]['url'][$f]."$smb".$all_messages[$i]['date'][$f]."$smb".$all_messages[$i]['time'][$f]."$smb".$all_messages[$i]['text'][$f]."$smb".$all_messages[$i]['user_id'][$f]."$smb1";
					fwrite($fp,$zapis);	
					}
				}
			fclose($fp);
			}
		if ($error==false)		
			{
			echo("<center>".$language['version_2.0'][3]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['version_2.0'][8]."</a></center>");
			}
		}
		else
		{
		$to=($page)*($vars['kvo'])-1;
		if(($page)*($vars['kvo'])-1>$themes_data[$i]['count'])$to=$themes_data[$i]['count']-1;
		$from=($page-1)*($vars['kvo']);
		echo("<table><td>".$language['version_2.0'][1]." <td>".$themes_data[$i]['title']." ".$language['version_2.0'][2]." $page?</table><br>
		<br><form method=post action=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&act=delete_page&page=$page&sure=yes\">
		".$language['version_2.0'][5]."<input type=text name=del_from value=\"".($from)."\">
		".$language['version_2.0'][6]."<input type=text name=del_to value=\"".($to)."\"><br>
		<input type=submit value=\"".$language['version_2.0'][7]."\"></FORM>
		<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_1.61'][17]."</a>");
		}		
	echo("</centeR>");
	}
elseif($act=="open_topic")
	{
	echo("<centeR>");
	if($sure=="yes")
		{
		$fp=fopen($vars['dir_themes']."/".$topic.".".$vars['theme_ext'],"w+");
		$themes_data[$i]['status']="";
		$zapis=$themes_data[$i]['title']."$smb".$themes_data[$i]['nick']."$smb".$themes_data[$i]['email']."$smb".$themes_data[$i]['url']."$smb".$themes_data[$i]['date']."$smb".$themes_data[$i]['views']."$smb".$themes_data[$i]['descr']."$smb".$themes_data[$i]['rights']."$smb".$themes_data[$i]['namesrights']."$smb".$themes_data[$i]['names']."$smb".$themes_data[$i]['status']."$smb1".$themes_data[$i]['ips'];
		fwrite($fp,$zapis);
		fclose($fp);
		echo($language['version_2.0'][34]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['version_1.61'][19]."</a>");
		}
		else
		{
		echo("<table><td>".$language['version_2.0'][32]."<td> \"".$themes_data[$i]['title']."\"?</table><br>
		<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&act=open_topic&sure=yes\">".$language['version_2.0'][33]."</a><br>
		<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_1.61'][17]."</a>");
		}		
	echo("</centeR>");

	}
elseif($act=="close_topic")
	{
	echo("<centeR>");
	if($sure=="yes")
		{
		$fp=fopen($vars['dir_themes']."/".$topic.".".$vars['theme_ext'],"w+");
		$themes_data[$i]['status']="closed";
		$zapis=$themes_data[$i]['title']."$smb".$themes_data[$i]['nick']."$smb".$themes_data[$i]['email']."$smb".$themes_data[$i]['url']."$smb".$themes_data[$i]['date']."$smb".$themes_data[$i]['views']."$smb".$themes_data[$i]['descr']."$smb".$themes_data[$i]['rights']."$smb".$themes_data[$i]['namesrights']."$smb".$themes_data[$i]['names']."$smb".$themes_data[$i]['status']."$smb1".$themes_data[$i]['ips'];
		fwrite($fp,$zapis);
		fclose($fp);
		echo($language['version_2.0'][30]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['version_1.61'][19]."</a>");
		}
		else
		{
		echo("<table><td>".$language['version_2.0'][28]."<td> \"".$themes_data[$i]['title']."\"?</table><br>
		<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&act=close_topic&sure=yes\">".$language['version_2.0'][29]."</a><br>
		<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_1.61'][17]."</a>");
		}		
	echo("</centeR>");

	}
elseif($act=="cleanup")
	{
	echo("<centeR>");
	if($sure=="yes")
		{
		$fp=fopen($vars['dir_themes']."/".$topic.".".$vars['mess_ext'],"w+");
			for($f=0;$f<$themes_data[$i]['count'];$f++)
				{
				$usr_id=-1;
				for($r=0;$r<count($all_users);$r++)
					{
					if($all_messages[$i]['nick'][$f]==$users_info['login'][$r]){$usr_id=$r; break;}
					}
				if(($user_rights<=$users_info['rights'][$usr_id])&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login']))
					{
					$zapis=$all_messages[$i]['nick'][$f]."$smb".$all_messages[$i]['email'][$f]."$smb".$all_messages[$i]['url'][$f]."$smb".$all_messages[$i]['date'][$f]."$smb".$all_messages[$i]['time'][$f]."$smb".$all_messages[$i]['text'][$f]."$smb".$all_messages[$i]['user_id'][$f]."$smb1";
					fwrite($fp,$zapis);
					}
				}
		fclose($fp);
		echo($language['version_1.61'][18]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['version_1.61'][19]."</a>");
		}
		else
		{
		echo("<table><td>".$language['version_1.61'][15]."<td>".$themes_data[$i]['title']."</table><br>
		".$language['version_1.61'][14]."<br><a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&act=cleanup&sure=yes\">".$language['version_1.61'][16]."</a><br>
		<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_1.61'][17]."</a>");
		}		
	echo("</centeR>");

	}
elseif($act=="delete")
	{
	if(!unlink($vars['dir_themes']."/".$topic.".".$vars['theme_ext']))
		{
		echo("<CENTER>Error TD01! Невозможно удалить тему...</CENTER>");
		}
		else
		{
		if(!unlink($vars['dir_themes']."/".$topic.".".$vars['mess_ext']))
			{
			echo("<CENTER>Error TD02! Ошибка удаления темы...</CENTER>");
			}
			else
			{
			$file2=$vars['dir_voting']."/".$topic.".".$vars['voting_ext'];
			if(file_exists($file2)==true)
				{
				unlink($file2);
				}
			echo("<CENTER>".$language['topic_delete_success']."</CENTER>");
			}
		}
	}
	else
	{
	$reg=0;
	$usr_id=0;
	for($g=0;$g<count($all_users);$g++)
		{
		if($users_info['login'][$g]==$themes_data[$i]['nick'])
			{
			$usr_id=$g;
			$reg=1;
			}
		}
	echo("<CENTER>".$language['topic_delete_confirm']." \"".$themes_data[$i]['title']."\" ?<table><td>
	".$language['topic_delete_author']."<td>".$users_info['nick'][$usr_id]."<tr><td>");
	if ($themes_data[$i]['email']!="")
		{
		echo("".$language['topic_delete_email']."<td><a href=\"mailto:".$themes_data[$i]['email']."\">".$themes_data[$i]['email']."</a><tr><td>");
		}
	if ($themes_data[$i]['url']!="")
		{
		echo("".$language['topic_delete_url']."<td><a href=\"".$themes_data[$i]['url']."\">".$themes_data[$i]['url']."</a>");
		}
	echo("</table>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&act=delete\" method=post>
	<input type=submit value=\"".$language['topic_delete_delbut']."\"></FORM>
	<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">BACK</a></CENTER>");

	}
}
?>