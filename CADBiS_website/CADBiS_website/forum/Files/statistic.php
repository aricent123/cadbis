<?php
include "$file_read_statistic";

echo("<CENTER>".$language['stat_totalusers']." ".count($all_users)."; ");

if($forum_ext!="")echo("".$language['stat_totalmessages']." $total_messages; ");

echo("".$language['version_2.0'][0]." $total_messages_f; <Br>
".$language['stat_largestwrite']." $max_mess ($max_mess_c); ".$language['stat_largesttopics']." $max_themes ($max_themes_c)<br>
".$language['stat_largestraiting']." $max_raiting ($max_raiting_c)<br>
");
/*
echo("".$language['version_2.0'][38]."");
for($i=0;$i<count($new_users);$i++)
	{
	echo($new_users[$i]);
	if($i<count($new_users)-1)echo(", ");
	}
*/
echo("
</CENTER>");
?>