<?php

//списки таблиц
$GV["users_tbl"]="users";
//$GV["dbname"]="nibs";
$GV["groups_tbl"]="packets";
$GV["blacklist_tbl"]="blacklist";
$GV["actions_tbl"]="actions";
$GV["events_tbl"]="events";
$GV["url_denied_tbl"]="url_denied";


function radius_restart()
{
 //$prg="sudo killall -HUP radiusd";
 //$prg="sudo ps -wuax";
 //$res = shell_exec("killall -HUP radiusd");
 //$res=shell_exec("nohup $prg> /dev/null 2>&1 &");
 //$res=shell_exec("$prg");
 //$res=shell_exec("sudo ps -ux | grep radiusd");
 //echo("res: $prg <br />".$res);
 //$res="radiusd -XA";
 //echo($res);
 //echo("killall -HUP radiusd");
}

function norm_date_yymmdd($time)
{
 $date=getdate($time);
 if($date['mday']<10)$date['mday']="0".$date['mday'];
if($date['mon']<10)$date['mon']="0".$date['mon'];
if($date['year']<10)$date['year']="0".$date['year'];
$date=$date['year']."-".$date['mon']."-".$date['mday'];
return $date;
}


function norm_date_yymmddhhmmss($time)
{
 $date=getdate($time);
if($date['mday']<10)$date['mday']="0".$date['mday'];
if($date['mon']<10)$date['mon']="0".$date['mon'];
if($date['year']<10)$date['year']="0".$date['year'];
if($date['hours']<10)$date['hours']="0".$date['hours'];
if($date['minutes']<10)$date['minutes']="0".$date['minutes'];
if($date['seconds']<10)$date['seconds']="0".$date['seconds'];

$date=$date['year']."-".$date['mon']."-".$date['mday']." ".$date['hours'].":".$date['minutes'].":".$date['seconds'];

return $date;
}

class CBilling
{
//переменные члены класса
var $server;
var $database;
var $login;
var $password;
var $link;

//конструктор
function CBilling($server,$database,$login,$password)
         {
	    $this->server=$server;
	    $this->database=$database;
	    $this->login=$login;
	    $this->password=$password;
	    $this->link = mysql_connect($server,$login, $password)
                        or die("Could not connect: " . mysql_error());
            mysql_select_db($this->database) or die("Could not select database");
	   $this->squid_porno_file = "./DB3fsdi2382ofdo0r20u23jidf/squid/porno";
	   }


function GetCountry($ip)
 {
 static $rem_ips = array();
 
 $ipvalue = ip2value($ip);
 $q = "SELECT sip,eip,ctry,country FROM ip2country WHERE sip<={$ipvalue} AND eip>={$ipvalue}";
 
 // OPTIMIZATION - remembered diapazons
 foreach($rem_ips as $rem_ip)
  if($rem_ip['sip']<$ipvalue && $rem_ip['eip']>$ipvalue)
    return array('ctry'=>strtolower($rem_ip['ctry']),'country'=>$rem_ip['country']);
 // -- eof optimization
    
 $res = mysql_query($q);
 if(!mysql_num_rows($res)) return false;
 $res = mysql_fetch_assoc($res);
   
 // remember the retrieved IP data:
 $rem_ips[] = array('sip'=>$res['sip'], 'eip'=>$res['eip'], 'ctry'=>$res['ctry'], 'country'=>$res['country']);
 
 return array('ctry'=>strtolower($res['ctry']),'country'=>$res['country']);  
 }

//добавление пользователя
function AddUser($user)
	{
	global $GV,$CURRENT_USER;

	/*
	uid - ДЗПУ (скорее всего используется функция time() ) -
	user                                                   +
	password                                                 +
	crypt_method - взять один из них (посмотреть как сделано в NIBS и что при этом добавляется в -
	таблицу. Лучше использовать MD5)
        gid                 +
        fio         +
        phone        +
        address       +
        prim - ДЗПУ    +
        add_date        +
        add_uid ( Alter table users add (`add_uid` after `add_date`);... ) +
        expired - ДЗПУ        +
	*/

	 if($this->IsUserExists($user['user']))return "Ошибка, данный логин уже занят!";

	$query="Insert into `".$GV["users_tbl"].
        "`(`user`,`password`,`gid`,`fio`,`phone`,`address`,`prim`,`add_uid`,`nick`,`gender`,`email`,`icq`,`url`,`rang`,`group`,`city`,`country`,`raiting`,`signature`,`info`,`add_date`
        ,`max_total_traffic`,`max_month_traffic`,`max_week_traffic`,`max_day_traffic`,`simultaneous_use`) values ('"
        .$user['user']."','".$user['password']."','".$user['gid']."','".$user['fio']."','".$user['phone']."','".$user['address']."','"
	  .$user['prim']."','".$user['add_uid']."','".$user['nick']."','".$user['gender']."','".$user['email']."','".$user['icq']."','".$user['url']."','"
	  .$user['rang']."','".$user['group']."','".$user['city']."','".$user['country']."','".$user['raiting']."','".$user['signature']."','".$user['info']."','"
	  .norm_date_yymmdd(time())."',".$user['max_total_traffic'].",".$user['max_month_traffic'].",".$user['max_week_traffic'].",".$user['max_day_traffic'].",".$user['simultaneous_use'].");";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error());

        //Журналирование событий
        $data["uid"]=$CURRENT_USER["id"];
        $data["event"]="Добавление пользователя: ".$user["user"];
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
        radius_restart();
	return "";
	}

//Есть ли такой пользователь
function IsUserExists($login)
	{
	 global $GV;
	 $temp_query="select count(user) from ".$GV["users_tbl"]." where user='$login';";
	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(user)']);
         return ($cnt==1);
	}

//Есть ли такой пользователь по uid
function IsUserExistsByUid($uid)
	{
	 global $GV;
	 $temp_query="select count(user) from ".$GV["users_tbl"]." where uid='$uid';";
	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user) by UID): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(user)']);
         return ($cnt==1);
	}


//Количество пользователей
function UsersCount()
	{
	 global $GV;
	 $temp_query="select count(user) from ".$GV["users_tbl"]." where user='$login';";
	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(user)']);
         return $cnt;

}

//Взять количество всех пользователей тарифа
function GetCountUsersOfTarif($gid)
	{
	 global $GV;
	 $temp_query="select count(user) from ".$GV["users_tbl"]." where gid='$gid';";
	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(".$temp_query."): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(user)']);
         return $cnt;
	}

//Взять всех пользователей тарифа
function GetUsersOfTarif($gid)
	{
	global $GV;
	 $temp_query="select uid,user,fio,nick,icq,email,url from ".$GV["users_tbl"]." where gid='".$gid."' order by `user`;";

	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(get users of tarif): " . mysql_error());

	 $res=array();
         while($row=mysql_fetch_array($temp_result))
          {$res[]=$row;}
         return $res;
	}

//посчитать общее время и траффик пользователя
function GetUserTotalAcctsData($uid)
{
    global $GV;
        $year=date("Y");
        $month=date("m");
        $day=date("d");
	$daycount=(date('d')<date("t"))?date("d"):date("t");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";
    $user=$this->GetLoginByUid($uid);
    $query="SELECT sum(time_on),sum(in_bytes),sum(out_bytes) from `".$GV["actions_tbl"]."` where user='".$user."' and start_time>'".$bdate."' and stop_time<'".$adate."';";
    $result=mysql_query($query,$this->link)or die("Invalid query(Get User Data): " . mysql_error());
    if (mysql_num_rows($result) == 0) {return NULL;}
    $row = mysql_fetch_assoc($result);
    $res["traffic"]=$row["sum(out_bytes)"];
    $res["time"]=$row["sum(time_on)"];
    return $res;

}

//Взять суммарный трафик и время тарифа
function GetTarifTotalAccts($gid)
	{
	global $GV;
	$query="select user,gid from `".$GV["users_tbl"]."` where gid='".$gid."';";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;

	$year=date("Y");
        $month=date("m");
        $day=date("d");
	$daycount=(date('d')<date("t"))?date("d"):date("t");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";

	while($row=mysql_fetch_array($result))
	  {
	  $users[$k]["user"]=$row["user"];
	  $users[$k]["gid"]=$row["gid"];
	  $k++;
	  }

	$res["traffic"]=0;
	$res["time"]=0;
	for($i=0;$i<count($users);++$i)
	  {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>'".$bdate."' and stop_time<'".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
	  $res["traffic"]+=(int)($row["sum(out_bytes)"]);
	  $res["time"]+=(int)($row["sum(time_on)"]);
	  }

	 return $res;
	}


function GetMonthMaxAccts()
 {
 global $GV;
 $conf = $this->GetCADBiSConfig();
 $res=NULL;
 $res["traffic"]=$conf["max_month_traffic"];//1*1024*1024*1024;
 $res["time"]=$GV["max_month_time"];//9999999999999999;
 return $res;
 }

/**
 * Трафик Пользователей за месяц
 *
 * @return array
 */
function GetMonthTotalAccts()
	{
	global $GV;
        $year=date("Y");
        $month=date("m");
        $day=date("d");
	$daycount=(date('d')<date("t"))?date("d"):date("t");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";
       	$query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."';";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	//die($row["sum(out_bytes)"]);
	$res=NULL;
	//die("".(int)$row["sum(out_bytes)"]);
	$res["traffic"]=$row["sum(out_bytes)"];
	$res["time"]=$row["sum(time_on)"];
	return $res;
	}


//Трафик Пользователей за период
function GetPeriodTotalAccts($fdate,$tdate)
	{
	global $GV;
       	$query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>='".$fdate."' and stop_time<='".$tdate."' and stop_time<>'0000-00-00 00:00:00';";
	$result=mysql_query($query);
	$row=mysql_fetch_array($result);
	$res=NULL;
	$res["traffic"]=(int)$row["sum(out_bytes)"];
	$res["time"]=(int)$row["sum(time_on)"];
	return $res;
	}

