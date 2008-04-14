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

if(($themes_data[$i]['nick']==$CURRENT_USER["id"])||($user_rights>4))
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
if($act=="transfer_page")
	{
	echo("<centeR>");
	if($sure=="yes")
		{
		include $file_read_all_topics_on_forum;
		$buf=explode("$smb",$topic_to);
		$topic_to=$buf[0];
		$forum_to=$buf[1];
		$vars['theme_ext']=$forum_to;

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
			
			$okk=true;
			$top_id1=0;
			while($all_themes1['name'][$top_id1]!=$topic_to)$top_id1++;
				if($all_themes1['names'][$top_id1]!="")
					{
					$names=explode(",",$all_themes1['names'][$top_id1]);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<=$all_themes1['namesrights'][$top_id1]))
						{
						$okk=false;
						}
					}
					
					if($okk==true)
					{
					$fa=fopen($vars['dir_themes']."/"."$topic_to".".".$vars['mess_ext'],"a+");	
					for($f=$del_from;$f<$del_to+1;$f++)
						{
						$okk=true;
						$usr_id=-1;
						for($r=0;$r<count($all_users);$r++)
							{
							if($all_messages[$i]['nick'][$f]==$users_info['login'][$r]){$usr_id=$r; break;}
							}
						if(($user_rights<=$users_info['rights'][$usr_id])&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login']))
							{
							$okk=false;
							}
						
						if($okk==true)
							{
							$all_messages[$i]['text'][$f].=$language['version_1.61'][11].$themes_data[$i]['title'].$language['version_1.61'][12];
							$all_messages[$i]['time'][$f]=time();
							$zapis=$all_messages[$i]['nick'][$f]."$smb".$all_messages[$i]['email'][$f]."$smb".$all_messages[$i]['url'][$f]."$smb".$all_messages[$i]['date'][$f]."$smb".$all_messages[$i]['time'][$f]."$smb".$all_messages[$i]['text'][$f]."$smb".$all_messages[$i]['user_id'][$f]."$smb1";
							fwrite($fa,$zapis);
							}
						}
					}
		fclose($fa);
		
		if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"w")))
			{
			echo("<center>Ошибка ME01! Невозможно удалить сообщение</center>");	
			$error=true;
			}
			else
			{
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
			$_file=file($vars['dir_themes']."/"."$topic_to".".".$vars['mess_ext']);
			$file_=implode("",$_file);
			$_file=explode($smb1,$file_);
				for($j=0;$j<count($_file);$j=$j+$vars['kvo'])
				{
				$links++;
				}
			echo("<center>".$language['version_2.0'][46]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_2.0'][8]."</a>, 
			<a href=\"?p=$p&forum_ext=$forum_to&id=showtopic&topic=$topic_to&page=$links#last\">".$language['editmes'][1]."</a>
			</a></center>");
			
			}
		}
		else
		{
		$to=($page)*($vars['kvo'])-1;
		if(($page)*($vars['kvo'])-1>$themes_data[$i]['count'])$to=$themes_data[$i]['count']-1;
		$from=($page-1)*($vars['kvo']);
		include "$file_read_all_topics_on_forum";
		echo("<br><form method=post action=\"?p=$p&forum_ext=$forum_ext&id=topic_transfer&topic=$topic&act=transfer_page&page=$page&sure=yes\">
		".$language['version_2.0'][47]."<input type=text name=del_from value=\"".($from)."\">
		".$language['version_2.0'][48]."<input type=text name=del_to value=\"".($to)."\"><br>");
		echo("".$language['version_1.61'][4]."<select name=topic_to>
		");
		for($i=0;$i<count($all_themes1['title']);$i++)
			{
			$okk=true;
			if($user_rights<$themes_data[$i]['rights'])$okk=false;
			if($all_themes1['names'][$i]!="")
				{
				$names=explode(",",$all_themes1['names'][$i]);
				$ok=false;
				for($s=0;$s<count($names);$s++)
					{
					if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
					}
				if(($ok==false)&&($user_rights<$all_themes1['namesrights'][$i]))
					{
					$okk=false;
					}
				}
			if(($okk==true)&&($all_themes1['name'][$i]!="$topic"))echo("<option value=\"".$all_themes1['name'][$i]."$smb".$all_themes1['ext'][$i]."\">".$all_themes1['title'][$i]."(".$all_themes1['forum'][$i].")");
			}
		echo("<input type=submit value=\"".$language['version_1.61'][10]."\"></FORM>
		<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['version_1.61'][17]."</a>");
		}		
	echo("</centeR>");
	}
elseif($act=="transfer")
	{
	$h=0;
	while($forums['ext'][$h]!=$forum_to)$h++;
	if($user_rights<$forums['rights'][$h])exit;
	if(!rename($vars['dir_themes']."/".$topic.".".$vars['theme_ext'],$vars['dir_themes']."/".$topic.".".$forum_to))
		{
		echo("<CENTER>Error TT01! Невозможно переместить тему...</CENTER>");
		}
		else
		{
		echo("<CENTER>".$language['version_1.7'][8]."
		<a href=\"?p=$p&forum_ext=$forum_ext&id=set_forum&forum_ex=$forum_to\">".$language['version_1.7'][9]."</a></CENTER>");
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
	echo("<CENTER>".$language['version_1.7'][4]." \"".$themes_data[$i]['title']."\" ?<table><td>
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
	<form action=\"?p=$p&forum_ext=$forum_ext&id=topic_transfer&topic=$topic&act=transfer\" method=post>
	<table><td>".$language['version_1.7'][7].":<td>".$forums['title'][$forum_id]."<tr><td>
	".$language['version_1.7'][5]."<td><select name=forum_to>");
	for($h=0;$h<count($forums['title']);$h++)
		{
		if($user_rights>=$forums['rights'][$h])echo("<option value=\"".$forums['ext'][$h]."\">".$forums['title'][$h]);
		}
	echo("</table><input type=submit value=\"".$language['version_1.7'][3]."\"></FORM>
	<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">НАЗАД</a></CENTER>");

	}
}
?>
