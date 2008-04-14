<?php


//списки таблиц
$GV["users_tbl"]="users";
//$GV["dbname"]="nibs";
$GV["groups_tbl"]="packets";
$GV["blacklist_tbl"]="blacklist";
$GV["actions_tbl"]="actions";
$GV["events_tbl"]="events";

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

	   }


//добавление пользователя
function AddUser($user)
	{
	global $GV;
 
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
       
	 if($this->IsUserExists($user[user]))return "Ошибка, данный логин уже занят!";
	 
	$query="Insert into `".$GV["users_tbl"].
        "`(`user`,`password`,`gid`,`fio`,`phone`,`address`,`prim`,`add_uid`,`nick`,`gender`,`email`,`icq`,`url`,`rang`,`group`,`city`,`country`,`raiting`,`signature`,`info`,`add_date`,`expired`,`crypt_method`) values ('"
        .$user[user]."','".$user[password]."','".$user[gid]."','".$user[fio]."','".$user[phone]."','".$user[address]."','"
	  .$user[prim]."','".$user[add_uid]."','".$user[nick]."','".$user[gender]."','".$user[email]."','".$user[icq]."','".$user[url]."','"
	  .$user[rang]."','".$user[group]."','".$user[city]."','".$user[country]."','".$user[raiting]."','".$user[signature]."','".$user[info]."','"
	  .norm_date_yymmdd(time())."','".$user[expired]."','0');";
	
        //die($query);
        $result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error()); 
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
    $res["traffic"]=$row["sum(in_bytes)"]+$row["sum(out_bytes)"];
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
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
	  $res["traffic"]+=(int)($row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"]);
	  $res["time"]+=(int)($row["sum(time_on)"]);
	  }
	
	 return $res;
	}	


function GetMonthMaxAccts()
 {
 global $GV;
 $res=NULL;
 $res["traffic"]=$GV["max_month_traffic"];//1*1024*1024*1024;
 $res["time"]=$GV["max_month_time"];//9999999999999999;
 return $res;
 }

//Трафик Пользователей за сегодня
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
	$res=NULL;
	$res["traffic"]=(int)$row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"];
	$res["time"]=(int)$row["sum(time_on)"];
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
	$res["traffic"]=(int)$row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"];
	$res["time"]=(int)$row["sum(time_on)"];
	return $res;
	}

