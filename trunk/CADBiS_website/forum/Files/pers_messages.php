<?php
$usr_id=0;
if($user_re!="")
	{
	while($user_re!=$all_users[$usr_id])$usr_id++;
	}
$user_re=$users_info['login'][$usr_id];
include $file_read_users;
if($HTTP_SESSION_VARS['forum_login']=="")exit;
//Считаем номер юзера
$user_num=0;
while($HTTP_SESSION_VARS['forum_login']!=$users_info['login'][$user_num])
	{
	$user_num++;
	}
$usr_id=$user_num;
include "$file_read_private_messages";

if($act=="new")
{
/////////////////////////////ПРОВЕРЯЕМ ПРАВИЛЬНОСТЬ ЗАПОЛНЕНИЯ\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;
if (strlen($subject)<$vars['subj_min'])
	{
	echo("<center><br><b>".$language['persmes'][0]." ".$vars['subj_min']." ".$language['persmes'][30]."!</b></center>");
	$error=true;
	}
if (strlen($message)<$vars['pmes_min'])
	{
	echo("<center><br><b>".$language['persmes'][1]." ".$vars['pmes_min']." ".$language['persmes'][30]."!</b></center>");
	$error=true;
	}
$message_=explode(" ",$message);
for ($i=0;$i<count($message_);$i++)
	{
	if(strlen($message_[$i])>$vars['word_len'])
		{
		echo("<center><br><b>".$language['persmes'][2]." ".$vars['word_len']." ".$language['persmes'][30]."!</b></center>");
		$error=true;
		break;
		}
	}
$reg=false;
for($i=0;$i<count($all_users);$i++)
	{
	if ($userr==$all_users[$i])
		{
		$reg=true;
		$user_id=$i;
		}
	}
if($reg==false) {echo("<center><br><b>".$language['persmes'][3]."</b></center>"); $error=true;}
////////////////////////ЕСЛИ ЕСТЬ ОШИБКА, ПИШЕМ ЮЗЕРУ ССЫЛКУ ОБРАТНО\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$message=strip_tags($message);
$message=strip_tags($message);
$message=htmlspecialchars($message);
$subject=strip_tags($subject);
$subject=htmlspecialchars($subject);
$message=nl2br($message);
echo("<center><br>".$language['persmes'][31]." <a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&user_re=$userr&sub1=$subject&mes1=$message\">".$language['persmes'][13]."</a></center>");
}
//end

