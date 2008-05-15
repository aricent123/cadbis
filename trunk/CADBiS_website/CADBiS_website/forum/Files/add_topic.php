<?php

if ($act=="add")
	{

/////////////////////////////ПРОВЕРЯЕМ ПРАВИЛЬНОСТЬ ЗАПОЛНЕНИЯ\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;
if (strlen($topic)<$vars['title_min'])
	{
	echo("<center><br><b>".$language['addtopic_titlemin']." ".$vars['title_min']." ".$language['addtopic_symbols']."</b></center>");
	$error=true;
	}
if (strlen($nick)<$vars['nick_min'])
	{
	echo("<center><br><b>".$language['addtopic_nickmin']." ".$vars['nick_min']." ".$language['addtopic_symbols']."</b></center>");
	$error=true;
	}
if (strlen($email)<$vars['email_min'])
	{
	echo("<center><br><b>".$language['addtopic_emailmin']." ".$vars['email_min']." ".$language['addtopic_symbols']."</b></center>");
	$error=true;
	}
if (strlen($url)<$vars['url_min'])
	{
	echo("<center><br><b>".$language['addtopic_urlmin']." ".$vars['url_min']." ".$language['addtopic_symbols']."</b></center>");
	$error=true;
	}
if (strlen($desc)<$vars['desc_min'])
	{
	echo("<center><br><b>".$language['addtopic_descrmin']." ".$vars['desc_min']." ".$language['addtopic_symbols']."</b></center>");
	$error=true;
	}
for($i=0;$i<count($all_users);$i++)
	{
	if (($nick==$users_info['login'][$i])&&($HTTP_SESSION_VARS['forum_login']!=$users_info['login'][$i]))
		{
		echo("<center><br><b>".$language['addtopic_nickforb']."</b></center>");
		$error=true;
		}
	}
////////////////////////ЕСЛИ ЕСТЬ ОШИБКА, ПИШЕМ ЮЗЕРУ ССЫЛКУ ОБРАТНО\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$desc=strip_tags($desc);
$nick=strip_tags($nick);
$email=strip_tags($email);
$url=strip_tags($url);
$topic=strip_tags($topic);
$desc=nl2br($desc);
echo("<center><br>".$language['addmes'][7]." <a href=\"?p=$p&forum_ext=$forum_ext&id=add_topic&tit1=$topic&nic1=$nick&ema1=$email&url1=$url&des1=$desc\">НАЗАД</a></center>");
}
//end

	if ($error!=true)
		{
		if($url=="http://")$url="";		
////////////////////////////////////РЕДАКТИРУЕМ ПЕРЕМЕННЫЕ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

$nick=strip_tags($nick);
$nick=str_replace("$smb","",$nick);
$nick=substr($nick,0,$vars['nick_len']);
$nick=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($nick)))));

$email=strip_tags($email);
$email=str_replace("$smb","",$email);
$email=substr($email,0,$vars['email_len']);
$email=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($email)))));

$url=strip_tags($url);
$url=str_replace("$smb","",$url);
$url=substr($url,0,$vars['url_len']);
$url=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url)))));

$desc=htmlspecialchars($desc);
$desc=str_replace("$smb","",$desc);
$desc=substr($desc,0,$vars['desc_len']);
$desc=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($desc)))));

$topic=htmlspecialchars($topic);
$topic=str_replace("$smb","",$topic);
$topic=substr($topic,0,$vars['title_len']);
$topic=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($topic)))));
 
$t_rights=strip_tags($t_rights);
$t_rights=str_replace("$smb","",$t_rights);
$t_rights=substr($t_rights,0,1);

