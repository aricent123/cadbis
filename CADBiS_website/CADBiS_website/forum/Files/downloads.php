<?php
include $file_read_downl;
echo("<CENTER>");
	echo("$table_all $td_all");
	for($i=0;$i<count($down_ext);$i++)
		{
		if($i>=0&&count($down_files[$down_ext[$i]]&&count($down_files[$down_ext[$i+1]])>0)>0)//echo("<tr>$td_all");
		if(count($down_files[$down_ext[$i]])>0)
			{
			echo("<tr>$td_all<b>".$down_ext[$i]." - files:</b><tr>$td_all");
			}
		for($j=0;$j<count($down_files[$down_ext[$i]]);$j++)
			{
			echo("<a href=\"".$vars['dir_downloads']."/".$down_files[$down_ext[$i]][$j]."\">".$down_files[$down_ext[$i]][$j]."</a> (".ceil(filesize($vars['dir_downloads']."/".$down_files[$down_ext[$i]][$j])/1024)." Kb)");
			if($down_descs[$down_ext[$i]][$j]!="")echo("<br><SMALL>".$down_descs[$down_ext[$i]][$j]."</SMALL><br>\n");
			else echo("<br><SMALL>".$language['version_1.6'][17]."</SMALL><br>\n");
			}
		if((count($down_files[$down_ext[$i+1]])>0)&&(count($down_files[$down_ext[$i]])>0)&&($i<count($down_ext)-1))
			{
			//echo("<tr>$td_all");
			}
		}
	echo("</table>");

include $file_add_downloads;
			
?>