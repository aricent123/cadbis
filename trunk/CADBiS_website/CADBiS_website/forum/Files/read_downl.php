<?php
include "$file_read_down_ext";
$file1=file($vars['file_files_desc']);
$file=implode("",$file1);
$array1=explode("$smb",$file);
$down_filenames=array();
$down_filedescs=array();
for($i=0;$i<count($array1);$i+=2)
	{
	$down_filenames[]=$array1[$i];
	$down_filedescs[]=$array1[$i+1];
	}
for($i=0;$i<count($down_ext);$i++)
	{
	$down_files["".$down_ext[$i].""]=array();
	$down_descs["".$down_ext[$i].""]=array();
	}
//echo("open dir ".$vars['dir_downloads']."<br>");
$dir=opendir($vars['dir_downloads']);
while($file=readdir($dir))
	{
	//echo("$file<br>");
	if(($file!=".")&&($file!=".."))
		{
		$ok=false;
		for($i=0;$i<count($down_ext);$i++)
			{	
			if(get_file_ext($file)==$down_ext[$i])$ok=true;
			}
		if($ok==true)
			{
			$down_files["".get_file_ext($file).""][]=$file;
			$file_desc="";
			for($j=0;$j<count($down_filenames);$j++)
				{
				if($down_filenames[$j]==$file)
					{
					$file_desc=$down_filedescs[$j];
					}
				}
			$down_descs[get_file_ext($file)][]=$file_desc;

			}
		}
	}

?>
