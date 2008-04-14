<?php
global $smb,$smb1,$usr_id,$vars,$users_info,$language,$user_rights;
$file_smiles1=$DIRS["smdsforum_forumdir"]."/Files/smiles1.php";
$file_read_down_ext=$DIRS["smdsforum_forumdir"]."/Files/read_down_ext.php";
$file_forum_ini=$DIRS["smdsforum_forumdir"]."/forum.ini";
$file_forums=$DIRS["smdsforum_forumdir"]."/forums.txt";
$file_smiles=$DIRS["smdsforum_forumdir"]."/smiles.txt";
$file_all_forums=$DIRS["smdsforum_forumdir"]."/Files/all_forums.php";
$file_directories=$DIRS["smdsforum_forumdir"]."/dirs.php";
$file_read_forums=$DIRS["smdsforum_forumdir"]."/Files/read_forums.php";
$file_add_downloads=$DIRS["smdsforum_forumdir"]."/Files/add_downloads.php";
$file_downloads=$DIRS["smdsforum_forumdir"]."/Files/downloads.php";
$file_read_downl=$DIRS["smdsforum_forumdir"]."/Files/read_downl.php";
$file_write_vars=$DIRS["smdsforum_forumdir"]."/Files/write_vars.php";
$file_find=$DIRS["smdsforum_forumdir"]."/Files/find.php";
$file_register=$DIRS["smdsforum_forumdir"]."/Files/register.php";
$file_reg=$DIRS["smdsforum_forumdir"]."/Files/reg.php";
$file_showtopic=$DIRS["smdsforum_forumdir"]."/Files/showtopic.php";
$file_showall=$DIRS["smdsforum_forumdir"]."/Files/showall.php";
$file_profile=$DIRS["smdsforum_forumdir"]."/Files/profile.php";
$file_read_users=$DIRS["smdsforum_forumdir"]."/Files/read_users.php";
$file_read_themes=$DIRS["smdsforum_forumdir"]."/Files/read_themes.php";
$file_read_last_messages=$DIRS["smdsforum_forumdir"]."/Files/read_last_messages.php";
$file_auth=$DIRS["smdsforum_forumdir"]."/Files/auth.php";
$file_read_color_schemes=$DIRS["smdsforum_forumdir"]."/Files/read_color_schemes.php";
$file_buttons=$DIRS["smdsforum_forumdir"]."/Files/buttons.php";
$file_check_auth=$DIRS["smdsforum_forumdir"]."/Files/check_auth.php";
$file_ses_dec=$DIRS["smdsforum_forumdir"]."/Files/ses_dec.php";
$file_exit=$DIRS["smdsforum_forumdir"]."/Files/exit.php";
$file_show_messages=$DIRS["smdsforum_forumdir"]."/Files/show_messages.php";
$file_make_links=$DIRS["smdsforum_forumdir"]."/Files/make_links.php";
$file_add_topic=$DIRS["smdsforum_forumdir"]."/Files/add_topic.php";
$file_add_message=$DIRS["smdsforum_forumdir"]."/Files/add_message.php";
$file_read_all_messages=$DIRS["smdsforum_forumdir"]."/Files/read_all_messages.php";
$file_delete=$DIRS["smdsforum_forumdir"]."/Files/delete.php";
$file_topic_delete=$DIRS["smdsforum_forumdir"]."/Files/topic_delete.php";
$file_topic_transfer=$DIRS["smdsforum_forumdir"]."/Files/topic_transfer.php";
$file_topic_edit=$DIRS["smdsforum_forumdir"]."/Files/topic_edit.php";
$file_edit=$DIRS["smdsforum_forumdir"]."/Files/edit.php";
$file_faq=$DIRS["smdsforum_forumdir"]."/Files/faq.php";
$file_transfer=$DIRS["smdsforum_forumdir"]."/Files/transfer.php";
$file_smiles=$DIRS["smdsforum_forumdir"]."/Files/smiles.php";
$file_smiles_replace=$DIRS["smdsforum_forumdir"]."/Files/smiles_replace.php";
$file_smiles_replace_back=$DIRS["smdsforum_forumdir"]."/Files/smiles_replace_back.php";
$file_statistic=$DIRS["smdsforum_forumdir"]."/Files/statistic.php";
$file_read_statistic=$DIRS["smdsforum_forumdir"]."/Files/read_statistic.php";
$file_calc_raitings=$DIRS["smdsforum_forumdir"]."/Files/calc_raitings.php";
$file_user_info=$DIRS["smdsforum_forumdir"]."/Files/user_info.php";
$file_online=$DIRS["smdsforum_forumdir"]."/Files/online.php";
$file_read_online=$DIRS["smdsforum_forumdir"]."/Files/read_online.php";
$file_write_log=$DIRS["smdsforum_forumdir"]."/Files/write_log.php";
$file_read_logs=$DIRS["smdsforum_forumdir"]."/Files/read_logs.php";
$file_read_blocked_ips=$DIRS["smdsforum_forumdir"]."/Files/read_blocked_ips.php";
$file_show_rights=$DIRS["smdsforum_forumdir"]."/Files/show_rights.php";
$file_show_themes=$DIRS["smdsforum_forumdir"]."/Files/show_themes.php";
$file_view_voting=$DIRS["smdsforum_forumdir"]."/Files/view_voting.php";
$file_add_voting=$DIRS["smdsforum_forumdir"]."/Files/add_voting.php";
$file_admin=$DIRS["smdsforum_forumdir"]."/Files/admin.php";
$file_write_vars=$DIRS["smdsforum_forumdir"]."/Files/write_vars.php";
$file_info_banner="info_banner.php";
$file_pers_messages=$DIRS["smdsforum_forumdir"]."/Files/pers_messages.php";
$file_read_user_messages=$DIRS["smdsforum_forumdir"]."/Files/read_user_messages.php";
$file_read_private_messages=$DIRS["smdsforum_forumdir"]."/Files/read_private_messages.php";
$file_read_all_topics_on_forum=$DIRS["smdsforum_forumdir"]."/Files/read_all_topics_on_forum.php";
$file_read_all_messages_on_forum=$DIRS["smdsforum_forumdir"]."/Files/read_all_messages_on_forum.php";
$file_themes_pages=$DIRS["smdsforum_forumdir"]."/Files/themes_pages.php";
$file_mat_filter=$DIRS["smdsforum_forumdir"]."/Files/mat_filter.php";
$file_smiles1=$DIRS["smdsforum_forumdir"]."/Files/smiles1.php";
$file_admin_menu=$DIRS["smdsforum_forumdir"]."/Files/admin_menu.php";
$file_classes=$DIRS["smdsforum_forumdir"]."/Files/classes.php";

