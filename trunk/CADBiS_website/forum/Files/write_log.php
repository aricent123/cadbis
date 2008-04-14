<?php
include "$file_read_logs";

//читаем IP-адрес и всё остальное
$ip=get_ip_address();
$ip_void=get_just_ip();
global $HTTP_SERVER_VARS;
$port=$HTTP_SERVER_VARS['REMOTE_PORT'];
$browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
$referer=$HTTP_SERVER_VARS['HTTP_REFERER'];
$connection=$HTTP_SERVER_VARS['HTTP_CONNECTION'];
//$ip.=" (".gethostbyaddr($ip).")";

if ($port==""){$port="n/a";}
if ($referer==""){$referer="n/a";}
if ($host==""){$host="n/a";}
if ($browser==""){$browser="n/a";}
if ($connection==""){$connection="n/a";}
	//зона по Гринвичу 
	$zone=$vars['time_zone'];
	//Текущая дата по Гринвичу 
	$date=gmdate("d/m/Y H:i:s", time() + $zone);
$ok=false;
for($i=0;$i<count($logs['ip']);$i++)
	{
	if ($logs['ip'][$i]==$ip)
		{
		$logs['old_logs'][$i]="<br><br>[".$logs['date'][$i]."] port:'".$logs['port'][$i]."' referer:'".$logs['referer'][$i]."'"."<br>browser:".$logs['browser'][$i]."\n".$logs['old_logs'][$i];
		$logs['date'][$i]=$date;
		$logs['browser'][$i]=$browser;
		$logs['referer'][$i]=$referer." on forum:\"".$forums['title'][$forum_id]."\"";
		$logs['connection'][$i]=$connection;
		$logs['port'][$i]=$port;
		$logs['host'][$i]=gethostbyaddr($ip_void);
		$ok=true;
		}
	}


if($ok==false)
	{
	$logs['ip'][]=$ip;
	$logs['old_logs'][].="";
	$logs['date'][]=$date;
	$logs['browser'][]=$browser;
	$logs['referer'][]=$referer;
	$logs['connection'][]=$connection;
	$logs['port'][]=$port;
	$logs['host'][]=gethostbyaddr($ip_void);
	}
$zapis="";

for($i=0;$i<count($logs['ip']);$i++)
	{	
	//echo($zapis."<hr>");
	if($logs['ip'][$i]!="") $zapis.=$logs['ip'][$i]."$smb".$logs['host'][$i]."$smb".$logs['date'][$i]."$smb".$logs['browser'][$i]."$smb".$logs['referer'][$i]."$smb".$logs['connection'][$i]."$smb".$logs['port'][$i]."$smb".$logs['old_logs'][$i]."$smb1";
	}
	if(!($fp=fopen($vars['file_logs'],"w+")))
		{
		echo("<CENTER>Error WL01! Ошибка записи лог-файла</CENTER>");
		}
		else
		{
		if(!(fwrite($fp,$zapis))){echo("<CENTER>Error WL02! Ошибка записи лог-файла</CENTER>");}
		fclose($fp);
		}
	

?>