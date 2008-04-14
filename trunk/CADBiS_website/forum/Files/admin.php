<?php
/*
if($act=="user_admin")
	{
	echo("<center><b>".$language['admin_vars01_01']."</b></center>");
	include $file_read_users;
	for($k=0;$k<count($all_users);$k++)
		{
		if($userr==$all_users[$k]){$reg=1;$usr_id=$k;}
		}
	$error=false;
	if((($user_rights<8)&&($users_info['rights'][$usr_id]>6)&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login']))||(($users_info['rights'][$usr_id]>=$user_rights)&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login'])))
		{
		$error=true;
		forbidden(8);
		}
	if($error==false)                   
	{
	echo("<CENTER>".$table_all.$td_all."
	".$language['admin_vars01_02']."".$td_all."".$all_users[$usr_id]."<input type=submit  value=\"".$language['admin_vars01_03']."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin_delete&userr=$userr'\"><tr>".$td_all);
	if((file_exists($vars['dir_avatars']."/".$all_users[$usr_id].".gif")))
		{
		echo("
		".$language['admin_vars01_04']."".$td_all."
		<img border=0 src=\"".$vars['dir_avatars']."/".$all_users[$usr_id].".gif"."\">"."<tr>".$td_all."
		");
		}
	echo("<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin_edit&userr=$userr\" method=post>
	".$language['admin_vars01_05']."".$td_all."<input type=text name=var[] value=\"".$users_info['login'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_06']."".$td_all."<input type=text name=var[] value=\"".$users_info['pass'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_07']."".$td_all."<input type=text name=var[] value=\"".$users_info['nick'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_08']."".$td_all."<input type=text name=var[] value=\"".$users_info['city'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_09']."".$td_all."<input type=text name=var[] value=\"".$users_info['sex'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_10']."".$td_all."<input type=text name=var[] value=\"".$users_info['email'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_11']."".$td_all."<input type=text name=var[] value=\"".$users_info['url'][$usr_id]."\"  style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_12']."".$td_all."<input type=text name=var[] value=\"".$users_info['date'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_13']."".$td_all."<input type=text name=var[] value=\"".$users_info['rang'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_14']."".$td_all."<input type=text name=var[] value=\"".$users_info['count'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	(".$users_info1['raiting'][$usr_id].") ".$language['admin_vars01_15']."".$td_all."<input type=text name=var[] value=\"".$users_info['raiting'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_16']."".$td_all."<input type=text name=var[] value=\"".$users_info['rights'][$usr_id]."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01_17']."".$td_all."<TEXTAREA name=var[] style=\"width:300; height:50\">".$users_info['sign'][$usr_id]."</textarea><tr>".$td_all."
	".$language['admin_vars01_18']."".$td_all."<TEXTAREA name=var[] style=\"\" name=info style=\"width:300; height:50\">".$users_info['info'][$usr_id]."</textarea>
	</table>
	<input type=submit value=\"".$language['admin_vars01_19']."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a></CENTER>");	
	}
	}
elseif($act=="user_admin_edit")
	{
	for($k=0;$k<count($all_users);$k++)
		{
		if($userr==$all_users[$k]){$reg=1;$usr_id=$k;}
		}
	$error=false;
	if(($user_rights<7)&&($users_info['rights'][$usr_id]>5)&&($users_info['login'][$usr_id]!=$HTTP_SESSION_VARS['forum_login']))
		{
		$error=true;
		forbidden(7);
		}
	if($error==false)
	{
	for($i=0;$i<count($var);$i++)
		{
		$var[$i]=str_replace("$smb","",$var[$i]);
		$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
		$var[$i]=str_replace("''","\"",$var[$i]);
		}
	$login2=$var[0];
	$pass2=$var[1];
	$nick2=$var[2];
	$city2=$var[3];
	$sex2=$var[4];
	$email2=$var[5];
	$url2=$var[6];
	$date2=$var[7];
	$rang2=$var[8];
	$count2=$var[9];
	$raiting2=$var[10];
	$rights2=$var[11];
	$sign2=$var[12];
	$info2=$var[13];

	if(($user_rights<8)&&($rights2>7))$rights2=7;
	if(($user_rights<7)&&($rights2>6))$rights2=6;
	$user_info=$login2."$smb".$pass2."$smb".$nick2."$smb".$city2."$smb".$sex2."$smb".$email2."$smb".$url2."$smb".$date2."$smb".$rang2."$smb".$count2."$smb".$raiting2."$smb".$rights2."$smb".$sign2."$smb".$info2;
	$user_index=$usr_id;
	for($k=0;$k<count($user_messages[$user_index]['from']);$k++)
	{
	$user_info.="$smb1".$user_messages[$user_index]['from'][$k]."$smb".$user_messages[$user_index]['subject'][$k]."$smb".$user_messages[$user_index]['status'][$k]."$smb".$user_messages[$user_index]['date'][$k]."$smb".$user_messages[$user_index]['time'][$k]."$smb".$user_messages[$user_index]['priority'][$k]."$smb".$user_messages[$user_index]['text'][$k];
	}
	if(!($fp=fopen($vars['dir_users']."/$userr","w+")))
		{
		//если чего-то не получается- значит ошибка!
		echo("<center><font size=6><b>ОШИБКА ACHP01!<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin&userr=$userr>".$language['admin_back']."</a></b></font></center>");
		}
	$ok=false;
	if (fwrite($fp,$user_info)) $ok=true;
	fclose($fp);
	if($ok==true)
		{
		echo("<center>".$language['admin_vars02_06']."<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin&userr=$userr>".$language['admin_back']."</a></center>");
		}
		else
		{
		//если чего-то не получается- значит ошибка!
		echo("<center><font size=6><b>ОШИБКА ACHP02!<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin&userr=$userr>".$language['admin_back']."</a></b></font></center>");
		}
	}
	}
elseif($act=="user_admin_delete")
	{
	echo("<center><b>".$language['admin_vars02_01']."</b></center>");
	if($do!="true")
		{
		for($k=0;$k<count($all_users);$k++)
		{
		if($userr==$all_users[$k]){$reg=1;$usr_id=$k;}
		}
		echo("<CENTER><B><BIG>".$language['admin_vars02_02']."".$users_info['nick'][$usr_id]." ?</b></BIG>");
		echo("<input type=submit value=\"".$language['admin_vars02_03']."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin_delete&userr=$userr&do=true';\">");
		echo("<input type=submit value=\"".$language['admin_vars02_04']."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin&userr=$userr';\">");
		echo("</CENTER>");
		}
		else
		{
		echo("<CENTER>");
		$error=false;
		if(!unlink($vars['dir_users']."/".$userr))$error=true;
		if(file_exists($vars['dir_avatars']."/".$userr.".gif")==true)
			{
			if(!unlink($vars['dir_avatars']."/".$userr.".gif"))$error=true;
			}
		if($error==false)echo("".$language['admin_vars02_05']."<br>"); else echo("".$language['admin_vars02_07']."<br>");
		echo("<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a></CENTER>");
		}
	}
else
*/
if($act=="var_admin1")
{
echo("<center><b>".$language['admin_vars01'][0]."</b></center>");
if($do!="true")
	{
	echo("<CENTER>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin1&do=true\" method=post>
	<u>".$language['admin_vars01'][1]."</u><br>
	".$table_all.$td_all."");
	$form_name=str_replace("\"","''",$vars['forum_name']);
	echo("
	".$language['admin_vars01'][2]."".$td_all."<input type=text name=var[] value=\"".$vars['your_email']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][3]."".$td_all."<input type=text name=var[] value=\"".$vars['your_name']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][4]."".$td_all."<input type=text name=var[] value=\"".$vars['forum_addr']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][5]."".$td_all."<input type=text name=var[] value=\"".$form_name."\" style=\"width:300;\"></table>
	<u>".$language['admin_vars01'][6]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars01'][7]."".$td_all."<input type=text name=var[] value=\"".$vars['login_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][8]."".$td_all."<input type=text name=var[] value=\"".$vars['login_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][9]."".$td_all."<input type=text name=var[] value=\"".$vars['pass_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][10]."".$td_all."<input type=text name=var[] value=\"".$vars['pass_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][11]."".$td_all."<input type=text name=var[] value=\"".$vars['nick_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][12]."".$td_all."<input type=text name=var[] value=\"".$vars['nick_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][44]."".$td_all."<input type=text name=var[] value=\"".$vars['email_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][45]."".$td_all."<input type=text name=var[] value=\"".$vars['email_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][13]."".$td_all."<input type=text name=var[] value=\"".$vars['url_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][14]."".$td_all."<input type=text name=var[] value=\"".$vars['url_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][15]."".$td_all."<input type=text name=var[] value=\"".$vars['city_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][16]."".$td_all."<input type=text name=var[] value=\"".$vars['city_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][17]."".$td_all."<input type=text name=var[] value=\"".$vars['sign_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][18]."".$td_all."<input type=text name=var[] value=\"".$vars['sign_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][19]."".$td_all."<input type=text name=var[] value=\"".$vars['info_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][20]."".$td_all."<input type=text name=var[] value=\"".$vars['info_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][21]."".$td_all."<input type=text name=var[] value=\"".$vars['title_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][22]."".$td_all."<input type=text name=var[] value=\"".$vars['title_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][23]."".$td_all."<input type=text name=var[] value=\"".$vars['desc_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][24]."".$td_all."<input type=text name=var[] value=\"".$vars['desc_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][25]."".$td_all."<input type=text name=var[] value=\"".$vars['mes_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][26]."".$td_all."<input type=text name=var[] value=\"".$vars['mes_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][27]."".$td_all."<input type=text name=var[] value=\"".$vars['subj_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][28]."".$td_all."<input type=text name=var[] value=\"".$vars['subj_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][29]."".$td_all."<input type=text name=var[] value=\"".$vars['pmes_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][30]."".$td_all."<input type=text name=var[] value=\"".$vars['pmes_min']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][31]."".$td_all."<input type=text name=var[] value=\"".$vars['word_len']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars01'][32]."".$td_all."<input type=text name=var[] value=\"".$vars['smls_count']."\" style=\"width:300;\"></table>
	
	<u>".$language['admin_vars01'][33]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars01'][34]."".$td_all."<input type=text name=var[] value=\"".$vars['time_zone']."\" style=\"width:150;\">(".gmdate("d/m/Y h:i:s",time()+$vars['time_zone']).")<tr>".$td_all."	
	".$language['admin_vars01'][35]."".$td_all."<input type=text name=var[] value=\"".$vars['avatar_maxwidth']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['admin_vars01'][36]."".$td_all."<input type=text name=var[] value=\"".$vars['avatar_maxheight']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['admin_vars01'][37]."".$td_all."<input type=text name=var[] value=\"".$vars['avatar_maxsize']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['admin_vars01'][38]."".$td_all."<input type=text name=var[] value=\"".$vars['kvo']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['admin_vars01'][39]."".$td_all."<input type=text name=var[] value=\"".$vars['t_kvo']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['version_1.6'][20]."".$td_all."<input type=text name=var[] value=\"".$vars['file_maxsize']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['version_1.6'][21]."".$td_all."<input type=text name=var[] value=\"".$vars['file_maxdesc']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['version_1.6'][27]."".$td_all."<input type=text name=var[] value=\"".$vars['file_maxlen']."\" style=\"width:300;\"><tr>".$td_all."	
	".$language['admin_vars01'][40]."".$td_all."<input type=text name=var[] value=\"".$vars['mat_filter_word']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['version_2.0'][57]."".$td_all."<input type=text name=var[] value=\"".$vars['pers_color']."\" style=\"width:300;\"><tr>".$td_all."
	");	
	echo("".$language['admin_vars01'][46]."".$td_all."<select name=var[]>");
	echo("<option value=\"all\" ");
	if($vars['language']=="all")echo("selected");	
	echo(">".$language['admin_vars01'][47]."");
	for($j=0;$j<count($languages);$j++)
		{
		echo("<option value=\"".$languages[$j]."\" ");
		if($vars['language']==$languages[$j])echo("selected");
		echo(">".$languages[$j]."");
		}
	echo("</select><tr>".$td_all."");
	echo("".$language['admin_vars01'][41]."".$td_all."<input type=text name=var[] value=\"".$vars['time_exit']."\" style=\"width:300;\">
	");
	echo("</table><input type=submit value=\"".$language['admin_vars01'][42]."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
}
else
{
for($i=0;$i<count($var);$i++)
	{
	$var[$i]=str_replace("[}={]","",$var[$i]);
	$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
	$var[$i]=str_replace("''","\"",$var[$i]);
	}
$vars['your_email']=$var[0];
$vars['your_name']=$var[1];
$vars['forum_addr']=$var[2];
$vars['forum_name']=$var[3];

$vars['login_len']=$var[4];
$vars['login_min']=$var[5];
$vars['pass_len']=$var[6];
$vars['pass_min']=$var[7];
$vars['nick_len']=$var[8];
$vars['nick_min']=$var[9];
$vars['email_len']=$var[10];
$vars['email_min']=$var[11];
$vars['url_len']=$var[12];
$vars['url_min']=$var[13];
$vars['city_len']=$var[14];
$vars['city_min']=$var[15];
$vars['sign_len']=$var[16];
$vars['sign_min']=$var[17];
$vars['info_len']=$var[18];
$vars['info_min']=$var[19];
$vars['title_len']=$var[20];
$vars['title_min']=$var[21];
$vars['desc_len']=$var[22];
$vars['desc_min']=$var[23];
$vars['mes_len']=$var[24];
$vars['mes_min']=$var[25];
$vars['subj_len']=$var[26];
$vars['subj_min']=$var[27];
$vars['pmes_len']=$var[28];
$vars['pmes_min']=$var[29];
$vars['word_len']=$var[30];
$vars['smls_count']=$var[31];

$vars['time_zone']=$var[32];
$vars['avatar_maxwidth']=$var[33];
$vars['avatar_maxheight']=$var[34];
$vars['avatar_maxsize']=$var[35];
$vars['kvo']=$var[36];
$vars['t_kvo']=$var[37];
$vars['file_maxsize']=$var[38];
$vars['file_maxdesc']=$var[39];
$vars['file_maxlen']=$var[40];
$vars['mat_filter_word']=$var[41];
$vars['pers_color']=$var[42];
$vars['language']=$var[43];
$vars['time_exit']=$var[44];
include "$file_write_vars";
echo("<CENTER>".$language['admin_vars01'][43]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin1>".$language['admin_back']."</a></CENTER>");
}
	}
elseif($act=="var_admin2")
	{
	echo("<center><b>".$language['admin_vars02'][0]."</b></center>");
	echo("<CENTER>");
/*	$dir=opendir($vars['dir_smiles']);
	while($file=readdir($dir))
		{
		if(($file!=".")&&($file!=".."))
			{
			$smls[]=$file;
			}
		}
*/	$i=1;
	while(file_exists($vars['dir_smiles']."/sml0".$i.".gif")==true)
		{
		$smls[$i]="sml0$i.gif";
		$i++;
		}
	while(file_exists($vars['dir_smiles']."/sml".$i.".gif")==true)
		{
		$smls[$i]="sml$i.gif";
		$i++;
		}	
	$count_smls=$i;
	if($do=="delete")
		{
		if($all=="true")
			{
			if($sure=="true")
				{
				echo("deleting all smiles...<br>");
				$hdl=opendir($vars['dir_smiles']);
				while($file=readdir($hdl))
					{
					if(($file!=".")&&($file!=".."))
						{
						if(!(unlink($vars['dir_smiles']."/".$file)))echo("<Br>".$language['admin_vars02'][1]." $file");
						else echo("<Br>".$language['admin_vars02'][3]." $file ".$language['admin_vars02'][4]."");
						}
					}
				echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2>".$language['admin_back']."</a></CENTER>");
				}
				else
				{
				echo("<br>".$language['admin_vars02'][5]."<br>
				<br><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2&do=delete&all=true&sure=true\">".$language['admin_vars02'][6]."</a><br>
				<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2>".$language['admin_back']."</a></CENTER>
				");
				}
			}
			else
			{
			$sml_id=0;
			while($smls[$sml_id]!=$sml)$sml_id++;
			echo("deleting <img src=\"".$vars['dir_smiles']."/".$smls[$sml_id]."\">...<br>");
			if(!(unlink($vars['dir_smiles']."/".$smls[$sml_id]))){echo("".$language['admin_vars02'][7]."");}
			for($i=$sml_id+1;$i<$count_smls;$i++)
				{
				if($i<11)rename($vars['dir_smiles']."/".$smls[$i],$vars['dir_smiles']."/sml0".($i-1).".gif");
				else rename($vars['dir_smiles']."/".$smls[$i],$vars['dir_smiles']."/sml".($i-1).".gif");
				}
			echo("".$language['admin_vars02'][8]." <a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2>".$language['admin_back']."</a></CENTER>");	
			}
		}
		elseif($do=="load")
		{
		$count_smu=0;
		while($HTTP_POST_FILES["smiles_upl"]["tmp_name"][$count_smu]!="")$count_smu++;
		for($g=0;$g<$count_smu;$g++)
			{
			$zak=$HTTP_POST_FILES["smiles_upl"]["tmp_name"][$g];
			$f_name=$HTTP_POST_FILES["smiles_upl"]["name"][$g];
			if($count_smls+1<10)$zakname="sml0".($count_smls).".gif";
			else $zakname="sml".($count_smls).".gif";
			//echo($zak."->".$vars['dir_smiles']."/".$zakname);
			if(copy($zak,$vars['dir_smiles']."/".$zakname))echo("<br>$f_name ".$language['admin_vars02'][9]."");
			else echo("<br>".$language['admin_vars02'][10]." $f_name: ".$HTTP_POST_FILES["smiles_upl"]["error"][$g]."!");
			$count_smls++;
			}
		echo("<Br><a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2>".$language['admin_back']."</a></CENTER>");	
		}
		else
		{
		echo("<table border=0><td>");
		if($count_smls<=1)echo("".$language['admin_vars02'][11]."");
		for($i=1;$i<$count_smls;$i++)
			{
			if($i<10) echo("[SML0".($i)."]<td><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2&do=delete&sml=".$smls[$i]."\">".$language['admin_vars02'][12]."</a><td><img border=1 src=\"".$vars['dir_smiles']."/".$smls[$i]."\"><td>");
			else echo("[SML".($i)."]<td><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2&do=delete&sml=".$smls[$i]."\">".$language['admin_vars02'][12]."</a><td><img border=1 src=\"".$vars['dir_smiles']."/".$smls[$i]."\"><td>");
			if($i/3==round($i/3))echo("<tr><td>");
			}
			
		echo("</table><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2&do=delete&all=true&sure=false\">".$language['admin_vars02'][13]."</a>
		<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin2&do=load\" method=post enctype=\"multipart/form-data\" >
		<input type=hidden name=count_smls value=\"$ldsmcount\">");
		if(isset($ldsmcount)==false)$ldsmcount=1;
		for($e=0;$e<$ldsmcount;$e++)
			{
			echo("<input name=smiles_upl[] type=file>");
			if(($e+1)/3==round(($e+1)/3))echo("<br>");
			}
		echo("<br><input name=submit value=\"".$language['admin_vars02'][14]."\" type=submit><br>
		<SMALL><b><u>Note:</b></u> ".$language['admin_vars02'][15]."</SMALL>
		</form><form action=\"?p=$p&forum_ext=$forum_ext\" method=post>".$language['admin_vars02'][16].":<br><input type=hidden name=id value=admin><input type=hidden name=act value=var_admin2>
		<input type=text style=\"width:30\" name=ldsmcount value=\"$ldsmcount\"><input type=submit value=\"".$language['admin_vars02'][17]."\"></form>
		");
		echo("<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a></CENTER>");	
		}
	}
elseif($act=="var_admin3")
	{
	echo("<center><b>".$language['admin_vars03'][0]."</b></center>");
if($do!="true")
	{
	echo("<CENTER>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin3&do=true\" method=post>
	<u>".$language['admin_vars03'][1]."</u><br>
	".$table_all.$td_all."");
	echo("".$language['admin_vars03'][2]."".$td_all."<select name=var[]>");
	if($usr_view['nick']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][3]."".$td_all."<select name=var[]>");
	if($usr_view['avatar']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][4]."".$td_all."<select name=var[]>");
	if($usr_view['city']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][5]."".$td_all."<select name=var[]>");
	if($usr_view['email']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][6]."".$td_all."<select name=var[]>");
	if($usr_view['url']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][7]."".$td_all."<select name=var[]>");
	if($usr_view['sex']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][8]."".$td_all."<select name=var[]>");
	if($usr_view['sign']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][9]."".$td_all."<select name=var[]>");
	if($usr_view['rang']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][10]."".$td_all."<select name=var[]>");
	if($usr_view['count']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][11]."".$td_all."<select name=var[]>");
	if($usr_view['raiting']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select>");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select>");
	echo("</table><u>".$language['admin_vars03'][12]."</u><br>
	".$table_all.$td_all."");
	echo("".$language['admin_vars03'][13]."".$td_all."<select name=var[]>");
	if($vars['show_thhp']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][14]."".$td_all."<select name=var[]>");
	if($vars['show_view_t']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select>");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select>");

	echo("</table><u>".$language['admin_vars03'][15]."</u><br>
	".$table_all.$td_all."");
	echo("".$language['admin_vars03'][16]."".$td_all."<select name=var[]>");
	if($vars['show_users']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][17]."".$td_all."<select name=var[]>");
	if($vars['show_online']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][18]."".$td_all."<select name=var[]>");
	if($vars['show_statistic']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][19]."".$td_all."<select name=var[]>");
	if($vars['show_info_banner']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][20]."".$td_all."<select name=var[]>");
	if($vars['show_owner']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][21]."".$td_all."<select name=var[]>");
	if($vars['show_newpm']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][22]."".$td_all."<select name=var[]>");
	if($vars['show_gentime']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][23]."".$td_all."<select name=var[]>");
	if($vars['show_rights']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select>");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select>");
	echo("</table><u>".$language['admin_vars03'][24]."</u><br>
	".$table_all.$td_all."");
	echo("".$language['admin_vars03'][25]."".$td_all."<select name=var[]>");
	if($vars['log_index']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][26]."".$td_all."<select name=var[]>");
	if($vars['log_auth']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['version_1.6'][4]."".$td_all."<select name=var[]>");
	if($vars['downl_enable']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][27]."".$td_all."<select name=var[]>");
	if($vars['make_chmod']=="true")echo("<option value=true selected>".$language['admin_vars03'][30]."<option value=false>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	else echo("<option value=true>".$language['admin_vars03'][30]."<option value=false selected>".$language['admin_vars03'][31]."</select><tr>".$td_all."");
	echo("".$language['admin_vars03'][28]."".$td_all."<select name=var[]>");
	if($vars['mat_filter']=="true")echo("<option value=true selected>".$language['admin_vars03'][32]."<option value=false>".$language['admin_vars03'][33]."</select>");
	else echo("<option value=true>".$language['admin_vars03'][32]."<option value=false selected>".$language['admin_vars03'][33]."</select>");
	echo("</table><input type=submit value=\"".$language['admin_vars01'][42]."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
}
else
{
for($i=0;$i<count($var);$i++)
	{
	$var[$i]=str_replace("[}={]","",$var[$i]);
	$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
	$var[$i]=str_replace("''","\"",$var[$i]);
	}
//exit;
$usr_view['nick']=$var[0];
$usr_view['avatar']=$var[1];
$usr_view['city']=$var[2];
$usr_view['email']=$var[3];
$usr_view['url']=$var[4];
$usr_view['sex']=$var[5];
$usr_view['sign']=$var[6];
$usr_view['rang']=$var[7];
$usr_view['count']=$var[8];
$usr_view['raiting']=$var[9];
$vars['show_thhp']=$var[10];
$vars['show_view_t']=$var[11];
$vars['show_users']=$var[12];
$vars['show_online']=$var[13];
$vars['show_statistic']=$var[14];
$vars['show_info_banner']=$var[15];
$vars['show_owner']=$var[16];
$vars['show_newpm']=$var[17];
$vars['show_gentime']=$var[18];
$vars['show_rights']=$var[19];
$vars['log_index']=$var[20];
$vars['log_auth']=$var[21];
$vars['downl_enable']=$var[22];
$vars['make_chmod']=$var[23];
$vars['mat_filter']=$var[24];
include "$file_write_vars";

echo("<CENTER>".$language['admin_vars03'][29]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin3>".$language['admin_back']."</a></CENTER>");
}




	
	}
elseif($act=="var_admin4")
	{
	echo("<center><b>".$language['admin_vars04'][0]."</b></center>");
$rangs1=array();
$file1=file($vars['file_rangs']);
$file="";
foreach($file1 as $string)
	{
	$file.=$string;
	}
$rangs1=explode("$smb",$file);
$j=0;
for($i=0;$i<count($rangs1);$i=$i+2)
	{
	$rangs[$j]['rang']=$rangs1[$i];
	$rangs[$j]['count']=$rangs1[$i+1];
//	echo("rangs[$j]=".$rangs1[$i]."<br>rangs[$j][count]=".$rangs1[$i+1]."<br>");
	$j++;
	}
$vars['rangs_count']=$j;
if($do!="true")
		{
		echo("<CENTER>
		<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4&do=true\" method=post>");
		if(isset($count)==false)
			{
			$count=$vars['rangs_count'];
			}
			else if($count==0)$count=1;
		echo("<table><td>".$language['admin_vars04'][1]."<tr><td>");
		for($i=0;$i<$count;$i=$i+1)
			{
			echo("<input type=text name=rngs[] value=\"".$rangs[$i]['rang']."\"><td>");
			echo("<input type=text name=rngs[] value=\"".$rangs[$i]['count']."\"><tr><td>");
			}
		echo("</table>");
		echo("<input type=submit value=\"".$language['admin_vars01'][42]."\"></form>");
		$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"+ rang\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4&count=".($count+1)."';\">
		<input type=submit value=\"- rang\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4&count=".($count-1)."';\">
		");
		}
		else
		{
		echo("
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4&count=".($count+1)."\">+ Rang</a> 
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4&count=".($count-1)."\">- Rang</a> 
		");
		}
		echo("<br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
		else
		{
		$zapis="";
		for ($i=0;$i<count($rngs);$i++)
			{
			$zapis.=$rngs[$i];
			if($i<count($rngs)-1) $zapis.="$smb";
			}
		$fp=fopen($vars['file_rangs'],"w+");
		fwrite($fp,$zapis);
		fclose($fp);
		echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin4>".$language['admin_back']."</a></CENTER>");
		}



	}
elseif($act=="var_admin5")
	{
	
echo("<center><b>".$language['admin_vars04'][3]."</b></center>");

if($do!="true")
	{
	echo("<CENTER>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin5&do=true\" method=post>
	<u>".$language['admin_vars04'][4]."</u><br>
	".$table_all.$td_all);
	for($r=0;$r<7;$r++) echo("$r".$language['admin_vars04'][5]."".$td_all."<input type=text name=var[] value=\"".$levels[$r]."\" style=\"width:300;\"><tr>".$td_all);
	echo("".$language['admin_vars04'][6]."".$td_all."<input type=text name=var[] value=\"".$levels[7]."\" style=\"width:300;\"></table>
	<u>".$language['admin_vars04'][7]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars04'][8]."".$td_all."<input type=text name=var[] value=\"".$vars['guest_rights']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars04'][9]."".$td_all."<input type=text name=var[] value=\"".$vars['reg_rights']."\" style=\"width:300;\"></table>
	");
	echo("<input type=submit value=\"".$language['admin_vars01'][42]."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
}
else
{
for($i=0;$i<count($var);$i++)
	{
	$var[$i]=str_replace("[}={]","",$var[$i]);
	$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
	$var[$i]=str_replace("''","\"",$var[$i]);
	}
$levels[0]=$var[0];
$levels[1]=$var[1];
$levels[2]=$var[2];
$levels[3]=$var[3];
$levels[4]=$var[4];
$levels[5]=$var[5];
$levels[6]=$var[6];
$levels[7]=$var[7];
$vars['guest_rights']=$var[8];
$vars['reg_rights']=$var[9];

include "$file_write_vars";
echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin5>".$language['admin_back']."</a></CENTER>");
}



	}
elseif($act=="var_admin6")
	{
	echo("<center><b>".$language['admin_vars04'][10]."</b></center>");
if($do!="true")
		{
		echo("<CENTER>
		<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6&do=true\" method=post>");
		if(isset($count)==false)
			{$count=count($blocked_ips);
			}
			else if($count==0)$count=1;
			echo("<table><td>");
		for($i=0;$i<$count;$i++)
			{
			echo("".($i+1)." IP:<input type=text name=ips[] value=\"".$blocked_ips[$i]."\"><td>");
			if(($i+1)/3==round(($i+1)/3))echo("<tr><td>");
			}
		echo("</table>");
		echo("<input type=submit value=\"".$language['admin_vars01'][42]."\"></form>");
		$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"+ IP\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6&count=".($count+1)."';\">
		<input type=submit value=\"- IP\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6&count=".($count-1)."';\">
		");
		}
		else
		{
		echo("
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6&count=".($count+1)."\">+ IP</a> 
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6&count=".($count-1)."\">- IP</a>
		");
		}
		echo("
		<br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
		else
		{
		$zapis="";
		for ($i=0;$i<count($ips);$i++)
			{
			$zapis.=$ips[$i];
			if($i<count($ips)-1) $zapis.="$smb";
			}
		$fp=fopen($vars['file_blocked_ips'],"w+");
		fwrite($fp,$zapis);
		fclose($fp);
		echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin6>".$language['admin_back']."</a></CENTER>");
		}

	}
	
elseif($act=="var_admin7")
	{
	echo("<center><b>".$language['admin_vars05'][0]."</b></center>");



if($do!="true")
	{
	echo("<CENTER>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin7&do=true\" method=post>
	".$language['admin_vars05'][1]."<br>
	<u>".$language['admin_vars05'][2]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars05'][3]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_users']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][4]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_themes']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][5]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_voting']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][6]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_avatars']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][7]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_smiles']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][8]."".$td_all."<input type=text name=var[] value=\"".$vars['file_online']."\" style=\"width:300;\"><tr>".$td_all."

	".$language['admin_vars05'][9]."".$td_all."<input type=text name=var[] value=\"".$vars['file_rangs']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][10]."".$td_all."<input type=text name=var[] value=\"".$vars['file_logs']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][11]."".$td_all."<input type=text name=var[] value=\"".$vars['file_auth_logs']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][12]."".$td_all."<input type=text name=var[] value=\"".$vars['file_mat_filter']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][13]."".$td_all."<input type=text name=var[] value=\"".$vars['file_blocked_ips']."\" style=\"width:300;\"></table>

	<u>".$language['version_1.6'][3]."</u><br>
	".$table_all.$td_all."
	".$language['version_1.6'][0]."".$td_all."<input type=text name=var[] value=\"".$vars['dir_downloads']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['version_1.6'][1]."".$td_all."<input type=text name=var[] value=\"".$vars['file_files_ext']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['version_1.6'][2]."".$td_all."<input type=text name=var[] value=\"".$vars['file_files_desc']."\" style=\"width:300;\"></table>



	<u>".$language['admin_vars05'][14]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars05'][15]."".$td_all."<input type=text name=var[] value=\"".$vars['theme_ext']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][16]."".$td_all."<input type=text name=var[] value=\"".$vars['mess_ext']."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][17]."".$td_all."<input type=text name=var[] value=\"".$vars['voting_ext']."\" style=\"width:300;\">
	");
	echo("</table><input type=submit value=\"".$language['admin_vars01'][42]."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
}
else
{
for($i=0;$i<count($var);$i++)
	{
	$var[$i]=str_replace("[}={]","",$var[$i]);
	$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
	$var[$i]=str_replace("''","\"",$var[$i]);
	}
$vars['dir_users']=$var[0];
$vars['dir_themes']=$var[1];
$vars['dir_voting']=$var[2];
$vars['dir_avatars']=$var[3];
$vars['dir_smiles']=$var[4];
$vars['file_online']=$var[5];
$vars['file_rangs']=$var[6];
$vars['file_logs']=$var[7];
$vars['file_auth_logs']=$var[8];
$vars['file_mat_filter']=$var[9];
$vars['file_blocked_ips']=$var[10];
$vars['dir_downloads']=$var[11];
$vars['file_files_ext']=$var[12];
$vars['file_files_desc']=$var[13];
$vars['theme_ext']=$var[14];
$vars['mess_ext']=$var[15];
$vars['voting_ext']=$var[16];

include "$file_write_vars";
echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin7>".$language['admin_back']."</a></CENTER>");
}




	}
elseif($act=="var_admin8")
	{
	
echo("<center><b>".$language['admin_vars05'][18]."</b></center>");




if($do!="true")
	{
	echo("<CENTER>
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin8&do=true\" method=post>
	".$language['admin_vars05'][19]."<br>
	<u>".$language['admin_vars05'][20]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars05'][21]."".$td_all."<input type=text name=var[] value=\"".$smb."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][22]."".$td_all."<input type=text name=var[] value=\"".$smb1."\" style=\"width:300;\"></table>
	".$language['admin_vars05'][23]."<br>
	<u>".$language['admin_vars05'][24]."</u><br>
	".$table_all.$td_all."
	".$language['admin_vars05'][25]."".$td_all."<input type=text name=var[] value=\"".$table_all."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][26]."".$td_all."<input type=text name=var[] value=\"".$td_all."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][27]."".$td_all."<input type=text name=var[] value=\"".$table_messages."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][28]."".$td_all."<input type=text name=var[] value=\"".$td_messages."\" style=\"width:300;\"><tr>".$td_all."
	".$language['admin_vars05'][29]."".$td_all."<input type=text name=var[] value=\"".$td_userinfo."\" style=\"width:300;\"></table>
	");
	echo("<input type=submit value=\"".$language['admin_vars01'][42]."\"></form><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
}
else
{
for($i=0;$i<count($var);$i++)
	{
	$var[$i]=str_replace("[}={]","",$var[$i]);
	$var[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($var[$i])))));
	$var[$i]=str_replace("''","\"",$var[$i]);
	}
$smb=$var[0];
$smb1=$var[1];
$table_all=$var[2];
$td_all=$var[3];
$table_messages=$var[4];
$td_messages=$var[5];
$td_userinfo=$var[6];

include "$file_write_vars";
echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin8>".$language['admin_back']."</a></CENTER>");
}






	}

elseif($act=="logs")
	{
	echo("<center><b>".$language['admin_vars06'][0]."</b></center>");
	include "$file_read_logs";
	echo("<CENTER>");
	if($do=="view_old")
		{
		$log_id=0;
		while($logs['ip'][$log_id]!=$log)$log_id++;
		echo("".$language['admin_vars06'][1]." ".$log." (".$logs['host'][$log_id].") :<br>");
		echo("".$language['admin_vars06'][2]." ".$logs['date'][$log_id]."<br>");
		echo("".$language['admin_vars06'][3]."'".$logs['port'][$log_id]."' ".$language['admin_vars06'][4]."'".$logs['referer'][$log_id]."'<br>");
		echo("".$language['admin_vars06'][5]." ".$logs['browser'][$log_id]."<br>");
		echo("".$language['admin_vars06'][6]." ".$logs['connection'][$log_id]."<br>");
		echo($logs['old_logs'][$log_id]);
		echo("<br><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs&do=delete&log=".$logs['ip'][$log_id]."\">".$language['admin_vars06'][7]."</a><br>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs\">".$language['admin_back']."</a>");		
		}
	elseif($do=="delete")
		{
		$log_id=0;
		while($logs['ip'][$log_id]!=$log)$log_id++;
		$logs['ip'][$log_id]="";
		for($i=0;$i<count($logs['ip']);$i++)
			{	
			//echo($zapis."<hr>");
			if($logs['ip'][$i]!="") $zapis.=$logs['ip'][$i]."$smb".$logs['host'][$i]."$smb".$logs['date'][$i]."$smb".$logs['browser'][$i]."$smb".$logs['referer'][$i]."$smb".$logs['connection'][$i]."$smb".$logs['port'][$i]."$smb".$logs['old_logs'][$i]."$smb1";
			}
			if(!($fp=fopen($vars['file_logs'],"w+")))
				{
				echo("<CENTER>Error ALD01! Ошибка записи лог-файла</CENTER>");
				}
				else
				{
				if(!(fwrite($fp,$zapis))){echo("<CENTER>Error ALD02! Ошибка записи лог-файла</CENTER>");}
				fclose($fp);
				}
				echo("".$language['admin_vars06'][8]." <a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs\">".$language['admin_back']."</a>");
		}
		elseif($do=="delete_all")
		{
		if($sure=="true")
			{
			if(!($fp=fopen($vars['file_logs'],"w+")))
				{
				echo("<CENTER>Error ALD01! Ошибка записи лог-файла</CENTER>");
				}
				else
				{
				echo("".$language['admin_vars06'][9]." <a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs\">".$language['admin_back']."</a>");
				}
			}
			else
			{
			echo("".$language['admin_vars06'][10]."<Br><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs&do=delete_all&sure=true\">".$language['admin_vars02_03']."</a>
			<br><a href=\"?p=$p&forum_ext=$forum_ext&id=admin\">".$language['admin_back']."</a>");
			}
		}
		else
		{
		echo("".$table_all.$td_all."");
		for($i=0;$i<count($logs['ip']);$i++)
			{
			echo("".$language['admin_vars06'][11]." '".$logs['ip'][$i]."' (".$logs['host'][$i].") :<br>");
			echo("".$language['admin_vars06'][12]." ".$logs['date'][$i]."<br>");
			echo("".$language['admin_vars06'][13]." ".$logs['browser'][$i]."<br>");
			echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs&do=view_old&log=".$logs['ip'][$i]."\">".$language['admin_vars06'][14]."</a><br>");
			echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs&do=delete&log=".$logs['ip'][$i]."\">".$language['admin_vars06'][15]."</a>");
			if($i<count($logs['ip'])-1)echo("<tr>$td_all");
			}
		if(count($logs['ip'])==0)
			{
			echo("".$language['admin_vars06'][16]." <a href=\"?p=$p&forum_ext=$forum_ext&id=admin\">".$language['admin_back']."</a>");
			}
		echo("</table><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=logs&do=delete_all\">".$language['admin_vars06'][17]."</a><br><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
	echo("</CENTER>");
	}
elseif($act=="auth_logs")
	{
	echo("<center><b>".$language['admin_vars06'][18]."</b></center>");
	echo("<CENTER>");
	if($do=="clear")
		{
		$fp=fopen($vars['file_auth_logs'],"w+");
		fclose($fp);
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=auth_logs\">".$language['admin_back']."</a>");	
		}
		else
		{
		include $vars['file_auth_logs'];
		echo("<br><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=auth_logs&do=clear\">".$language['admin_vars06'][19]."</a><br>");
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin\">".$language['admin_back']."</a>");
		}
	echo("</CENTER>");
	}
elseif($act=="var_admin12")
	{
echo("<CENTER>".$language['version_1.7'][10]."<br>");

if($do!="true")
		{
		include "$file_read_forums";
		echo("<form action=\"?p=$p&forum_ext=$forum_ext\" method=post>
		<input type=hidden name=id value=admin>
		<input type=hidden name=act value=var_admin12>
		<input type=hidden name=do value=true>
		");
		
		if(isset($count)==false)
			{
			$count=count($forums['title']);
			}
			else if($count==0)$count=1;
			echo("<centeR><table cellspacing=0 cellpadding=0><td>
			".$language['version_1.7'][11]."<td>".$language['version_1.7'][12]."<td>".$language['version_1.7'][13]."<td>".$language['version_1.7'][14]."<td>
			".$language['version_1.7'][15]."<td>".$language['version_1.7'][16]."<tr>");
		for($i=0;$i<$count;$i++)
			{
			$forums['descr'][$i]=str_replace("<br />","",$forums['descr'][$i]);
			echo("<td><input type=text name=f_titles[] value=\"".$forums['title'][$i]."\">");
			echo("<td><textarea name=f_descrs[] cols=35 rows=2>".$forums['descr'][$i]."</textarea>");
			echo("<td><input style=\"width:80\" type=text name=f_exts[] value=\"".$forums['ext'][$i]."\">");
			echo("<td><input style=\"width:80\" type=text name=f_rights[] value=\"".$forums['rights'][$i]."\">");
			echo("<td><input type=text name=f_admins[] value=\"".$forums['admins'][$i]."\">");
			echo("<td><select name=f_use[]><option value=\"true\" selected>yes<option value=\"false\">no</select>");
			if($i<$count-1)echo("<tr>");
			}
		echo("</table></center><br><input name=button type=submit value=\"".$language['admin_vars01'][42]."\"></form>");
		$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"".$language['version_1.7'][17]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12&count=".($count+1)."';\">
		<input type=submit value=\"".$language['version_1.7'][18]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12&count=".($count-1)."';\">
		");
		}
		else
		{
		echo("
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12&count=".($count+1)."\">".$language['version_1.7'][17]."</a> 
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12&count=".($count-1)."\">".$language['version_1.7'][18]."</a> 
		");
		}
		echo("
		<br><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
		else
		{
		$zapis="";
		for($i=0;$i<count($f_titles);$i++)
			{
			$f_titles[$i]=str_replace("$smb","",$f_titles[$i]);
			$f_titles[$i]=str_replace("$smb1","",$f_titles[$i]);
			$f_descrs[$i]=str_replace("$smb","",$f_descrs[$i]);
			$f_descrs[$i]=str_replace("$smb1","",$f_descrs[$i]);
			$f_exts[$i]=str_replace("$smb","",$f_exts[$i]);
			$f_exts[$i]=str_replace("$smb1","",$f_exts[$i]);
			$f_rights[$i]=str_replace("$smb","",$f_rights[$i]);
			$f_rights[$i]=str_replace("$smb1","",$f_rights[$i]);
			$f_admins[$i]=str_replace("$smb","",$f_admins[$i]);
			$f_admins[$i]=str_replace("$smb","",$f_admins[$i]);
			$f_descrs[$i]=nl2br($f_descrs[$i]);
			$f_titles[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($f_titles[$i])))));
			$f_descrs[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($f_descrs[$i])))));
			}
			$zapis="";
			for($i=0;$i<count($f_titles);$i++)
				{
				if($f_use[$i]!="false")
					{
					$zapis.=$f_titles[$i]."$smb".$f_descrs[$i]."$smb".$f_exts[$i]."$smb".$f_rights[$i]."$smb".$f_admins[$i];
					if(($i<count($f_titles)-1)&&($f_use[$i+1]!="false"))$zapis.="$smb1";
					}
					else
					{
					if($i<count($f_titles)-1)$zapis.="$smb1";
					}
				}
			$fp=fopen($file_forums,"w+");
			fwrite($fp,$zapis);
			fclose($fp);
			echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin12>".$language['admin_back']."</a></CENTER>");
			
		}

	}
