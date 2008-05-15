<?php
echo("| <a href=\"?p=$p&id=forums\">".$language['version_1.7'][0]."</a> ");
if($forum_ext!="")echo("| ".$language['but_index']." ");
if(($user_rights>1)&&($forum_ext!=""))echo("| ".$language['but_newtopic']);
if($user_rights>2 && !_isrootdef())
    {echo(" | ".$language['but_private']);
    if($count_nrpers_messages)
      echo("(<b>".$count_nrpers_messages."</b>/".count($user_messages[$usr_id]['from']).")");
     }
	if($user_rights>6)echo(" | ".$language['but_admin']);
echo(" | ");
if (($user_rights>0)&&($vars['downl_enable']=="true"))
echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=downloads\">".$language['version_1.6'][16]."</a> | ");
echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=find\">".$language['version_1.6'][15]."</a> |");
echo(" ".$language['but_faq']." |");

if($vars['language']=="all")
	{
echo("</centeR><div align=right><SMALL>");
	for($i=0;$i<count($languages);$i++)
		{
		if($HTTP_COOKIE_VARS['language']!=$languages[$i])
		echo("<a href=\"?p=$p&forum_ext=$forum_ext&setlng_=".$languages[$i]."\">".$languages[$i]."</a>");
		else echo($languages[$i]);		
		echo(", ");
		
		}
	echo("</SMALL></div>");
	}
?>