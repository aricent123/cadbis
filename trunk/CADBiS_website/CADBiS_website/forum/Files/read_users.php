<?php

if($MDL->IsModuleExists("users"))
 {
 global $usr_id,$CURRENT_USER;
  $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
  $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
 $list=$USR->GetUsers();
 for($i=0;$i<count($list);++$i)
    {
    $ud=$list[$i];
    $all_users[$i]=$list[$i]["id"];
 
	$users_info['login'][$i]=$ud["login"];
	$users_info['pass'][$i]=$ud["passwd"];
	$users_info['nick'][$i]=$ud["nick"];
	$users_info['city'][$i]=$ud["city"];
	$users_info['sex'][$i]=make_gender_str($ud["gender"]);
	$users_info['email'][$i]=$ud["email"];
	$users_info['url'][$i]=$ud["url"];
	$users_info['id'][$i]=$all_users[$i];
	$users_info['date'][$i]=date_dmy($ud["regdate"]);
	$users_info['rang'][$i]=$ud["rang"];         
	$fud=get_forum_user_data($all_users[$i]);
	$users_info['count'][$i]=$fud["count"];
	//echo($list[$i]["id"]."==($i)".$CURRENT_USER["id"]."<br>");
         if($list[$i]["id"]==$CURRENT_USER["id"])
           {$usr_id=$i;}
	$users_info['raiting'][$i]=$ud["raiting"];
	$users_info['rights'][$i]=$ud["level"];
	$users_info['sign'][$i]=$ud["signature"];
	$users_info['info'][$i]=$ud["info"];
    }	 
 }
 else
 {
 die("MODULE 'USERS' NOT FOUND :( Forum willnt work!");
 }

/*
//директория с которой будем работать...
$dir=$vars['dir_users'];
//очищаем массив с юзерами
$all_users=array();
//открываем директорию
$dirct=opendir($dir);
//читаем директорию, выбирая нужные файлы
while($file=readdir($dirct))
	{
	if(($file!=".")&&($file!=".."))
		{
		$all_users[]=$file;
		}
	}
asort($all_users);
//очищаем инфу о всех юзерах
$users_info['login']=array();
$users_info['pass']=array();
$users_info['nick']=array();
$users_info['city']=array();
$users_info['email']=array();
$users_info['url']=array();
$users_info['raiting']=array();
$users_info['count']=array();
$users_info['rang']=array();
$users_info['sex']=array();
$users_info['rights']=array();
$users_info['sign']=array();
$users_info['info']=array();
//читаем всю инфу для каждого юзера
for($i=0;$i<count($all_users);$i++)
	{
	$user=$all_users[$i];
	//весь файл читаем в пер-ную $all_info
	$all_info=file($vars['dir_users']."/$user");
	$all_info1="";
	foreach($all_info as $inf)
		{
		$all_info1.=$inf;
		}
	$user_vars=array();
	$user_vars_all=array();
	//разбиваем строку по $smb
	$user_vars_all=explode("$smb1",$all_info1);
	//разбиваем строку по $smb
	$user_vars=explode("$smb",$user_vars_all[0]);

	for($r=0;$r<count($user_vars);$r++)
		{
		$user_vars[$r]=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($user_vars[$r])))));
		}
	//присваиваем соответствующим элементам значения
	$users_info['login'][$i]=$user_vars[0];
	$users_info['pass'][$i]=$user_vars[1];
	$users_info['nick'][$i]=$user_vars[2];
	$users_info['city'][$i]=$user_vars[3];
	$users_info['sex'][$i]=$user_vars[4];
	$users_info['email'][$i]=$user_vars[5];
	$users_info['url'][$i]=$user_vars[6];
	$users_info['date'][$i]=$user_vars[7];
	$users_info['rang'][$i]=$user_vars[8];
	$users_info['count'][$i]=$user_vars[9];
	$users_info['raiting'][$i]=$user_vars[10];
	$users_info['rights'][$i]=$user_vars[11];
	//echo($users_info['rights'][$i]."(".$users_info['login'][$i].")<br>");
	$users_info['sign'][$i]=$user_vars[12];
	$users_info['info'][$i]=$user_vars[13];	
/*	$j=0;
	$k=0;
	while($j<13)
		{
		$k++;
		}
/*	echo("read user $user:<br>");
	for($j=0;$j<=7;$j++)
		{
		echo($user_vars[$j]."<br>");
		}
*/
//	}                            */
?>