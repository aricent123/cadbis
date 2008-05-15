<?php
$i=0;
while($all_themes[$i]!="$topic"){
$i++;
}
//echo("$user_rights==".$themes_data[$i]['rights']);
if($user_rights<$themes_data[$i]['rights'])
	{
	forbidden($themes_data[$i]['rights']);
	$error=true;
	}
if($error==false)
{
if($themes_data[$i]['names']!="")
	{
	$names=explode(",",$themes_data[$i]['names']);
	$ok=false;
	for($s=0;$s<count($names);$s++)
		{
		if($names[$s]==$CURRENT_USER["id"])$ok=true;
		}
	if(($ok==false)&&($user_rights<$themes_data[$i]['namesrights']))
		{
		forbidden($themes_data[$i]['namesrights']);
		$error=true;
		}
	}
}
if($error==false)
{
$viewed=false;

	$ipss=array();
	$ipss=explode($smb,$themes_data[$i]['ips']);
        $ip=get_ip_address();
	for($f=0;$f<count($ipss);$f++)
		{
		if($ip==$ipss[$f])$viewed=true;
		}
	if($viewed==false)
	{
	$themes_data[$i]['ips'].="$smb".$ip;
	$themes_data[$i]['views']++;
	$error=false;
	$zapis=$themes_data[$i]['title']."$smb".$themes_data[$i]['nick']."$smb".$themes_data[$i]['email']."$smb".$themes_data[$i]['url']."$smb".$themes_data[$i]['date']."$smb".$themes_data[$i]['views']."$smb".$themes_data[$i]['descr']."$smb".$themes_data[$i]['rights']."$smb".$themes_data[$i]['namesrights']."$smb".$themes_data[$i]['names']."$smb".$themes_data[$i]['status']."$smb1".$themes_data[$i]['ips'];
	$error=false;
	$fil=$topic;
	$fp=fopen($vars['dir_themes']."/"."$fil".".".$vars['theme_ext'],"w+");
	fwrite($fp,$zapis);
	fclose($fp);
	
	}
echo("<center>".$language['showtopic_viewtopic']." \"".$themes_data[$i]['title']."\"
<br><SMALL>
".$themes_data[$i]['descr']."
</SMALL>
");
if (isset($page)==false)$page=1;
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
	$mes_user_rights==$users_info['rights'][$usr_id];
	}
	else
	{
	$author=$themes_data[$i]['nick'];
	$mes_user_rights=-1;
	}
echo("<table><td>
".$language['showtopic_author']."<td>$author<tr><td>");
if ($themes_data[$i]['email']!="")
	{
	echo("".$language['showtopic_email']."<td><a href=\"mailto:".$themes_data[$i]['email']."\">".$themes_data[$i]['email']."</a><tr><td>");
	}
if ($themes_data[$i]['url']!="")
	{
	echo("".$language['showtopic_url']."<td><a href=\"".$themes_data[$i]['url']."\">".$themes_data[$i]['url']."</a>");
	}
echo("</table>");

$error=false;
include $file_read_forums;
if(($user_rights>4)&&($themes_data[$i]['nick']!=$CURRENT_USER["id"]))
	{
	$admins_f=explode(",",$forums['admins'][$forum_id]);
	$usr_id1=-1;
	$okk=false;
	for($m=0;$m<count($admins_f);$m++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$admins_f[$m])
			{
			$okk=true;
			}
		}
	if($okk==false)$error=true;
	}
if($error!=true)
{
if(($themes_data[$i]['nick']==$CURRENT_USER["id"])||(($user_rights>4)&&($user_rights>$mes_user_rights)))
	{
	if((($user_rights>4)&&($user_rights>$users_info['rights'][$usr_id]))||(($themes_data[$i]['nick']==$CURRENT_USER["id"])&&($user_rights>2)))
	{
	if(($themes_data[$i]['count']==0))
		{
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&page=$page\">".$language['showtopic_deletetopic']."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_transfer&topic=$topic&page=$page\">".$language['version_1.7'][3]."</a>, ");
		}
		elseif(($user_rights>4))
		{
		if($themes_data[$i]['status']!="closed")echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&act=close_topic&topic=$topic&page=$page\">".$language['version_2.0'][27]."</a>, ");
		else echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&act=open_topic&topic=$topic&page=$page\">".$language['version_2.0'][31]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&topic=$topic&page=$page\">".$language['showtopic_deletetopic']."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_transfer&topic=$topic&page=$page\">".$language['version_1.7'][3]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&act=delete_page&topic=$topic&page=$page\">".$language['version_2.0'][4]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_transfer&act=transfer_page&topic=$topic&page=$page\">".$language['version_2.0'][45]."</a>, ");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_delete&act=cleanup&topic=$topic&page=$page\">".$language['version_1.61'][20]."</a>, ");
		}
		$file2=$vars['dir_voting']."/".$topic.".".$vars['voting_ext'];
		if((file_exists($file2)==false))
			{
			echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=add_voting&topic=$topic&page=$page\">".$language['showtopic_addvoting']."</a>, ");
			}
			else
			{
			echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page&vote_act=delete\">".$language['showtopic_deletevoting']."</a>, ");	
			}
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=topic_edit&topic=$topic&page=$page\">".$language['showtopic_edit']."</a>");
	}
	}
}
echo(""."$table_all"."$td_all"."
");
include "$file_view_voting";
//$vars['kvo'];
$page--;
if (($themes_data[$i]['count']>$vars['kvo'])&&($show!="all"))
{
echo("".$language['showtopic_page']." ");
include "$file_make_links";
echo("<br>");
if (($page+1)*$vars['kvo']<$themes_data[$i]['count'])
	{
	echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_showedfrom']." ".($page*$vars['kvo']+1)." ".$language['showtopic_to']." ".(($page+1)*$vars['kvo'])."; <a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&show=all\">".$language['showtopic_showall']."</a>");
	for($j=$page*$vars['kvo'];$j<($page+1)*$vars['kvo'];$j++)
		{
		include "$file_show_messages";
		if ($j<$themes_data[$i]['count']-1)echo("<tr>"."$td_all"."");
		}
	echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_showedfrom']." ".($page*$vars['kvo']+1)." ".$language['showtopic_to']." ".(($page+1)*$vars['kvo'])."; <a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&show=all\">".$language['showtopic_showall']."</a>");
	}
	else
	{
	echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_showedfrom']." ".($page*$vars['kvo']+1)." ".$language['showtopic_to']." ".($themes_data[$i]['count'])."; <a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&show=all\">".$language['showtopic_showall']."</a>");
	for($j=$page*$vars['kvo'];$j<$themes_data[$i]['count'];$j++)
		{
		include "$file_show_messages";
		if ($j<$themes_data[$i]['count']-1)echo("<tr>"."$td_all"."");
		}
echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_showedfrom']." ".($page*$vars['kvo']+1)." ".$language['showtopic_to']." ".($themes_data[$i]['count'])."; <a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&show=all\">".$language['showtopic_showall']."</a>");
	}
echo("<br>");
echo("".$language['showtopic_page']." ");
include "$file_make_links";
}
elseif(($show=="all")||($themes_data[$i]['count']<=$vars['kvo']))
{
echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_allshowed']."");
if ($links<1){echo("<br>");}
if($show=="all")
	{
	echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['showtopic_showpage']."</a>");
	}
for($j=0;$j<$themes_data[$i]['count'];$j++)
	{
	include "$file_show_messages";
	if ($j<$themes_data[$i]['count']-1)echo("<tr>"."$td_all"."");
	}
if ($links<1){echo("<br>");}
echo("".$language['showtopic_total']."".$themes_data[$i]['count']."; ".$language['showtopic_allshowed']."");
if ($links<1){echo("<br>");}
if($show=="all")
	{
	echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic\">".$language['showtopic_showpage']."</a>");
	}

}
echo("</table>");

echo("<center>");
if(($user_rights>0)&&($themes_data[$i]['status']!="closed"))
	{
	echo("<a name=post>");
	include "$file_add_message";
	}
}
?>