$dir_where_languages=$DIRS["smdsforum_forumdir"]."/Files";
$dir_where_colors=$DIRS["smdsforum_forumdir"]."/Files";

$file_star0=$DIRS["smdsforum_forumdir"]."/Images/star0.gif";
$file_star1=$DIRS["smdsforum_forumdir"]."/Images/star1.gif";
$file_star2=$DIRS["smdsforum_forumdir"]."/Images/star2.gif";
$file_voting=$DIRS["smdsforum_forumdir"]."/Images/voting.gif";
$file_last=$DIRS["smdsforum_forumdir"]."/Images/last.gif";
$file_r_no=$DIRS["smdsforum_forumdir"]."/Images/no.gif";
$file_r_ok=$DIRS["smdsforum_forumdir"]."/Images/ok.gif";
$file_y_new=$DIRS["smdsforum_forumdir"]."/Images/y_new.gif";
$file_n_new=$DIRS["smdsforum_forumdir"]."/Images/n_new.gif";
if($t_page<1)$t_page=1;

include $DIRS["smdsforum_forumdir"]."/vars.php";
include $file_read_blocked_ips;
include $file_read_users;
$reg=0;
//Проверяем помним ли мы юзера...
if (check_auth())
        {
        session_register('forum_login');
        $reg=1;
        $forum_login=$CURRENT_USER['login'];
        $HTTP_SESSION_VARS['forum_login']=$CURRENT_USER['login'];
        $user_rights=$CURRENT_USER["level"];
        }
        else
         $user_rights=$vars['guest_rights'];

if(($id=="set_forum")&&($forum_ex!=""))
	{
	$forum_ext=$forum_ex;
	}
