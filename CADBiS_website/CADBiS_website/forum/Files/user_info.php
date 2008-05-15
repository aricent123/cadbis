<?php

for($k=0;$k<count($all_users);$k++)
	{
	if($all_users[$k]==$user_info){$reg=1;$usr_id=$k;}
	}
$user_num=$usr_id;
echo("<CENTER>".$table_all.$td_all."");
if($users_info['sex'][$user_num]=="женский")$sexx=$language['sex_woman'];
elseif($users_info['sex'][$user_num]=="мужской")$sexx=$language['sex_man'];
else $sexx=$users_info['sex'][$user_num];

echo("".$language['version_2.0'][50]."<tr>$td_all $table_all $td_all
USER ID:".$td_all."".$all_users[$usr_id]."<tr>".$td_all);
if((file_exists($vars['dir_avatars']."/".$all_users[$usr_id].".gif")))
	{
	echo("
	".$language['version_2.0'][52]."".$td_all."
	<img border=0 src=\"".$vars['dir_avatars']."/".$all_users[$usr_id].".gif"."\">"."<tr>".$td_all."
	");
	}
echo("".$language['userinfo_nick']."".$td_all."".$users_info['nick'][$usr_id]."<tr>".$td_all."
".$language['userinfo_date']."".$td_all."".$users_info['date'][$usr_id]."<tr>".$td_all."
".$language['userinfo_rang']."".$td_all."".$users_info['rang'][$usr_id]."<tr>".$td_all."
".$language['userinfo_count']."".$td_all."".$users_info['count'][$usr_id]."<tr>".$td_all."
".$language['userinfo_topics']."".$td_all."".$total_topics_f[$usr_id]."<tr>".$td_all."
".$language['userinfo_raiting']."".$td_all."".$users_info1['raiting'][$usr_id]."
</table><tr>$td_all");
if(($users_info['city'][$usr_id]!="")||($users_info['email'][$usr_id]!="")||($users_info['url'][$usr_id]!=""))
{
echo("".$language['version_2.0'][49]." ".$users_info['nick'][$usr_id]."<tr>$td_all  $table_all $td_all");
if($users_info['city'][$usr_id]!="")echo("".$language['userinfo_city']."".$td_all."".$users_info['city'][$usr_id]."");
if($users_info['email'][$usr_id]!="")echo("<tr>".$td_all."".$language['userinfo_email']."".$td_all."<a href=\"mailto:".$users_info['email'][$usr_id]."\">".$users_info['email'][$usr_id]."</a>");
if($users_info['url'][$usr_id]!="")echo("<tr>".$td_all."".$language['userinfo_url']."".$td_all."<a href=\"".$users_info['url'][$usr_id]."\">".$users_info['url'][$usr_id]."</a>");
echo("</table><tr>$td_all");
}
echo("".$language['version_2.0'][51]."<tr>$td_all  $table_all $td_all
".$language['userinfo_sex']."".$td_all."".$sexx."");
if($users_info['sign'][$usr_id]!="")echo("<tr>".$td_all."".$language['userinfo_sign']."".$td_all."".$users_info['sign'][$usr_id]."");
if($users_info['info'][$usr_id]!="")echo("<tr>".$td_all."".$language['userinfo_info']."".$td_all."".$users_info['info'][$usr_id]."");
echo("</table></table></CENTER>");
?>