<?php
$i=0;
while($all_themes[$i]!="$topic"){
$i++;
}
$usr_id=0;
for($g=0;$g<count($all_users);$g++)
	{
	if($users_info['login'][$g]==$themes_data[$i]['nick'])
		{
		$usr_id=$g;
		$reg=1;
		}
	}
if($reg==1)
	{
	$mes_user_rights=$users_info['rights'][$usr_id];
	}
	else
	{
	$mes_user_rights=-1;
	}
if($page==0)$page=1;
$error=true;
if(($themes_data[$i]['nick']==$CURRENT_USER["id"])||($user_rights>4))
	{
	if(($user_rights>$mes_user_rights)||($themes_data[$i]['nick']==$CURRENT_USER["id"]))
	{
	$error=false;
	}
	}

include $file_read_forums;
if(($user_rights>4)&&($themes_data[$i]['nick']!=$CURRENT_USER["id"]))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($m=0;$m<count($admins_f);$m++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
		}
	if($okk==false)$error=true;
	}

if($error==false)
{
if($act=="edit")
	{
	




/////////////////////////////ÏĞÎÂÅĞßÅÌ ÏĞÀÂÈËÜÍÎÑÒÜ ÇÀÏÎËÍÅÍÈß\\\\\\\\\\\\\\\\\\\\\\\\\\\

//begin
$error=false;
if (strlen($topic1)<$vars['title_min'])
	{
	echo("<center><br><b>".$language['addtopic_titlemin']." ".$vars['title_min']." ".$language['addtopic_symbols']."!</b></center>");
	$error=true;
	}
if (strlen($nick)<$vars['nick_min'])
	{
	echo("<center><br><b>".$language['addtopic_nickmin']." ".$vars['nick_min']." ".$language['addtopic_symbols']."!</b></center>");
	$error=true;
	}
if (strlen($email)<$vars['email_min'])
	{
	echo("<center><br><b>".$language['addtopic_emailmin']." ".$vars['email_min']." ".$language['addtopic_symbols']."!</b></center>");
	$error=true;
	}
if (strlen($url)<$vars['url_min'])
	{
	echo("<center><br><b>".$language['addtopic_urlmin']." ".$vars['url_min']." ".$language['addtopic_symbols']."!</b></center>");
	$error=true;
	}
if (strlen($desc1)<$vars['desc_min'])
	{
	echo("<center><br><b>".$language['addtopic_descrmin']." ".$vars['desc_min']." ".$language['addtopic_symbols']."!</b></center>");
	$error=true;
	}
for($f=0;$f<count($all_users);$f++)
	{
	if (($nick==$users_info['login'][$f])&&($HTTP_SESSION_VARS['forum_login']!=$users_info['login'][$f])&&($user_rights<=$themes_data[$i]['rights']))
		{
		echo("<center><br><b>".$language['addtopic_nickforb']."</b></center>");
		$error=true;
		}
	}
////////////////////////ÅÑËÈ ÅÑÒÜ ÎØÈÁÊÀ, ÏÈØÅÌ ŞÇÅĞÓ ÑÑÛËÊÓ ÎÁĞÀÒÍÎ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\
if ($error==true)	
{
$desc=strip_tags($desc);
$nick=strip_tags($nick);
$email=strip_tags($email);
$url=strip_tags($url);
$topic1=strip_tags($topic1);
$desc1=nl2br($desc1);
echo("<center><br>".$language['addmes'][7]." <a href=\"?p=$p&forum_ext=$forum_ext&id=topic_edit&topic=$topic&page=$page\">".$language['editmes'][8]."</a></center>");
}
//end

if ($error!=true)
{
		if($url=="http://")$url="";		
////////////////////////////////////ĞÅÄÀÊÒÈĞÓÅÌ ÏÅĞÅÌÅÍÍÛÅ\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\

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

$desc1=htmlspecialchars($desc1);
$desc1=str_replace("$smb","",$desc1);
$desc1=substr($desc1,0,$vars['desc_len']);
$desc1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($desc1)))));

$topic1=htmlspecialchars($topic1);
$topic1=str_replace("$smb","",$topic1);
$topic1=substr($topic1,0,$vars['title_len']);
$topic1=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($topic1)))));
 
$names=strip_tags($names);
$names=str_replace("$smb","",$names);
$names=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($names)))));

if($user_rights<5)
	{
	$names=$themes_data[$i]['names'];
	$nrights=$themes_data[$i]['namesrights'];
	}
	else
	{
	$nrights=$user_rights;
	}

