<?php

$user=$all_users[$usr_id];
//весь файл читаем в пер-ную $all_info
$all_info1=get_file($vars['dir_users']."/$user");
	$user_vars=array();
	$user_vars_all=array();
	//разбиваем строку по $smb
	$user_vars_all=explode("$smb1",$all_info1);

	include "$file_read_user_messages";
	///СОРТИРУЕМ ПО УБЫВАНИЮ ВРЕМЕНИ СОЗДАНИЯ ПОСЛЕДНЕГО ОТВЕТА
	$f=0;
	while($f<count($user_messages[$usr_id]['from'])-1)
		{
		if($user_messages[$usr_id]['time'][$f]<$user_messages[$usr_id]['time'][$f+1])
			{
			$buf=$user_vars_all[$f+1];
			$user_vars_all[$f+1]=$user_vars_all[$f+2];
			$user_vars_all[$f+2]=$buf;
			$buf=$user_messages[$usr_id]['time'][$f];
			$user_messages[$usr_id]['time'][$f]=$user_messages[$usr_id]['time'][$f+1];
			$user_messages[$usr_id]['time'][$f+1]=$buf;
			$f=-1;
			}
		$f=$f+1;
		}	

	include "$file_read_user_messages";
	$count_nrpers_messages=0;
	for($f=0;$f<count($user_messages[$usr_id]['from']);$f++)
		{
		if($user_messages[$usr_id]['status'][$f]!="прочитано")$count_nrpers_messages++;
		}

?>