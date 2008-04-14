<?php

$found=array();
$context=array();
$ffiles=array();
$g=0;
$total=0;
if($act=="find")
	{
	$words=explode(" ",$findword);
for($b=0;$b<count($forums['ext']);$b++)
	{
	$vars['theme_ext']=$forums['ext'][$b];
	//$forum_ext=$forums['ext'][$b];
	$all_messages=array();
	$themes_data=array();
	include "$file_read_themes";

	for($i=0;$i<count($all_themes);$i++)
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
			if($forums['rights'][$b]>$user_rights)$okk=false;
			if($okk==false)continue;
		for($j=0;$j<$themes_data[$i]['count'];$j++)
			{
			$total++;
			//echo(strtolower($all_messages[$i]['text'][$j]).",".strtolower($findword)."<hr>");
			$findtext=strip_tags(strtolower($all_messages[$i]['text'][$j]));
			$findtext=nl2br($findtext);
			$ok=true;
			for($k=0;$k<count($words);$k++)
				{
				if(strstr($findtext,strtolower($words[$k]))==false)
					{
					//echo($words[$k]." - не встречается в $findtext<br>");
					$ok=false;
					}
				}
			if($ok==true)
				{
				$pos=strpos($findtext,strtolower($findword));
				$to=strlen($findword)+50;
				/*while(($findtext[$pos+$to]!=" ")&&($findtext[$pos+$to]!="")||($findtext[$pos+$to]!="."))
					{
					$to++;
					//echo($findtext[$pos+$to]."<br>");
					if($to>1000)break;
					}
				*/
				$to+=30;
				$findtext=strip_tags($all_messages[$i]['text'][$j]);
				$findtext=nl2br($findtext);
				$found_title[$g]=$themes_data[$i]['title'];
				//$found_fext[$g]=$forums['ext'][$b];
				$found[$g]="...".substr($findtext,$pos,$to)."...";
				//echo("<hr>current forum:".$forums['title'][$b].": $findword found in:".$all_messages[$i]['text'][$j]);
				$links=0;
				for($k=0;$k<$j+1;$k=$k+$vars['kvo'])
					{
					$links++;
					}
				$flink[$g]="<a href=\"?p=$p&forum_ext=".$forums['ext'][$b]."&id=showtopic&topic=".$all_themes[$i]."&page=$links#$j\">";
				$g++;
				}
			}
		}

	}
	//exit;
	$vars['theme_ext']=$forum_ext;
	$all_messages=array();
	$themes_data=array();
	include "$file_read_themes";

	echo("<CENTER>".$language['version_1.6'][36]." $total");
	if(count($found)>0)
		{
		echo("<br>".$language['version_1.6'][37]." ".count($found)." ".$language['version_1.6'][38]."</CENTER><br>");
		for($i=0;$i<count($found);$i++)
			{
			echo("<br><br>".$language['version_1.6'][39]." <b>'".$found_title[$i]."'</b><br>'".$found[$i]."'<br><b>".$flink[$i]."".$language['version_1.6'][40]."</a></b>");
			}
		}
		else
		{
		echo("<br>".$language['version_1.6'][41]." '$findword' ".$language['version_1.6'][42]."");
		}
	
	echo("<br><br><CENTER><a href=?p=$p&forum_ext=$forum_ext&id=find>".$language['version_1.6'][43]."</a></CENTER>");
	}
	else
	{
	echo("
	<CENTER>
	<FORM action=?p=$p&forum_ext=$forum_ext&id=find&act=find method=post>
	<input type=text name=findword>
	<input type=submit value=\"".$language['version_1.6'][44]."\">
	</FORM></CENTER>
	");
	}



?>