$t_rights=strip_tags($t_rights);
$t_rights=str_replace("$smb","",$t_rights);
$t_rights=substr($t_rights,0,1);

if($t_rights>7) $t_rights=7;
if($t_rights>$user_rights) $t_rights=$user_rights;

if($t_rights<0) $t_rights=0;

if ($themes_data[$i]['nick']==$HTTP_SESSION_VARS['forum_login'])
	{
	$nick=$HTTP_SESSION_VARS['forum_login'];
	}
	else
	{
	$nick=$themes_data[$i]['nick'];
	}

$i=0;
while($all_themes[$i]!="$topic"){
$i++;
}
$themes_data[$i]['title']=$topic1;
$themes_data[$i]['nick']=$nick;
$themes_data[$i]['email']=$email;
$themes_data[$i]['url']=$url;
$desc1=$FLTR->DirectProcessText($desc1,1,0,1);
$themes_data[$i]['descr']=$desc1;
$themes_data[$i]['rights']=$t_rights;

$err=false;
$zapis=$themes_data[$i]['title']."$smb".$themes_data[$i]['nick']."$smb".$themes_data[$i]['email']."$smb".$themes_data[$i]['url']."$smb".$themes_data[$i]['date']."$smb".$themes_data[$i]['views']."$smb".$themes_data[$i]['descr']."$smb".$themes_data[$i]['rights']."$smb".$nrights."$smb".$names."$smb".$themes_data[$i]['status']."$smb1".$themes_data[$i]['ips'];
	$error=false;
	$fil=$topic;
		if(!($fp=fopen($vars['dir_themes']."/"."$fil".".".$vars['theme_ext'],"w+")))
			{
			echo("<center>Îøèáêà TCH01! Íåâîçìîæíî èçìåíèòü òåìó</center>");	
			$err=true;
			}
			else
			{
			if(!(fwrite($fp,$zapis)))
				{
				echo("<center>Îøèáêà TCH02! Íåâîçìîæíî  èçìåíèòü òåìó</center>");		
				$err=true;
				}
			}
echo("<CENTER>");
if($err==false){
echo("".$language['topicedit'][0]."<br>");
}
echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['editmes'][8]."</a></CENTER>");
}
	}
	else
	{
	$reg=0;
	$usr_id=0;
	for($g=0;$g<count($all_users);$g++)
		{
		if($users_info['login'][$g]==$themes_data[$i]['nick'])
			{
			$usr_id=$g;
			$reg=1;
			}
		}
	if($reg==1)
	{
	$author=$users_info['nick'][$usr_id];
	}
	else
	{
	$author=$themes_data[$i]['nick'];
	}
	echo("<CENTER>".$language['topicedit'][1]." \"".$themes_data[$i]['title']."\" ?<table><td>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=topic_edit&topic=$topic&act=edit\" method=post>
	".$language['topicedit'][2]."<td><input type=text name=topic1 value=\"".$themes_data[$i]['title']."\" style=\"width:300;\"><tr><td>
	".$language['topicedit'][3]."<td><input type=text name=nick value=\"".$author."\" style=\"width:300;\"><tr><td>
	".$language['topicedit'][4]."<td><input type=text name=email value=\"".$themes_data[$i]['email']."\" style=\"width:300;\"><tr><td>
	".$language['topicedit'][5]."<td><input type=text name=url value=\"".$themes_data[$i]['url']."\" style=\"width:300;\"><tr><td>
	".$language['topicedit'][6]."<td><select name=t_rights style=\"width:300;\">");
	for($j=0;$j<=$user_rights;$j++)
		{
		if ($j==$themes_data[$i]['rights'])echo("<option value=$j selected>$j"); else echo("<option value=$j>$j");
		}
	echo("
	</select><tr><td>
	".$language['topicedit'][7]."<td><TEXTAREA name=desc1 style=\"width:300;height:80\">".$FLTR->ReverseProcessText($themes_data[$i]['descr'])."</TEXTAREA>");
	if($user_rights>4)
		{
		echo("<tr><td>".$language['topicedit'][9]."".$language['topicedit'][10]."<td><input type=text name=names value=\"".$themes_data[$i]['names']."\" style=\"width:300;\">");
		}
	echo("
	");
	echo("</table>
	<input type=submit value=\"".$language['topicedit'][8]."\"></FORM>
	<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['editmes'][8]."</a></CENTER>");

	}
}
?>