if ($error!=true)
		{
		if($url=="http://")$url="";
		////////////////////////////////////РЕДАКТИРУЕМ ПЕРЕМЕННЫЕ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		
		$subject=htmlspecialchars($subject);
		$subject=str_replace("$smb","",$subject);
		$subject=str_replace("$smb1","",$subject);
		$subject=substr($subject,0,$vars['subj_len']);
		$subject=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($subject)))));

		$message=htmlspecialchars($message);
		$message=str_replace("$smb","",$message);
		$message=str_replace("$smb1","",$message);
		if($user_rights<5)$message=substr($message,0,$vars['mes_len']);
		$message=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($message)))));

		if($vars['mat_filter']=="true")
			{
			
			}
		
		include "$file_smiles_replace";
		/*$message=nl2br($message);
		 
		//Делаем ссылки...
		$k=0;
		$unsymb=array("!","\n"," ",",","@","\$", "%", "^", "*", "(", ")","[","]","{","}","<",">","+","А","Б","В","Г","Д","Е","Ё","Ж","З","И","Й","К","Л","М","Н","О","П","Р","С","Т","У","Ф","Х","Ц","Ч","Ш","Щ","Ъ","Ы","Ь","Э","Ю","Я","а","б","в","г","д","е","ё","ж","з","и","й","к","л","м","н","о","п","р","с","т","у","ф","х","ц","ч","ш","щ","ъ","ы","ь","э","ю","я","\'","\"");
		while($k<strlen($message)){
		$may_link1=$message[$k].$message[$k+1].$message[$k+2].$message[$k+3].$message[$k+4].$message[$k+5].$message[$k+6];
		$may_link2=$message[$k].$message[$k+1].$message[$k+2];
		if(($may_link1=="http://")||($may_link2=="www"))
			{
			$j=$k;
			$link="";
				$ex=false;
				do
				{
				$link.=$message[$j];
				$j++;
					foreach($unsymb as $unsym)
					{
					if($message[$j]==$unsym){$d=$j;$ex=true;break;}
					}
				}
			while($j<strlen($message)&&($ex==false));
			$d=$d-strlen($link);
			$error=false;
			if($message[$d-5].$message[$d-4].$message[$d-3].$message[$d-2].$message[$d-1]=="src=\"")$error=true;
			if($error!=true)
			{
			$link=str_replace($unsymb,"", $link);
			if(($may_link2=="www")&&($may_link1!="http://")){$link2="<a href=\"http://$link\">$link</a>";}
			else{$link2="<a href=\"$link\">$link</a>";}
			$buf1=substr($message,0,$k);
			$buf2=substr($message,$k,strlen($message));
			$message=$buf1."<!$k!>".$buf2;
			$message=str_replace("<!$k!>".$link,$link2,$message);
			$k+=strlen($link2);
			}
		}
		if($k>=strlen($message)){break;}
		$k++;
		}
                */
                $message=$FLTR->DirectProcessText($message,1,0,1);
		
		if(($status!="")&&($user_rights<5))$status="не прочитано";
		if($status=="")$status="не прочитано";
		include $file_read_users;
		
		if($language['persmes'][4]==$status)$status="не прочитано";
		if($language['persmes'][5]==$status)$status="не прочитано";

		if($vars['mat_filter']=="true")
			{
			include "$file_mat_filter";
			$message=str_replace($unsymb,$vars['mat_filter_word'],$message);
			}
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
		//$date=gmdate("$daysweek[$dayweek], j $months[$month], H:i", $zone);
		$zapis="$smb1".$users_info['login'][$usr_id]."$smb"."$subject"."$smb"."$status"."$smb"."$date"."$smb".time()."$smb"."$user_rights"."$smb"."$message";
		$error=false;
		if(!($fp=fopen($vars['dir_users']."/".$userr,"a")))
			{
			echo("<center>Ошибка PMA01! Невозможно добавить сообщение</center>");	
			$error=true;
			}
			else
			{
			if(!(fwrite($fp,$zapis)))
				{
				echo("<center>Ошибка PMA02! Невозможно добавить сообщение!</center>");	
				$error=true;
				}
			}
		if ($error==false)		
			{
			echo("<meta http-equiv=\"Refresh\" content=\"1;URL='?p=$p&forum_ext=$forum_ext&id=pers_messages'\"><center>".$language['persmes'][32]."<a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages\">".$language['persmes'][13]."</a>");
			}
		}

}
elseif($act=="read")
{
$mes_id=0;
while($user_messages[$user_num]['time'][$mes_id]!=$p_mes)$mes_id++;
$user_messages[$user_num]['status'][$mes_id]="прочитано";
echo("<table width=100%><td><CENTER>".$language['persmes'][6]."\"".$user_messages[$user_num]['subject'][$mes_id]."\"");
//echo("<b>".$language['persmes'][7]." ".$user_messages[$user_num]['from'][$mes_id].", ".$user_messages[$user_num]['date'][$mes_id]."</b> )<table width=100%>$td_messages");
$i=$mes_id;
$user_index=0;
$user_re=$user_messages[$user_num]['from'][$mes_id];
$subject_re=$user_messages[$user_num]['subject'][$mes_id];
if($subject_re=="")$subject_re=" ";
while($users_info['login'][$user_index]!=$user_messages[$user_num]['from'][$mes_id])$user_index++;
echo("$table_messages"."$td_userinfo<SMALL>"."");
		if ($usr_view['nick']==true)
		{
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=userinfo&user_info=".$all_users[$user_index]."\" title=\"".$language['persmes'][8]."\">");
		echo("".$users_info['nick'][$user_index]."");
		echo("</a><br>");
		}
		if (($usr_view['avatar']=="true"))
		{
		echo("<a href=\"?p=users&act=userinfo&id=".$all_users[$user_index]."\" title=\"".$language['persmes'][8]."\">");
			if((file_exists($vars['dir_avatars']."/".$all_users[$user_index]."")))
			{
			echo("<img border=0 src=\"".$vars['dir_avatars']."/".$all_users[$user_index].""."\">");
			}
			else
			{
				if($users_info['sex'][$user_index]=="женский")
				{
				echo("<img border=0 src=\"".$vars['dir_avatars']."/deafult_woman"."\">");
				}
				else
				{
				echo("<img border=0 src=\"".$vars['dir_avatars']."/deafult_man"."\">");
				}
			}
		echo("</a><br>");
		}
		if ($usr_view['rang']=="true")
		{
		echo("".$users_info['rang'][$user_index]."<br>");
		}
		if (($usr_view['city']=="true")&&($users_info['city'][$user_index]!=""))
		{
		echo("".$users_info['city'][$user_index]."<br>");
		}
		if (($usr_view['email']=="true")&&($users_info['email'][$user_index]!=""))
		{
		echo("<a href=\"mailto:".$users_info['email'][$user_index]."\">".$users_info['email'][$user_index]."</a><br>");
		}
		if (($usr_view['url']=="true")&&($users_info['url'][$user_index]!=""))
		{
		echo("<a href=\"".$users_info['url'][$user_index]."\">".$users_info['url'][$user_index]."</a><br>");
		}
		if (($usr_view['sex']=="true"))
		{
		if($users_info['sex'][$user_index]=="женский")$sexx=$language['sex_woman'];
		elseif($users_info['sex'][$user_index]=="мужской")$sexx=$language['sex_man'];
		else $sexx=$users_info['sex'][$user_index];
		echo("".$language['persmes'][9]." ".$sexx."<br>");
		}
		if ($usr_view['count']=="true")
		{
		echo("".$language['persmes'][10]." ".$users_info['count'][$user_index]."<br>");
		}
		if ($usr_view['raiting']=="true")
		{
		echo("".$language['persmes'][11]."<br> ");
			if($users_info1['raiting'][$user_index]>50)
			{
			for ($f=0;$f<$users_info1['raiting'][$user_index];$f+=10)
				{
				echo("<img src=\"$file_star2\">");
				}
			}
			elseif($users_info1['raiting'][$user_index]>0)
			{
			for ($f=0;$f<$users_info1['raiting'][$user_index];$f++)
				{
				echo("<img src=\"$file_star1\">");
				}
			}
			else
			{
			echo("<img src=\"$file_star0\">");
			}
		echo("<br>");
		}
	echo("</SMALL>"."$td_messages"."<table width=100% border=0 height=100% valign=top><td valign=top><SMALL><b>
	".$user_messages[$user_num]['date'][$mes_id].", ");
	echo("</a></b></SMALL><tr><td valign=top  height=100%>");
	echo($user_messages[$user_num]['text'][$mes_id]);
	if (($usr_view['sign']=="true")&&($users_info['sign'][$user_index]!=""))
	{
	echo("<tr><td valign=bottom><SMALL><br>==============================<br>".$users_info['sign'][$user_index]."</SMALL>");
	}
	//echo("</table>");
	//echo("</table>");
	
echo("</table></table><tr><td><CENTER><a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=delete&p_mes=".$user_messages[$user_num]['time'][$mes_id]."\">".$language['persmes'][12]."</a>");
	$user_index=$user_num;
		$userr=$all_users[$user_num];
		//$user_info=$users_info['login'][$user_index]."$smb".$users_info['pass'][$user_index]."$smb".$users_info['nick'][$user_index]."$smb".$users_info['city'][$user_index]."$smb".$users_info['sex'][$user_index]."$smb".$users_info['email'][$user_index]."$smb".$users_info['url'][$user_index]."$smb".$users_info['date'][$user_index]."$smb".$users_info['rang'][$user_index]."$smb".$users_info['count'][$user_index]."$smb".$users_info['raiting'][$user_index]."$smb".$users_info['rights'][$user_index]."$smb".$users_info['sign'][$user_index]."$smb".$users_info['info'][$user_index];
		$user_info="";
		$file=get_file($vars['dir_users']."/$userr");
		$file=explode($smb1,$file);
		$user_info=$file[0];
                for($k=0;$k<count($user_messages[$user_index]['from']);$k++)
		{
		if(($user_messages[$user_index]['from'][$k]!="")&&($user_messages[$user_index]['time'][$k]!=""))
			{
			$user_info.="$smb1".$user_messages[$user_index]['from'][$k]."$smb".$user_messages[$user_index]['subject'][$k]."$smb".$user_messages[$user_index]['status'][$k]."$smb".$user_messages[$user_index]['date'][$k]."$smb".$user_messages[$user_index]['time'][$k]."$smb".$user_messages[$user_index]['priority'][$k]."$smb".$user_messages[$user_index]['text'][$k];
			}
		}

		if(!($fp=fopen($vars['dir_users']."/$userr","w+")))
			{
			//если чего-то не получается- значит ошибка!
			echo("<center><font size=6><b>ОШИБКА CSPM01!<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></b></font></center>");
			}
		$ok=false;
		if (fwrite($fp,$user_info)) $ok=true;
		fclose($fp);
		if($ok==true)
			{
			}
			else
			{
			//если чего-то не получается- значит ошибка!
			echo("<center><font size=6><b>ОШИБКА CSPM02!<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></b></font></center>");
			}
echo("</table><CENTER>");
}
elseif($act=="delete")
{
if($all!="true")
{
$mes_id=0;
while($user_messages[$user_num]['time'][$mes_id]!=$p_mes)$mes_id++;
}
if($do=="yes")
	{
	$user_index=$user_num;
	if($all=="true")
		{
		for($i=0;$i<count($user_messages[$user_index]['from']);$i++)
			{
			$user_messages[$user_index]['from'][$i]="";
			$user_messages[$user_index]['time'][$i]="";
			}
		}
		else
		{
		$user_messages[$user_index]['from'][$mes_id]="";
		$user_messages[$user_index]['time'][$mes_id]="";
		}
	
		$userr=$all_users[$user_num];
		//$user_info=$users_info['login'][$user_index]."$smb".$users_info['pass'][$user_index]."$smb".$users_info['nick'][$user_index]."$smb".$users_info['city'][$user_index]."$smb".$users_info['sex'][$user_index]."$smb".$users_info['email'][$user_index]."$smb".$users_info['url'][$user_index]."$smb".$users_info['date'][$user_index]."$smb".$users_info['rang'][$user_index]."$smb".$users_info['count'][$user_index]."$smb".$users_info['raiting'][$user_index]."$smb".$users_info['rights'][$user_index]."$smb".$users_info['sign'][$user_index]."$smb".$users_info['info'][$user_index];
		//$user_info=$users_info['count'][$user_index]."$smb".$users_info['forum_data'][$user_index];
		
                $user_info="";
		$file=get_file($vars['dir_users']."/$userr");
		$file=explode($smb1,$file);
		$user_info=$file[0];		
                for($k=0;$k<count($user_messages[$user_index]['from']);$k++)
		{
		if(($user_messages[$user_index]['from'][$k]!="")&&($user_messages[$user_index]['time'][$k]!=""))
			{
			$user_info.="$smb1".$user_messages[$user_index]['from'][$k]."$smb".$user_messages[$user_index]['subject'][$k]."$smb".$user_messages[$user_index]['status'][$k]."$smb".$user_messages[$user_index]['date'][$k]."$smb".$user_messages[$user_index]['time'][$k]."$smb".$user_messages[$user_index]['priority'][$k]."$smb".$user_messages[$user_index]['text'][$k];
			}
		}
		if(!($fp=fopen($vars['dir_users']."/$userr","w+")))
			{
			//если чего-то не получается- значит ошибка!
			echo("<center><font size=6><b>ОШИБКА DPM01!<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></b></font></center>");
			}
		$ok=false;
		if (fwrite($fp,$user_info)) $ok=true;
		fclose($fp);
		if($ok==true)
			{
			echo("<center>".$language['persmes'][33]."<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></center>");
			}
			else
			{
			//если чего-то не получается- значит ошибка!
			echo("<center><font size=6><b>ОШИБКА DPM02!<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></b></font></center>");
			}
	}
	else
	{
	if($all=="true")
		{
		echo("<center>".$language['persmes'][14]."<br>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=delete&all=true&do=yes\">".$language['persmes'][15]."</a><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></center>");	
		}
		else
		{
		$user_index=0;
		while($users_info['login'][$user_index]!=$user_messages[$user_num]['from'][$mes_id])$user_index++;
		echo("<center>".$language['persmes'][16]." <b>".$language['persmes'][7]." ".$users_info['nick'][$user_index]." \"".$user_messages[$user_num]['subject'][$mes_id]."\"</b>?<br>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=delete&do=yes&p_mes=$p_mes\">".$language['persmes'][17]."</a><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a></center>");	
		}
		
	}

}
else
{
echo("<CENTER>".$table_all.$td_all);
	if(count($user_messages[$user_num]['from'])>0)
	{
	echo("".$language['persmes'][18]."$td_all"."".$language['persmes'][19]."$td_all"."".$language['persmes'][20]."$td_all"."".$language['persmes'][21]."$td_all"."".$language['persmes'][22]."<tr>$td_all");
	for($i=0;$i<count($user_messages[$user_num]['from']);$i++)
		{
		if($user_messages[$user_num]['priority'][$i]>5) 
			{
			$tags="<font color=red>";
			$tags1="</font>";
			//$user_messages[$user_num]['from'][$i]="(admin)".$user_messages[$user_num]['from'][$i];
			}
			else 
			{
			$tags="";
			$tags1="";
			}
			if($user_messages[$user_num]['status'][$i]!="прочитано")
			{
			$tags.="<b>";
			$tags1.="</b>";
			if($user_messages[$user_num]['status'][$i]=="не прочитано")$user_messages[$user_num]['status'][$i]=$language['persmes'][4];
			}
			else
			{
			$user_messages[$user_num]['status'][$i]=$language['persmes'][5];
			}
		$usr_id=0;
		while($user_messages[$user_num]['from'][$i]!=$users_info['login'][$usr_id])$usr_id++;
		echo($tags.$users_info['nick'][$usr_id]."$tags1$td_all".$tags.$user_messages[$user_num]['subject'][$i].$tags1."$td_all".$tags.$user_messages[$user_num]['date'][$i].$tags1."$td_all".$tags.$user_messages[$user_num]['status'][$i].$tags1."
		$td_all"."<table><td><a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=read&p_mes=".$user_messages[$user_num]['time'][$i]."\">".$language['version_2.0'][67]."</a><tr><Td>
		<a href=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=delete&p_mes=".$user_messages[$user_num]['time'][$i]."\">".$language['version_2.0'][68]."</a></table>");
		if($i<count($user_messages[$user_num]['from'])-1)echo("<tr>$td_all");
		}
	}
	else echo("<CENTER>".$language['persmes'][23]."</CENTER>");
echo("</table>");
if(count($user_messages[$user_num]['from'])>0)echo("<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages&act=delete&all=true>".$language['persmes'][15]."</a>");
}
if(($act!="delete")&&($act!="new"))
{
$mes1=str_replace("<br />","\n",$mes1);
$sub1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($sub1)))));
$mes1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($mes1)))));