//Читаем форумы и ищем id текущего форума.
include $file_read_forums;
for($f=0;$f<count($forums['title']);$f++)
{
if($forums['ext'][$f]==$forum_ext)
	{$forum_id=$f;break;}
}


if($id=="showtopic1")
	{
	$forum_ext=$forum_to;	
	$vars['theme_ext']=$forum_to;
	echo($language['title_topview']);
	$id="showtopic";
	}
if(($forum_ext!="")&&($forums['rights'][$forum_id]<=$user_rights))
	$vars['theme_ext']=$forums['ext'][$forum_id];



//Читаем сообщения
include "$file_read_themes";

//Считаем статистику...
//include "$file_read_statistic";

include $dir_where_languages."/"."lang_rus.php";	



function norm_if_user_notexists($id)
 {
 global $vars,$smb,$smb1;
	if(!file_exists($vars['dir_users']."/".$id))
	  {
          $fp=fopen($vars['dir_users']."/".$id,"w+");
          fwrite($fp,"0".$smb.$smb);
          fclose($fp);
          }          
 }

 
function get_forum_user_data($id)
 {
  global $vars,$smb,$smb1;
  if($id=="!ROOT!")return NULL;
	norm_if_user_notexists($id);
        $all_info=get_file($vars['dir_users']."/".$id);
	$user_vars_all=explode("$smb1",$all_info);
	$user_vars=explode("$smb",$user_vars_all[0]);
	$res['count']=$user_vars[0];
	$res['forum_data']=$user_vars[1];
 return $res;
 }

function save_forum_user_data($id,$data)
 {
  global $vars,$smb,$smb1;
  $all_info=get_file($vars['dir_users']."/".$id);  
  $user_vars_all=explode("$smb1",$all_info);
 $res="";
 $res=$data["count"].$smb.$data['forum_data'].$smb;
  if($user_vars_all[1])
    {
    for($i=0;$i<count($user_vars_all)-1;++$i)
    $res.=$smb1.$user_vars_all[$i+1];
    }
 $fp=fopen($vars['dir_users']."/".$id,"w+");
 fwrite($fp,$res);
 fclose($fp);
 }

//Функция "страница запрещена для просмотра"
function forbidden($var)
        {
        global $language;
        global $user_rights;
        echo($language['forbidden_n']." $var!</b>".$language['back_topindex']);
        }

//Если голосуем, или удаляем голосовалку...
if ((($vote_act=="true")||($vote_act=="delete"))&&($user_rights<2))
        {
        $vote_act="false";
        $voted=true;
        }

if(!isset($forum_id))$forum_id="";
if(isset($forum_id) && ($forums['rights'][$forum_id]>$user_rights)&&($forum_ext!=""))
	$id="forums";