elseif($act=="var_admin10")
	{
echo("<CENTER>".$language['version_1.6'][6]."<br>");

if($do!="true")
		{
		include "$file_read_down_ext";
		echo("<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10&do=true\" method=post>");

		if(isset($count)==false)
			{
			$count=count($down_ext);
			}
			else if($count==0)$count=1;
			echo("<centeR><table cellspacing=0 cellpadding=0>");
		for($i=0;$i<$count;$i++)
			{
			echo("<td><input type=text name=vrss[] value=\"".$down_ext[$i]."\">");
			if((round(($i+1)/5)==($i+1)/5))echo("<tr>");
			}
		echo("</table></center><br><input type=submit value=\"".$language['admin_vars01'][42]."\"></form>");
		$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"".$language['admin_vars07'][1]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10&count=".($count+1)."';\">
		<input type=submit value=\"".$language['admin_vars07'][2]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10&count=".($count-1)."';\">
		");
		}
		else
		{
		echo("
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10&count=".($count+1)."\">".$language['admin_vars07'][1]."</a> 
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10&count=".($count-1)."\">".$language['admin_vars07'][2]."</a> 
		");
		}
		echo("
		<br><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
		else
		{
		$zapis="";
		for($i=0;$i<count($vrss);$i++)
			{
			$vrss[$i]=str_replace("$smb","",$vrss[$i]);
			$vrss[$i]=str_replace("$smb1","",$vrss[$i]);
			$vrss[$i]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($vrss[$i])))));
			$vrss[$i]=str_replace("''","\"",$vrss[$i]);
			$zapis.=$vrss[$i];
			if($i<count($vrss)-1) $zapis.="$smb";
			}
		$fp=fopen($vars['file_files_ext'],"w+");
		fwrite($fp,$zapis);
		fclose($fp);
		echo("<CENTER>".$language['admin_vars04'][2]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin10>".$language['admin_back']."</a></CENTER>");
		}

	}
