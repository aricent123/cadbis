<?php
$_file1=file($file_forums);
$_file=implode("",$_file1);
$_vrs1=explode($smb1,$_file);
$forums['title']=array();
$forums['descr']=array();
$forums['ext']=array();
$forums['rights']=array();
$forums['admins']=array();
for($m=0;$m<count($_vrs1);$m++)
	{
	$_vrs=explode($smb,$_vrs1[$m]);
	$forums['title'][]=$_vrs[0];
	$forums['descr'][]=$_vrs[1];
	$forums['ext'][]=$_vrs[2];
	$forums['rights'][]=$_vrs[3];
	$forums['admins'][]=$_vrs[4];
	$f_count=0;
	}
?>