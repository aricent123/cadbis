<?php

/////////////////////////////ПРОВЕРЯЕМ ПРАВИЛЬНОСТЬ ЗАПОЛНЕНИЯ\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;
if (strlen($login)<$vars['login_min'])
	{
	echo("<center><br><b>".$language['reg'][9]." ".$vars['login_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($password)<$vars['pass_min'])
	{
	echo("<center><br><b>".$language['profilechange'][0]." ".$vars['pass_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($nick)<$vars['nick_min'])
	{
	echo("<center><br><b>".$language['profilechange'][1]." ".$vars['nick_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($city)<$vars['city_min'])
	{
	echo("<center><br><b>".$language['profilechange'][2]." ".$vars['city_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($email)<$vars['email_min'])
	{
	echo("<center><br><b>".$language['profilechange'][3]." ".$vars['email_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($url)<$vars['url_min'])
	{
	echo("<center><br><b>".$language['profilechange'][4]." ".$vars['url_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($sign)<$vars['sign_min'])
	{
	echo("<center><br><b>".$language['profilechange'][5]." ".$vars['sign_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
if (strlen($info)<$vars['info_min'])
	{
	echo("<center><br><b>".$language['profilechange'][0]." ".$vars['info_min']." ".$language['profilechange'][7]."!</b></center>");
	$error=true;
	}
//Проверяем не занят ли логин...
for($i=0;$i<count($all_users);$i++)
	{
	if ($login==$users_info['login'][$i])
		{
		echo("<center><br><b>".$language['reg'][0]." '$login' ".$language['reg'][1]."</b></center>");
		$error=true;
		}
	}
////////////////////////ЕСЛИ ЕСТЬ ОШИБКА, ПИШЕМ ЮЗЕРУ ССЫЛКУ ОБРАТНО\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$nick=strip_tags($nick);
$password=strip_tags($password);
$login=strip_tags($login);
$sex=strip_tags($sex);
$city=strip_tags($city);
$email=strip_tags($email);
$url=strip_tags($url);
$info=strip_tags($info);
$sign=strip_tags($sign);
$info=nl2br($info);
$sign=nl2br($sign);
echo("<center><br>".$language['reg'][2]." <a href=\"?p=$p&forum_ext=$forum_ext&id=register&log1=$login&sex1=$sex&pas1=$password&nic1=$nick&cit1=$city&ema1=$email&url1=$url&sig1=$sign&inf1=$info\">НАЗАД</a></center>");
}
//end


///////////////////////ЕСЛИ ОШИБОК НЕТ, ПРОДОЛЖАЕМ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==false)
{
////////////////////////////////////РЕДАКТИРУЕМ ПЕРЕМЕННЫЕ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$unsymb = array("!",",",".","@", "#", "\$", "%", "^", "&", "*", "(", ")","[","]","{","}","<",">","=","-","+","\'","\"",":","/","$smb");
$login=strip_tags($login);
$login=str_replace($unsymb, "", $login);
$login=substr($login,0,$vars['login_len']);

$password=strip_tags($password);
$password=str_replace("$smb","",$password);
$password=substr($password,0,$vars['pass_len']);
$password=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($password)))));

$nick=strip_tags($nick);
$nick=str_replace("$smb","",$nick);
$nick=substr($nick,0,$vars['nick_len']);
$nick=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($nick)))));

$city=strip_tags($city);
$city=str_replace("$smb","",$city);
$city=substr($city,0,$vars['city_len']);
$city=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($city)))));

$email=strip_tags($email);
$email=str_replace("$smb","",$email);
$email=substr($email,0,$vars['email_len']);
$email=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($email)))));

$url=strip_tags($url);
$url=str_replace("$smb","",$url);
$url=substr($url,0,$vars['url_len']);
$url=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url)))));

$sign=htmlspecialchars($sign);
$sign=nl2br($sign);
$sign=substr($sign,0,$vars['sign_len']);
$sign=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($sign)))));

$info=htmlspecialchars($info);
$info=nl2br($info);
$info=substr($info,0,$vars['info_len']);
$info=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($info)))));

if ($sex!="женский")$sex="мужской";
//end
	if($url=="http://") $url="";
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
$date=gmdate("j ", time() + $zone);
$date.=$months[$month];
$date.=gmdate(", Y г.", time() + $zone);

//юзер получает ранг нулевого уровня...
$rang=$rangs[0]['rang'];

$rights="";
//права
$rights=$vars['reg_rights'];

//полная инфа о юзере
$user_info=$login."$smb".$password."$smb".$nick."$smb".$city."$smb".$sex."$smb".$email."$smb".$url."$smb".$date."$smb".$rang."$smb"."0"."$smb"."$smb"."$rights"."$smb".$sign."$smb".$info;

//ID нового юзера= к-во секунд от создания UNIX (UNIX formatted date)
$new_user_id=time();

//Создаём файл с новым юзером(лузером)
if(!($fp=fopen($vars['dir_users']."/$new_user_id","w+")))
	{
	//если чего-то не получается- значит ошибка!
	echo("<center><font size=6><b>ОШИБКА REG01!</b></font></center>");
	}
$ok=false;
if (fwrite($fp,$user_info)) $ok=true;
fclose($fp);
if($ok==true)
	{
	$you_registered="<CENTER><br>".$language['reg'][3]."<br><table>
	<td>".$language['profilechange'][25]."<td>$login<tr>
	<td>".$language['profilechange'][26]."<td>$password<tr>
	<td>".$language['profilechange'][27]."<td>$nick<tr>
	<td>".$language['profilechange'][28]."<td>$city<tr>
	<td>".$language['profilechange'][29]."<td>$email<tr>
	<td>".$language['profilechange'][30]."<td>$url<tr>
	<td>".$language['reg'][4]."<td>";
	if($sex=="женский")$sexx=$language['sex_woman']; else $sexx=$language['sex_man'];
	$you_registered.="$sexx<tr><td>".$language['profilechange'][31]."<td>$sign<tr>
	<td>".$language['profilechange'][32]."<td>$info</table>
	".$language['reg'][5]."<a href=\"".$vars['forum_addr']."\">".$vars['forum_addr']."</a></CENTER>";
	echo("$you_registered");
	if ($email!="")	
		{
		$headers="MIME-Version: 1.0\r\n";
		$headers.="Content-type: text/html; charset=Windows-1251\r\n";
		$from=$vars['your_name'];
		$emailfrom=$vars['your_email'];
		$headers.="From: $from<$emailfrom>\r\n"."Reply-To: $emailfrom\r\nReturn-path: $emailfrom\r\n";
		$subject="".$language['reg'][10]." \"".$vars['forum_name']."\"";
		if(!mail($email, $subject, $you_registered,$headers))
			{
			echo("<center>".$language['reg'][6]." '$email'! ".$language['reg'][7]."</center>");
			}
			else
			{
			echo("<center>".$language['reg'][8]." ($email)!</center>");
			}
		}
	}
	else
	{
	//если чего-то не получается- значит ошибка!
	echo("<center><font size=6><b>ОШИБКА REG02!</b></font></center>");
	}
}
?>