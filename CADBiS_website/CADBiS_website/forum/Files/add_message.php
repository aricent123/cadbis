<?php
$tid=0;
while($all_themes[$tid]!=$topic)$tid++;
if ($act=="add")
	{
/////////////////////////////ÏĞÎÂÅĞßÅÌ ÏĞÀÂÈËÜÍÎÑÒÜ ÇÀÏÎËÍÅÍÈß\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;
if (strlen($nick)<$vars['nick_min'])
	{
	echo("<center><br><b>".$language['addmes'][0]." ".$vars['nick_min']." ".$language['addmes'][5]."!</b></center>");
	$error=true;
	}
if (strlen($email)<$vars['email_min'])
	{
	echo("<center><br><b>".$language['addmes'][1]." ".$vars['email_min']." ".$language['addmes'][5]."!</b></center>");
	$error=true;
	}
if (strlen($url)<$vars['url_min'])
	{
	echo("<center><br><b>".$language['addmes'][2]." ".$vars['url_min']." ".$language['addmes'][5]."!</b></center>");
	$error=true;
	}
if (strlen($message)<$vars['mes_min'])
	{
	echo("<center><br><b>".$language['addmes'][3]." ".$vars['mes_min']." ".$language['addmes'][5]."!</b></center>");
	$error=true;
	}
$message_=explode(" ",$message);
for ($i=0;$i<count($message_);$i++)
	{
	if(strlen($message_[$i])>$vars['word_len'])
		{
		echo("<center><br><b>".$language['addmes'][4]." ".$vars['word_len']." ".$language['addmes'][5]."!</b></center>");
		$error=true;
		break;
		}
	}
for($i=0;$i<count($all_users);$i++)
	{
	if (($nick==$users_info['login'][$i])&&($HTTP_SESSION_VARS['forum_login']!=$users_info['login'][$i]))
		{
		echo("<center><br><b>".$language['addmes'][6]."</b></center>");
		$error=true;
		}
	}
if($themes_data[$tid]['status']=="closed")$error=true;
////////////////////////ÅÑËÈ ÅÑÒÜ ÎØÈÁÊÀ, ÏÈØÅÌ ŞÇÅĞÓ ÑÑÛËÊÓ ÎÁĞÀÒÍÎ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$message=strip_tags($message);
$message=htmlspecialchars($message);
$nick=strip_tags($nick);
$nick=htmlspecialchars($nick);
$email=strip_tags($email);
$email=htmlspecialchars($email);
$url=strip_tags($url);
$url=htmlspecialchars($url);
$message=nl2br($message);
echo("<center><br>".$language['addmes'][7]." <a href=\"?p=$p&forum_ext=$forum_ext&id=add_message&topic=$topic&nic1=$nick&ema1=$email&url1=$url&mes1=$message\">ÍÀÇÀÄ</a></center>");
}
//end