//Трафик Пользователей за месяц
function GetMonthUsersAccts($order=">traffic",$draw=false,$gid="all")
	{
	global $GV;
	if($gid!="all")
	 $gidwhere=" where `gid`='".$gid."' ";else  $gidwhere="";
	if($gid!="all")
	 $gidandwhere=" and `gid`='".$gid."' ";else  $gidandwhere="";
        if($order==">user")
          $uorder=" order by LOWER(`user`) desc";
        elseif($order=="<user")
          $uorder=" order by LOWER(`user`) asc";
        elseif($order==">fio")
          $uorder=" order by LOWER(`fio`) desc";
        elseif($order=="<fio")
          $uorder=" order by LOWER(`fio`) asc";
        else
          $uorder="";
	$query="select user,fio,uid,nick from `".$GV["users_tbl"]."`$gidwhere".$uorder.";";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {$users[$k]["fio"]=$row["fio"];
          $users[$k]["user"]=$row["user"];
          $users[$k]["nick"]=$row["nick"];
          $users[$k++]["uid"]=$row["uid"];}

	$daycount=(date('d')<date("t"))?date("d"):date("t");

        $year=date("Y");
        $month=date("m");
        $day=date("d");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";


	if($draw)
          {
          $sum_traf=0;
          $sum_time=0;

          for($i=0;$i<count($users);++$i)
            {
	    $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."' and `user`='".$users[$i]["user"]."';";
	    $result=mysql_query($query);
	    $row=mysql_fetch_array($result);
	    $sum_traf+=(int)$row["sum(out_bytes)"];
	    $sum_time+=(int)$row["sum(time_on)"];
	    }
	    $res_others["traffic"]=0;
	    $res_others["time"]=0;
	    $res_others["user"]="другие";
 	  }
        $res=NULL;
        $k=0;
	for($i=0;$i<count($users);++$i)
	  {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>='".$bdate."' and stop_time<'".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
          $traf=(int)($row["sum(out_bytes)"]);
          $time=(int)($row["sum(time_on)"]);
	  $ok=true;
	  if($draw)
	    if($traf && $traf<0.06*$sum_traf)
 	     {
 	     $ok=false;
	     $res_others["traffic"]+=$traf;
	     $res_others["time"]+=$time;
	     }
 	    else $ok=true;

	  if($ok && ($traf || $time))
	    {
   	    $res[$k]["traffic"]=$traf;
   	    $res[$k]["time"]=$time;
	    $res[$k]["user"]=$users[$i]["user"];
	    $res[$k]["uid"]=$users[$i]["uid"];
	    $res[$k]["nick"]=$users[$i]["nick"];
	    $res[$k++]["fio"]=$users[$i]["fio"];
	    }
	  }
	  if(count($res)){
          if($order==">traffic")usort($res,"accts_compare_traffic_desc");
          elseif($order==">time")usort($res,"accts_compare_time_desc");
          elseif($order=="<traffic")usort($res,"accts_compare_traffic_asc");
          elseif($order=="<time")usort($res,"accts_compare_time_asc");
          $res=array_values($res);}
	   if($draw && $res_others["traffic"])
	     {
	     $res[$k]["traffic"]=$res_others["traffic"];
	     $res[$k]["time"]=$res_others["time"];
	     $res[$k]["nick"]=$res_others["user"];
	     $res[$k]["fio"]=$res_others["user"];
	     $res[$k]["user"]=$res_others["user"];
 	     }
         //$res["date"]=date("F Y");
	 return $res;
	}

	
//Трафик Пользователей за сегодня
function GetTodayUsersAccts($order=">traffic",$draw=false,$gid="all")
	{
	global $GV;
	if($gid!="all")
	 $gidwhere=" where `gid`='".$gid."' ";else  $gidwhere="";
        if($order==">user")
          $uorder=" order by LOWER(`user`) desc";
        elseif($order=="<user")
          $uorder=" order by LOWER(`user`) asc";
        elseif($order==">fio")
          $uorder=" order by LOWER(`fio`) desc";
        elseif($order=="<fio")
          $uorder=" order by LOWER(`fio`) asc";
        else
          $uorder="";

	$query="select user,fio,uid,nick from `".$GV["users_tbl"]."`$gidwhere".$uorder.";";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {$users[$k]["fio"]=$row["fio"];
          $users[$k]["user"]=$row["user"];
          $users[$k]["nick"]=$row["nick"];
          $users[$k++]["uid"]=$row["uid"];}

        $year=date("Y");
        $month=date("m");
        $day=date("d");
        $bdate=$year."-".$month."-".($day-1)." 23:59:59";
        $adate=$year."-".$month."-".($day+1)." 00:00:00";


	if($draw)
          {
          $sum_traf=0;
          $sum_time=0;

          for($i=0;$i<count($users);++$i)
            {
	    $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."' and `user`='".$users[$i]["user"]."';";
	    $result=mysql_query($query);
	    $row=mysql_fetch_array($result);
	    $sum_traf+=(int)$row["sum(out_bytes)"];
	    $sum_time+=(int)$row["sum(time_on)"];
	    }
	    $res_others["traffic"]=0;
	    $res_others["time"]=0;
	    $res_others["user"]="другие";
 	  }
        $res=NULL;
        $k=0;
	for($i=0;$i<count($users);++$i)
	  {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>'".$bdate."' and stop_time<'".$adate."';";
	  //die($query);
          $result=mysql_query($query);
          if(mysql_num_rows($result)==0)continue;
	  $row=mysql_fetch_array($result);
          $traf=(int)($row["sum(out_bytes)"]);
          $time=(int)($row["sum(time_on)"]);
	  $ok=true;
	  if($draw)
	    if($traf<0.06*$sum_traf)
 	     {
 	     $ok=false;
	     $res_others["traffic"]+=$traf;
	     $res_others["time"]+=$time;
	     }
 	    else $ok=true;

	  if($ok && ($traf || $time))
	    {
   	    $res[$k]["traffic"]=$traf;
   	    $res[$k]["time"]=$time;
	    $res[$k]["user"]=$users[$i]["user"];
	    $res[$k]["uid"]=$users[$i]["uid"];
	    $res[$k]["nick"]=$users[$i]["nick"];
	    $res[$k++]["fio"]=$users[$i]["fio"];
	    }
	  }
	  if(count($res)){
          if($order==">traffic")usort($res,"accts_compare_traffic_desc");
          elseif($order==">time")usort($res,"accts_compare_time_desc");
          elseif($order=="<traffic")usort($res,"accts_compare_traffic_asc");
          elseif($order=="<time")usort($res,"accts_compare_time_asc");
          $res=array_values($res);}
	   if($draw && $res_others["traffic"])
	     {
	     $res[$k]["traffic"]=$res_others["traffic"];
	     $res[$k]["time"]=$res_others["time"];
	     $res[$k]["nick"]=$res_others["user"];
	     $res[$k]["fio"]=$res_others["user"];
	     $res[$k]["user"]=$res_others["user"];
 	     }
	 return $res;
	}

//Трафик Пользователей за неделю
function GetWeekUsersAccts($order=">traffic",$draw=false,$gid="all")
	{
	global $GV;
	if($gid!="all")
	 $gidwhere=" where `gid`='".$gid."' ";else  $gidwhere="";
	if($gid!="all")
	 $gidandwhere=" and `gid`='".$gid."' ";else  $gidandwhere="";
        if($order==">user")
          $uorder=" order by LOWER(`user`) desc";
        elseif($order=="<user")
          $uorder=" order by LOWER(`user`) asc";
        elseif($order==">fio")
          $uorder=" order by LOWER(`fio`) desc";
        elseif($order=="<fio")
          $uorder=" order by LOWER(`fio`) asc";
        else
          $uorder="";
	$query="select user,fio,uid,nick from `".$GV["users_tbl"]."`$gidwhere".$uorder.";";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {$users[$k]["fio"]=$row["fio"];
          $users[$k]["user"]=$row["user"];
          $users[$k]["nick"]=$row["nick"];
          $users[$k++]["uid"]=$row["uid"];}

        get_current_week($bdate,$adate,1);

        $res=NULL;
        $k=0;
	if($draw)
          {
          $sum_traf=0;
          $sum_time=0;

          for($i=0;$i<count($users);++$i)
            {
	    $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."' and `user`='".$users[$i]["user"]."';";
	    $result=mysql_query($query);
	    $row=mysql_fetch_array($result);
	    $sum_traf+=(int)$row["sum(out_bytes)"];
	    $sum_time+=(int)$row["sum(time_on)"];
	    }
	    $res_others["traffic"]=0;
	    $res_others["time"]=0;
	    $res_others["user"]="другие";
 	  }

	for($i=0;$i<count($users);++$i)
	  {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>='".$bdate."' and stop_time<='".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
          $traf=(int)($row["sum(out_bytes)"]);
          $time=(int)($row["sum(time_on)"]);
	  $ok=true;
	  if($draw)
	    if($traf<0.06*$sum_traf)
 	     {
 	     $ok=false;
	     $res_others["traffic"]+=$traf;
	     $res_others["time"]+=$time;
	     }
 	    else $ok=true;

	  if($ok && ($traf || $time))
	    {
   	    $res[$k]["traffic"]=$traf;
   	    $res[$k]["time"]=$time;
	    $res[$k]["user"]=$users[$i]["user"];
	    $res[$k]["uid"]=$users[$i]["uid"];
	    $res[$k]["nick"]=$users[$i]["nick"];
	    $res[$k++]["fio"]=$users[$i]["fio"];
	    }
	  }
	  if(count($res)){
          if($order==">traffic")usort($res,"accts_compare_traffic_desc");
          elseif($order==">time")usort($res,"accts_compare_time_desc");
          elseif($order=="<traffic")usort($res,"accts_compare_traffic_asc");
          elseif($order=="<time")usort($res,"accts_compare_time_asc");
          $res=array_values($res);	  }
	   if($draw && $res_others["traffic"])
	     {
	     $res[$k]["traffic"]=$res_others["traffic"];
	     $res[$k]["time"]=$res_others["time"];
	     $res[$k]["nick"]=$res_others["user"];
	     $res[$k]["fio"]=$res_others["user"];
	     $res[$k]["user"]=$res_others["user"];
 	     }
	 return $res;
	}

//Сессии за период времени
function GetSessions($fdate,$tdate)
	{
	global $GV;
       $res=NULL;
        $query="select * from `".$GV["actions_tbl"]."` where start_time>'".$fdate."' and stop_time<'".$tdate."' and stop_time<>'0000-00-00 00:00:00' order by `start_time`;";
	  $result=mysql_query($query);
          $k=0;
          while($row=mysql_fetch_array($result))
            {
            $res[$k]=$row;
            $udata=$this->GetUserData($this->GetUidByLogin($row["user"]));
            $res[$k]["fio"]=$udata["fio"];
            $k++;
            }
	 return $res;
	}

//Сессии за период времени с группировкой по дням
function GetSessionsByDay($fdate,$tdate)
	{
	global $GV;
       $res=NULL;
        $query="select * from `".$GV["actions_tbl"]."` where start_time>'".$fdate."' and stop_time<'".$tdate."' and stop_time<>'0000-00-00 00:00:00' order by `start_time`;";
	  $result=mysql_query($query);
          $k=0;
          while($row=mysql_fetch_array($result))
            {
            $res[$k]=$row;
            $udata=$this->GetUserData($this->GetUidByLogin($row["user"]));
            $res[$k]["fio"]=$udata["fio"];
            $k++;
            }
	 return $res;
	}


