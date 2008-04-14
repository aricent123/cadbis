<?php
$links=0;
for($j=0;$j<$themes_data[$i]['count'];$j=$j+$vars['kvo'])
	{
	$links++;
	}
if ($links>1)
	{
	for($j=1;$j<=$links;$j++)
		{
		if ($j!=$page+1)echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$j\">$j</a>, ");
		else echo("$j, ");
		}
	}
?>