if(check_auth()){
$data=get_forum_user_data($CURRENT_USER["id"]);
$forum_dat=$data["forum_data"];
$__vrs=explode("{&}",$forum_dat);
$forum_dat="";
$ok=false;
for($i=0;$i<count($__vrs);$i++)
	{
	$__vrs1=explode("=",$__vrs[$i]);
	if($forum_ext!=$__vrs1[0])
		{
		}
		else
		{
		//echo("'".$forum_ext."=="."'".$__vrs1[0]);
		$__vrs1[1]=time();
		$ok=true;
		}
	if(($__vrs1[0]!="")&&($__vrs1[1]!=""))$forum_dat.=$__vrs1[0]."=".$__vrs1[1]."{&}";
	}
if($ok==false)
	{
	//echo("ok false");
	$forum_dat.=$forum_ext."=".time()."{&}";
	}
//echo($forum_dat);

if(($id=="set_forum")||($id=="")&& check_auth())
	{
	$data=get_forum_user_data($CURRENT_USER["id"]);		
		$data["forum_data"]=$forum_dat;
		save_forum_user_data($CURRENT_USER["id"],$data);
	}

if(($id=="forums")&&($allf==true)&& check_auth())
	{
	$data=get_forum_user_data($CURRENT_USER["id"]);	
	$forum_dat=$data["forum_data"];
	$__vrs=explode("{&}",$forum_dat);
	$forum_dat="";
	for($i=0;$i<count($__vrs);$i++)
		{
		$ok=false;
		$__vrs1=explode("=",$__vrs[$i]);
		for($j=0;$j<count($forums['title']);$j++)
		{
		if($forums['ext'][$j]!=$__vrs1[0])
			{
			}
			else
			{
			$ok=true;
			}
		if(($__vrs1[0]!="")&&($__vrs1[1]!=""))$forum_dat.=$__vrs1[0]."=".time()."{&}";
		$__vrs1[1]=time();
		if($ok==false)
			{
			$forum_dat.=$forums['ext'][$j]."=".time()."{&}";
			}
		}
		}
		$data["forum_data"]=$forum_dat;
		save_forum_user_data($CURRENT_USER["id"],$data);
	}


if($id=="showtopic" && check_auth())
	{
	$data=get_forum_user_data($CURRENT_USER["id"]);	
	$forum_dat=$data["forum_data"];
	$__vrs=explode("{&}",$forum_dat);
	$forum_dat="";
	$ok=false;
        for($i=0;$i<count($__vrs);$i++)
	{
	$__vrs1=explode("=",$__vrs[$i]);
	if($forum_ext!=$__vrs1[0])
		{
		}
		else
		{
		$tid=0;
		while($all_themes[$tid]!=$topic)
			{
			$tid++;
			}
		if($__vrs1[1]<$all_messages[$tid+1]['time'][count($all_messages[$tid+1]['time'])-2])
			{
			}
			else
			{
			$__vrs1[1]=time();
			}
		}
	if(($__vrs1[0]!="")&&($__vrs1[1]!=""))$forum_dat.=$__vrs1[0]."=".$__vrs1[1]."{&}";
	}
	$data["forum_data"]=$forum_dat;
	save_forum_user_data($CURRENT_USER["id"],$data);
	}
}

//Если юзер пытается залесть в админы переменных...
if(($id==admin)&&($act!="user_admin")&&($act!="user_admin_edit")&&($act!="user_admin_delete")&&($act!=""))
        {
        if($user_rights<7) 
                {
                $error=true;
                $text_error=$language['forbidden_7']." ".$language['back_topindex'];
                }
        }

//Если права юзера<0 то не пускаем его (он заблокирован по аккаунту)
if($user_rights<0)
        {
        forbidden(0);
        exit;
        }

if(($id=="auth")&&($auth=="auth"))
{
include "$file_auth";
exit;
}

if($id=="smiles1")
{
echo("<table width=600 height=10><td>");
include $file_smiles1;
exit;
}



if(($id=="showtopic")||(($id=="pers_messages")&&($act!="new"))||(($id=="add_message")&&($act!="add")))
	{
	if($user_rights>0)echo("<body onload=\"form1.message.focus();selectedtext = document.selection.createRange().duplicate();\">");
	}
echo("<TITLE>".$vars['forum_name']."</TITLE>");
echo("<CENTER><B><BIG>".$vars['forum_name']."</CENTER></B></BIG><CENTER>");
$nick1="";
if($reg!=0)
{
  $nick1=$users_info['nick'][$usr_id];
  
}
$user_rights1=$user_rights;
if($user_rights>7)$user_rights1=7;
if($user_rights>3 && !_isrootdef())
  include "$file_read_private_messages";
if($reg==0)
        {
        echo($language['welcome'].$levels[0].$language['hello_guest']);
        }
        elseif($user_rights<5)
        {
        echo($language['welcome'].$levels[$user_rights1]." ".$nick1.$language['hello_user']);
        if(($count_nrpers_messages>0)&&($vars['show_newpm']=="true"))
                {
                echo("<Br><font color=\"".$vars['pers_color']."\">".$language['nr_private']."".count($user_messages[$usr_id]['from'])."".$language['nr_private1']." $count_nrpers_messages".$language['nr_private2']);
                }
        }
        else
        {
        echo($language['welcome'].$levels[$user_rights1]." ".$nick1.$language['hello_admin']);
        if(($count_nrpers_messages>0)&&($vars['show_newpm']=="true"))
                {
	          echo("<Br><font color=\"".$vars['pers_color']."\">".$language['nr_private']."".count($user_messages[$usr_id]['from'])."".$language['nr_private1']." $count_nrpers_messages".$language['nr_private2']);
                }
        }
echo("</center>");