//Сессии всеъ Пользователей за период времени
function GetUsersSessions($fdate,$tdate)
	{
	global $GV;
	$query="select user,fio,uid,nick from `".$GV["users_tbl"]."`;";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {$users[$k]["fio"]=$row["fio"];
          $users[$k]["user"]=$row["user"];
          $users[$k]["nick"]=$row["nick"];
          $users[$k++]["uid"]=$row["uid"];}

       $res=NULL;$l=0;
	for($i=0;$i<count($users);++$i)
	  {
	  $query="select * from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>='".$fdate."' and stop_time<='".$tdate."' order by `start_time`;";
	  $result=mysql_query($query);
         $k=0;
          while($row=mysql_fetch_array($result))
            {
            $res[$l][$k]=$row;
            $res[$l][$k]["user"]=$users[$i]["user"];
            $res[$l][$k]["fio"]=$users[$i]["fio"];
            $k++;
            }
       	  if(mysql_num_rows($result)!=0){$l++;}
          }
	 return $res;
	}



//$query="select * from `".$GV["events_tbl"]."` where `event` like '%goga%';";
//		die($query);



//Сессии Пользователя за период времени
function GetUserSessions($user,$fdate,$tdate)//
	{
	global $GV;
        $res=array();
	  $query="select * from `".$GV["actions_tbl"]."` where user='".$user."' and start_time>='".$fdate."' and stop_time<='".$tdate."'  order by `start_time` desc;";
	  $result=mysql_query($query);
          $k=0;
             $udata=$this->GetUserData($this->GetUidByLogin($user));
          while($row=mysql_fetch_array($result))
            {
            $res[$k]=$row;
            $res[$k]["fio"]=$udata["fio"];
            $k++;
            }
	 return $res;
	}

/////////новое
//Исправления админом за период времени
/*function GetUserSessions($user,$fdate,$tdate)//GetAdminEvents select * from events  where `event` like '%goga%';
	{
	global $GV;
        $res=NULL;
		$query="select * from `".$GV["events_tbl"]."` where `event` like '%goga%';";
		die($query);
	  //$query="select * from `".$GV["events_tbl"]."` where uid='".$user."' and date>='".$fdate."' and date<='".$tdate."'  order by `date` desc;";
	  $result=mysql_query($query);
          $k=0;
             $udata=$this->GetUserData($this->GetUidByLogin($user));
          while($row=mysql_fetch_array($result))
            {
            $res[$k]=$row;
            $res[$k]["fio"]=$udata["fio"];
            $k++;
            }
	 return $res;
	}*/

////////конец нового



//Взять суммарный траффик и время тарифа за определённый период
function GetTarifAccts($gid,$fdate,$tdate,$draw=0)
	{
	global $GV;
	$query="select user,gid from `".$GV["users_tbl"]."` where gid='".$gid."';";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$users=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {
	  $users[$k]["user"]=$row["user"];
	  $users[$k]["gid"]=$row["gid"];
	  $k++;
	  }
	$res["traffic"]=0;
	$res["time"]=0;
	for($i=0;$i<count($users);++$i)
	  {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>='".$fdate."' and stop_time<='".$tdate."' ;";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
	  $res["traffic"]+=(int)($row["sum(out_bytes)"]);
	  $res["time"]+=(int)($row["sum(time_on)"]);
	  $res["gid"]=$gid;
	  }
	 return $res;
	}


//Взять суммарный трафик и время тарифов за определённый период
function GetTarifsAccts($fdate,$tdate,$draw=0)
	{
	global $GV;
	$query="select gid,packet,prim from `".$GV["groups_tbl"]."`;";
	$result=mysql_query($query,$this->link)or die($query." : ".mysql_error());
	if(mysql_num_rows($result)==0){return NULL;}
	$tarifs=NULL;
	$k=0;
	while($row=mysql_fetch_array($result))
	  {
	  $tarifs[$k]["packet"]=$row["packet"];
	  $tarifs[$k]["gid"]=$row["gid"];
	  $tarifs[$k]["prim"]=$row["prim"];
	  $k++;
	  }
        $res=NULL;
        if($draw)
          {
          $res_others=NULL;
          $res_others["traffic"]=0;
          $res_others["time"]=0;
          $sumtraf=0;$sumtime=0;
          for($i=0;$i<count($tarifs);++$i)
            {
            $accts=$this->GetTarifAccts($tarifs[$i]["gid"],$fdate,$tdate);
            $sumtraf+=$accts["traffic"];
            $sumtime+=$accts["time"];
            }
          }
        $k=0;
	for($i=0;$i<count($tarifs);++$i)
	  {
	  $accts=$this->GetTarifAccts($tarifs[$i]["gid"],$fdate,$tdate);
	  $ok=true;
          if($draw)
            {
            if($accts["traffic"]<$sumtraf*0.06)
             {
             $ok=false;
             $res_others["traffic"]+=$accts["traffic"];
             $res_others["time"]+=$accts["time"];
             }
             else $ok=true;

            }

            if($ok && ($accts["traffic"]>0 || $accts["time"]>0 || !$draw))
            {
  	    $res[$k]["traffic"]=$accts["traffic"];
	    $res[$k]["time"]=$accts["time"];
	    $res[$k]["packet"]=$tarifs[$i]["packet"];
	    $res[$k]["gid"]=$tarifs[$i]["gid"];
	    $res[$k]["prim"]=$tarifs[$i]["prim"];
	    $k++;
            }
	  }
         if($res_others["traffic"]>0 || $res_others["time"]>0)
           {
           $k++;
           $res[$k]["traffic"]=$res_others["traffic"];
           $res[$k]["time"]=$res_others["time"];
	   $res[$k]["gid"]=0;
           $res[$k]["packet"]="Другие";
           $res[$k]["prim"]="";
           }
         usort($res,"accts_compare_traffic_desc");
         $res=array_values($res);
	 return $res;
	}




//Есть ли такой пользователь
function IsTarifExists($packet)
	{
	 $temp_query="select count(packet) from ".$GV["groups_tbl"]." where packet='".$packet."';";

	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(packet)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(packet)']);
         return ($cnt==1);
	}

//Получить ID  пользователя
function GetUidByLogin($login)
	{
	 global $GV;
	 $temp_query="select `uid` from ".$GV["users_tbl"]." where user='".$login."';";

	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);

         return $row["uid"];
	}

//Получить Login пользователя by ID
function GetLoginByUid($uid)
	{
	global $GV;
	 $temp_query="select `user` from ".$GV["users_tbl"]." where uid='".$uid."';";

	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);

         return $row["user"];
	}



//удаление пользователя
function DeleteUser($uid)
	{
		
        //журналирование событий
        global $CURRENT_USER;
	$data["uid"]=$CURRENT_USER["id"];
        $data["event"]="Попытка удаления пользователя: ".$this->GetLoginByUid($uid);
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
		
	 //проверка
	if(!$this->IsUserExists($this->GetLoginByUid($uid))) return "Ошибка, Такого пользователя нет!";
	global $GV,$CURRENT_USER;
	$query="Delete from `".$GV["users_tbl"]."` where uid='$uid';";
	$result=mysql_query($query,$this->link)or die("Invalid query(DeleteUser): " . mysql_error());




        return $result;
	}


