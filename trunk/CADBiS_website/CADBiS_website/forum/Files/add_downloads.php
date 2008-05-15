<?php
if($act=="add")
	{
		$zak=$HTTP_POST_FILES["down_file"]["tmp_name"];
		$zakname=$HTTP_POST_FILES["down_file"]["name"];
	$error=false;
	for($i=0;$i<count($down_ext);$i++)
		{
		for($j=0;$j<count($down_files[$down_ext[$i]]);$j++)
			{
			if($zakname==$down_files[$down_ext[$i]][$j])
				{
				echo($language['version_1.6'][26]);
				echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
				$error=true;
				}
			}
		}
	if($error==false)
		{
		$ok=false;
		for($i=0;$i<count($down_ext);$i++)
			{
			//echo($down_ext."==".get_file_ext($zakname)."<br>");
			if($down_ext[$i]==get_file_ext($zakname))$ok=true;
			}
			if($ok==false)
			{
			echo($language['version_1.6'][35]);
			echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
			$error=true;
			}
		}
	if($error==false)
		{
		if(filesize($zak)>$vars['file_maxsize']*1024)
			{
			echo($language['version_1.6'][29]."".$vars['file_maxsize']."".$language['version_1.6'][31]);
			echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
			$error=true;
			}
		}

	if($error==false)
		{
		if(strlen($zakname)>$vars['file_maxlen'])
			{
			echo($language['version_1.6'][30]."".$vars['file_maxlen']."".$language['version_1.6'][32]);
			echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
			$error=true;
			}
		}

	if($error==false)
		{	
		$filename=$zakname;
		$filename=strtolower($filename);	
		$file_des=htmlspecialchars($file_des);
		$file_des=str_replace("$smb","",$file_des);
		$file_des=str_replace("$smb1","",$file_des);
		if($user_rights<5)$file_des=substr($file_des,0,$vars['mes_len']);
		$file_des=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($file_des)))));
		$file_des=substr($file_des,0,$vars['file_maxdesc']);
		if (copy($zak,$vars['dir_downloads']."/".$filename))
			{
			$zapis="";
			for($i=0;$i<count($down_filenames);$i++)
				{
				$zapis.=$down_filenames[$i]."$smb".$down_filedescs[$i];
				$zapis.=$smb;
				}
			$zapis.=$filename."$smb".$file_des;
			$fp=fopen($vars['file_files_desc'],"w+");
			fwrite($fp,$zapis);
			fclose($fp);		



			echo($language['version_1.6'][34]);
			echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
			}
			else
			{
			echo($language['version_1.6'][33]);
			echo("<br><a href=?p=$p&forum_ext=$forum_ext&id=downloads>".$language['version_1.6'][28]."</a>");
			}
		
		}

	}
	else
	{
	echo($language['version_1.6'][18]);
	echo($language['version_1.6'][19]);
	for($i=0;$i<count($down_ext);$i++)
		{
		echo($down_ext[$i]);
		if($i<count($down_ext)-1)echo(",");else echo(".");
		}
	echo("
	<FORM action=\"?p=$p&forum_ext=$forum_ext&id=downloads&act=add\" method=post encType=multipart/form-data><table><td>
      ".$language['version_1.6'][23]."<td><INPUT type=file name=down_file style=\"width:300;$input_text\"><tr><td>
	".$language['version_1.6'][24]."<td><TEXTAREA cols=35 rows=4 name=file_des style=\"width:300; height:50;$input_text\"></TEXTAREA>
	</table><input type=submit value=\"".$language['version_1.6'][25]."\" style=\"$input_button\"></FORM>
	");
      
	}

?>