//Трафик Пользователей за месяц
function GetMonthUsersAccts($order=">traffic",$draw=false,$gid="all")
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
	
	$daycount=(date('d')<date("t"))?date("d"):date("t");
	
        $year=date("Y");
        $month=date("m"); 
        $day=date("d");
        $bdate=$year."-".$month."-1 00:00:00";
        $adate=$year."-".$month."-".$daycount." 23:59:59";
                

	if($draw)
          {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
	  $sum_traf=(int)$row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"];
	  $sum_time=(int)$row["sum(time_on)"];
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
          $traf=(int)($row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"]);
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
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>'".$bdate."' and stop_time<'".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
	  $sum_traf=(int)$row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"];
	  $sum_time=(int)$row["sum(time_on)"];
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
          $traf=(int)($row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"]);
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
	
        get_current_week(&$bdate,&$adate,1);
                
        $res=NULL;
        $k=0;
	if($draw)
          {
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where start_time>='".$bdate."' and stop_time<='".$adate."';";  
	  $result=mysql_query($query);

	  $row=mysql_fetch_array($result);
	  $sum_traf=(int)$row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"];
	  $sum_time=(int)$row["sum(time_on)"];
	  $res_others["traffic"]=0;
	  $res_others["time"]=0;
	  $res_others["user"]="другие";  }

	for($i=0;$i<count($users);++$i)
	  {	  
	  $query="select sum(out_bytes),sum(in_bytes),sum(time_on) from `".$GV["actions_tbl"]."` where user='".$users[$i]["user"]."' and start_time>='".$bdate."' and stop_time<='".$adate."';";
	  $result=mysql_query($query);
	  $row=mysql_fetch_array($result);
          $traf=(int)($row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"]);
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
	
//Сессии Пользователя за период времени
function GetUserSessions($user,$fdate,$tdate)
	{
	global $GV;
        $res=NULL;
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
	
	
	
//Взять суммарный траффик и время тарифа за определённый период
function GetTarifAccts($gid,$fdate,$tdate)
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
	  $res["traffic"]+=(int)($row["sum(out_bytes)"]+(int)$row["sum(in_bytes)"]);
	  $res["time"]+=(int)($row["sum(time_on)"]);
	  $res["gid"]=$gid;
	  }
	 return $res;
	}


//Взять суммарный трафик и время тарифов за определённый период
function GetTarifsAccts($fdate,$tdate)
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
	for($i=0;$i<count($tarifs);++$i)
	  {
	  $accts=$this->GetTarifAccts($tarifs[$i]["gid"],$fdate,$tdate); 	  
  	  $res[$i]["traffic"]=$accts["traffic"];
	  $res[$i]["time"]=$accts["time"];
	  $res[$i]["packet"]=$tarifs[$i]["packet"];
	  $res[$i]["gid"]=$tarifs[$i]["gid"];
	  $res[$i]["prim"]=$tarifs[$i]["prim"];          	  
	  }
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
	 //проверка
	if(!$this->IsUserExists($this->GetLoginByUid($uid))) return "Ошибка, Такого пользователя нет!"; 
	global $GV;
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
              $tmp["expired"]=$row["expired"];
              $tmp["crypt_method"]=$row["crypt_method"];             
	      //echo($row["user"]."<br>");
  return $tmp;
}



//обновление пользователя
function UpdateUser($uid,$data)
	{
	global $GV;
	$query="Update `".$GV["users_tbl"]."` set `password`='".$data[password].
        "', `crypt_method`='0', `gid`='".$data["gid"]."', `nick`='".$data["nick"].
        "', `fio`='".$data["fio"]."', `gender`='".$data["gender"]."', `phone`= '".$data["phone"]."', `email`= '".
        $data["email"]."', `icq`='".$data["icq"]."',`url`='".$data["url"]."',  `address`='".$data["address"]."', `rang`='".$data["rang"].
        "', `group`='".$data["group"]."', `city`='".$data["city"]."', `country`='".$data["country"].
        "', `raiting`='".$data["raiting"]."', `signature`='".$data["signature"]."', `info`='".$data["info"].
        "', `prim`='".$data["prim"]."', `add_date`='".$data["add_date"]."', `blocked`='".$data["blocked"].
        "', `activated`='".$data["activated"]."',`phone`='".$data["phone"]."', `total_time`='".$data["total_time"]."',
        `total_traffic`='".$data["total_traffic"]."' , `user`='".$data[user]."' where `uid`='".$uid."' ;";
        //die($query);
        $result=mysql_query($query,$this->link)or die("Invalid query(Update Users): " . mysql_error());
	}



//список Тарифов
function GetTarifs()
	{
	global $GV;
	$query="SELECT * from `".$GV["groups_tbl"]."`;";
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
           $res[$k]["level"]=$row["level"];
           $res[$k]["prim"]=$row["prim"];
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
              $res["session_timeout"]=$row["session_timeout"];
              $res["idle_timeout"]=$row["idle_timeout"];
              $res["level"]=$row["level"];
              $res["prim"]=$row["prim"];
              }
         return $res;
}

//Изменение информации конкретной группы
function UpdateTarif($gid,$data)
	{
	 global $GV;
	
        $query="Update `".$GV["groups_tbl"]."` set `packet`='".$data[packet]."', ".
        "`blocked`='".$data["blocked"]."', `total_time_limit`='".$data["total_time_limit"]."', `month_time_limit`='".$data["month_time_limit"].
        "', `week_time_limit`='".$data["week_time_limit"]."', `day_time_limit`='".$data["day_time_limit"]."', `total_traffic_limit`= '".
        $data["total_traffic_limit"]."', `month_traffic_limit`= '".
        $data["month_traffic_limit"]."', `week_traffic_limit`='".$data["week_traffic_limit"]."',`day_traffic_limit`='".$data["day_traffic_limit"]."', `login_time`='".
        $data["login_time"]."', `port_limit`='".$data["port_limit"].
        "', `session_timeout`=' ".$data["session_timeout"]."', `idle_timeout`='".$data["idle_timeout"]."',`prim`='".$data["prim"]."', `level`='".$data["level"]."' where `gid`='".$gid."' ;";
        //die($query);
        $result=mysql_query($query,$this->link)or die("Invalid query(Update Tarif): " . mysql_error()); 
	
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
          `idle_timeout`,`level`) values ('".$data[packet]."','3','0','".$data[blocked]."','"
	  .$data[total_time_limit]."','".$data[month_time_limit]."','".$data[week_time_limit]."','".$data[day_time_limit]."','"
          .$data[total_traffic_limit]."','".$data[month_traffic_limit]."','"
	  .$data[week_traffic_limit]."','".$data[day_traffic_limit]."','".$data[login_time]."','"
          .$data[simultaneous_use]."','".$data[port_limit]."','".$data[session_timeout]."','".$data[idle_timeout]."','"
          .$data[level]."');";
	$result=mysql_query($query,$this->link)or die("Invalid query(Add User): " . mysql_error()); 
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
function GetOnlineUsersData()
	{
	global $GV;
	$query="SELECT * from `".$GV["actions_tbl"]."` where `terminate_cause`='Online';";
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
  $query="Update `".$GV["actions_tbl"]."` set `terminate_cause`='KilledBy-".$CURRENT_USER['id']."', `stop_time`='".norm_date_yymmddhhmmss(time())."' where `port`='".$port."' and `terminate_cause`='Online';";
  $result=mysql_query($query,$this->link)or die("Invalid query(".$query."): " . mysql_error());
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
	global $GV;
 	$query="Insert into `".$GV["exents_tbl"]."`(`eid`,`uid`,`event`,`date`) values ('".$data[eid]."','".$data[uid]."','".$data[event]."','".$data[date]."');";
        $result=mysql_query($query,$this->link)or die("Invalid query(Add Event): " . mysql_error()); 
	return "";
	}

};

?>