<?php
$unsymb=array();
$_file=file($vars['file_mat_filter']);
$_file1="";
for($i=0;$i<count($_file);$i++)
	{
	$_file1.=$_file[$i];
	}
$unsymb=explode("$smb",$_file1);

?>