<?php
echo("<center>");

$t_links=0;
for($j=0;$j<count($all_themes);$j=$j+$vars['t_kvo'])
	{
	$t_links++;
	}

if (isset($t_page)==false)$t_page=1;
$t_page--;
include "$file_themes_pages";

if (count($all_themes)>0)
{
echo("
$table_all"."$td_all"."
".$language['showall'][0].""."$td_all"."
".$language['showall'][1].""."$td_all"."
".$language['showall'][2].""."$td_all"."
".$language['showall'][3].""."$td_all"."
".$language['showall'][4].""."$td_all"."
".$language['showall'][5].""."$td_all"."
".$language['showall'][6]."");
//if(($vars['show_view_t']=="true")&&($vars['show_thhp']=="true"))echo(""."$td_all"."".$language['showall'][7]."");
if(($vars['show_view_t']=="true"))echo(""."$td_all"."".$language['showall'][7]."");
echo("<tr>"."$td_all"."");





//$t_page++;

//if(count($all_themes)>=$t_links)
if($t_links>0)
{
if (($t_page+1)*$vars['t_kvo']<count($all_themes))
	{
	
	for($i=$t_page*$vars['t_kvo'];$i<($t_page+1)*$vars['t_kvo'];$i++)
		{
		$error_r="false";
		if($vars['show_thhp']!="true")
			{
			if(($user_rights<$themes_data[$i]['rights'])&&($vars['show_thhp']=="false"))$error_r="true";
			}
			
		if($error_r=="false")
			{
			include "$file_show_themes";
			//if($i<($t_page+1)*$vars['t_kvo']-1)echo("<tr>"."$td_all"."");
			}

			//if(($vars['show_thhp']=="true")&&($vars['show_view_t']=="true"))
			if(($vars['show_view_t']=="true")&&($error_r=="false"))
				{
				$okk=true;
				if($user_rights<$themes_data[$i]['rights'])$okk=false;
				if($themes_data[$i]['names']!="")
					{
					$names=explode(",",$themes_data[$i]['names']);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<$themes_data[$i]['namesrights']))
						{
						$okk=false;
						}
					}
				if($okk==true)echo("$td_all<img width=30 height=30 src=\"$file_r_ok\">");
				else echo("$td_all<img width=30 height=30 src=\"$file_r_no\">");
				}
			
			if($error_r=="false")
			{
			if($i<($t_page+1)*$vars['t_kvo']-1)echo("<tr>"."$td_all"."");
			}
		}
	}
	else
	{
	for($i=$t_page*$vars['t_kvo'];$i<count($all_themes);$i++)
		{
		$error_r="false";
		if($vars['show_thhp']!="true")
			{
			if(($user_rights<$themes_data[$i]['rights'])&&($vars['show_thhp']=="false"))$error_r="true";
			}
		if($error_r=="false")
			{
			include "$file_show_themes";
			//if($i<$themes_data[$i]['rights']-1)echo("<tr>"."$td_all"."");
			}

			//if(($vars['show_thhp']=="true")&&($vars['show_view_t']=="true"))
			if(($vars['show_view_t']=="true")&&($error_r=="false"))
				{
			$okk=true;
				if($user_rights<$themes_data[$i]['rights'])$okk=false;
				if($themes_data[$i]['names']!="")
					{
					$names=explode(",",$themes_data[$i]['names']);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<$themes_data[$i]['namesrights']))
						{
						$okk=false;
						}
					}
				if($okk==true)echo("$td_all<img width=30 height=30 src=\"$file_r_ok\">");
				else echo("$td_all<img width=30 height=30 src=\"$file_r_no\">");
				}
		if($error_r=="false")
			{
			if($i<count($all_themes)-1)echo("<tr>"."$td_all"."");
			}
		}

	}
}
else
{
for($i=0;$i<count($all_themes);$i++)	
	{
	$error_r="false";
		if($vars['show_thhp']!="true")
			{
			if(($user_rights<$themes_data[$i]['rights'])&&($vars['show_thhp']=="false"))$error_r="true";
			}
		if($error_r=="false")
			{
			//if($i>0)echo("<tr>"."$td_all"."");
			include "$file_show_themes";
			//if($i<count($all_themes)-1)echo("<tr>"."$td_all"."");
			}

			//if(($vars['show_thhp']=="true")&&($vars['show_view_t']=="true"))
			if(($vars['show_view_t']=="true")&&($error_r=="false"))
				{
				$okk=true;
				if($user_rights<$themes_data[$i]['rights'])$okk=false;
				if($themes_data[$i]['names']!="")
					{
					$names=explode(",",$themes_data[$i]['names']);
					$ok=false;
					for($s=0;$s<count($names);$s++)
						{
						if($names[$s]==$HTTP_SESSION_VARS['forum_login'])$ok=true;
						}
					if(($ok==false)&&($user_rights<$themes_data[$i]['namesrights']))
						{
						$okk=false;
						}
					}
				if($okk==true)echo("$td_all<img width=30 height=30 src=\"$file_r_ok\">");
				else echo("$td_all<img width=30 height=30 src=\"$file_r_no\">");

				}
		if($error_r=="false")
			{
			if($i<count($all_themes)-1)echo("<tr>"."$td_all"."");
			}
		
		}
}
echo("</table>");
}
else
{
echo("".$language['showall'][8]."");
}
echo("<br>");
include "$file_themes_pages";
echo("</center>");
?>