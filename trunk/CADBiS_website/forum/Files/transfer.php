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


	if(($act=="transfer")&&($all_messages[$top_id]['nick'][$mes]!=""))
		{
		
		$buf=explode("$smb",$topic_to);
		$topic_to=$buf[0];
		$forum_to=$buf[1];
		$vars['theme_ext']=$forum_to;
		$okk=true;
		if($forum_to!=$forum_ext)
			{
			$forum_id1=0;
			while($forums['ext'][$forum_id1]!=$forum_to)$forum_id1++;
			$error=false;
			$admins_f=explode(",",$forums['admins'][$forum_id1]);
			$usr_id1=-1;
			$okk=false;
				for($m=0;$m<count($admins_f);$m++)
					{
					if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
					}
					if($okk==false)$error=true;
			if(($user_rights>=$forums['rights'][$forum_id1])&&($error!=true))
			{
			}
			else
			{
			$okk=false;
			}
			$_file=file($vars['dir_themes']."/".$topic_to.".".$forum_to);
			$_file=implode("",$_file);
			//echo($_file);
			$file_=explode("$smb1",$_file);
			$_file=explode("$smb",$file_[0]);
			/*
			$topic_to_info['names']=$_file[9];
			$topic_to_info['namesrights']=$_file[8];
			$topic_to_info['rights']=$_file[7];	
			*/
			//echo("names=".$_file[9]."namesrights=".$_file[8]."rights=".$_file[7]);
			if($user_rights<$_file[7])$okk=false;
			if($_file[9]!="")
					{
					$names=explode(",",$_file[9]);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<$_file[8]))
						{
						$okk=false;
						}
					}

			}
			else
			{

			$top_id1=0;


			while($all_themes[$top_id1]!=$topic_to)$top_id1++;
			$okk=true;
				if($user_rights<$themes_data[$top_id1]['rights'])$okk=false;
				if($themes_data[$top_id1]['names']!="")
					{
					$names=explode(",",$themes_data[$top_id1]['names']);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<$themes_data[$top_id1]['namesrights']))
						{
						$okk=false;
						}
					}
			}
			if($okk==true)
			{
			$error=false;
			if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"w")))
				{
				echo("<center>Ошибка MT01! Невозможно удалить сообщение</center>");	
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
	
		if($forum_to==$forum_ext)
			{			
			if(!($fp=fopen($vars['dir_themes']."/"."$topic_to".".".$vars['mess_ext'],"w")))
				{
				echo("<center>Ошибка MT02! Невозможно переместить сообщение</center>");	
				$error=true;
				}
			for($i=0;$i<$themes_data[$top_id1]['count'];$i++)
				{
				$zapis=$all_messages[$top_id1]['nick'][$i]."$smb".$all_messages[$top_id1]['email'][$i]."$smb".$all_messages[$top_id1]['url'][$i]."$smb".$all_messages[$top_id1]['date'][$i]."$smb".$all_messages[$top_id1]['time'][$i]."$smb".$all_messages[$top_id1]['text'][$i]."$smb".$all_messages[$top_id1]['user_id'][$i]."$smb1";
				fwrite($fp,$zapis);
				}
			$all_messages[$top_id]['text'][$mes].=$language['version_1.61'][11].$themes_data[$top_id]['title'].$language['version_1.61'][12];
			$all_messages[$top_id]['time'][$mes]=time();
			$zapis=$all_messages[$top_id]['nick'][$mes]."$smb".$all_messages[$top_id]['email'][$mes]."$smb".$all_messages[$top_id]['url'][$mes]."$smb".$all_messages[$top_id]['date'][$mes]."$smb".$all_messages[$top_id]['time'][$mes]."$smb".$all_messages[$top_id]['text'][$mes]."$smb".$all_messages[$top_id]['user_id'][$mes]."$smb1";
			fwrite($fp,$zapis);
			fclose($fp);
			}
			else
			{
			if(!($fp=fopen($vars['dir_themes']."/"."$topic_to".".".$vars['mess_ext'],"a+")))
				{
				echo("<center>Ошибка MT02! Невозможно переместить сообщение</center>");	
				$error=true;
				}
			$all_messages[$top_id]['text'][$mes].=$language['version_1.61'][11].$themes_data[$top_id]['title'].$language['version_1.61'][12];
			$all_messages[$top_id]['time'][$mes]=time();
			$zapis=$all_messages[$top_id]['nick'][$mes]."$smb".$all_messages[$top_id]['email'][$mes]."$smb".$all_messages[$top_id]['url'][$mes]."$smb".$all_messages[$top_id]['date'][$mes]."$smb".$all_messages[$top_id]['time'][$mes]."$smb".$all_messages[$top_id]['text'][$mes]."$smb".$all_messages[$top_id]['user_id'][$mes]."$smb1";
			fwrite($fp,$zapis);
			fclose($fp);
			}			
			if ($error==false)		
				{
				echo("<center>".$language['version_1.61'][8]."</center>");
				}	
		if($forum_to==$forum_ext)
			{	
			for($j=0;$j<$themes_data[$top_id1]['count'];$j=$j+$vars['kvo'])
				{
				$links++;
				}	
				echo("<CENTER><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&page=$page&topic=".$all_themes[$top_id]."\">".$language['version_1.6'][13]."</a>, ");
				echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&page=$links&topic=".$topic_to."#last\">".$language['version_1.61'][13]."</a></CENTER>");
			}
			else
			{
			$_file=file($vars['dir_themes']."/"."$topic_to".".".$vars['mess_ext']);
			$file_=implode("",$_file);
			$_file=explode($smb1,$file_);

				for($j=0;$j<count($_file);$j=$j+$vars['kvo'])
				{
				$links++;
				}	
				echo("<CENTER><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&page=$page&topic=".$all_themes[$top_id]."\">".$language['version_1.6'][13]."</a>, ");
				echo("<a href=\"?p=$p&forum_ext=$forum_to&id=showtopic1&forum_to=$forum_to&page=$links&topic=".$topic_to."#last\">".$language['version_1.61'][13]."</a></CENTER>");
			}
		}
		}
		else
		{
		echo("<center>
		<form action=?p=$p&forum_ext=$forum_ext&id=transfer&topic=$topic&mes=$mes&page=$page&act=transfer method=post><table><td><table><td>
		".$language['version_1.61'][9]."<td>".$themes_data[$top_id]['title']."<tr><td>");
		echo("".$language['version_1.61'][7]."<td>".$all_messages[$top_id]['date'][$mes]."<tr><td>");
		if($reg==1)echo("".$language['version_1.61'][5]."<td>".$users_info['nick'][$user_id]."<tr><td>");
		else echo("".$language['version_1.61'][5]."<td>".$all_messages[$top_id]['nick'][$mes]."<tr><td>");
		echo("".$language['version_1.61'][6]."<td>".$all_messages[$top_id]['text'][$mes]."<tr><td>");
		$all_themes1['title']=array();
		$all_themes1['ext']=array();
		$all_themes1['name']=array();
		$all_themes1['names']=array();
		$all_themes1['forum']=array();
		$all_themes1['rights']=array();
		$all_themes1['namesrights']=array();
		$hdl=opendir($vars['dir_themes']);
		while($file=readdir($hdl))
			{
			for($j=0;$j<count($forums['title']);$j++)
				{
				if(strstr($file,".".$forums['ext'][$j])==true)
					{
					$error=false;
						$admins_f=explode(",",$forums['admins'][$j]);
						$usr_id1=-1;
						$okk=false;
						for($m=0;$m<count($admins_f);$m++)
							{
							if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
							}
						if($okk==false)$error=true;

						if(($user_rights>=$forums['rights'][$j])&&($error!=true))
						{
						$_file=file($vars['dir_themes']."/".$file);
						$file_=implode("",$_file);
						$file_=explode("$smb1",$file_);
						$_file=explode("$smb",$file_[0]);
						if($user_rights>=$_file[7])
							{
							$all_themes1['title'][]=$_file[0];	
							$all_themes1['ext'][]=$forums['ext'][$j];
							$all_themes1['name'][]=substr($file,0,-strlen(".".$forums['ext'][$j]));
							$all_themes1['names'][]=$_file[9];
							$all_themes1['namesrights'][]=$_file[8];
							$all_themes1['rights'][]=$_file[7];
							$all_themes1['forum'][]=$forums['title'][$j];
							}
						}
					}
				}
			}
		echo("".$language['version_1.61'][4]."<td><select name=topic_to>");
		for($i=0;$i<count($all_themes1['title']);$i++)
			{
			$okk=true;
			if($user_rights<$themes_data[$i]['rights'])$okk=false;
			if($all_themes1['names'][$i]!="")
				{
				//echo("\n topic: ".$all_themes1['title'][$i]." names:".$all_themes1['names'][$i]."\n");
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
			if($okk==true)echo("<option value=\"".$all_themes1['name'][$i]."$smb".$all_themes1['ext'][$i]."\">".$all_themes1['title'][$i]."(".$all_themes1['forum'][$i].")");
			}

	
		
		echo("
		</table><tr><td>");
		echo("<center><input type=submit value=\"".$language['version_1.61'][10]."\"></table></form><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['delete_back']."</a></center>");
		}
	}
	else
	{
	echo("<center><b>FORBIDDEN!</b></centeR>");
	}
	
?>