//список пользователей
function FindUsers($searchstr,$see,$gid="all")
	{
	global $GV;
	$ok=false;
	if($gid!="all")$wheregid=" and gid='".$gid."'"; else $wheregid="";
	$like="";
        for($i=0;$i<count($see);++$i)
         switch($see[$i])
           {
           case"user":   {if($ok)$like.=" or ";$like.=" LOWER(`user`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"fio":    {if($ok)$like.=" or ";$like.=" LOWER(`fio`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"email":  {if($ok)$like.=" or ";$like.=" LOWER(`email`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"country":{if($ok)$like.=" or ";$like.=" LOWER(`country`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"city":   {if($ok)$like.=" or ";$like.=" LOWER(`city`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"address":{if($ok)$like.=" or ";$like.=" LOWER(`address`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"signature":{if($ok)$like.=" or ";$like.=" LOWER(`signature`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"info":   {if($ok)$like.=" or ";$like.=" LOWER(`info`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"prim":   {if($ok)$like.=" or ";$like.=" LOWER(`prim`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           case"nick":   {if($ok)$like.=" or ";$like.=" LOWER(`nick`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}
           };
         if(!count($see)){if($ok)$like.=" or ";$like.=" LOWER(`prim`) like '%".$searchstr."%'";if(!$ok)$ok=1;break;}

         if($ok)$like=" where (".$like.")";
	$query="SELECT `uid` from `".$GV["users_tbl"]."`".$like."$wheregid;";
	//die($query);
	$result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error());
   	if (mysql_num_rows($result) == 0)
                return NULL;
       $res=NULL;
    while ($row = mysql_fetch_assoc($result))
        {$res[]=$row["uid"];}

      return $res;
        }

//список пользователей
function GetUsersList($sort="date")
	{
	global $GV;
	$order=" order by `add_date` asc";
	switch($sort)
	  {
	  case "login": $order=" order by `user` asc";break;
	  case "fio": $order=" order by `fio` asc";break;
	  };
	$query="SELECT `uid` from `".$GV["users_tbl"]."`".$order.";";
	$result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error());
	//$res=mysql_fetch_array($result);
   	if (mysql_num_rows($result) == 0)
                return NULL;

       $res=NULL;
    // До тех пор, пока в результате содержатся ряды, помещаем их в
    // ассоциативный массив.
    // Заметка: если запрос возвращает только один ряд -- нет нужды в цикле.
    // Заметка: если вы добавите extract($row); в начало цикла, вы сделаете
    //          доступными переменные $userid, $fullname, $userstatus.
    while ($row = mysql_fetch_assoc($result))
        {$res[]=$row["uid"];}

      return $res;
        }

//информация о всех пользователях
function GetUsers()
	{
	global $GV;
	$query="SELECT * from `".$GV["users_tbl"]."`;";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Users): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)
          return false;

       $tmp=NULL;
    // До тех пор, пока в результате содержатся ряды, помещаем их в
    // ассоциативный массив.
    // Заметка: если запрос возвращает только один ряд -- нет нужды в цикле.
    // Заметка: если вы добавите extract($row); в начало цикла, вы сделаете
    //          доступными переменные $userid, $fullname, $userstatus.
    $k=0;
    while ($row = mysql_fetch_assoc($result))
        {
             $tmp[$k]["uid"]=$row["uid"];
              $tmp[$k]["user"]=$row["user"];
              $tmp[$k]["gid"]=$row["gid"];
              $tmp[$k]["fio"]=$row["fio"];
              $tmp[$k]["nick"]=$row["nick"];
              $tmp[$k]["phone"]=$row["phone"];
              $tmp[$k]["address"]=$row["address"];
              $tmp[$k]["prim"]=$row["prim"];
              $tmp[$k]["password"]=$row["password"];
              $tmp[$k]["add_uid"]=$row["add_uid"];
              $tmp[$k]["gender"]=$row["gender"];
              $tmp[$k]["group"]=$row["group"];
              $tmp[$k]["email"]=$row["email"];
              $tmp[$k]["icq"]=$row["icq"];
              $tmp[$k]["url"]=$row["url"];
              $tmp[$k]["rang"]=$row["rang"];
              $tmp[$k]["group"]=$row["group"];
              $tmp[$k]["city"]=$row["city"];
              $tmp[$k]["country"]=$row["country"];
              $tmp[$k]["raiting"]=$row["raiting"];
              $tmp[$k]["signature"]=$row["signature"];
              $tmp[$k]["info"]=$row["info"];
              $tmp[$k]["add_date"]=$row["add_date"];
              $tmp[$k]["expired"]=$row["expired"];
              $tmp[$k]["crypt_method"]=$row["crypt_method"];
        $k++;
        }
      return $tmp;
      }




//информация о 1
function GetUserData($uid)
{     global $GV;
	$query="SELECT * from `".$GV["users_tbl"]."`where uid='".$uid."';";
	$result=mysql_query($query,$this->link)or die("Invalid query(Get User Data): " . mysql_error());

	if (mysql_num_rows($result) == 0) {
        return NULL;
        }
        $row = mysql_fetch_assoc($result);
              $tmp=array();
              $tmp["uid"]=$row["uid"];
              $tmp["user"]=$row["user"];
              $tmp["gid"]=$row["gid"];
              $tmp["fio"]=$row["fio"];
              $tmp["nick"]=$row["nick"];
              $tmp["phone"]=$row["phone"];
              $tmp["address"]=$row["address"];
              $tmp["prim"]=$row["prim"];
              $tmp["password"]=$row["password"];
              $tmp["add_uid"]=$row["add_uid"];
              $tmp["gender"]=$row["gender"];
              $tmp["group"]=$row["group"];
              $tmp["email"]=$row["email"];
              $tmp["icq"]=$row["icq"];
              $tmp["url"]=$row["url"];
              $tmp["rang"]=$row["rang"];
              $tmp["group"]=$row["group"];
              $tmp["city"]=$row["city"];
              $tmp["country"]=$row["country"];
              $tmp["raiting"]=$row["raiting"];
              $tmp["signature"]=$row["signature"];
              $tmp["info"]=$row["info"];
              $tmp["add_date"]=$row["add_date"];
              $tmp["simultaneous_use"]=$row["simultaneous_use"];
              $tmp["max_total_traffic"]=$row["max_total_traffic"];
              $tmp["max_month_traffic"]=$row["max_month_traffic"];
              $tmp["max_week_traffic"]=$row["max_week_traffic"];
              $tmp["max_day_traffic"]=$row["max_day_traffic"];
	      //echo($row["user"]."<br>");
  return $tmp;
}



//обновление пользователя
function UpdateUser($uid,$data)
	{
	global $GV;
	var_dump($data);die;
	
	$query="Update `".$GV["users_tbl"]."` set `password`='".$data['password'].
        "', `gid`='".$data["gid"]."', `nick`='".$data["nick"].
        "', `fio`='".$data["fio"]."', `gender`='".$data["gender"]."', `phone`= '".$data["phone"]."', `email`= '".
        $data["email"]."', `icq`='".$data["icq"]."',`url`='".$data["url"]."',  `address`='".$data["address"]."', `rang`='".$data["rang"].
        "', `group`='".$data["group"]."', `city`='".$data["city"]."', `country`='".$data["country"].
        "', `raiting`='".$data["raiting"]."', `signature`='".$data["signature"]."', `info`='".$data["info"].
        "', `prim`='".$data["prim"]."', `add_date`='".$data["add_date"]."', `blocked`='".$data["blocked"].
        "', `phone`='".$data["phone"]."', `total_time`='".$data["total_time"]."',
        `total_traffic`='".$data["total_traffic"]."' 
        , `user`='".$data['user']."' 
        , `simultaneous_use`='".$data['simultaneous_use']."'
        , `max_total_traffic`='".$data['max_total_traffic']."'
        , `max_month_traffic`='".$data['max_month_traffic']."'
        , `max_week_traffic`='".$data['max_week_traffic']."'
        , `max_day_traffic`='".$data['max_day_traffic']."'
        
        where `uid`='".$uid."' ;";
        //die($query);
        $result=mysql_query($query,$this->link)or die("Invalid query(Update Users): " . mysql_error());

        global $CURRENT_USER;
	$data["uid"]=$CURRENT_USER["id"];
        $data["event"]="Обновление пользователя: ".$this->GetLoginByUid($uid);
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
        radius_restart();

	}



//список Тарифов
function GetTarifs()
	{
		global $GV;
		$query= 'select p.*, 
			count(u.uid) as users_count, 
			sum(u.simultaneous_use) as simuluse_sum 
			from `'.$GV["groups_tbl"].'` p inner join `users` u on u.gid = p.gid group by u.gid';
		//$query="SELECT * from `".$GV["groups_tbl"]."`;";
		$result=mysql_query($query,$this->link)or die("Invalid query(Get Groups List): " . mysql_error());
		//$res=mysql_fetch_array($result);
		if (mysql_num_rows($result) == 0)
	               return NULL;
	
	        $res=array();
	        $k=0;
	         while ($row = mysql_fetch_assoc($result))
	           {
	
	           $res[$k]["packet"]=$row["packet"];
	           $res[$k]["gid"]=$row["gid"];
	           $res[$k]["blocked"]=$row["blocked"];
	           $res[$k]["total_time_limit"]=$row["total_time_limit"];
	           $res[$k]["month_time_limit"]=$row["month_time_limit"];
	           $res[$k]["week_time_limit"]=$row["week_time_limit"];
	           $res[$k]["day_time_limit"]=$row["day_time_limit"];
	           $res[$k]["total_traffic_limit"]=$row["total_traffic_limit"];
	           $res[$k]["month_traffic_limit"]=$row["month_traffic_limit"];
	           $res[$k]["day_traffic_limit"]=$row["day_traffic_limit"];
	           $res[$k]["week_traffic_limit"]=$row["week_traffic_limit"];
	           $res[$k]["login_time"]=$row["login_time"];
	           $res[$k]["port_limit"]=$row["port_limit"];
	           $res[$k]["session_timeout"]=$row["session_timeout"];
	           $res[$k]["idle_timeout"]=$row["idle_timeout"];
	           $res[$k]["rang"]=$row["rang"];
	           $res[$k]["exceed_times"]=$row["exceed_times"];
	           $res[$k]["level"]=$row["level"];
	           $res[$k]["prim"]=$row["prim"];
	           $res[$k]["users_count"]=$row["users_count"];
	           $res[$k]["simuluse_sum"]=$row["simuluse_sum"];
	           $k++;
	           }
         return $res;
	}




//список групп
function GetTarifsList()
	{
	global $GV;
	$query="SELECT packet from `".$GV["groups_tbl"]."`;";
	$result=mysql_query($query,$this->link)or die("Invalid query(Get Groups List): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)

        return false;

        $res=array();
        while ($row = mysql_fetch_assoc($result))
               $res[]=$row["packet"];


         return $res;
	}


//информация о запрещенных IP
function GetBlackList()
	{
	global $GV;
	$query="SELECT * from `".$GV["blacklist_tbl"]."`;";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Black List): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)
                  return NULL;

       $res=array();
       // До тех пор, пока в результате содержатся ряды, помещаем их в
       // ассоциативный массив.
       // Заметка: если запрос возвращает только один ряд -- нет нужды в цикле.
       // Заметка: если вы добавите extract($row); в начало цикла, вы сделаете
       //          доступными переменные $userid, $fullname, $userstatus.
       $k=0;
       while ($row = mysql_fetch_assoc($result))
             { $res[$k]["user"]=$row["user"];
              $k++;
             }

        return $res;
	}


//информация об 1 группе
function GetTarifData($gid)
	{
	global $GV;
	$query="SELECT * from `".$GV["groups_tbl"]."` where gid='".$gid."';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Tarif Data): " . mysql_error());
        if (mysql_num_rows($result) == 0)
                return false;

        $res=array();
        while ($row = mysql_fetch_assoc($result))
              {
              $res["packet"]=$row["packet"];
              $res["gid"]=$row["gid"];
              $res["blocked"]=$row["blocked"];
              $res["total_time_limit"]=$row["total_time_limit"];
              $res["month_time_limit"]=$row["month_time_limit"];
              $res["week_time_limit"]=$row["week_time_limit"];
              $res["day_time_limit"]=$row["day_time_limit"];
              $res["total_traffic_limit"]=$row["total_traffic_limit"];
              $res["month_traffic_limit"]=$row["month_traffic_limit"];
              $res["week_traffic_limit"]=$row["week_traffic_limit"];
              $res["day_traffic_limit"]=$row["day_traffic_limit"];
              $res["login_time"]=$row["login_time"];
              $res["port_limit"]=$row["port_limit"];
              $res["level"]=$row["level"];
              $res["prim"]=$row["prim"];
              $res["exceed_times"]=$row["exceed_times"];
              $res["rang"]=$row["rang"];
              }
         return $res;
}

//Изменение информации конкретной группы
function UpdateTarif($gid,$data)
	{		
		
		
		$query = 'update `packets` p set ';
		$query .= '		
		`packet`=\''.$data['packet'].'\'
	    , `blocked`= '.$data['blocked'].'
        , `total_time_limit`='.$data['total_time_limit'].'
        , `month_time_limit`='.$data['month_time_limit'].'
        , `week_time_limit`='.$data['week_time_limit'].'
        , `day_time_limit`='.$data['day_time_limit'].'
        , `total_traffic_limit`= '.$data['total_traffic_limit'].'
        , `month_traffic_limit`= '.$data['month_traffic_limit'].'
        , `week_traffic_limit`='.$data['week_traffic_limit'].'
        , `day_traffic_limit`='.$data['day_traffic_limit'].'
        , `login_time`=\''.$data['login_time'].'\'
        , `port_limit`= '.$data['port_limit'].'
        , `prim`=\''.$data['prim'].'\'
        , `level`=\''.$data['level'].'\' 
        , `exceed_times`='.$data['exceed_times'].'
        , `rang`='.$data['rang'].'		
		';
		$query .= "where p.gid={$gid}";		
	    $result=mysql_query($query,$this->link)or die("Invalid query(Update Tarif): " . mysql_error());

	global $CURRENT_USER;
	$data["uid"]=$CURRENT_USER["id"];
        $data["event"]="Обновление тарифа: ".$data["packet"];
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
       
	}





//Добавить тариф
function AddTarif($data)
	{
	global $GV;

	 //if($this->IsTarifExists($data["packet"]))return "Ошибка, данный тариф уже занят!";

	$query="Insert into `".$GV["groups_tbl"].
        "`(`packet`,`direction`,`activation_time`,
          `blocked`,`total_time_limit`,`month_time_limit`,`week_time_limit`,
          `day_time_limit`,`total_traffic_limit`,`month_traffic_limit`,`week_traffic_limit`,
          `day_traffic_limit`,`login_time`,`simultaneous_use`,`port_limit`,`session_timeout`,
          `idle_timeout`,`level`,`exceed_times`,`rang`) values ('".$data['packet']."','2','0','".$data['blocked']."','"
	  .$data['total_time_limit']."','".$data['month_time_limit']."','".$data['week_time_limit']."','".$data['day_time_limit']."','"
          .$data['total_traffic_limit']."','".$data['month_traffic_limit']."','"
	  .$data['week_traffic_limit']."','".$data['day_traffic_limit']."','".$data['login_time']."','"
          .$data['simultaneous_use']."','".$data['port_limit']."','".$data['session_timeout']."','".$data['idle_timeout']."'
          ,'".$data['level']."'
          ,'".$data['exceed_times']."'
          ,'".$data['rang']."');";
	$result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error());

	global $CURRENT_USER;
	$data["uid"]=$CURRENT_USER["id"];
        $data["event"]="Добавление тарифа: ".$data["packet"];
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
	radius_restart();
	return "";



	/*
	gid - ДЗПУ (скорее всего time() )
	packet
	prefix - ДЗПУ
	deposit - ДЗПУ
	credit - ДЗПУ
	tos - ДЗПУ (трафик)
	do_with_tos - ДЗПУ (не снимать с депозита)
	direction - ДЗПУ (Входящий)
	fixed - ДЗПУ (NULL)
	fixed_cost - ДЗПУ (NULL)
	activated - ДЗПУ (активирован)
	activation_time
	blocked
	total_time_limit - ДЗПУ (?)
	month_time_limit
	week_time_limit
	day_time_limit
	total_traffic_limit
	month_traffic_limit
	week_traffic_limit
	day_traffic_limit
	total_money_limit
	month_money_limit
	week_money_limit
	day_money_limit
	login_time
	huntgroup_name
	simultaneous_use - ДЗПУ (нибс)
	port_limit
	session_timeout
	idle_timeout - ДЗПУ (нибс)
	allowed_prefixes - ДЗПУ (нибс)
	framed_ip - ДЗПУ (нибс)
	framed_mask - ДЗПУ (нибс)
	no_pass - ДЗПУ (нельзя)
	no_acct - ДЗПУ (надо)
	allow_callback - ДЗПУ (нибс)
	other_params - ДЗПУ
	create_system_user - ДЗПУ
	+
	description
	level

	*/

	}

//Удалить тариф
function DeleteTarif($gid)
	{
	global $GV;
        global $CURRENT_USER;
		$data["uid"]=$CURRENT_USER["id"];
		$packet = $this->GetTarifData($gid);
        $data["event"]="Удаление тарифа: ".$packet['packet'];
        $data["date"]=norm_date_yymmddhhmmss(time());
        $this->AddEvent($data);
	
	$query="Delete from `".$GV["groups_tbl"]."` where gid='$gid';";
	$result=mysql_query($query,$this->link)or die("Invalid query(DeleteTarif): " . mysql_error());
        return $result;
	}



//информация о online пользователях
function GetOnlineUsersList()
	{
	global $GV;
	$query="SELECT user from `".$GV["actions_tbl"]."` where `terminate_cause`='Online';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Online Users): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)
          return NULL;

       $res=array();
    // До тех пор, пока в результате содержатся ряды, помещаем их в
    // ассоциативный массив.
    // Заметка: если запрос возвращает только один ряд -- нет нужды в цикле.
    // Заметка: если вы добавите extract($row); в начало цикла, вы сделаете
    //          доступными переменные $userid, $fullname, $userstatus.
    $k=0;
    while ($row = mysql_fetch_assoc($result))
        {
        $res[$k]["user"]=$row["user"];
        $k++;
        }
      return $res;
      }


//информация о online пользователях
function GetOnlineUsersData($sort="start_time")
	{
	global $GV;
	$orderby=" order by `".$sort."`";
	switch($sort)
	 {
	 case "out_bytes": $desc=" desc";break;
	 case "time_on": $desc=" desc";break;
	 default: $desc="";
	 };
	$query="SELECT * from `".$GV["actions_tbl"]."` where `terminate_cause`='Online'$orderby".$desc.";";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Online Users Data): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)
          return NULL;

       $res=NULL;
    // До тех пор, пока в результате содержатся ряды, помещаем их в
    // ассоциативный массив.
    // Заметка: если запрос возвращает только один ряд -- нет нужды в цикле.
    // Заметка: если вы добавите extract($row); в начало цикла, вы сделаете
    //          доступными переменные $userid, $fullname, $userstatus.
    $k=0;
    while ($row = mysql_fetch_assoc($result))
        {
        $res[$k]["user"]=$row["user"];

        $res[$k]["gid"]=$row["gid"];
        $res[$k]["id"]=$row["id"];
        $res[$k]["time_on"]=$row["time_on"];
        $res[$k]["start_time"]=$row["start_time"];
        $res[$k]["stop_time"]=$row["stop_time"];
        $res[$k]["in_bytes"]=$row["in_bytes"];
        $res[$k]["ip"]=$row["ip"];
        $res[$k]["out_bytes"]=$row["out_bytes"];
        $res[$k]["server"]=$row["server"];
        $res[$k]["client_ip"]=$row["client_ip"];
        $res[$k]["port"]=$row["port"];
        $res[$k]["connect_info"]=$row["connect_info"];
        $res[$k]["call_from"]=$row["call_from"];
        $res[$k]["terminate_cause"]=$row["terminate_cause"];
        $res[$k]["comment"]=$row["comment"];
        $res[$k]["hour_in_bytes"]=$row["hour_in_bytes"];
        $res[$k]["hour_out_bytes"]=$row["hour_out_bytes"];
        $res[$k]["hour_traffic_money"]=$row["hour_traffic_money"];
        $res[$k]["last_change"]=$row["last_change"];
        $res[$k]["before_billing"]=$row["before_billing"];
        $res[$k]["billing_minus"]=$row["billing_minus"];
        $res[$k]["unique_id"]=$row["unique_id"];
        $k++;
        }
      return $res;
      }




