<?php

$top_id=0;
while($all_themes[$top_id]!=$topic)$top_id++;
$ok=true;
$usr_id=-1;
$reg=0;
for($r=0;$r<count($all_users);$r++)
		{
		if(($users_info['login'][$r]==$all_messages[$top_id]['nick'][$mes])&&($all_users[$r]==$all_messages[$top_id]['user_id'][$mes])){$reg=1;$usr_id=$r;}
		}

include $file_read_forums;
if(($user_rights>4)&&($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login']))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($i=0;$i<count($admins_f);$i++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$i])$okk=true;
		}
	if($okk==false)$ok=false;
	}

//if(($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])||($all_messages[$top_id]['user_id'][$mes]!=$all_users[$usr_id])||(($user_rights<=$users_info['rights'][$usr_id])&&($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])))$ok=false;
if((($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])||($all_messages[$top_id]['user_id'][$mes]!=$all_users[$usr_id]))&&($user_rights<5))$ok=false;
if($reg==1) if(($user_rights<=$users_info['rights'][$usr_id])&&($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])) $ok=false;

if($ok!=false)
	{
	$reg=0;
	for($r=0;$r<count($all_users);$r++)
		{
		if($users_info['login'][$r]==$all_messages[$top_id]['nick'][$mes])$reg=1;
		}
	if($reg==1)
	{
	$user_id=0;
	while($users_info['login'][$user_id]!=$all_messages[$top_id]['nick'][$mes])$user_id++;
	}
	if($act=="delete")
		{
		$error=false;
		if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"w")))
			{
			echo("<center>Ошибка ME01! Невозможно удалить сообщение</center>");	
			$error=true;
			}
			else
			{
			for($i=0;$i<$themes_data[$top_id]['count'];$i++)
				{
				$zapis=$all_messages[$top_id]['nick'][$i]."$smb".$all_messages[$top_id]['email'][$i]."$smb".$all_messages[$top_id]['url'][$i]."$smb".$all_messages[$top_id]['date'][$i]."$smb".$all_messages[$top_id]['time'][$i]."$smb".$all_messages[$top_id]['text'][$i]."$smb".$all_messages[$top_id]['user_id'][$i]."$smb1";
				if($i!=$mes)fwrite($fp,$zapis);
				}
			fclose($fp);
			}
		if ($error==false)		
			{
			echo("<center>".$language['delete_success']."</center>");
			}
		}
		else
		{
		echo("<center>".$language['delete_confirm']."
		<form action=?p=$p&forum_ext=$forum_ext&id=delete&topic=$topic&mes=$mes&page=$page&act=delete method=post><table><td><table><td>
		".$language['delete_date']."<td>".$all_messages[$top_id]['date'][$mes]."<tr><td>");
		if($reg==1)echo("".$language['delete_nick']."<td>".$users_info['nick'][$user_id]."<tr><td>");
		else echo("".$language['delete_nick']."<td>".$all_messages[$top_id]['nick'][$mes]."<tr><td>");
		echo("".$language['delete_email']."<td>".$all_messages[$top_id]['email'][$mes]."<tr><td>
		".$language['delete_url']."<td>".$all_messages[$top_id]['url'][$mes]."<tr><td>
		".$language['delete_message']."<td>".$all_messages[$top_id]['text'][$mes]."<tr><td>
		</table><tr><td>");
		echo("<center><input type=submit value=\"".$language['delete_delete']."\"></table></form><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['delete_back']."</a></center>");
		}
	}
	else
	{
	echo("<center><b>FORBIDDEN!</b></centeR>");
	}
	
?>