echo("<hr class=tbl1><center>");
include "$file_buttons";
echo("</center><hr class=tbl1>");

if($error==false)
{
if ($id=="forums")
{
echo($language['version_1.7'][1]);
include "$file_all_forums";
}
elseif ($id=="showtopic")
{
echo($language['title_topview']);
if ($user_rights>=0) include "$file_showtopic";else {forbidden(0);}
}
elseif($id=="find")
{
echo($language['title_find']);
include "$file_find";
}
elseif($id=="downloads")
{
echo($language['version_1.6'][14]);
if (($user_rights>0)&&($vars['downl_enable']=="true")) include "$file_downloads";else {forbidden(1);}
}
elseif($id=="pers_messages")
{
echo($language['title_private']);
if ($user_rights>2) include "$file_pers_messages";else {forbidden(3);}
}
elseif($id=="add_message")
{
echo($language['title_addmessage']);
if ($user_rights>0) include "$file_add_message"; else {forbidden(1);}
}
elseif($id=="admin")
{
if ($user_rights>5) include "$file_admin";else {forbidden(5);}
}
elseif($id=="add_topic")
{
echo($language['title_addtopic']);
if ($user_rights>1) include "$file_add_topic";else {forbidden(2);}
}
elseif($id=="register")
{
echo($language['title_register']);
include "$file_register";
}
elseif(($id=="auth")&&($auth!="auth"))
{
echo($language['title_authorize']);
include "$file_auth";
}
elseif($id=="ses_dec")
{
include "$file_ses_dec";
}
elseif($id=="faq")
{
include "$file_faq";
}
elseif($id=="edit")
{
echo($language['title_mesedit']);
if ($user_rights>2) include "$file_edit";else {forbidden(3);}
}
elseif($id=="add_voting")
{
echo($language['title_addvoting']);
if ($user_rights>2) include "$file_add_voting";else {forbidden(3);}
}
elseif($id=="smiles1")
{
include "$file_smiles1";
}
elseif($id=="userinfo")
{
echo($language['title_userinfo']);
if ($user_rights>0) include "$file_user_info";else {forbidden(1);}
}
elseif($id=="transfer")
{
echo($language['version_1.61'][2]);
if ($user_rights>4) include "$file_transfer";else {forbidden(5);}
}
elseif($id=="delete")
{
echo($language['title_mesdelete']);
if ($user_rights>2) include "$file_delete";else {forbidden(3);}
}
elseif($id=="topic_transfer")
{
echo($language['version_1.7'][6]);
if ($user_rights>4) include "$file_topic_transfer";else {forbidden(5);}
}
elseif($id=="topic_delete")
{
echo($language['title_topdelete']);
if ($user_rights>2) include "$file_topic_delete";else {forbidden(3);}
}
elseif($id=="topic_edit")
{
echo($language['title_topedit']);
if ($user_rights>2) include "$file_topic_edit";else {forbidden(3);}
}
elseif($id=="reg")
{
include "$file_reg";
echo($language['back_topindex']);
}
elseif($id=="profile")
{
echo($language['title_profile']);
include "$file_check_auth";
include "$file_profile";
}
else
{
if($forum_ext!="")
	{
	if($forums['rights'][$forum_id]<=$user_rights)
		{
		if($vars['log_index']=="true") {include "$file_write_log";}
		echo("<center><b>".$forums['title'][$forum_id]."</b></center>");

		include "$file_showall";
		}
		else
		{
		forbidden($forums['rights'][$forum_id]);
		}
	}
	else
	{               
	echo($language['version_1.7'][1]);
	include "$file_all_forums";
	}
}
}
else
{
echo("$text_error");
}
echo("<hr class=tbl1><center>");
include "$file_buttons";
echo("</center><hr class=tbl1>");
if($vars['show_statistic']=="true")
  {
  include "$file_statistic";}
echo("<br>");
if($vars['show_users']=="true") include "$file_online";
if($vars['show_info_banner']=="true") include "$file_info_banner";
if($vars['show_rights']=="true") {include "$file_show_rights";}
if($vars['show_owner']=="true") echo($language['owner']);

global $timer;
$timer->stop();
$gentime=$timer->elapsed();
if($vars['show_gentime']=="true")echo("\n".$language['gentime']."$gentime</SMALL></center>");
?>

