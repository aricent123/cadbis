<?php

$online['nick']=array();
$online['time']=array();
$online['type']=array();
$online['ip']=array();
$file1=file($vars['file_online']);
$file="";
foreach($file1 as $string)
	{
	$file.=$string;
	}
$strings=explode("$smb1",$file);

for($i=0;$i<count($strings);$i++)
	{
	$variables=explode("$smb",$strings[$i]);
	$online['type'][$i]=$variables[0];
	$online['nick'][$i]=$variables[1];
	$online['login'][$i]=$variables[2];
	$online['time'][$i]=$variables[3];
	$online['ip'][$i]=$variables[4];
	$online['forum'][$i]=$variables[5];
	$online['topic'][$i]=$variables[6];
	$online['rights'][$i]=$variables[7];
	}
$online_cnt=count($strings)-1;

//Выкидываем ушедших юзеров
for($i=0;$i<$online_cnt;$i++)
	{
	if($online['time'][$i]<time()-$vars['time_exit'])
		{
		//echo($online['time'][$i]."<".(time()-$vars['time_exit'])."<br>");
		//echo("выкидываем ".$online['nick'][$i]." с IP:".$online['ip'][$i]."<br>");
		$online['type'][$i]="";
		$online['nick'][$i]="";
		$online['login'][$i]="";
		$online['time'][$i]="";
		$online['ip'][$i]="";
		}
	}

//читаем IP-адрес и всё остальное
$ip=get_ip_address();

//Записываем заход...
if($HTTP_SESSION_VARS['forum_login']!="")
	{
	$nick=$HTTP_SESSION_VARS['forum_login'];
	$usr_id=0;
	while($users_info['login'][$usr_id]!=$nick && $usr_id<count($users_info['login']))
          {$usr_id++;}
	$nick=$users_info['nick'][$usr_id];
	$ok=false;
	for($i=0;$i<$online_cnt;$i++)
		{
		if(($users_info['nick'][$usr_id]==$online['nick'][$i])||($users_info['login'][$usr_id]==$online['login'][$i]))	
			{
			$online['type'][$i]="reg";
			$online['nick'][$i]=$nick;
			$online['login'][$i]=$users_info['login'][$usr_id];
			$online['time'][$i]=time();
			$online['ip'][$i]=$ip;
			$ok=true;
			$online['forum'][$i]=$forums['title'][$forum_id];
			if($id=="showtopic")
				{
				$tid=0;
				while($all_themes[$tid]!=$topic)$tid++;
				$online['topic'][$i]=$themes_data[$tid]['title'];
				}
			$online['rights'][$i]=$user_rights;
			}
		}
	if ($ok==false)
		{
		$online['type'][]="reg";
		$online['nick'][]=$nick;
		$online['login'][]=$users_info['login'][$usr_id];
		$online['time'][]=time();
		$online['ip'][]=$ip;
		$online['forum'][]=$forums['title'][$forum_id];
		if($id=="showtopic")
			{
			$tid=0;
			while($all_themes[$tid]!=$topic)$tid++;
			$online['topic'][]=$themes_data[$tid]['title'];
			}
		$online['rights'][]=$user_rights;
		}
	}
else
	{
	$nick=$ip;
	$ok=false;
	for($i=0;$i<$online_cnt;$i++)
		{
		if($ip==$online['nick'][$i])	
			{
			$online['type'][$i]="guest";
			$online['nick'][$i]=$nick;
			$online['login'][$i]="";
			$online['time'][$i]=time();
			$online['ip'][$i]=$ip;
			$online['forum'][$i]=$forums['title'][$forum_id];
			if($id=="showtopic")
				{
				$tid=0;
				while($all_themes[$tid]!=$topic)$tid++;
				$online['topic'][$i]=$themes_data[$tid]['title'];
				}
			$online['rights'][$i]=$user_rights;
			$ok=true;
			}
		}
	if ($ok==false)
		{
		$online['type'][]="guest";
		$online['nick'][]=$nick;
		$online['login'][]="";
		$online['time'][]=time();
		$online['ip'][]=$ip;
		$online['forum'][]=$forums['title'][$forum_id];
		if($id=="showtopic")
			{
			$tid=0;
			while($all_themes[$tid]!=$topic)$tid++;
			$online['topic'][]=$themes_data[$tid]['title'];
			}
		$online['rights'][]=$user_rights;
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
			$zapis.=$online['type'][$i]."$smb".$online['nick'][$i]."$smb".$online['login'][$i]."$smb".$online['time'][$i]."$smb".$online['ip'][$i]."$smb".$online['forum'][$i]."$smb".$online['topic'][$i]."$smb".$online['rights'][$i]."$smb1";
			}
		}
	if(!(fwrite($fp,$zapis)))echo("<CENTER>Error UO02! Невозможно провести статистику...</CENTER>");
	fclose($fp);
	}
$guests=0;
foreach($online['type'] as $type)
	{
	if($type=="guest")$guests++;
	}
$regs=0;
foreach($online['type'] as $type)
	{
	if($type=="reg")$regs++;
	}
?>