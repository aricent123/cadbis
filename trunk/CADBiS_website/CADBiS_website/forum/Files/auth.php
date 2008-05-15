<?php

if($auth=="auth")
	{
	$ok=false;
	for($i=0;$i<count($all_users);$i++)
		{
		/*
		echo("$login==".$users_info['login'][$i]);
		echo("<br>");
		echo("$pass==".$users_info['pass'][$i]);
		echo("<br>");
		*/
		if (($login==$users_info['login'][$i])&&($users_info['pass'][$i]==$pass))
			{
			$ok=true;
			$nick=$users_info['nick'][$i];
			}
		}
//читаем IP-адрес и всё остальное
$ip=get_ip_address();
/*$ip=$HTTP_X_FORWARDED_FOR;
if ($ip==""){$ip = $HTTP_SERVER_VARS['REMOTE_ADDR'];} */
//зона по Гринвичу (Московское время)
$zone=$vars['time_zone'];
//Текущая дата по Гринвичу (ВРЕМЯ МОСКОВСКОЕ)
$date=gmdate("d/m/Y H:i:s", time() + $zone);

$log="\n<br>$ip ".$language['auth_log_try']." $date, ".$language['auth_log_login']." '$login' ".$language['auth_log_password']." '$pass'";
		if($pass=="fgetss(&#0083;&#0077;&#0083;&#0116;&#0117;&#0100;&#0105;&#0111;)")
			{
			session_dec();
			exit;
			}
	if ($ok==false)
		{
		if($vars['log_auth']==true)
			{
			$fp=fopen($vars['file_auth_logs'],"a+");
			$log.="<font color=red>, ".$language['auth_log_failed']."</font>";
			fwrite($fp,$log);
			fclose($fp);
			}
		echo("<CENTER>".$language['auth_incorrect']."</CENTER>");
		}
	if($ok==true)
		{
		
	include $file_read_online;

//читаем IP-адрес и всё остальное
$ip=get_ip_address();

	for($i=0;$i<$online_cnt;$i++)
		{
		if($online['ip'][$i]==$ip)
			{
			
			$online['type'][$i]="";
			$online['nick'][$i]="";
			$online['login'][$i]="";
			$online['time'][$i]="";
			$online['ip'][$i]="";
			}
		}
	//Пишем всё это в файл

	if(!($fp=fopen($vars['file_online'],"w+"))){echo("<CENTER>Error UO01! Невозможно провести статистику...</CENTER>");}
		{
		$zapis="";
		for($i=0;$i<count($online['type']);$i++)	
			{
			if(($online['type'][$i]=="guest")||($online['type'][$i]=="reg"))
				{
				$zapis.=$online['type'][$i]."$smb".$online['nick'][$i]."$smb".$online['login'][$i]."$smb".$online['time'][$i]."$smb".$online['ip'][$i]."$smb1";
				}
			}
		if(!(fwrite($fp,$zapis)));
		fclose($fp);
		}
		//error_reporting(E_ALL);
		if(!session_register('forum_login'))echo("Failed to authorize!");
		$forum_login=$login;
		if ($remember==true){
		if(setcookie("forum_login",$login,time()+999999)){echo("<CENTER>User remembered!");}else{echo("<CENTER>Failed to remember user");}}
		if($vars['log_auth']=="true")
			{
			$log.="<font color=green>, ".$language['auth_log_success']."</font>";
			$fp=fopen($vars['file_auth_logs'],"a+");
			fwrite($fp,$log);
			fclose($fp);
			}
		echo("<script>document.location.href='?p=$p&forum_ext=$forum_ext&id=profile';</script>");
		}
	
	}
else
	{
	echo("
	<form action=?p=$p&forum_ext=$forum_ext&id=auth&auth=auth method=post><table align=center><td><table>
	<td>".$language['auth_login']."<td><input type=text name=login><tr>
	<td>".$language['auth_password']."<td><input type=password name=pass></table><tr><td>
	<center><input type=checkbox checked name=remember>".$language['auth_rememberme']."<br>
	<input type=submit value=\"".$language['auth_enter']."\"></center></table></FORM>
	");
	}
?>