if ($error!=true)
		{
		if($url=="http://")$url="";
		////////////////////////////////////ĞÅÄÀÊÒÈĞÓÅÌ ÏÅĞÅÌÅÍÍÛÅ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		
		$nick=strip_tags($nick);
		$nick=str_replace("$smb","",$nick);
		$nick=str_replace("$smb1","",$nick);
		$nick=substr($nick,0,$vars['nick_len']);
		$nick=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($nick)))));
		
		$email=strip_tags($email);
		$email=str_replace("$smb","",$email);
		$email=str_replace("$smb1","",$email);
		$email=substr($email,0,$vars['email_len']);
		$email=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($email)))));
		
		$url=strip_tags($url);
		$url=str_replace("$smb","",$url);
		$url=str_replace("$smb1","",$url);
		$url=substr($url,0,$vars['url_len']);
		$url=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url)))));
		
		$message=htmlspecialchars($message);
		$message=str_replace("$smb","",$message);
		$message=str_replace("$smb1","",$message);
		if($user_rights<5)$message=substr($message,0,$vars['mes_len']);
		$message=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($message)))));
		
		if($vars['mat_filter']=="true")
			{
			include "$file_mat_filter";
			$message=str_replace($unsymb,$vars['mat_filter_word'],$message);
			}
					
		include "$file_smiles_replace";

		//$message=nl2br($message);
		
                /*
                //Äåëàåì ññûëêè...
		$k=0;
		$unsymb=array("!","\n"," ",",","@","\$", "%", "^", "*", "(", ")","[","]","{","}","<",">","+","À","Á","Â","Ã","Ä","Å","¨","Æ","Ç","È","É","Ê","Ë","Ì","Í","Î","Ï","Ğ","Ñ","Ò","Ó","Ô","Õ","Ö","×","Ø","Ù","Ú","Û","Ü","İ","Ş","ß","à","á","â","ã","ä","å","¸","æ","ç","è","é","ê","ë","ì","í","î","ï","ğ","ñ","ò","ó","ô","õ","ö","÷","ø","ù","ú","û","ü","ı","ş","ÿ","\'","\"");
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
		} */
		$message=$FLTR->DirectProcessText($message,1,0,1);

		
		include $file_read_users;

		//äàòà ğåãèñòğàöèè
		//ìåñÿöû ïî-ğóññêè
		for($n=0;$n<count($language['months']);$n++){
		//echo("[$n]".$months[$n]."=".$language['months'][$n]."<br>");
		$months[$n+1]=$language['months'][$n];
		}
		for($n=0;$n<count($language['weekdays']);$n++)
		{
		//echo("[$n]".$daysweek[$n]."=".$language['weekdays'][$n]);
		$daysweek[]=$language['weekdays'][$n];
		}
		//çîíà ïî Ãğèíâè÷ó (Ìîñêîâñêîå âğåìÿ)
		$zone=$vars['time_zone'];
		//Ìåñÿö
		$month=gmdate("n", time() + $zone);
		//echo("<br>month=".$months[$month-1]);
		//Äåíü íåäåëè
		$dayweek=gmdate("w", time() + $zone);
		//echo("<br>dayweek=".$daysweek[$dayweek]);
		//Òåêóùàÿ äàòà ïî Ãğèíâè÷ó (ÂĞÅÌß ÌÎÑÊÎÂÑÊÎÅ)
		$date=$daysweek[$dayweek];
		$date.=gmdate(", j ",time()+$zone);
		$date.=$months[$month];
		$date.=gmdate(", H:i", time() + $zone);
		//$date=gmdate("$daysweek[$dayweek], j $months[$month], H:i", $zone);
	

		if (check_auth())
			{
                        $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
                        $USR->SetSeparators($GV["sep1"],$GV["sep2"]);			
			$nick=$CURRENT_USER['login'];
			$g=0;
			while($users_info['login'][$g]!=$nick && $g<count($users_info))$g++;
			$user_index=$g;
			$users_info['count'][$user_index]++;
			$data=get_forum_user_data($users_info['id'][$user_index]);
			$data['count']=$users_info['count'][$user_index];
			save_forum_user_data($users_info['id'][$user_index],$data);

			$spec_rang=true;
				for($i=0;$i<$vars['rangs_count'];$i++)
				{
				if($users_info['rang'][$user_index]==$rangs[$i]['rang'])
					{
					$spec_rang=false;
					}
				}
				if($spec_rang==false)
				{
				for($i=0;$i<$vars['rangs_count'];$i++)
					{
					if($users_info['count'][$user_index]>=$rangs[$i]['count'])
						{
						$users_info['rang'][$user_index]=$rangs[$i]['rang'];
						}
					}
				}
			$ud=$USR->GetUserData($users_info['id'][$g]);
			$ud["rang"]=$users_info['rang'][$user_index];
                        $USR->SaveUser($ud);			
			}
		if(check_auth())
			{
			$zapis="$nick"."$smb"."$email"."$smb"."$url"."$smb"."$date"."$smb".time()."$smb"."$message"."$smb".$all_users[$user_index]."$smb1";
			}
			else
			{
			$zapis="$nick"."$smb"."$email"."$smb"."$url"."$smb"."$date"."$smb".time()."$smb"."$message"."$smb".""."$smb1";
			}
		$error=false;
		if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"a")))
			{
			echo("<center>Îøèáêà MA01! Íåâîçìîæíî äîáàâèòü ñîîáùåíèå</center>");	
			$error=true;
			}
			else
			{
			if(!(fwrite($fp,$zapis)))
				{
				echo("<center>Îøèáêà MA02! Íåâîçìîæíî äîáàâèòü ñîîáùåíèå!</center>");	
				$error=true;
				}
			}
		if ($error==false)		
			{
			$top=0;
			while($all_themes[$top]!=$topic)
				{
				$top++;
				}

			$links=0;
			for($j=0;$j<$themes_data[$top]['count']+1;$j=$j+$vars['kvo'])
				{
				$links++;
				}	

			echo("<meta http-equiv=\"Refresh\" content=\"1;URL='?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$links#last'\"><center>".$language['addmes'][14]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$links#last\">".$language['editmes'][1]."</a>");
			}
		}
	}
