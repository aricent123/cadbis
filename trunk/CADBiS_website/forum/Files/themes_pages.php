<?php
echo("".$language['topics_total']." ".count($all_themes)." ".$language['topics_showed']." ");
if ($t_links>1)
	{
	if (($t_page+1)*$vars['t_kvo']<count($all_themes))
		{
		echo("".$language['topics_from']." ".($t_page*$vars['t_kvo']+1)." ".$language['topics_to']." ".(($t_page+1)*$vars['t_kvo'])."<br>");
		}
		else
		{
		echo("".$language['topics_from']." ".($t_page*$vars['t_kvo']+1)." ".$language['topics_to']." ".(count($all_themes))."<Br>");
		}
	echo("");
	for($j=1;$j<=$t_links;$j++)
		{
		if ($j!=$t_page+1)echo("<a href=\"?p=$p&forum_ext=$forum_ext&t_page=$j\">$j</a>, ");
		else echo("$j, ");
		}
	}
	else
	{
	echo("".$language['topics_all']."");
	}
echo("<br>");
?>