if($user_re!="")echo("<center><b>".$language['persmes'][24]."</b></center>");
else echo("<center><b>".$language['persmes'][25]."</b></center>");

echo("<table><td><table><td>"."<form name=form1 action=\"?p=$p&forum_ext=$forum_ext&id=pers_messages&act=new\" method=post>
".$language['persmes'][26]."<td><Select name=userr style=\"width:350;$input_text\">");
for($i=0;$i<count($all_users);$i++)
	{
	if($users_info['rights'][$i]>2)
		{
		echo("<option value=\"".$all_users[$i]."\"");
		if ($user_re==$users_info['login'][$i]) {echo(" selected ");}
		echo(">".$users_info['nick'][$i]."");
		}
	}
	echo("</select><tr><Td>");
	if($user_rights>4)
		{
		echo("".$language['persmes'][21]."<td><input type=text name=status value=\"".$language['persmes'][4]."\" style=\"width:350;$input_text\"><tr><Td>");
		}
	echo("
	".$language['persmes'][27]."<td><input type=text name=subject maxlength=\"".$vars['subj_len']."\"");
	if($subject_re=="")echo("value=\"$sub1\"");else echo("value=\"RE: $subject_re\"");
	echo("style=\"width:350;$input_text\"><tr><Td>
	".$language['persmes'][28]."<td><TEXTAREA cols=42 rows=7 name=message onclick='selectedtext = document.selection.createRange().duplicate();' onchange='selectedtext = document.selection.createRange().duplicate();' style=\"$input_text\">$mes1</TEXTAREA></table>
	<center><input type=submit value=\"".$language['persmes'][29]."\" style=\"$input_button\"></form></center><td valign=top>");
	include "$file_smiles";
	echo("</table>");
}
if($act=="read")echo("<a href=?p=$p&forum_ext=$forum_ext&id=pers_messages>".$language['persmes'][13]."</a>");
?>