else
	{
	if(isset($quote)==true)
		{
		$reg=0;
		for($k=0;$k<count($all_users);$k++)
			{
			if(($all_messages[$tid]['nick'][$quote]==$users_info['login'][$k])&&($all_messages[$tid]['user_id'][$quote]==$all_users[$k])){$reg=1;$user_index=$k;}
			}
		if($reg==1)$nick=$users_info['nick'][$user_index];else $nick=$all_messages[$tid]['nick'][$quote];
		$mess_=$all_messages[$tid]['text'][$quote];
		$message=$mess_;
		include "$file_smiles_replace_back";
		$mess_=$message;
		$mess_="[QUOTE=".$nick."=QUOTE]".$mess_."[/QUOTE]";
		$mes1=$mess_;
		$mes1=strip_tags($mes1);
		}
		else
		{
		$mes1=str_replace("<br />","\n",$mes1);
		}
	$nic1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($nic1)))));
	$mes1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($mes1)))));
	$url1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url1)))));
	$ema1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($ema1)))));
	$user_index=-1;
	for($k=0;$k<count($all_users);$k++)
	{
	if($HTTP_SESSION_VARS['forum_login']==$users_info['login'][$k]){$user_index=$k;}
	}
	$theme_id=0;
	while($all_themes[$theme_id]!=$topic)
		{
		$theme_id++;
		}
	echo("<center>".$language['addmes'][8]." \"".$themes_data[$theme_id]['title']."\"<br><table width=100%><td width=50%>
	<form name=form1 action=\"?p=$p&forum_ext=$forum_ext&id=add_message&act=add&topic=$topic\" method=post><table width=100%><td width=100%><table width=100%><td width=40%>
	".$language['addmes'][9]."<td width=60%><input type=text name=nick value=\"");
	if ($nic1!="")echo($nic1); 
	else echo($users_info['nick'][$user_index]); 
	echo("\" maxlength=\"".$vars['nick_len']."\" class=inputbox style=\"width:100%\" ><tr><td>
	".$language['addmes'][10]."<td width=60%><input type=text name=email value=\"");
	if ($ema1!="")echo($ema1); 
	else echo($users_info['email'][$user_index]); 
	echo("\" maxlength=\"".$vars['email_len']."\" class=inputbox style=\"width:100%\" ><tr><td>
	".$language['addmes'][11]."<td width=60%><input type=text name=url value=\"");
	if ($url1!="")echo($url1); 
	else {if($users_info['url'][$user_index]!="")echo($users_info['url'][$user_index]); else{echo("http://");}}
	echo("\" maxlength=\"".$vars['url_len']."\" class=inputbox style=\"width:100%\" ><tr><td>
	".$language['addmes'][12]."<td width=60%><textarea class=inputbox style=\"width:100%\" cols=42 rows=7 name=message maxlength=\"".$vars['mes_len']."\" onclick='selectedtext = document.selection.createRange().duplicate();' onchange='selectedtext = document.selection.createRange().duplicate();'>$mes1</textarea></table><tr><td>
	<center><input type=submit value=\"".$language['addmes'][13]."\" class=button></table></form></center><td valign=top>
	");
	include "$file_smiles";
	echo("</table>");
	}

?>