elseif($act=="var_admin11")
	{
echo("<CENTER>".$language['version_1.6'][8]."<br>");
include "$file_read_downl";
if($do!="true")
		{
		for($i=0;$i<count($down_ext);$i++)
			{
			if(count($down_files[$down_ext[$i]])>0)
			{
			echo("<p><b><u>".$down_ext[$i]."- files:</u></b><br><br>");
			for($j=0;$j<count($down_files[$down_ext[$i]]);$j++)
				{
				echo("<a href=\"".$vars['dir_downloads']."/".$down_files[$down_ext[$i]][$j]."\">".$down_files[$down_ext[$i]][$j]."</a><br><SMALL>".$down_descs[$down_ext[$i]][$j]."</SMALL>");
				echo("&nbsp;&nbsp;<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin11&do=true&sur=false&filed=".$down_files[$down_ext[$i]][$j]."\">".$language['version_1.6'][8]."</a>");
				echo("<br>");
				}
			}
			}
			
		
		}
		else
		{
		if($sur=="true")
			{
			if(unlink($vars['dir_downloads']."/".$filed)==true)
				{
				echo($language['version_1.6'][12]."!");
				$zapis="";
				for($i=0;$i<count($down_filenames);$i++)
					{
					if($filed==$down_filenames[$i])
						{
						$down_filenames[$i]="";
						$down_filedescs[$i]="";
						}
						else
						{
						$zapis.=$down_filenames[$i]."$smb".$down_filedescs[$i];
						if($i<count($down_filenames)-1)$zapis.="$smb";
						}
					}
				$fp=fopen($vars['file_files_desc'],"w+");
				fwrite($fp,$zapis);
				fclose($fp);
				}
				else echo("<br>ERROR DFD01! Contact forum developer!");
			}
			else
			{
			echo($language['version_1.6'][9]."'".$filed."' ? <br>");
			echo("
			<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin11&do=true&filed=$filed&sur=true\">".$language['version_1.6'][10]."</a>
			");
			/*
			echo("&nbsp;&nbsp;
			<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin11>".$language['version_1.6'][11]."</a>
			");
			*/
			}
		echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin11>".$language['version_1.6'][13]."</a>");
		}
		echo("
		<br><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
	}
elseif($act=="var_admin9")
	{
	
if($do!="true")
		{
		include "$file_mat_filter";
		echo("<CENTER>".$language['admin_vars07'][0]."<br>
		<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9&do=true\" method=post>");
		if(isset($count)==false)
			{$count=count($unsymb);
			}
			else if($count==0)$count=1;
			echo("<centeR><table cellspacing=0 cellpadding=0>");
		for($i=0;$i<$count;$i++)
			{
			echo("<td><input type=text name=vrss[] value=\"".$unsymb[$i]."\">");
			if((round(($i+1)/5)==($i+1)/5))echo("<tr>");
			}
		echo("</table></center><br><input type=submit value=\"".$language['admin_vars01'][42]."\"></form>");
		$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"".$language['admin_vars07'][1]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9&count=".($count+1)."';\">
		<input type=submit value=\"".$language['admin_vars07'][2]."\" onclick=\"document.location.href='?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9&count=".($count-1)."';\">
		");
		}
		else
		{
		echo("
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9&count=".($count+1)."\">".$language['admin_vars07'][1]."</a> 
		<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9&count=".($count-1)."\">".$language['admin_vars07'][2]."</a> 
		");
		}
		echo("<br>
		<form method=get name=sign action=\"?p=$p&id=$id&act=$act\">".$language['admin_vars07'][3]."
		<input type=hidden name=forum_ext value=\"$forum_ext\">
		<input type=hidden name=id value=admin><input type=hidden name=act value=var_admin9>
		<input name=count style=\"width:40\" value=$count type=text><input type=submit style=\"width:100\" value=\"".$language['admin_vars07'][4]."\" ></form>");
		if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
		{
		echo("
		<input type=submit value=\"+\" style=\"width:30\" onclick=\"document.sign.count.value++\">
		<input type=submit value=\"-\" style=\"width:30\" onclick=\"document.sign.count.value--\">
		");
		}
		else
		{
		echo("
		<a onclick=\"document.sign.count.value++\"><u>+</u></a>
		<a onclick=\"document.sign.count.value--\"><u>-</u></a>
		");
		}
		echo("
		<br><br>
		<a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a>");
		}
		else
		{
		$zapis="";
		for ($i=0;$i<count($vrss);$i++)
			{
			$zapis.=$vrss[$i];
			if($i<count($vrss)-1) $zapis.="$smb";
			}
		$fp=fopen($vars['file_mat_filter'],"w+");
		fwrite($fp,$zapis);
		fclose($fp);
		echo("<CENTER>".$language['admin_vars07'][5]." &nbsp;<a href=?p=$p&forum_ext=$forum_ext&id=admin&act=var_admin9>".$language['admin_back']."</a></CENTER>");
		}

	}
elseif($act=="delete_old_users")
	{
	function delete_user($userr)
		{
		global $vars;
		$error1=false;
		$error2=false;
		if(!unlink($vars['dir_users']."/".$userr))$error1=true;
		if(file_exists($vars['dir_avatars']."/".$userr.".gif")==true)
			{
			if(!unlink($vars['dir_avatars']."/".$userr.".gif"))$error2=true;
			}
		if(($error1==false)&&($error2==false))return "OK";
		elseif($error1==true)return "error1";
		elseif($error2==true)return "error2";
		}
	echo("<CENTER><b>".$language['version_2.0'][63]."</b></CENTER><CENTER>");
	if(($do=="true")&&($dod!="true"))
		{
		echo("<form action=?p=$p&forum_ext=$forum_ext&id=admin&act=delete_old_users&do=true&dod=true method=post><table><td>\n");
		for($i=0;$i<count($all_users);$i++)
			{
			$stat=stat($vars['dir_users']."/".$all_users[$i]);
			$stat=$stat['mtime'];
			//echo(abs($stat-time()).">=".($delta*3600*24)."(".$users_info['login'][$i].")<br>\n");
			if(abs($stat-time())>=$delta*3600*24)
				{
				if($user_rights<8)
					{
					if(($users_info['rights'][$i]<7)||($users_info['login'][$i]==$HTTP_SESSION_VARS['forum_login']))
						{
						echo($users_info['login'][$i]."<td><input type=checkbox name=duser[] value=\"".$all_users[$i]."\"><tr><td>\n");
						}
					}
					else
					{
					echo($users_info['login'][$i]."<td><input type=checkbox name=duser[] value=\"".$all_users[$i]."\"><tr><td>\n");
					}
				}
			}
		echo("</table><input type=submit value=\"DELETE\"></form>\n");
		echo("<p><a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=delete_old_users\">".$language['admin_back']."</a>");
		}
	elseif(($do=="true")&&($dod=="true"))
		{
		for($i=0;$i<count($duser);$i++)
			{
			$stat=stat($vars['dir_users']."/".$duser[$i]);
			$stat=$stat['mtime'];
			$dusrid=-1;
			for($j=0;$j<count($all_users);$j++)
				{
				if($all_users[$j]==$duser[$i])$dusrid=$j;
				}
			if($user_rights<8)
				{
				if(($users_info['rights'][$i]<7)||($users_info['login'][$i]==$HTTP_SESSION_VARS['forum_login']))
					{
					if(delete_user($duser[$i])=="error1")
						{
						echo("Ошибка удаления аккаунта: ".$users_info['login'][$i]."<br>");
						}
						else
						{
						echo("<br>".$language['version_2.0'][66]." ".$users_info['login'][$dusrid]." (".round(abs($stat-time())/(3600*24))." ".$language['version_2.0'][65].")");
						}
					}
				}
				else
				{
				if(delete_user($duser[$i])=="error1")
					{
					echo("Ошибка удаления аккаунта: ".$users_info['login'][$i]."<br>");
					}
					else
					{
					echo("<br>".$language['version_2.0'][66]." ".$users_info['login'][$dusrid]." (".round(abs($stat-time())/(3600*24))." ".$language['version_2.0'][65].")");
					}
				}
			}
		echo("<p>".$language['persmes'][33]." <a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=delete_old_users\">".$language['admin_back']."</a>");
		}
		else
		{
		echo("
		<form action=?p=$p&forum_ext=$forum_ext&id=admin&act=delete_old_users&do=true&dod=false method=post>
		".$language['version_2.0'][64]."<input type=text name=delta value=30>".$language['version_2.0'][65]."<br>
		<input type=submit value=\"".$language['persmes'][17]."\"</form>
		<CENTER><b><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a></b>");
		}
	}
elseif($act=="statistic")
	{
	echo("<CENTER><b>".$language['version_2.0'][56]."</b></CENTER>");
	if($n<1)$n=30;
	include("$file_classes");
	include $file_read_all_topics_on_forum;
	include $file_read_all_messages_on_forum;
	if(isset($every)==false)$every=2;
	$my_params[0]=500;
	$my_params[1]=500;
	$my_params[2]=255;
	$my_params[3]=255;
	$my_params[4]=255;
	$my_params[5]=0;
	$my_params[6]=0;
	$my_params[7]=0;
	$my_params[8]=190;
	$my_params[9]=190;
	$my_params[10]=190;
	$my_params[11]=50;
	$my_params[12]=50;
	$my_params[13]="";//fonts/times.ttf
	$my_params[14]="";
	if($n<=14){$my_params[15]="Day of the week.";}else{$my_params[15]="Day of month.";}
	$my_params[16]="Messages count.";
	$my_params[17]=1;
	$my_params[18]="messages.jpeg";
	$my_params[19]=$every;
	for($k=$n;$k>=0;$k--)
		{
		$time1=time()-24*3600*($k+1);
		$time2=time()-24*3600*$k;
		$cnt=0;
		for($i=0;$i<count($all_themes1['title']);$i++)
			{
			for($j=0;$j<$all_themes1['count'][$i];$j++)
				{
				if(($all_messages1[$i]['time'][$j]<$time2)&&($all_messages1[$i]['time'][$j]>$time1))$cnt++;
				}
			}
		$my_data[]=$cnt;
		$dweek=getdate($time2);
		if($n<=14){$my_label[]=substr($dweek['weekday'],0,3)."(".$dweek['mday'].")";}else{$my_label[]=$dweek['mday'];}
		}
	if($DSGraphics->Draw($my_params,$my_data,$my_label)==true)
	echo("<p align=center><img src=$my_params[18]></p>");
	$my_data=array();
	$my_label=array();
	$my_params[16]="Topics count.";
	$my_params[17]=1;
	$my_params[18]="topics.jpeg";
	for($k=$n;$k>=0;$k--)
		{
		$time1=time()-24*3600*($k+1);
		$time2=time()-24*3600*$k;
		$cnt=0;
		for($i=0;$i<count($all_themes1['title']);$i++)
			{
			if(($all_themes1['name'][$i]<$time2)&&($all_themes1['name'][$i]>$time1))$cnt++;
			}
		$my_data[]=$cnt;
		$dweek=getdate($time2);
		if($n<=14){$my_label[]=substr($dweek['weekday'],0,3)."(".$dweek['mday'].")";}else{$my_label[]=$dweek['mday'];}
		}
	if($DSGraphics->Draw($my_params,$my_data,$my_label)==true)
	echo("<p align=center><img src=$my_params[18]></p>");
	$my_data=array();
	$my_label=array();

	$my_params[16]="Registrations count.";
	$my_params[17]=1;
	$my_params[18]="users.jpeg";
	for($k=$n;$k>=0;$k--)
		{
		$time1=time()-24*3600*($k+1);
		$time2=time()-24*3600*$k;
		$cnt=0;
		for($i=0;$i<count($all_users);$i++)
			{
			if(($all_users[$i]<$time2)&&($all_users[$i]>$time1))$cnt++;
			}
		$my_data[]=$cnt;
		$dweek=getdate($time2);
		if($n<=14){$my_label[]=substr($dweek['weekday'],0,3)."(".$dweek['mday'].")";}else{$my_label[]=$dweek['mday'];}
		}
	if($DSGraphics->Draw($my_params,$my_data,$my_label)==true)
	echo("<p align=center><img src=$my_params[18]></p>");

	echo("<CENTER><form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=statistic\" method=post>
	".$language['version_2.0'][54]."<input name=n type=text value=\"$n\"><br>
	".$language['version_2.0'][61]."<input type=text name=every value=\"$every\"><input type=submit value=\"".$language['version_2.0'][55]."\"></form></CENTER>
	<CENTER><b><a href=?p=$p&forum_ext=$forum_ext&id=admin>".$language['admin_back']."</a></b>");
	}
	elseif($act=="users_online")
	{
	include $file_read_online;
	echo("<CENTER>".$language['version_2.0'][10]."<table><td>");
			echo($language['version_2.0'][18]);
			echo("<td align=center>");
			echo($language['version_2.0'][19]);
			echo("<td align=center>");
			echo($language['version_2.0'][12]);
			echo("<td align=center>");
			echo($language['version_2.0'][24]);
			echo("<td align=center>");
			echo($language['version_2.0'][13]);
			echo("<td align=center>");
			echo($language['version_2.0'][14]);
			echo("<td align=center>");
			echo($language['version_2.0'][15]);
			echo("<td align=center>");
			echo($language['version_2.0'][21]);
			echo("<td align=center>");
			echo($language['version_2.0'][22]);
			echo("<tr><td><SMALL>");
	for($i=0;$i<count($online['type']);$i++)
		{
		if($online['forum'][$i]=="")$online['forum'][$i]=$language['version_2.0'][23];
                $tdate=getdate($online['time'][$i]);
		$tdate=$tdate['hours'].":".$tdate['minutes'].":".$tdate['seconds'];
		if($online['type'][$i]=="reg")
			{
			echo("$i");
			echo("</SMALL><td align=center><SMALL>");
			echo($online['ip'][$i]."(".gethostbyaddr(ip_to_just_ip($online['ip'][$i])).")");
			echo("</SMALL><td><SMALL>");
			echo($language['version_2.0'][16]);
			echo("</SMALL><td align=center><SMALL>");
			echo($online['rights'][$i]);
			echo("</SMALL><td align=center><SMALL>");
			echo($online['nick'][$i]);
			echo("</SMALL><td align=center><SMALL>");
			echo($online['login'][$i]);
			echo("</SMALL><td align=center><SMALL>");
			echo($tdate);
			echo("</SMALL><td><SMALL>");
			echo($online['forum'][$i]);
			echo("</SMALL><td><SMALL>");
			echo($online['topic'][$i]);
			}
			elseif($online['type'][$i]=="guest")
			{
			echo("$i");
			echo("</SMALL><td align=center><SMALL>");
			echo($online['ip'][$i]."(".gethostbyaddr(ip_to_just_ip($online['ip'][$i])).")");	
			echo("</SMALL><td align=center><SMALL>");
			echo($language['version_2.0'][17]);
			echo("</SMALL><td align=center><SMALL>");
			echo($online['rights'][$i]);
			echo("</SMALL><td align=center><SMALL>");
			echo($language['version_2.0'][20]);
			echo("</SMALL><td align=center><SMALL>");
			echo($language['version_2.0'][20]);
			echo("</SMALL><td align=center><SMALL>");
			echo($tdate);
			echo("</SMALL><td><SMALL>");
			echo($online['forum'][$i]);
			echo("</SMALL><td><SMALL>");
			echo($online['topic'][$i]);
			}
		if($i<count($online['type'])-1)echo("<tr><td><SMALL>");
		}
	echo("</table></CENTER>");
		echo("<CENTER><a href=\"?p=$p&forum_ext=$forum_ext&id=admin\">".$language['admin_back']."</a></CENTER>");
	}
	else	
	{
	echo("<CENTER>
	$table_all"."$td_all<b>".$language['admin_title']."</b><br>");
        /*".$language['admin_title_usr']);
	
	<form action=\"?p=$p&forum_ext=$forum_ext&id=admin&act=user_admin\" method=post><select name=userr>");
	for($i=0;$i<count($all_users);$i++)
		{
			
		if($user_rights<8)
			{
			if(($users_info['rights'][$i]<7)||($users_info['login'][$i]==$HTTP_SESSION_VARS['forum_login']))
				{
				echo("
				<option value=\"".$all_users[$i]."\">".$users_info['nick'][$i]."
				");
				}
			}
			else
			{
			echo("
			<option value=\"".$all_users[$i]."\">".$users_info['nick'][$i]."
			");
			}
		}
	echo("</select>
	<input type=submit value=\"".$language['admin_vars01_20']."\"></form>");    */
	echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=users_online\">".$language['version_2.0'][11]."</a>, ");
	echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=admin&act=delete_old_users\">".$language['version_2.0'][62]."</a>");

	if($user_rights>6)
	{
	include "$file_admin_menu";
	}
	echo("
	</table>
	");
	}	
echo("</CENTER>");

?>