//История пользователя
function GetUserHistory($login)
	{
	global $GV;
	$query="SELECT * from `".$GV["actions_tbl"]."` where `user`='".$login."';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get User History): " . mysql_error());
	//$res=mysql_fetch_array($result);
	if (mysql_num_rows($result) == 0)
          return false;

       $res=array();

    $k=0;
    while ($row = mysql_fetch_assoc($result))
        {
        $res[$k]["user"]=$row["user"];
        $k++;
        }
      return $res;
      }


function KillUser($port)
  {
global $GV,$CURRENT_USER;
  $query="select `user` from `".$GV["actions_tbl"]."` where `port`='".$port."' and `terminate_cause`='Online';";
  //die($query);
  $result=mysql_query($query,$this->link)or die("Invalid query(".$query."): " . mysql_error());
  $row = mysql_fetch_assoc($result);
  $user=$row["user"];

  $query="Update `".$GV["actions_tbl"]."` set `terminate_cause`='KilledBy-".$CURRENT_USER['id']."', `stop_time`='".norm_date_yymmddhhmmss(time())."' where `port`='".$port."' and `terminate_cause`='Online';";
  $result=mysql_query($query,$this->link)or die("Invalid query(".$query."): " . mysql_error());
/*  global $CURRENT_USER;
  $data["uid"]=$CURRENT_USER["id"];
  $data["event"]="Убийство пользователя: ".$user;
  $data["date"]=norm_date_yymmddhhmmss(time());
  $this->AddEvent($data);*/
  }

//Удаление не активных пользователей
function KillInactiveUsers()
	{
        global $GV;
        $nowtime=time()-60*10;
        $query="Update `".$GV["actions_tbl"]."` set `terminate_cause`='Inactive-Request', `stop_time`='".norm_date_yymmddhhmmss(time())."' where `terminate_cause`='Online' and `last_change`<'".$nowtime."' ;";
	$result=mysql_query($query,$this->link)or die("Invalid query(KillInactiveUsers): " . mysql_error());   return NULL;
        }


//всё связанное с событиями
//добавление события
function AddEvent($data)
	{
	global $GV,$CURRENT_USER;
 	$query="Insert into `".$GV["events_tbl"]."`(`uid`,`event`,`date`) values ('".$data['uid']."','".$data['event']."','".$data['date']."');";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add Event): " . mysql_error());
	}

//протоколирование убийства пользователя
function AddEventKillUser($user)
{
  global $GV,$CURRENT_USER;
  $data["uid"]=$CURRENT_USER["id"];
  $data["event"]="Убийство пользователя: ".$user;
  $data["date"]=norm_date_yymmddhhmmss(time());
  $this->AddEvent($data);
}

//блокировка пользователя
function BlockUser($uid)
{
	global $GV,$CURRENT_USER;
 	$query="Update `".$GV["users_tbl"]."` set `blocked`='1' where uid='".$uid."';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add Event): " . mysql_error());
}

//блокировка пользователя
function ActivateUser($uid)
{
	global $GV,$CURRENT_USER;
 	$query="Update `".$GV["users_tbl"]."` set `blocked`='0' where uid='".$uid."';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add Event): " . mysql_error());
}

function IsUserActivated($uid)
{
	global $GV;
	 $temp_query="select `blocked` from ".$GV["users_tbl"]." where uid='".$uid."';";

	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(select count(user)): " . mysql_error());

         $row=mysql_fetch_array($temp_result);

         return !$row["blocked"];
}

function GetEvents($from=null, $to=null, $sort = null)
{
$order = "eid";
if($sort != null) $order = $sort;
$sql = "SELECT * from events order by ".$order." desc; " ;
  //die($sql);
 $result = mysql_query($sql);
  $k=0;
 while($row = mysql_fetch_assoc($result))
  {

   $events[$k]["login"]= $this->GetLoginByUid($row["uid"]);
   $events[$k]["event"]= $row["event"];
   $events[$k]["date"]= $row["date"];
   $k++;
  }

  mysql_free_result($result);

 return $events;
}


