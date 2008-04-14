<?php

	$topic=$all_themes[$i];
	$links=0;
	for($j=0;$j<$themes_data[$i]['count'];$j=$j+$vars['kvo'])
		{
		$links++;
		}	
	echo("<a href=\"?p=$p&forum_ext=$forum_ext&id=showtopic&t_page=$t_page&show=all&topic=".$all_themes[$i]."\">".$themes_data[$i]['title']."</a>");
	if ($links>1)
		{
		echo(" <SMALL>(");
		for($j=1;$j<=$links;$j++)
			{
			echo("<a href=\"?p=$p&forum_ext=$forum_ext&t_page=$t_page&id=showtopic&topic=$topic&page=$j\">$j</a>, ");
			}
		echo(")</SMALL>");
		}
		$file2=$vars['dir_voting']."/".$all_themes[$i].".".$vars['voting_ext'];
		if(file_exists($file2)==true)
			{
			echo("<SMALL><u>(".$language['version_2.0'][25].")</u></SMALL>");
			}
	if($themes_data[$i]['status']=="closed")echo($language['version_2.0'][26]);
	echo(""."$td_all"."");

	//$reg=0;
	//for($h=0;$h<count($all_users);$h++){if($users_info['login'][$h]==$themes_data[$i]['nick'])$reg=1;}	

		if(is_user_exists($themes_data[$i]['nick']))
			{
			$ud=get_user_data($themes_data[$i]['nick']);
			$user=$ud["nick"];
			echo("<a href=\"?p=users&act=userinfo&id=".$themes_data[$i]['nick']."\">".$user."</a>
			"."$td_all"."");			
			}
			else
			{
			$user=$themes_data[$i]['nick'];
                	echo($user."
  		        "."$td_all"."");			
			}

	echo("
	".$themes_data[$i]['date']."
	"."$td_all"."");
	if ($links>0)
	{
		//$reg=0;
		//for($h=0;$h<count($all_users);$h++){if(($users_info['login'][$h]==$all_messages[$i]['nick'][$themes_data[$i]['count']-1])&&($all_messages[$i]['user_id'][$themes_data[$i]['count']-1]==$all_users[$h]))$reg=1;}	
		if(is_user_exists($all_messages[$i]['user_id'][$themes_data[$i]['count']-1]))
			{
			$ud=get_user_data($all_messages[$i]['user_id'][$themes_data[$i]['count']-1]);
			//while($users_info['id'][$usr_id]!=$all_messages[$i]['nick'][$themes_data[$i]['count']-1] && $usr_id<count($users_info['id']))$usr_id++;
			$user=$ud['nick'];
                        echo("<a href=\"?p=users&act=userinfo&id=".$all_messages[$i]['user_id'][$themes_data[$i]['count']-1]."\">
		        ".$user."</a>");
			}
			else
			{
			$user=$all_messages[$i]['nick'][$themes_data[$i]['count']-1];
		        echo("".$user."");
			}
		echo(",
		".$themes_data[$i]['refresh']."");
		echo(" <a href=\"?p=$p&forum_ext=$forum_ext&t_page=$t_page&id=showtopic&topic=$topic&page=$links#last\"><img src=\"$file_last\" border=0></a>");
	}
	echo("
	"."$td_all"."
	".$themes_data[$i]['views']."
	"."$td_all"."
	".$themes_data[$i]['count']."
	"."$td_all"."
	<SMALL>".$themes_data[$i]['descr']."</SMALL>");
?>                      