if($t_rights>7) $t_rights=7;
if($t_rights>$user_rights)$t_rights=$user_rights;
if($t_rights<0) $t_rights=0;

		//дата регистрации
		//месяцы по-русски
		for($n=0;$n<count($language['months']);$n++){
		//echo("[$n]".$months[$n]."=".$language['months'][$n]."<br>");
		$months[$n+1]=$language['months'][$n];
		}
		for($n=0;$n<count($language['weekdays']);$n++)
		{
		//echo("[$n]".$daysweek[$n]."=".$language['weekdays'][$n]);
		$daysweek[]=$language['weekdays'][$n];
		}
		//зона по Гринвичу (Московское время)
		$zone=$vars['time_zone'];
		//Месяц
		$month=gmdate("n", time() + $zone);
		//echo("<br>month=".$months[$month]);
		//День недели
		$dayweek=gmdate("w", time() + $zone);
		//echo("<br>dayweek=".$daysweek[$dayweek]);
		//Текущая дата по Гринвичу (ВРЕМЯ МОСКОВСКОЕ)
		$date=$daysweek[$dayweek];
		$date.=gmdate(", j ",time()+$zone);
		$date.=$months[$month];
		$date.=gmdate(", H:i", time() + $zone);
		//echo("<br>".$date);
		//exit;
		if (check_auth())
			{
			$nick=$CURRENT_USER['id'];
			}
		$fil=time();
		if($vars['mat_filter']=="true")
			{
			include "$file_mat_filter";
			$topic=str_replace($unsymb,$vars['mat_filter_word'],$topic);
			$desc=str_replace($unsymb,$vars['mat_filter_word'],$desc);
			}
		$desc=$FLTR->DirectProcessText($desc,1,0,1);
		$zapis="$topic"."$smb"."$nick"."$smb"."$email"."$smb"."$url"."$smb"."$date"."$smb"."0"."$smb"."$desc"."$smb"."$t_rights"."$smb"."$smb";
		$error=false;
		if(!($fp=fopen($vars['dir_themes']."/"."$fil".".".$vars['theme_ext'],"w+")))
			{
			echo("<center>Ошибка TA01! Невозможно создать тему</center>");	
			$error=true;
			}
			else
			{
			if(!(fwrite($fp,$zapis)))
				{
				echo("<center>Ошибка TA02! Невозможно создать тему</center>");	
				$error=true;
				}
			}
			if(!($fp=fopen($vars['dir_themes']."/"."$fil".".".$vars['mess_ext'],"w+")))
			{
			echo("<center>Ошибка TA03! Невозможно создать тему</center>");	
			$error=true;
			}

		if ($error==false)		
			{
			$t_links=0;
			for($j=0;$j<count($all_themes)+1;$j=$j+$vars['t_kvo'])
				{
				$t_links++;
				}
			if($t_links>0)$page="?t_page=$t_links"; else $page="";
			echo("<meta http-equiv=\"Refresh\" content=\"1;URL='?p=$p&forum_ext=$forum_ext&t_page=$page'\"><center>".$language['topicadd'][0]."<a href=\"?p=$p&forum_ext=$forum_ext&t_page=$page\">".$language['topicadd'][1]."</a>");
			}
		}

	}
else
	{
	$des1=str_replace("<br />","\n",$des1);
	for($k=0;$k<count($all_users);$k++)
	{
	if($CURRENT_USER['login']==$users_info['login'][$k]){$user_index=$k;}
	}
	echo("<center>
	<form action=?p=$p&forum_ext=$forum_ext&id=add_topic&act=add method=post><table width=500px><td width=100%><table width=100%><td width=40%>
	".$language['topicadd'][2]."<td width=60%><input style=\"width:100%\" class=inputbox type=text name=topic value=\"$tit1\" maxlength=\"".$vars['title_len']."\"><tr><td>
	".$language['topicadd'][3]."<td width=60%><input style=\"width:100%\" class=inputbox type=text name=nick value=\"");
	if ($nic1!="")echo($nic1); 
	else echo($users_info['nick'][$user_index]); 
	echo("\" maxlength=\"".$vars['nick_len']."\"><tr><td>
	".$language['topicadd'][4]."<td width=60%><input style=\"width:100%\" class=inputbox type=text name=email value=\"");
	if ($ema1!="")echo($ema1); 
	else echo($users_info['email'][$user_index]); 
	echo("\" maxlength=\"".$vars['email_len']."\" ><tr><td>
	".$language['topicadd'][5]."<td width=60%><input style=\"width:100%\" class=inputbox type=text name=url value=\"");
	if ($url1!="")echo($url1); 
	else {if($users_info['url'][$user_index]!="")echo($users_info['url'][$user_index]); else{echo("http://");}}
	echo("\" maxlength=\"".$vars['url_len']."\" style=\"width:100%\"><tr><td>
	".$language['topicadd'][6]."<td width=60%><select class=inputbox name=t_rights  style=\"width:100%\">");
	for($i=0;$i<=$user_rights;$i++)
		{
		echo("<option value=$i>$i");
		}
	echo("
	</select><tr><td>	
	".$language['topicadd'][7]."<td><textarea class=inputbox cols=35 rows=4   style=\"width:100%\" name=desc maxlength=\"".$vars['desc_len']."\">$des1</textarea></table><tr><td>
	<center><input type=submit class=button value=\"".$language['topicadd'][8]."\" style=\"$input_button\"></table></form></center>

	");
	}

?>