/*
 Sadykov
 CADBiS v.2.0 functions
*/
function GetUserDataOfUnique($unique_id)
 {
  $user = mysql_result(mysql_query("select user from actions where actions.unique_id = '$unique_id';"),0);
  return $this->GetUserData($this->GetUidByLogin($user));
 }

function GetUserLastUrls($unique_id,$sort="<date")
 {
  $desc =($sort[0]==">")?" desc":" asc";

  $order = substr($sort,1,strlen($sort)-1);

  switch($order)
 	{
 		case "url":
 		$order = "url$desc,date,length ";
 		break;
 		case "date":
 		$order = "date$desc,url,length ";
 		break;
 		case "length":
 		$order = "length$desc,date,url ";
 		break;
 	};
  global $GV;
  $result = mysql_query("select url,SUM(length) as length,UNIX_TIMESTAMP(date) as date,ip,content_type from url_log where unique_id='$unique_id' group by url order by $order;");
  //die("select url,SUM(length) as length,UNIX_TIMESTAMP(date) as date from url_log where unique_id='$unique_id' group by url order by $order;");
  $logs = array();
  while($log = mysql_fetch_object($result))
     $logs[]= array('url'=>$log->url,'length'=>$log->length,'date'=>$log->date,'ip'=>$log->ip,'content_type'=>$log->content_type);
  return $logs;
 }

function NormalUniqueID($unique_id)
{
if(strstr($unique_id,"-"))
  {$id_a = explode("-",$unique_id);
   return $id_a[1];
  }
return $unique_id;

}

function GetSessionData($unique_id)
{

 $unique_id_p = $this->NormalUniqueID($unique_id);
	global $GV;
       $res=NULL;
        $query="select * from `".$GV["actions_tbl"]."` where unique_id like '$unique_id';";
	  $result=mysql_query($query);
          while($row=mysql_fetch_array($result))
            {
			$tmp_res = mysql_query("select * from protocols where unique_id like '%$unique_id_p';");
			$protocol = null;
			while($p = mysql_fetch_array($tmp_res))
			  {
			  $protocol['length']+=(int)$p['length'];
			  $protocol['data'].=$p['data'];
			  }
            $res=array(
            'user'=>$this->GetUserData($this->GetUidByLogin($row["user"])),
            'session'=>$row,
            'protocol'=>$protocol
            );
            }
	 return $res;
}

function SetCidsForUrls($cids)
{
foreach($cids as $url=>$cid)
		mysql_query("update url_popularity set cid=$cid where url='$url';");
}

function IsProtocolExists($unique_id)
 {
 $unique_id = $this->NormalUniqueID($unique_id);
	return (mysql_result(mysql_query("Select count(*) from protocols where unique_id like '%$unique_id'"),0)>0);
 }


function GetUrlsPopularity($asort=">count", $uid = null, $limit=25,$gid=null,$groupby=null,$hideother=false)
 {
 switch($asort){
 	case ">count":$sort="up.count desc,up.length desc";break;
 	case "<count":$sort="up.count asc,up.length desc";break;
 	case ">length":$sort="up.length desc,up.count desc";break;
 	case "<length":$sort="up.length asc,up.count desc";break;
 	case ">ucount":$sort="ucount desc,up.count desc,up.length desc";break;
 	case "<ucount":$sort="ucount asc,up.count desc,up.length desc";break;
 	case ">url":$sort="up.url desc,up.count desc";break;
 	case "<url":$sort="up.url asc,up.count desc";break;
 	default:$sort="up.count desc,up.length desc";break;
 };

 switch($groupby){
 case "cid":$groupby = "group by up.cid";break;
 case "url":$groupby = "group by up.url";break;
 default:
 	$groupby = "group by up.url";break;
 };

 if($uid!=null)
 	$where = "where up.uid=$uid";
 else
 	$where = "";
 if($limit!="all")
 	$limit = "limit ".$limit;
 else
 	$limit ="";


 if(is_numeric($gid))
 	$gid = ($gid)?(($where)?" and gid=$gid":" where u.gid=$gid"):"";
 	else $gid="";
 $where .= $gid;
 if($hideother)
 	$where .= ($where)?" and up.cid>0":" where up.cid>0";
 	
 $sql = "select up.cid,uc.title as cattitle,up.url,count(up.`uid`) as `ucount`, sum(up.`count`) as `count`, sum(up.`length`) as length from `url_popularity` up inner join `users` u on u.uid=up.uid left join `url_categories` uc on up.cid=uc.cid $where $groupby order by $sort $limit";
	//die($sql);
 $result = mysql_query($sql)or die(mysql_error());
 $res = null;
 while($row = mysql_fetch_array($result))
   $res[]=$row;
 //foreach($res as $r)
// 	print($r['length'].", ");
 $res = $this->SortUrlsBy($asort,$res);
 //foreach($res as $r)
 //	print($r['length'].", ");
 return $res;
 }


function GetCtryPopularity($asort=">count", $uid = null, $limit=25,$gid=null,$year=null,$month=null)
 {
 switch($asort){
 	case ">count":$sort="cp.count desc,cp.length desc";break;
 	case "<count":$sort="cp.count asc,cp.length desc";break;
 	case ">length":$sort="cp.length desc,cp.count desc";break;
 	case "<length":$sort="cp.length asc,cp.count desc";break;
 	case ">ucount":$sort="ucount desc,cp.count desc,cp.length desc";break;
 	case "<ucount":$sort="ucount asc,cp.count desc,cp.length desc";break;
 	case ">ctry":$sort="cp.ctry desc";break;
 	case "<ctry":$sort="cp.ctry asc";break;
 	default:$sort="cp.count desc,cp.length desc";break;
 };

 if($uid!=null)
 	$where = "where cp.uid=$uid";
 else
 	$where = "";
 if($limit!="all")
 	$limit = "limit ".$limit;
 else
 	$limit ="";


 if(is_numeric($gid))
 	$gid = ($gid)?(($where)?" and gid=$gid":" where u.gid=$gid"):"";
 	else $gid="";
 $where .= $gid;
 $groupby = "group by cp.ctry";
 
 if(!is_null($year))
    $where .= (($where)?" and ":" where ").
      "cp.year=$year";
 if(!is_null($month))
    $where .= (($where)?" and ":" where ").
      "cp.month=$month"; 

 $sql = "select cp.ctry,count(cp.`uid`) as `ucount`, sum(cp.`count`) as `count`, sum(cp.`length`) as length from `ctry_popularity` cp inner join `users` u on u.uid=cp.uid $where $groupby order by $sort $limit";
 $result = mysql_query($sql)or die(mysql_error());
 $res = null;
 while($row = mysql_fetch_array($result))
   $res[]=$row;
 //foreach($res as $r)
// 	print($r['length'].", ");
 $res = $this->SortUrlsBy($asort,$res);
 //foreach($res as $r)
 //	print($r['length'].", ");
 return $res;
 }



function GetOnlineUserDataOfUnique($unique_id)
 {
  global $GV;
        $query="SELECT * from `".$GV["actions_tbl"]."` where `unique_id`='$unique_id';";
        $result=mysql_query($query,$this->link)or die("Invalid query(Get Online User Data Of Unique): " . mysql_error());
	if (mysql_num_rows($result) == 0)
          return NULL;

       $res=NULL;
    $k=0;
    $row = mysql_fetch_assoc($result);
    return $row;
 }


function GetDeniedLog($uid)
{
	$user = $this->GetUserData($uid);
	$result = mysql_query("Select * from `actions` where user='".$user['user']."'");
	$sessions=array();
	while($row = mysql_fetch_assoc($result))
		$sessions[] = $this->NormalUniqueID($row['unique_id']);

	$result = mysql_query("select url,UNIX_TIMESTAMP(date) as date from `url_denied_log` where unique_id in('".implode("','",$sessions)."') group by url;");
	$denied = array();
	while($row = mysql_fetch_assoc($result))
		$denied[]=$row;


	return $denied;

}

/////////////////////////////////////////////////////////////
//надо ещё добавить протоколирование!!!!!!!!!!!!!!!!!!!!!!!!
/*
url_popularity - РЕЙТИНГ ВСЕХ КОГДА-ЛИБО ПОСЕЩЁННЫХ САЙТОВ
url_denied - ЗАПРЕЩЁННЫЕ УРЛЫ ДЛЯ РАЗЛИЧНЫХ ТАРИФОВ
url_denied_log - ЛОГ ЗАПРЕТОВ НА ПОСЕЩЕНИЯ (ПО СЕССИЯМ)
url_log - ОПЕРАТИВНЫЕ ДАННЫЕ О ПОСЕЩЕНИЯХ (В ПОСЛЕДСТВИИ АГРЕГИРУЮТСЯ)
protocols - ДОЛГОСРОЧНЫЕ ПРОТОКОЛЫ СЕССИЙ
*/



//добавление запрещенного URL
function AddDeniedURL($gid,$URL)
	{
	global $GV;
 	$query="Insert into `".$GV["url_denied_tbl"]."`(`gid`,`url`) values ($gid,'$URL');";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add Denied URL): " . mysql_error());
	}

//Есть ли такой запрещенный URl
function IsDeniedURLExists($duid)
	{
	 global $GV;
	 $temp_query="select count(duid) as c from ".$GV["url_denied_tbl"]." where duid=$duid;";
	 $temp_result=mysql_query($temp_query,$this->link)or die("Invalid query(Is User Exists): " . mysql_error());

         $row=mysql_fetch_array($temp_result);
         $cnt=round($row['count(duid)']);
         return ($cnt==1);
	}

//удаление запрещенного URL
function DeleteDeniedURL($duid)
{
	global $GV,$CURRENT_USER;
	$query="Delete from `".$GV["url_denied_tbl"]."` where duid=$duid;";
	$result=mysql_query($query,$this->link)or die("Invalid query(Delete Denied URL): " . mysql_error());
}

function SortUrlsBy($sort, $result)
{
if(!count($result))return;
 switch($sort)
    {
     case "<url":usort($result,"_urls_sort_functorURL_asc");break;
     case ">url":usort($result,"_urls_sort_functorURL_desc");break;
     case "<date":usort($result,"_urls_sort_functorDATE_asc");break;
     case ">date":usort($result,"_urls_sort_functorDATE_desc");break;
     case "<length":usort($result,"_urls_sort_functorLEN_asc");break;
     case ">length":usort($result,"_urls_sort_functorLEN_desc");break;
     case "<count":usort($result,"_urls_sort_functorCNT_asc");break;
     case ">count":usort($result,"_urls_sort_functorCNT_desc");break;
     case "<ucount":usort($result,"_urls_sort_functorUCNT_asc");break;
     case ">ucount":usort($result,"_urls_sort_functorUCNT_desc");break;
     default:usort($result,"_urls_sort_functorDATE_asc");break;
    }
    return $result;
}


