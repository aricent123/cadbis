<?php
//����������, � ������� ����� ��������
$dir=$vars['dir_themes'];
//���������...
$dirct=opendir($dir);
$i=0;
//�������
$all_themes=array();
//������ ��� ������ �����
while($file=readdir($dirct))
	{            
	if(strstr($file,$vars['theme_ext'])==true)
		{
		$all_themes[]=str_replace(".".$vars['theme_ext'],"",$file);
		$i++;
		}
	}
//������ ��� ��������� (1-� ���...)
include "$file_read_all_messages";

///��������� �� �������� ������� �������� ���������� ������
$i=0;
while($i<count($all_themes))
	{
	if (($all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1])>($all_messages[$i]['time'][$themes_data[$i]['count']-1]))
		{
		$buf=$all_themes[$i];
		$buf2=$all_messages[$i]['time'][$themes_data[$i]['count']-1];
//		echo("<br>������ '".$themes_data[$i]['title']."'(".($i).") �".$themes_data[$i+1]['title']."'(".($i+1).")");
		$all_messages[$i]['time'][$themes_data[$i]['count']-1]=$all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1];
		$all_messages[$i+1]['time'][$themes_data[$i+1]['count']-1]=$buf2;
		$all_themes[$i]=$all_themes[$i+1];
		$all_themes[$i+1]=$buf;
		$i=-1;
		}
	$i=$i+1;
	}

//������ ��� ��������� (2-� ���...)
include "$file_read_all_messages";
?>