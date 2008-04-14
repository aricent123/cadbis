<?php
$reg=0;
$user_index=0;

global $HTTP_SERVER_VARS;
include $file_read_forums;
//проверяем, писал зарегеный юззер или нет...
for($k=0;$k<count($all_users);$k++)
	{
	if(($all_messages[$i]['nick'][$j]==$users_info['login'][$k])&&($all_messages[$i]['user_id'][$j]==$all_users[$k])){$reg=1;$user_index=$k;}
	//echo("'".$all_messages[$i]['nick'][$j]."'=='".$users_info['login'][$k]."'");       
	}
$user_num=$user_index;
	$str_url="http://".$HTTP_SERVER_VARS['HTTP_HOST'].$HTTP_SERVER_VARS['PHP_SELF']."?".$HTTP_SERVER_VARS['QUERY_STRING'];
//если незарегенный, то...
if($user_rights>4)$word="".$language['version_2.0'][69]."$j<br>";else $word="";
if ($reg==0)
	{
	echo("
	$table_messages"."$td_userinfo"."<a name=\"$j\"><SMALL>$word");
	if (($usr_view['nick']=="true"))
		{
		echo("<a href=\"JavaScript:do_text2('[B]".$all_messages[$i]['nick'][$j].", [/B]');\">".$all_messages[$i]['nick'][$j]."</a><br>");
		}
	if (($usr_view['avatar']=="true"))
		{
		echo("<img src=\"".$vars['dir_avatars']."/guest"."\">");
		}
	echo("<br>".$language['version_1.61'][1]."</SMALL>
	"."$td_messages"."<table width=100% border=0 valign=top height=100%><td valign=top width=100%>
	<SMALL><b>
	".$all_messages[$i]['date'][$j]."");
	if ($j==$themes_data[$i]['count']-1)
		{
		echo("<a name=last>");
		}
		echo(", ");
	if($all_messages[$i]['email'][$j]!="")
		{
		echo("<a href=\"mailto:".$all_messages[$i]['email'][$j]."\">");
		echo("".$all_messages[$i]['email'][$j]."</a>, ");
		}
	if($all_messages[$i]['url'][$j]!="")
		{
		echo("<a href=\"".$all_messages[$i]['url'][$j]."\">");	
		echo("".$all_messages[$i]['url'][$j]."</a>, ");
		}
	echo("</b></SMALL><td align=right><SMALL><a onclick=\"window.clipboardData.setData('Text','$str_url#$j');\" href=\"$str_url#$j\">".$language['version_2.0'][59]."</a>,<a href=\"?p=$p&forum_ext=$forum_ext&id=add_message&topic=$topic&quote=$j#post\">".$language['version_2.0'][60]."</a></SMALL><tr><td valign=top colspan=2  height=100%><br>");
	echo($all_messages[$i]['text'][$j]);

	if(($user_rights>4)&&($all_messages[$i]['nick'][$j]!=$HTTP_SESSION_VARS['forum_login']))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($m=0;$m<count($admins_f);$m++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
		}
	}
	if(($user_rights>4)&&($okk==true))
		{
		echo("<tr><td align=right colspan=2>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=edit&topic=$topic&mes=$j&page=".($page+1)."\">".$language['showmes'][0]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=delete&topic=$topic&mes=$j&page=".($page+1)."\">".$language['showmes'][1]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=transfer&topic=$topic&mes=$j&page=".($page+1)."\">".$language['version_1.61'][0]."</a>");
		}
	echo("</table>");
	echo("</table>");
	}
else
	{
	echo("
	$table_messages"."$td_userinfo<a name=\"$j\"><SMALL>$word"."");
		if ($usr_view['nick']=="true")
		{
		echo("<a href=\"JavaScript:do_text2('[B]".$users_info['nick'][$user_index].", [/B]');\">");
		//echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=userinfo&user_info=".$all_users[$user_index]."\" title=\"".$language['showmes'][2]."\">");
		echo("".$users_info['nick'][$user_index]."");
		echo("</a><br>");
		}
		if (($usr_view['avatar']=="true"))
		{
		echo("<a href=\"?p=users&act=userinfo&id=".$all_users[$user_index]."\" title=\"".$language['showmes'][2]."\">");
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
		if($users_info['sex'][$user_num]=="женский"){$sexx=$language['sex_woman'];}
		elseif($users_info['sex'][$user_num]=="мужской"){$sexx=$language['sex_man'];}
		else {$sexx=$users_info['sex'][$user_num];}
		echo("".$language['showmes'][3]." ".$sexx."<br>");
		}
		if ($usr_view['count']=="true")
		{
		echo("".$language['showmes'][4]." ".$users_info['count'][$user_index]."<br>");
		}
		if ($usr_view['raiting']=="true")
		{
		echo("".$language['showmes'][5]."<br> ");
			if($users_info1['raiting'][$user_index]>10)
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
	echo("</SMALL>"."$td_messages"."<table width=100% border=0 height=100% valign=top><td width=100% valign=top>
	<SMALL><b>
	".$all_messages[$i]['date'][$j]."");
	if ($j==$themes_data[$i]['count']-1)
		{
		echo("<a name=last>");
		}
		echo(", ");
	if($all_messages[$i]['email'][$j]!="")
		{
		echo("<a href=\"mailto:".$all_messages[$i]['email'][$j]."\">");
		echo("".$all_messages[$i]['email'][$j]."</a>, ");
		}
	if($all_messages[$i]['url'][$j]!="")
		{
		echo("<a href=\"".$all_messages[$i]['url'][$j]."\">");	
		echo("".$all_messages[$i]['url'][$j]."</a>, ");
		}
	echo("</b></SMALL><td align=right><SMALL><a onclick=\"window.clipboardData.setData('Text','$str_url#$j');\" href=\"$str_url#$j\">".$language['version_2.0'][59]."</a>,<a href=\"?p=$p&forum_ext=$forum_ext&id=add_message&topic=$topic&quote=$j#post\">".$language['version_2.0'][60]."</a></SMALL>
	<tr><td colspan=2 valign=top width=100% height=100%><br>");
	echo($all_messages[$i]['text'][$j]);
	if (($usr_view['sign']=="true")&&($users_info['sign'][$user_index]!=""))
	{
	echo("<tr><td colspan=2 valign=bottom><SMALL><br>==============================<br>".$users_info['sign'][$user_index]."</SMALL>");
	}

	if(($user_rights>4)&&($all_messages[$i]['nick'][$j]!=$HTTP_SESSION_VARS['forum_login']))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($m=0;$m<count($admins_f);$m++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])$okk=true;
		}
	}
	else $okk=true;
	if($okk==true)
		{
		if((($user_rights>$users_info['rights'][$user_index])&&($user_rights>4))||(($user_rights>=3&&$all_messages[$i]['nick'][$j]==$HTTP_SESSION_VARS['forum_login'])&&($all_messages[$i]['user_id'][$j]==$all_users[$user_index])))
		{
		echo("<tr><td align=right colspan=2 valign=bottom height=10%>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=edit&topic=$topic&mes=$j&page=".($page+1)."\">".$language['showmes'][0]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=delete&topic=$topic&mes=$j&page=".($page+1)."\">".$language['showmes'][1]."</a>, ");
		if($user_rights>4)echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=transfer&topic=$topic&mes=$j&page=".($page+1)."\">".$language['version_1.61'][0]."</a>");
		}
		}
	echo("</table>");
	echo("</table>");
	}
?>