function GetUserUrlsByPeriod($uid,$from,$to,$sort="<count")
	{
	global $GV;
  	$user = $this->GetUserData($uid);
    $result = mysql_query("select unique_id from `actions` where start_time>='$from' and stop_time<='$to' and user='".$user['user']."'");

    $sessions = array();
    while($row = mysql_fetch_assoc($result))
      $sessions[]=$this->NormalUniqueID($row['unique_id']);

	$sep0="{@}";
	$sep1="{*}";
   	$all_data ="";
    foreach($sessions as $session)
    {
    	$result = mysql_query("select * from protocols where unique_id='$session'");
    	while($row = mysql_fetch_assoc($result))
    		$all_data .= $row['data'];
    }
    $result_tmp = array();
    $tmp0 = explode($sep0,$all_data);
    foreach($tmp0 as $tmp)
    	{
    	if(!$tmp)continue;
    	 $tmp1 = explode($sep1,$tmp);
    	 $result_tmp[$tmp1[1]]['count']+=(int)$tmp1[2];
     	 $result_tmp[$tmp1[1]]['length']+=(int)$tmp1[3];
     	 $result_tmp[$tmp1[1]]['dates'][]=strtotime($tmp1[0]);
    	}
    $result = array();
    foreach($result_tmp as $url=>$el)
    {
     $result[]=array(
     			'url'=>$url,
     			'count'=>$el['count'],
     			'length'=>$el['length'],
     			'date'=>array_max_value($el['dates']),
     			'dates'=>$el['dates']
     			);

    }
	$result = $this->SortUrlsBy($sort,$result);
    return $result;
   }

//
function SaveTarifDeniedURLs($gid,$urls)
	{
	if(!$gid)
		{
		copy($this->squid_porno_file,$this->squid_porno_file.".backup");
		$urls = array_unique($urls);
		$fp = fopen($this->squid_porno_file,"w+");
		fwrite($fp,implode("\n",$urls));
		fclose($fp);
		}
		else
		{
		mysql_query("delete from url_denied where gid=$gid");
		foreach($urls as $url)
			mysql_query("insert into url_denied(gid,url) values($gid,'$url')");
		}
	}

//запрещенные URL
function GetTarifDeniedURLs($gid)
{
	if(!$gid)
		{
		$res=file($this->squid_porno_file);
		$res = str_replace(array("\r","\n"),array("",""),$res);
		}
		else
		{
	 	global $GV;
		 $query="SELECT url from `".$GV["url_denied_tbl"]."` where `gid`=$gid;";
		 $result=mysql_query($query,$this->link)or die("Invalid query(Get Tarif Denied URLs): " . mysql_error());

		 if (mysql_num_rows($result) == 0)
		          return NULL;

		       $res=array();

		    $k=0;
		    while ($row = mysql_fetch_assoc($result))
		        {
		        $res[$k]=$row["url"];
		        $k++;
		        }
		}
		return $res;
}

function GetAvailableCountries()
 {
  $result = mysql_query("select distinct ctry,country from ip2country order by country");
  $ret = array();
  while($res = mysql_fetch_assoc($result))
    $ret[] = $res;
  return $ret;
 }
 
function GetDiapasons($ctry)
 {
   $result = mysql_query("select * from ip2country where ctry='$ctry'");
  $ret = array();
  while($res = mysql_fetch_assoc($result))
    $ret[] = $res;

  return $ret;   
 }

function SaveDiapasons($ids,$sips,$eips,$sources,$assigned)
 {
  for($i=0;$i<count($ids);++$i)
   {                        
   $sql ="UPDATE ip2country set sip={$sips[$i]}, eip={$eips[$i]}, source='{$sources[$i]}', assigned={$assigned[$i]} WHERE id={$ids[$i]}";
   $result = mysql_query($sql);
   }
 }

function AddDiapason($diap)
 {
   $sql = "INSERT INTO `ip2country`(sip,eip,source,assigned,ctry,cntry,country) VALUES({$diap['sip']},{$diap['eip']},'{$diap['source']}',UNIX_TIMESTAMP(CURDATE()),'{$diap['ctry']}','{$diap['cntry']}','{$diap['country']}');";
   mysql_query($sql);
 }
 
function DeleteDiapason($id)
 {
   mysql_query("DELETE FROM ip2country where id=$id;");
 } 
 
 
	/**
	 * Returns array of configuration items
	 * @return array
	 */
	function GetCADBiSConfig()
	{     
		$query="SELECT * from `cadbis_config`";
		$result=mysql_query($query,$this->link)or die("Invalid query(Get User Data): " . mysql_error());
	    $ret = array();
		while($res = mysql_fetch_assoc($result))
		   $ret[$res['name']] = $res['value'];
		  return $ret;   
	}
	
	/**
	 * Update the value of the cadbis config variable
	 *
	 * @param string $name
	 * @param string $value
	 */
	function UpdateConfigVar($name, $value)
	{
		$sql = sprintf("update `cadbis_config` set value='%s' where name='%s'",$value, $name);
	   	mysql_query($sql) or die("Error executing query: ".$sql);
	}
	
	/**
	 * Packets accts for today
	 *
	 * @param int $gid
	 */
	function GetTarifTodayAccts($gid)
		{
	        $fdate = date("Y-m-d 00:00:00");
	        $todate = date("Y-m-d 23:59:59");
			return $this->GetTarifAccts($gid, $fdate, $todate);
		}
		
		
		
		
		
		
