<?php
//äèðåêòîðèÿ, ñ êîòîðîé áóäåì ðàáîòàòü
$dir=$vars['dir_themes'];
//îòêðûâàåì...
$dirct=opendir($dir);
$i=0;
//î÷èùàåì
$all_themes=array();
//÷èòàåì âñå íóæíûå ôàéëû
while($file=readdir($dirct))
	{            
	if(strstr($file,$vars['theme_ext'])==true)
		{
		$all_themes[]=str_replace(".".$vars['theme_ext'],"",$file);
		$i++;
		}
	}
//÷èòàåì âñå ñîîáùåíèÿ (1-é ðàç...)
include "$file_read_all_messages";

///ÑÎÐÒÈÐÓÅÌ ÏÎ ÓÁÛÂÀÍÈÞ ÂÐÅÌÅÍÈ ÑÎÇÄÀÍÈß ÏÎÑËÅÄÍÅÃÎ ÎÒÂÅÒÀ
$i=0;
while($i<count($all_themes))
	{
	if (($all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1])>($all_messages[$i]['time'][$themes_data[$i]['count']-1]))
		{
		$buf=$all_themes[$i];
		$buf2=$all_messages[$i]['time'][$themes_data[$i]['count']-1];
//		echo("<br>Ìåíÿåì '".$themes_data[$i]['title']."'(".($i).") ñ".$themes_data[$i+1]['title']."'(".($i+1).")");
		$all_messages[$i]['time'][$themes_data[$i]['count']-1]=$all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1];
		$all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1]=$buf2;
		$all_themes[$i]=$all_themes[$i+1];
		$all_themes[$i+1]=$buf;
		$i=-1;
		}
	$i=$i+1;
	}

//÷èòàåì âñå ñîîáùåíèÿ (2-é ðàç...)
include "$file_read_all_messages";
?>