<?php
include "$file_read_forums";

for($i=0;$i<count($forums['title']);$i++)
	{
	$f_count=0;
	$hdl=opendir($vars['dir_themes']."/");
	$last_upd=0;
	while($file=readdir($hdl))
		{
		if(strstr($file,".".$forums['ext'][$i]))
			{
			$_file1_=file($vars['dir_themes']."/".substr($file,0,-strlen(".".$forums['ext'][$i])).".".$vars['mess_ext']);
			$_file1__=implode("",$_file1_);
			$_file_=explode("$smb1",$_file1__);
			$count_mes=count($_file_);
			//$_file=implode("",$_file_);
			//echo($_file_[count($_file_)-1]);
			//echo($file.":".$_file_[count($_file_)-2]."<hr>");
			//exit;
			$_mess=explode("$smb",$_file_[count($_file_)-2]);
			//echo($_mess[4].">".$last_upd."<br>");
			if($_mess[4]>$last_upd)
				{
				$last_upd=$_mess[4];
				$buf=implode("",file($vars['dir_themes']."/".$file));
				$top_title=substr($buf,0,strpos($buf,"$smb"));
				if(is_user_exists($_mess[6]))
					{
					$ud=get_user_data($_mess[6]);
					$forums['last'][$i]="\"$top_title\"<br><a href=\"?p=users&act=userinfo&id=".$_mess[6]."\">".$ud["nick"]."</a>, ".$_mess[3];
					}
					else
					{
					$forums['last'][$i]="\"$top_title\"<br>".$_mess[0].", ".$_mess[3];
					}
				
				$links=0;
				for($j=0;$j<$count_mes-1;$j=$j+$vars['kvo'])
					{
					$links++;
					}	
				$forums['pages'][$i]=$links;
				$forums['last_topic'][$i]=substr($file,0,-strlen(".".$forums['ext'][$i]));
				}
			//echo($_mess[4]."<br>");
			$f_count++;
			}
		}
	$forums['time'][$i]=$last_upd;
	$forums['topics'][$i]=$f_count;
	}

echo("<CENTER>$table_all"."$td_all
".$language['version_1.7'][24]."$td_all
".$language['version_1.7'][21]."$td_all
".$language['version_1.7'][22]."$td_all
".$language['version_1.7'][23]."$td_all
".$language['version_2.0'][9]."$td_all
".$language['version_1.7'][2]."$td_all
".$language['version_1.7'][20]."<tr>$td_all
");

if(check_auth())
 $data=get_forum_user_data($CURRENT_USER["id"]);
else 
 $data=NULL;
$forum_dat=$data['forum_data'];
//echo($forum_dat);
//exit;
$_vrs=explode("{&}",$forum_dat);
$forum_dat="";
for($i=0;$i<count($_vrs);$i++)
	{
	$_vrs1=explode("=",$_vrs[$i]);
	$forum_names[]=$_vrs1[0];
	$forum_times[]=$_vrs1[1];
	//echo($_vrs1[0]."<br>".$_vrs1[1]."<br>");
	}
//exit;
for($i=0;$i<count($forums['title']);$i++)
	{
	for($k=0;$k<count($forum_names);$k++)
		{
		if($forum_names[$k]==$forums['ext'][$i]){break;}
		}
	if($forum_times[$k]<$forums['time'][$i])echo("<img src=\"$file_y_new\">");else echo("<img src=\"$file_n_new\">");
	echo($td_all);
	echo("<a href=\"?p=$p&id=set_forum&forum_ex=".$forums['ext'][$i]."\">".$forums['title'][$i]."</a>$td_all");
	echo("<SMALL>".$forums['descr'][$i]."</SMALL>$td_all");
	echo($forums['topics'][$i]."$td_all");

	if($forums['last'][$i]!="")echo("<SMALL>".$forums['last'][$i]."<a href=\"?p=$p&forum_to=".$forums['ext'][$i]."&forum_ext=".$forums['ext'][$i]."&id=showtopic1&topic=".$forums['last_topic'][$i]."&page=".$forums['pages'][$i]."#last\"><img border=0 src=\"$file_last\"></a></SMALL>");
	echo("$td_all");
	$admins_f=explode(",",$forums['admins'][$i]);
	echo("<SMALL>");
	for($j=0;$j<count($admins_f);$j++)
		{
		$usr_id=0;
		while($users_info['login'][$usr_id]!=$admins_f[$j]){$usr_id++;if($usr_id>count($users_info['login']))break;}
		echo($users_info['nick'][$usr_id]);
		if($j<count($admins_f)-1)echo(",");
		}
	echo("</SMALL>$td_all");
	if($user_rights>=$forums['rights'][$i])echo("<img width=30 src=\"$file_r_ok\">"."");
	else echo("<img width=30 src=\"$file_r_no\">"."");
	if($i<count($forums['title'])-1)echo("<tr>$td_all");
	}
echo("</table>
<table><td>
<img src=\"$file_y_new\"><td>
".$language['version_1.7'][25]."
<tr><td>
<img src=\"$file_n_new\"><td>
".$language['version_1.7'][26]."
</table>
<a href=?p=$p&id=forums&allf=true>".$language['version_2.0'][58]."</a>
</CENTER>");
?>