/****************************************************************
 *		 				CADBiS 2.0 FUNCTIONS					*
 ****************************************************************/			
			
	// ----------------------------------------------------
	/**
	 * Adds new event log
	 *
	 * @param string $event
	 */
	function AddEventString($event)
	{
	  global $GV,$CURRENT_USER;
	  $data["uid"]=$CURRENT_USER["id"];
	  $data["event"]=$event;
	  $data["date"]=norm_date_yymmddhhmmss(time());
	  $this->AddEvent($data);
	}
	// ----------------------------------------------------
	/**
	 * Returns categories of content
	 *
	 * @param int $page - page number
	 * @param int $pop - categories per page
	 * @param string $orderby - sort field (allowed: title,cid)
	 * @param string $orderdir - sort direction (allowed: asc,desc)
	 * @return array - array[$i] = array('cid'=>$cid,'title'=>$title);
	 */ 
	public function GetUrlCategories($page = 0, $pop = 10, $orderby = 'default', $orderdir = 'asc')
	 {
	 $cats = array();
	 $sql = "select * from url_categories";
	 if($orderby!='default')
	 	$sql .= ' order by '.$orderby.' '.$orderdir;
	 else
	 	$sql .= ' order by title';
	 if($page > 0)
	 {
	 	$page--;
	 	$sql .= " limit ".($page*$pop).",$pop";
	 }
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_assoc($result))
	 	$cats[] = $row;
	 return $cats;
	 }
 	 // ----------------------------------------------------
	/**
	 * Returns categories of content - assoc array
	 *
	 * @param int $page - page number
	 * @param int $pop - categories per page
	 * @param string $orderby - sort field (allowed: title,cid)
	 * @param string $orderdir - sort direction (allowed: asc,desc)
	 * @return array - array[cid] = array('cid'=>$cid,'title'=>$title);
	 */  	 
 	 public function GetUrlCategoriesAssoc($page = 0, $pop = 10, $orderby = 'default', $orderdir = 'asc')
 	 {
 	 	$cats = $this->GetUrlCategories($page,$pop,$orderby,$orderdir);
		$cat_by_cid = array();
		foreach($cats as $cat)
			$cat_by_cid[$cat['cid']] = $cat;
		return $cat_by_cid; 	 	
 	 }
	 // ----------------------------------------------------
	 /**
	  * Returns data of the content category
	  *
	  * @param int $cid
	  * @return array - array('cid'=>$cid,'title'=>$title)
	  */
	 public function GetUrlCategoryData($cid)
	 {
		 $result = mysql_query(sprintf("select * from `url_categories` where cid = %d",$cid));
		 return mysql_fetch_assoc($result);
	 }
	 // ----------------------------------------------------
	/**
	 * Adds new url category into the database
	 *
	 * @param array $cat - array('cid'=>$cid,'title'=>$title)
	 */
	public function AddUrlCategory($cat)
	{
		mysql_query(sprintf('insert into `url_categories`(title,title_ru) values(\'%s\',\'%s\')',$cat['title'],$cat['title_ru']));
	}
	// ----------------------------------------------------
	/**
	 * Deletes url category from the database
	 *
	 * @param int $cid
	 */
	public function DeleteUrlCategory($cid)
	{
		mysql_query(sprintf('delete from `url_categories` where cid = %d',$cid));
	}
	// ----------------------------------------------------
	/**
	 * Enter description here...
	 *
	 * @param unknown_type $cid
	 * @param unknown_type $cat
	 */
	public function UpdateUrlCategory($cid,$cat)
	{
		$sql = sprintf('update `url_categories` set title = \'%s\',title_ru = \'%s\' where cid = %d',$cat['title'],$cat['title_ru'],$cid);
		mysql_query($sql);
	}
	// ----------------------------------------------------
	/**
	 * Returns keywords with their weights for url category
	 *
	 * @param int $cid
	 * @return array
	 */
	public function GetUrlCategoryKeywordsWithWeights($cid)
	{
	 $result = mysql_query(sprintf("select * from `url_categories_keywords` where cid=%d order by keyword",$cid));
	 $kwds = array();
	 while($row = mysql_fetch_assoc($result))
	 	$kwds[] = $row['keyword'].'/'.$row['weight'];
	 return $kwds;
	}
	// ----------------------------------------------------
	/**
	 * Returns keywords for url category
	 *
	 * @param int $cid
	 * @return array
	 */
	public function GetUrlCategoryKeywords($cid)
	{
	 $result = mysql_query(sprintf("select * from `url_categories_keywords` where cid=%d order by keyword",$cid));
	 $kwds = array();
	 while($row = mysql_fetch_assoc($result))
	 	$kwds[] = $row['keyword'];
	 return $kwds;
	}	
	// ----------------------------------------------------
	/**
	 * Returns weights for keywords
	 *
	 * @return array
	 */
	public function GetKeywordsWeights()
	{
	 $result = mysql_query(sprintf("select * from `url_categories_keywords`"));
	 $kwds = array();
	 while($row = mysql_fetch_assoc($result))
	 	$kwds[$row['keyword']] = $row['weight'];
	 return $kwds;
	}		
	// ----------------------------------------------------
	/**
	 * Returns url category by its keyword
	 *
	 * @param string $keyword
	 * @return int - cid
	 */
	public function GetUrlCategoryKeyword($keyword)
	{
	 $result = mysql_query(sprintf("select cid from `url_categories_keywords` where keyword='%s'",$keyword));
	 return mysql_result($result,0);
	}
	// ----------------------------------------------------
	/**
	 * Updaytes url category
	 *
	 * @param int $cid
	 * @param array $kwds
	 */
	public function UpdateUrlCategoryKeywords($cid, $kwds)
	{
		mysql_query(sprintf("delete from `url_categories_keywords` where cid=%d",$cid));
	 	foreach($kwds as $kwd){
	 		$tmp = explode('/',$kwd);
	 		$keyword = $tmp[0];
	 		if(!empty($tmp[1]) && $tmp[1]>0)
	 			$weight = $tmp[1];
	 		else
	 			$weight = 1;
	 		$sql = sprintf('insert into `url_categories_keywords`(cid,keyword,weight) value(%d,\'%s\',%d)',$cid,$keyword,$weight);
			mysql_query($sql);
	 	}
	}
	// ----------------------------------------------------
	/**
	 * Returns unsense words
	 *
	 * @return array
	 */
	public function GetUrlCategoriesUnsenseWords()
	{
	 $result = mysql_query(sprintf("select * from `url_categories_unsensewords` order by keyword"));
	 $kwds = array();
	 while($row = mysql_fetch_assoc($result))
	 	$kwds[] = $row['keyword'];
	 return $kwds;
	}
	// ----------------------------------------------------
	/**
	 * Updates unsense words list
	 *
	 * @param array $kwds
	 */
	public function UpdateUrlCategoriesUnsenseWords($kwds)
	{
		mysql_query(sprintf("truncate table `url_categories_unsensewords`"));
	 foreach($kwds as $kwd)
		mysql_query(sprintf('insert into `url_categories_unsensewords`(keyword) value(\'%s\')',$kwd));
	}
	// ----------------------------------------------------
	/**
	 * Returns denied categories for packet with gid
	 *
	 * @param int $gid
	 * @return array - array of denied cids
	 */
	public function GetUrlCategoriesDenied($gid)
	{
	 $result = mysql_query(sprintf("select * from `url_categories_denied` where gid=%d",$gid));
	 $cats = array();
	 while($row = mysql_fetch_assoc($result))
	 	$cats[] = $row['cid'];
	 return $cats;
	}
	// ----------------------------------------------------
	/**
	 * Updates category for the url
	 *
	 * @param int $u2cid
	 * @param string $url
	 * @param int $cid
	 */
	public function UpdateUrlCategoryMatch($u2cid,$url, $cid)
	{
		mysql_query(sprintf("update `url_categories_match` set cid='%s', url='%s' where u2cid=%d",$cid,$url,$u2cid));
	}
	// ----------------------------------------------------
	/**
	 * Updates url category by the title of category
	 *
	 * @param string $url
	 * @param string $name
	 */
	public function UpdateUrlCategoryMatchByName($url,$name)
	{
		$sql = sprintf("select cid from `url_categories` where title='%s'",$name);
		$cid = mysql_result(mysql_query($sql),0);
		$sql = sprintf("update `url_categories_match` set cid=%d where url='%s'",$cid,$url);
		mysql_query($sql);
	}
	// ----------------------------------------------------
	/**
	 * Deletes category for the url
	 *
	 * @param int $u2cid
	 */
	public function DeleteUrlCategoryMatch($u2cid)
	{
		mysql_query(sprintf("delete from `url_categories_match` where u2cid=%d",$u2cid));
	}
	// ----------------------------------------------------
	/**
	 * Attaches url for a given category with cid
	 *
	 * @param string $url
	 * @param int $cid
	 */
	public function AddUrlCategoryMatch($url, $cid)
	{
		$sql = sprintf("replace into `url_categories_match`(url,cid) values('%s',%d)",$url,$cid);
		mysql_query($sql);
	}
	// ----------------------------------------------------
	/**
	 * Returns urls with their categories 
	 *
	 * @param int $page
	 * @param int $pop
	 * @param string $orderby
	 * @param string $orderdir
	 * @param string $include
	 * @param string $exclude
	 * @return array - array[$i] = array('cid'=>$cid,'url'=>$cid)
	 */
	public function GetUrlCategoriesMatch($page = 0, $pop = 10, $orderby = 'default', $orderdir = 'asc', $include = array(), $exclude = array())
	 {
	 $cats = array();
	 $sql = "select * from `url_categories_match`";
	 if(!empty($exclude) && empty($include))
	 	$sql .= ' where cid not in('.implode(',',$exclude).')';
	 elseif(!empty($include) && empty($exclude))
	 	$sql .= ' where cid in('.implode(',',$include).')';
	 elseif(!empty($include) && empty($exclude))
	 	$sql .= ' where cid not in ('.implode(',',$exclude).') and cid in('.implode(',',$include).')';
	 if($orderby!='default')
	 	$sql .= ' order by '.$orderby.' '.$orderdir;
	 if($page > 0)
	 {
	 	$page--;
	 	$sql .= " limit ".($page*$pop).",$pop";
	 } 
	 $result = mysql_query($sql);
	 $urls = array();
	 while($row = mysql_fetch_assoc($result))
	 	$urls[] = $row;
	 return $urls;
	 }
	 // ----------------------------------------------------
	/**
	 * Returns count of the urls with cid>0 
	 *
	 * @return int
	 */
	public function GetCategoriesUrlMatchedCount()
	{
		return mysql_result(mysql_query("select count(1) from `url_categories_match` where cid > 0"),0);
	}
	// ----------------------------------------------------
	/**
	 * Returns count of the urls with cid=0
	 *
	 * @return int
	 */
	public function GetCategoriesUrlUnMatchedCount()
	{
		return mysql_result(mysql_query("select count(1) from `url_categories_match` where cid = 0"),0);
	}
	// ----------------------------------------------------
	/**
	 * Sets denied categories for the packet with gid
	 *
	 * @param int $gid
	 * @param array $dencats
	 */
	public function SetUrlCategoriesDenied($gid,$dencats)
	{
	 mysql_query(sprintf("delete from `url_categories_denied` where gid=%d",$gid));
	 foreach($dencats as $dencat)
	 	mysql_query(sprintf("insert into `url_categories_denied`(cid,gid) values(%d,%d)",$dencat,$gid));
	}
	// ----------------------------------------------------
	/**
	 * Attaches keyword for the category
	 *
	 * @param int $cid
	 * @param string $keyword
	 */
	public function UrlCategoryAttachKeyword($cid, $keyword)
	{
		$sql = sprintf("insert into `url_categories_keywords`(cid,keyword) values(%d,'%s')",$cid,$keyword);
		mysql_query($sql);
	}
	// ----------------------------------------------------
	/**
	 * Deletes keyword from the category
	 *
	 * @param string $keyword
	 */
	public function DeleteUrlCategoryKeyword($keyword)
	{
		mysql_query(sprintf("delete from `url_categories_keywords` where keyword='%s'",$keyword));
	}
	// ----------------------------------------------------
	/**
	 * Changes category for the keyword
	 *
	 * @param string $keyword
	 * @param int $cid
	 */
	public function ReplaceUrlCategoryKeyword($keyword, $cid)
	{
		mysql_query(sprintf("update `url_categories_keywords` set cid=%d where keyword='%s'",$cid,$keyword));
	}
	// ----------------------------------------------------
	/**
	 * Adds new unsense word
	 *
	 * @param string $keyword
	 */
	public function AddUrlCategoryUnsenseword($keyword)
	{
		$sql = sprintf('insert into `url_categories_unsensewords`(keyword) value(\'%s\')',$keyword);
		mysql_query($sql);
	}
	// ----------------------------------------------------
	/**
	 * Returns category(cid) by the url
	 *
	 * @param string $url
	 * @return int
	 */
	public function GetUrlCategory($url)
	{
		return mysql_result(mysql_query(sprintf("select cid from `url_categories_match` where url = '%s'",$url)),0);
	}
	// ----------------------------------------------------
	/**
	 * Returns count of rows in table
	 *
	 * @param string $table
	 * @return int
	 */
	public function GetRowsCount($table)
	{
		return mysql_result(mysql_query("select count(*) from `$table`"),0);
	}
	// ----------------------------------------------------
	public function AddUrlCategoryConflict($keyword,$forcid,$incid,$url)
	{
		mysql_query(sprintf('insert into `url_categories_conflicts`(keyword,forcid,incid,date,url) value(\'%s\',%d,%d,NOW(),\'%s\')',$keyword,$forcid,$incid,$url));
	}	
	// ----------------------------------------------------
	/**
	 * Returns conflict categories
	 *
	 * @param int $page - page number
	 * @param int $pop - categories per page
	 * @param string $orderby - sort field (allowed: title,cid)
	 * @param string $orderdir - sort direction (allowed: asc,desc)
	 * @return array - array[$i] = array('cid'=>$cid,'title'=>$title);
	 */ 
	public function GetUrlCategoriesConflicts($page = 0, $pop = 10, $orderby = 'default', $orderdir = 'asc')
	 {
	 $conflicts = array();
	 $sql = "select * from url_categories_conflicts";
	 if($orderby!='default')
	 	$sql .= sprintf(' order by %s %s',$orderby,$orderdir);
	 else
	 	$sql .= ' order by date desc';
	 if($page > 0)
	 {
	 	$page--;
	 	$sql .= sprintf(' limit %d,%d',($page*$pop),$pop);
	 }
	 $result = mysql_query($sql);
	 while($row = mysql_fetch_assoc($result))
	 	$conflicts[] = $row;
	 return $conflicts;
	 }
	 // ----------------------------------------------------
	 /**
	  * Get conflict by keyword
	  *
	  * @param string $word
	  */
	public function	 GetUrlCategoryConflictKeyword($keyword)
	{
		$result = mysql_query(sprintf("select * from `url_categories_conflicts` where keyword='%s'",$keyword));
		return mysql_fetch_assoc($result);
	}	 
	 // ----------------------------------------------------
	 /**
	  * Resolve conflict
	  *
	  * @param unknown_type $word
	  */
	public function	 ResolveUrlCategoryConflict($keyword)
	{
		mysql_query(sprintf("delete from `url_categories_conflicts` where keyword='%s'",$keyword));
	}
};
