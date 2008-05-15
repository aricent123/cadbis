<?php
$usr_id=0;
while($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login'])$usr_id++;
session_unregister('forum_login');
$HTTP_SESSION_VARS['forum_login']="";
setcookie('forum_login','',time()-3600);
$HTTP_COOKIE_VARS['forum_login']="";
include "$file_read_online";

	for($i=0;$i<$online_cnt;$i++)
		{
		//echo($users_info['login'][$usr_id]."==".$online['login'][$i]);
		if($users_info['login'][$usr_id]==$online['login'][$i])	
			{
			$online['type'][$i]="";
			$online['nick'][$i]="";
			$online['login'][$i]="";
			$online['time'][$i]=0;
			$online['ip'][$i]="";
			//echo("[Совпадение!] На $i");
			}
			
			//echo("<br>");
		}

if(!($fp=fopen($vars['file_online'],"w+"))){echo("<CENTER>Error UO01! Невозможно провести статистику...</CENTER>");}
	{
	$zapis="";
	for($i=0;$i<count($online['type']);$i++)	
		{
		if(($online['type'][$i]=="guest")||($online['type'][$i]=="reg"))
			{
			$zapis.=$online['type'][$i]."$smb".$online['nick'][$i]."$smb".$online['login'][$i]."$smb".$online['time'][$i]."$smb".$online['ip'][$i]."$smb1";
			//echo($zapis."<br>");
			}
		}
	if(!(fwrite($fp,$zapis)))echo("<CENTER>Error UO02! Невозможно провести статистику...</CENTER>");
	fclose($fp);
	}

echo("<meta http-equiv='Refresh' content='0;URL=?p=$p&forum_ext=$forum_ext'>");
?>