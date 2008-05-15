<?php
$top_id=0;
while($all_themes[$top_id]!=$topic)$top_id++;
$ok=true;
$usr_id=-1;
$reg=0;

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



for($r=0;$r<count($all_users);$r++)
		{
		if(($users_info['login'][$r]==$all_messages[$top_id]['nick'][$mes])&&($all_users[$r]==$all_messages[$top_id]['user_id'][$mes])){$reg=1;$usr_id=$r;}
		}
if((($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])||($all_messages[$top_id]['user_id'][$mes]!=$all_users[$usr_id]))&&($user_rights<5))
	{
	$ok=false;
	}
if($reg==1) 
	{
	if(($user_rights<=$users_info['rights'][$usr_id])&&($all_messages[$top_id]['nick'][$mes]!=$HTTP_SESSION_VARS['forum_login'])) 
		{
		$ok=false;
		}
	}


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
	if($act=="edit")
		{


/////////////////////////////ÏĞÎÂÅĞßÅÌ ÏĞÀÂÈËÜÍÎÑÒÜ ÇÀÏÎËÍÅÍÈß\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;

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
////////////////////////ÅÑËÈ ÅÑÒÜ ÎØÈÁÊÀ, ÏÈØÅÌ ŞÇÅĞÓ ÑÑÛËÊÓ ÎÁĞÀÒÍÎ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$message=strip_tags($message);
$nick=strip_tags($nick);
$email=strip_tags($email);
$url=strip_tags($url);
$message=nl2br($message);
echo("<center><br>".$language['addmes'][7]." <a href=\"?p=$p&forum_ext=$forum_ext&id=edit&topic=$topic&mes=$mes&page=$page&nic1=$nick&ema1=$email&url1=$url&mes1=$message\">ÍÀÇÀÄ</a></center>");
}
//end

if ($error!=true)
		{
		if($url=="http://")$url="";
		////////////////////////////////////ĞÅÄÀÊÒÈĞÓÅÌ ÏÅĞÅÌÅÍÍÛÅ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
		
		$email=strip_tags($email);
		$email=str_replace("$smb","",$email);
		$email=substr($email,0,$vars['email_len']);
		$email=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($email)))));
		
		$url=strip_tags($url);
		$url=str_replace("$smb","",$url);
		$url=substr($url,0,$vars['url_len']);
		$url=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($url)))));
		
		$message=htmlspecialchars($message);
		$message=str_replace("$smb","",$message);
		if($user_rights<5)$message=substr($message,0,$vars['mes_len']);
		$message=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($message)))));
		
		if($vars['mat_filter']=="true")
			{
			include "$file_mat_filter";
			$message=str_replace($unsymb,$vars['mat_filter_word'],$message);
			}
	

		include "$file_smiles_replace";
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
		
		
		$all_messages[$top_id]['email'][$mes]=$email;
		$all_messages[$top_id]['url'][$mes]=$url;
		$all_messages[$top_id]['text'][$mes]=$message;

		$error=false;
		if(!($fp=fopen($vars['dir_themes']."/"."$topic".".".$vars['mess_ext'],"w")))
			{
			echo("<center>Îøèáêà ME01! Íåâîçìîæíî èçìåíèòü ñîîáùåíèå</center>");	
			$error=true;
			}
			else
			{
			for($i=0;$i<$themes_data[$top_id]['count'];$i++)
				{
				$zapis=$all_messages[$top_id]['nick'][$i]."$smb".$all_messages[$top_id]['email'][$i]."$smb".$all_messages[$top_id]['url'][$i]."$smb".$all_messages[$top_id]['date'][$i]."$smb".$all_messages[$top_id]['time'][$i]."$smb".$all_messages[$top_id]['text'][$i]."$smb".$all_messages[$top_id]['user_id'][$i]."$smb1";
				fwrite($fp,$zapis);
				}
			fclose($fp);
			}
		if ($error==false)		
			{
			echo("<center>".$language['editmes'][0]."<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page#$mes\">".$language['editmes'][1]."</a>");
			}
		}

		}
		else
		{
		$mes1=$FLTR->ReverseProcessText($mes1);
		$url1=$FLTR->ReverseProcessString($url1);
		$ema1=$FLTR->ReverseProcessString($ema1);	
		echo("<CENTER><table><td><table><td>
		<form name=form1 action=?p=$p&forum_ext=$forum_ext&id=edit&topic=$topic&mes=$mes&page=$page&act=edit method=post><table><td>
		".$language['editmes'][2]."<td>".$all_messages[$top_id]['date'][$mes]."<tr><td>");
		if($reg==1)echo("".$language['editmes'][3]."<td>".$users_info['nick'][$user_id]."<tr><td>");
		else echo("".$language['editmes'][3]."<td>".$all_messages[$top_id]['nick'][$mes]."<tr><td>");
		echo("".$language['editmes'][4]."<td><input type=text name=email value=\"");
		if ($ema1!="")echo($ema1); 
		else echo($all_messages[$top_id]['email'][$mes]); 
		echo("\" maxlength=\"".$vars['email_len']."\" style=\"width:300;\"><tr><td>
		".$language['editmes'][5]."<td><input type=text name=url value=\"");
		if ($url1!="")echo($url1); 
		else {if($all_messages[$top_id]['url'][$mes]!="")echo($all_messages[$top_id]['url'][$mes]); else{echo("http://");}}
		echo("\" style=\"width:300;\"><tr><td>");
		$message=$all_messages[$top_id]['text'][$mes];
		include "$file_smiles_replace_back";
		$all_messages[$top_id]['text'][$mes]=$message;
		$all_messages[$top_id]['text'][$mes]=strip_tags($all_messages[$top_id]['text'][$mes]);

		if($mes1!="")
			{
			echo("".$language['editmes'][6]."<td><textarea cols=42 rows=7  name=message maxlength=\"".$vars['mes_len']."\" onclick='selectedtext = document.selection.createRange().duplicate();' onchange='selectedtext = document.selection.createRange().duplicate();'>$mes1</textarea></table><tr><td>");
			}
			else
			{
			echo("".$language['editmes'][6]."<td><textarea cols=42 rows=7  name=message maxlength=\"".$vars['mes_len']."\" onclick='selectedtext = document.selection.createRange().duplicate();' onchange='selectedtext = document.selection.createRange().duplicate();'>".$all_messages[$top_id]['text'][$mes]."</textarea></table><tr><td>");
			}
		echo("<center><input type=submit value=\"".$language['editmes'][7]."\"></form></table><td valign=top>");
		include "$file_smiles";
		echo("</table><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page#$mes\">".$language['editmes'][8]."</a></center>");
		}
	}
	else
	{
	echo("<center><b>FORBIDDEN!</b></centeR>");
	}

?>