<?php
$file2=$vars['dir_voting']."/".$topic.".".$vars['voting_ext'];
if($user_rights<1){$voted=true;}
if (file_exists($file2))
	{
	$file1=file($file2);
	$file3="";
	for($d=0;$d<count($file1);$d++)$file3.=$file1[$d];
	$file=explode($smb1,$file3);
	$title=$file[0];
	$content=explode($smb,$file[1]);
	$ips=explode($smb,$file[2]);
	echo("<center>$title</center>");
	if($voted!=true)$voted=false;
	for($d=0;$d<count($ips);$d++)
		{
		if($HTTP_SESSION_VARS['forum_login']==$ips[$d]){$voted=true;break;}
		}
	$all_votes=0;
	for($d=0;$d<count($content);$d+=2)
		{
		$all_votes+=$content[$d+1];
		}
$error=false;
	if($vote_act=="delete")
		{
		if($do=="yes")
			{
			$i=0;
			while($all_themes[$i]!="$topic")
			{
			$i++;
			}
		if(($HTTP_SESSION_VARS['forum_login']!=$themes_data[$i]['nick'])&&($user_rights<5)) {exit;}
		$file2=$vars['dir_voting']."/".$topic.".".$vars['voting_ext'];
		if(!(unlink($file2))){echo("<CENTER>Error VD01! Невозможно удалить голосование!</CENTER>");}
			else
			{
			echo("".$language['addvoting'][0]."");
			$error=true;
			}
			echo("<br><a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['addvoting'][3]."</a><br>");
			}
			else
			{
			$i=0;
			while($all_themes[$i]!="$topic")
			{
			$i++;
			}
			echo("<CENTER>".$language['addvoting'][1]." \"".$themes_data[$i]['title']."\" ? <br>");
			echo("<a href=?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page&vote_act=delete&do=yes>".$language['addvoting'][2]." <br>
			<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic&page=$page\">".$language['addvoting'][3]."</a></CENTER>");
			}
		}
if($vote_act=="delete")$error=true;
if($error==false)
{
	if(($vote_act=="true")&&($voted==false))
		{
		$content[$votechoice+1]++;
		if(!($fp=fopen($file2,"w+"))){echo("<CENTER>Error VA01! Невозможно проголосовать!</CENTER>");}
			else
			{
			$zapis=$title.$smb1;
			for($d=0;$d<count($content);$d+=2)
				{
				$zapis.=$content[$d].$smb.$content[$d+1];
				if($d<count($content)-2)$zapis.=$smb; 
				//echo($zapis."<br>");
				}
			$zapis.=$smb1;
			for($d=0;$d<count($ips);$d++)
				{
				$zapis.=$ips[$d];
				if($d<count($ips)-1) $zapis.=$smb;
				}
			$zapis.=$smb.$HTTP_SESSION_VARS['forum_login'];
//			echo($zapis);
			if(!(fwrite($fp,$zapis))){echo("<CENTER>Error VA02! Невозможно проголосовать!</CENTER>");}
			fclose($fp);
			$voted=true;
			$all_votes++;
			}
		}
	if ($voted==true)
		{
		echo("<table><td>");
		for($f=0;$f<count($content);$f+=2)
			{
			$percent=round(($content[$f+1]/$all_votes)*100);
			echo($content[$f].":<td><img src=\"".$file_voting."\" height=10 width=".$percent.">$percent%(".$content[$f+1].")<tr><td>");
			}
		echo("</table>");
		}
		else
		{
		echo("<CENTER><form aciton=?p=$p&forum_ext=$forum_ext&id=showtopic&topic=$topic method=post>
		<table><td>");
		for($f=0;$f<count($content);$f+=2)
			{
			echo($content[$f]."<td><input type=radio name=votechoice value=\"$f\" ");
		if($f==0)echo("checked ");
		echo("><tr><td>");
			}
		echo("</table><input type=hidden name=vote_act value=true><input type=submit value=\"".$language['addvoting'][4]."\"></form></CENTER>");
		}
	}
}
?>