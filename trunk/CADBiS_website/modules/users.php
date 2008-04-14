<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete users                       //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Users";
$MDL_DESCR="For users";
$MDL_UNIQUEID="users";
$MDL_MAKER="SMStudio";

if(!$_GETINFO)
{
//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for projects ****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CUsers"))
{
class CUsers
{
 var $data_dir; //data dir
 var $private_dir;
 var $groups_file;
 var $online_file;
 var $idle_interval;
 var $list_file;//file with list of users
 var $chr1;     //separator level1
 var $chr2;     //separator level2

 //-----------------------------------------------------------------------

 function CUsers($data_dir,$list_file,$private_dir,$groups_file,$online_file)
  {
  $this->data_dir=$data_dir;
  $this->list_file=$list_file;
  $this->private_dir=$private_dir;
  $this->groups_file=$groups_file;
  $this->online_file=$online_file;
  $this->idle_interval=90;
  }
//-----------------------------------------------------------------------
 function SetSeparators($sep1,$sep2)
  {
  $this->chr1=$sep1;
  $this->chr2=$sep2;
  }
//-----------------------------------------------------------------------
 function GetOnlineUsersList()
  {
  include $this->online_file;
  return $ONLINE;
  }
//-----------------------------------------------------------------------
 function IsUserOnline($id)
  {
  include $this->online_file;
  return (isset($ISONLINE["$id"]));
  }
//-----------------------------------------------------------------------
 function SetOffline($CURRENT_USER)
  {
  global $CURRENT_USER;
  include $this->online_file;
  for($i=0;$i<count($ONLINE);++$i)
    if($ONLINE[$i]["ip"]==$CURRENT_USER["ip"])
     {$ONLINE[$i]["id"]="!DELETE!";}
    elseif($ONLINE[$i]["time"]<time()-$this->idle_interval)
     {$ONLINE[$i]["id"]="!DELETE!";}
  $cnt=count($ONLINE);
  $k=0;
  $res="<?php\r\n";
  for($i=0;$i<count($ONLINE);++$i)
  if($ONLINE[$i]["id"]!="!DELETE!")
    {
    $res.="\$ISONLINE[\"".$ONLINE[$i]["id"]."\"]=true;\r\n";
    $res.="\$ONLINE[$k][\"id\"]=\"".$ONLINE[$i]["id"]."\";\r\n";
    $res.="\$ONLINE[$k][\"ip\"]=\"".$ONLINE[$i]["ip"]."\";\r\n";
    $res.="\$ONLINE[$k][\"time\"]=\"".$ONLINE[$i]["time"]."\";\r\n";
    $res.="\$ONLINE[$k][\"browser\"]=\"".$ONLINE[$i]["browser"]."\";\r\n";
    $k++;
    }
  $res.="?>";
   $fp=fopen($this->online_file,"w+");
   fwrite($fp,$res);
   fclose($fp);
  }
//-----------------------------------------------------------------------
 function UpdateOnline($CURRENT_USER)
  {
  global $CURRENT_USER;
  include $this->online_file;
  $ok=0;
  for($i=0;$i<count($ONLINE);++$i)
    if(!$ok && ($ONLINE[$i]["id"]==$CURRENT_USER["id"] && $ONLINE[$i]["ip"]==$CURRENT_USER["ip"]))
     {$ONLINE[$i]["time"]=time();$ONLINE[$i]["ip"]=$CURRENT_USER["ip"];$ok=true;}
    elseif($ONLINE[$i]["time"]<time()-$this->idle_interval)
     {$ONLINE[$i]["id"]="!DELETE!";}
    elseif($ok && ($ONLINE[$i]["id"]==$CURRENT_USER["id"] && $ONLINE[$i]["ip"]==$CURRENT_USER["ip"]))
     {$ONLINE[$i]["id"]="!DELETE!";}
  $cnt=count($ONLINE);
  if(!$ok)
    {
    $ONLINE[$cnt]["time"]=time();
    $ONLINE[$cnt]["id"]=$CURRENT_USER["id"];
    $ONLINE[$cnt]["ip"]=$CURRENT_USER["ip"];
    $ONLINE[$cnt]["browser"]=$CURRENT_USER["browser"];
    }
   $k=0;
  $res="<?php\r\n";
  for($i=0;$i<count($ONLINE);++$i)
  if($ONLINE[$i]["id"]!="!DELETE!")
    {
    $res.="\$ISONLINE[\"".$ONLINE[$i]["id"]."\"]=true;\r\n";
    $res.="\$ONLINE[$k][\"id\"]=\"".$ONLINE[$i]["id"]."\";\r\n";
    $res.="\$ONLINE[$k][\"ip\"]=\"".$ONLINE[$i]["ip"]."\";\r\n";
    $res.="\$ONLINE[$k][\"time\"]=\"".$ONLINE[$i]["time"]."\";\r\n";
    $res.="\$ONLINE[$k][\"browser\"]=\"".$ONLINE[$i]["browser"]."\";\r\n";
    $k++;
    }
  $res.="?>";

   $fp=fopen($this->online_file,"w+");
   fwrite($fp,$res);
   fclose($fp);
  }
//-----------------------------------------------------------------------
  function GetUserData($id)
  {
  global $MDL,$GV;
  $MDL->Load("smadbis");
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  $vars=$BILL->GetUserData($id);
  $tdata=$BILL->GetTarifData($vars["gid"]);

  if(!$vars)return NULL;
              $user["uid"]=$vars["uid"];
              $user["user"]=$vars["user"];
              $user["gid"]=$vars["gid"];
              $user["fio"]=$vars["fio"];
              $user["nick"]=$vars["nick"];
              $user["phone"]=$vars["phone"];
              $user["address"]=$vars["address"];
              $user["prim"]=$vars["prim"];
              $user["password"]=$vars["password"];
              $user["add_uid"]=$vars["add_uid"];
              $user["gender"]=$vars["gender"];
              $user["group"]=$vars["group"];
              $user["email"]=$vars["email"];
              $user["icq"]=$vars["icq"];
              $user["url"]=$vars["url"];
              $user["rang"]=$vars["rang"];
              $user["group"]=$vars["group"];
              $user["city"]=$vars["city"];
              $user["country"]=$vars["country"];
              $user["raiting"]=$vars["raiting"];
              $user["signature"]=$vars["signature"];
              $user["info"]=$vars["info"];
              $user["add_date"]=$vars["add_date"];
              $user["expired"]=$vars["expired"];
              $user["crypt_method"]=$vars["crypt_method"];
       $user['id']=                 $vars["uid"];
       $user['login']=              $vars["user"];
       $user['passwd']=             $vars["password"];
       $user['nick']=               $vars["nick"];
       $user['gender']=             $vars["gender"];
       $user['email']=              $vars["email"];
       $user['url']=                $vars["url"];
       $user['icq']=                $vars["icq"];
       $user['regdate']=            strtotime($vars["add_date"]);
       $user['rang']=               $vars["rang"];
       $user['group']=              $vars["group"];
       $user['raiting']=            $vars["raiting"];
       $user['country']=            $vars["country"];
       $user['city']=               $vars["city"];
       $user['signature']=          $vars["signature"];
       $user["info"]=               $vars["info"];
       $user["fio"]=                $vars["fio"];
       $user["gid"]=                $vars["gid"];
       $user["packet"]=             $tdata["packet"];

    return $user;
  /*
  if(!$this->IsUserExistsById($id))return array();
       $file=get_file($this->data_dir."/".$id);
       $vars=explode($this->chr1,$file);
       $user['id']=                 $id;
       $user['login']=              $vars[0];
       $user['passwd']=             $vars[1];
       $user['nick']=               $vars[2];
       $user['gender']=             $vars[3];
       $user['email']=              $vars[4];
       $user['url']=                $vars[5];
       $user['icq']=                $vars[6];
       $user['regdate']=            $vars[7];
       $user['rang']=               $vars[8];
       $user['group']=              $vars[9];
       $user['raiting']=            $vars[10];
       $user['country']=            $vars[11];
       $user['city']=               $vars[12];
       $user['signature']=          $vars[13];
       $user["info"]=               $vars[14];
       return $user;
  */
  }

//-----------------------------------------------------------------------

  function GetUserLevel($id)
  {
  include $this->groups_file;
  $data=$this->GetUserData($id);
  return $GROUPS[$data["group"]]["level"];
  }

//-----------------------------------------------------------------------

  function IsGroupExists($group)
  {
  include $this->groups_file;
  return isset($GROUPS[$group]);
  }

//-----------------------------------------------------------------------

  function GetGroupData($group)
  {
  include $this->groups_file;
  return $GROUPS[$group];
  }

//-----------------------------------------------------------------------

  function GetGroups()
  {
  include $this->groups_file;
  $res=array(array());
  for($i=0;$i<count($GROUPS_IDS);++$i)
    {
    $res[$i]=$GROUPS[$GROUPS_IDS[$i]];
    $res[$i]["id"]=$GROUPS_IDS[$i];
    }
  return $res;
  }

//-----------------------------------------------------------------------

  function SaveGroups($list)
  {
  $gid=time();
  $res="<?php\r\n";
  include $this->groups_file;
  for($i=0;$i<count($list);++$i)
    $res.="\$GROUPS_IDS[$i]=\"".$list[$i]["id"]."\";\r\n";
  $res.="\r\n";
  for($i=0;$i<count($list);++$i)
    {
    $res.="\$GROUPS[\"".$list[$i]["id"]."\"][\"level\"]=".$list[$i]["level"].";\r\n";
    $res.="\$GROUPS[\"".$list[$i]["id"]."\"][\"name\"]=\"".$list[$i]["name"]."\";\r\n";
    $res.="\$GROUPS[\"".$list[$i]["id"]."\"][\"descr\"]=\"".$list[$i]["descr"]."\";\r\n\r\n";
    }
   $res.="?>";

   $fp=fopen($this->groups_file,"w+");
   fwrite($fp,$res);
   fclose($fp);
  }

//-----------------------------------------------------------------------
  function AddGroup($name,$descr,$level)
  {
  $gid=time();
  $res="<?php\r\n";
  include $this->groups_file;
  for($i=0;$i<count($GROUPS_IDS);++$i)
    $res.="\$GROUPS_IDS[$i]=\"".$GROUPS_IDS[$i]."\";\r\n";
  $res.="\$GROUPS_IDS[".count($GROUPS_IDS)."]=\"".$gid."\";\r\n\r\n";

  for($i=0;$i<count($GROUPS_IDS);++$i)
    {
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"level\"]=".$GROUPS[$GROUPS_IDS[$i]]["level"].";\r\n";
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"name\"]=\"".$GROUPS[$GROUPS_IDS[$i]]["name"]."\";\r\n";
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"descr\"]=\"".$GROUPS[$GROUPS_IDS[$i]]["descr"]."\";\r\n\r\n";
    }
    $res.="\$GROUPS[\"".$gid."\"][\"level\"]=".$level.";\r\n";
    $res.="\$GROUPS[\"".$gid."\"][\"name\"]=\"".$name."\";\r\n";
    $res.="\$GROUPS[\"".$gid."\"][\"descr\"]=\"".$descr."\";\r\n";
   $res.="?>";

   $fp=fopen($this->groups_file,"w+");
   fwrite($fp,$res);
   fclose($fp);
  }

//-----------------------------------------------------------------------
  function DeleteGroup($id)
  {
  $gid=time();
  $res="<?php\r\n";
  include $this->groups_file;
  $k=0;
  for($i=0;$i<count($GROUPS_IDS);++$i)
    if($GROUPS_IDS[$i]!=$id)
    $res.="\$GROUPS_IDS[".($k++)."]=\"".$GROUPS_IDS[$i]."\";\r\n";
    $res.="\r\n";
  for($i=0;$i<count($GROUPS_IDS);++$i)
    if($GROUPS_IDS[$i]!=$id)
    {
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"level\"]=".$GROUPS[$GROUPS_IDS[$i]]["level"].";\r\n";
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"name\"]=\"".$GROUPS[$GROUPS_IDS[$i]]["name"]."\";\r\n";
    $res.="\$GROUPS[\"".$GROUPS_IDS[$i]."\"][\"descr\"]=\"".$GROUPS[$GROUPS_IDS[$i]]["descr"]."\";\r\n\r\n";
    }
   $res.="?>";
   $fp=fopen($this->groups_file,"w+");
   fwrite($fp,$res);
   fclose($fp);
  }

//-----------------------------------------------------------------------

  function GetUserId($login)
  {
  global $MDL,$GV;
  $MDL->Load("smadbis");
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  return $BILL->GetUidByLogin($login);
  //include $this->list_file;
  //return $USERS_IDS[$login];
  }

//-----------------------------------------------------------------------

  function GetUsersList()
  {
  global $MDL,$GV;
  $MDL->Load("smadbis");
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  return $BILL->GetUsersList();


  //include $this->list_file;
  //return $USERS;

  }

//-----------------------------------------------------------------------

  function GetUsersCount()
   {
   /*$user_files=array();
   $user_files=read_dir($this->data_dir);
   return count($user_files);*/
   global $MDL,$GV;
   $MDL->Load("smadbis");
   $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
   return $BILL->GetUsersCount();
   }

//-----------------------------------------------------------------------

  function GetUsers()
   {
   global $MDL,$GV;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
   $vars=$BILL->GetUsers();
   if(!$vars)return NULL;
  include $this->groups_file;
   for($i=0;$i<count($vars);$i++)
     {
    // echo($vars[$i]["user"]."<br>");
       $user[$i]['id']=                 $vars[$i]["uid"];
              $user[$i]["uid"]=$vars[$i]["uid"];
              $user[$i]["user"]=$vars[$i]["user"];
              $user[$i]["gid"]=$vars[$i]["gid"];
              $user[$i]["fio"]=$vars[$i]["fio"];
              $user[$i]["nick"]=$vars[$i]["nick"];
              $user[$i]["phone"]=$vars[$i]["phone"];
              $user[$i]["address"]=$vars[$i]["address"];
              $user[$i]["prim"]=$vars[$i]["prim"];
              $user[$i]["password"]=$vars[$i]["password"];
              $user[$i]["add_uid"]=$vars[$i]["add_uid"];
              $user[$i]["gender"]=$vars[$i]["gender"];
              $user[$i]["group"]=$vars[$i]["group"];
              $user[$i]["email"]=$vars[$i]["email"];
              $user[$i]["icq"]=$vars[$i]["icq"];
              $user[$i]["url"]=$vars[$i]["url"];
              $user[$i]["rang"]=$vars[$i]["rang"];
              $user[$i]["group"]=$vars[$i]["group"];
              $user[$i]["city"]=$vars[$i]["city"];
              $user[$i]["country"]=$vars[$i]["country"];
              $user[$i]["raiting"]=$vars[$i]["raiting"];
              $user[$i]["signature"]=$vars[$i]["signature"];
              $user[$i]["info"]=$vars[$i]["info"];
              $user[$i]["add_date"]=$vars[$i]["add_date"];
              $user[$i]["expired"]=$vars[$i]["expired"];
              $user[$i]["crypt_method"]=$vars[$i]["crypt_method"];
       $user[$i]['id']=                 $vars[$i]["uid"];
       $user[$i]['login']=              $vars[$i]["user"];
       $user[$i]['passwd']=             $vars[$i]["password"];
       $user[$i]['nick']=               $vars[$i]["nick"];
       $user[$i]['gender']=             $vars[$i]["gender"];
       $user[$i]['email']=              $vars[$i]["email"];
       $user[$i]['url']=                $vars[$i]["url"];
       $user[$i]['icq']=                $vars[$i]["icq"];
       $user[$i]['regdate']=            strtotime($vars[$i]["add_date"]);
       $user[$i]['rang']=               $vars[$i]["rang"];
       $user[$i]['group']=              $vars[$i]["group"];
       $user[$i]['raiting']=            $vars[$i]["raiting"];
       $user[$i]['country']=            $vars[$i]["country"];
       $user[$i]['city']=               $vars[$i]["city"];
       $user[$i]['signature']=          $vars[$i]["signature"];
       $user[$i]["info"]=               $vars[$i]["info"];
       $user[$i]["fio"]=                $vars[$i]["fio"];
       $user[$i]["gid"]=                $vars[$i]["gid"];
       $user[$i]["packet"]=             $vars[$i]["packet"];
       $user[$i]["level"]= $GROUPS[$user[$i]['group']]["level"];

     }
   return $user;
   /*$user_files=array();
   $user_files=read_dir($this->data_dir);
   $users=array(array());
   for($i=0;$i<count($user_files);$i++)
     {
       $id=$user_files[$i];
       $file=file($this->data_dir."/".$id);
       $file=implode("",$file);
       $vars=explode($this->chr1,$file);
       $users[$i]['id']=                 $id;
       $users[$i]['login']=              $vars[0];
       $users[$i]['passwd']=             $vars[1];
       $users[$i]['nick']=               $vars[2];
       $users[$i]['gender']=             $vars[3];
       $users[$i]['email']=              $vars[4];
       $users[$i]['url']=                $vars[5];
       $users[$i]['icq']=                $vars[6];
       $users[$i]['regdate']=            $vars[7];
       $users[$i]['rang']=               $vars[8];
       $users[$i]['group']=              $vars[9];
       $users[$i]['raiting']=            $vars[10];
       $users[$i]['country']=            $vars[12];
       $users[$i]['city']=               $vars[13];
       $users[$i]['signature']=          $vars[14];
       $users[$i]["info"]=               $vars[15];
     }
   return $users; */
   }

//-----------------------------------------------------------------------

 function SaveUser($data)
  {
   global $MDL,$GV;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
    $user= $BILL->GetUserData($data["id"]);
    $user[user]       = $data["login"];
    $user[password]   = $data["passwd"];
    $user[email]      = $data["email"];
    $user[add_date]   = norm_date_yymmdd($data["regdate"]);
    $user[nick]       = $data["nick"];
    $user[gender]     = $data["gender"];
    //$user[address]    = $data["address"];
    $user[icq]        = $data["icq"];
    $user[url]        = $data["url"];
    $user[rang]       = $data["rang"];
    $user[group]      = $data["group"];
    $user[city]       = $data["city"];
    $user[country]    = $data["country"];
    $user[raiting]    = $data["raiting"];
    $user[signature]  = $data["signature"];
    $user[info]       = $data["info"];
    return $BILL->UpdateUser($data["id"],$user);
  /*
    $string=$user['login'].$this->chr1.
               $user['passwd'].$this->chr1.
               $user['nick'].$this->chr1.
               $user['gender'].$this->chr1.
               $user['email'].$this->chr1.
               $user['url'].$this->chr1.
               $user['icq'].$this->chr1.
               $user['regdate'].$this->chr1.
               $user['rang'].$this->chr1.
               $user['group'].$this->chr1.
               $user['raiting'].$this->chr1.
               $user['country'].$this->chr1.
               $user['city'].$this->chr1.
               $user['signature'].$this->chr1.
               $user["info"].$this->chr1;
   $fp=fopen("$this->data_dir/".$user['id'],"w+");
   fwrite($fp,$string);
   fclose($fp);
   //OUT("<textarea cols=30 rows=5>$string</textarea>");
  include $this->list_file;
  $USERS_IDS[$user['login']]=$user['id'];
  for($i=0;$i<count($USERS);++$i)
    {if($USERS[$i]["id"]==$user['id'])$USERS[$i]["login"]=$user['login'];}
  $string="<?php\r\n";
  for($i=0;$i<count($USERS);++$i)
    {
    $string.="\$USERS[\"".$i."\"][\"id\"]=\"".$USERS[$i]["id"]."\";\r\n";
    $string.="\$USERS[\"".$i."\"][\"login\"]=\"".$USERS[$i]["login"]."\";\r\n";
    $string.="\$USERS_IDS[\"".$USERS[$i]["login"]."\"]=\"".$USERS[$i]["id"]."\";\r\n";
    }
  $string.="?>";
    //OUT("<textarea cols=30 rows=5>$string</textarea>");
  $fp=fopen($this->list_file,"w+");
  fwrite($fp,$string);
  fclose($fp);   */
  }

//-----------------------------------------------------------------------

 function DeleteUser($id)
  {
   global $MDL,$GV;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
   return $BILL->DeleteUser($id);
  /*
  if(!file_exists($this->data_dir."/".$id))return 0;
  unlink($this->data_dir."/".$id);
  include $this->list_file;
  for($i=0;$i<count($USERS);++$i)
    if($USERS[$i]["id"]==$id)
      unset($USERS[$i]);
 $USERS_NEW = array_values($USERS);
  $string="<?php\r\n";
  for($i=0;$i<count($USERS);++$i)
    {
    $string.="\$USERS[".$i."][\"id\"]=\"".$USERS_NEW[$i]["id"]."\";\r\n";
    $string.="\$USERS[".$i."][\"login\"]=\"".$USERS_NEW[$i]["login"]."\";\r\n";
    $string.="\$USERS_IDS[\"".$USERS_NEW[$i]["login"]."\"]=\"".$USERS_NEW[$i]["id"]."\";\r\n";
    }
  $string.="?>";
  $fp=fopen($this->list_file,"w+");
  fwrite($fp,$string);
  fclose($fp);  */
  }

//-----------------------------------------------------------------------

  function CheckAuth($login,$passwd)
  {
   global $MDL,$GV;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
    if(!$BILL->IsUserExists($login))return false;
   $data=$BILL->GetUserData($BILL->GetUidByLogin($login));
   //die($login."==".$data['user']." && ".md5($passwd)."==".$data['password']);
   return($login==$data['user'] && $passwd==$data['password']);
  /*include $this->list_file;
  $data=$this->GetUserData($USERS_IDS[$login]);
  return($login==$data['login'] && md5($passwd)==$data['passwd']);*/
  }

//-----------------------------------------------------------------------

  function AddUserWithData($data)
  {
   global $MDL,$GV,$CURRENT_USER;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
    $user[user]       = $data["login"];
    $user[password]   = $data["passwd"];
    $user[gid]        = $vars[3];
    $user[fio]        = "";
    $user[email]      = $data["email"];
    $user[phone]      = "";
    $user[prim]       = "";
    $user[add_date]    = $data["regdate"];
    $user[add_uid]    = $CURRENT_USER["id"];
    $user[nick]       = $data["nick"];
    $user[gender]     = $data["gender"];
    $user[address]    = $data["address"];
    $user[icq]        = $data["icq"];
    $user[url]        = $data["url"];
    $user[rang]       = $data["rang"];
    $user[group]      = $data["group"];
    $user[city]       = $data["city"];
    $user[country]    = $data["country"];
    $user[raiting]    = $data["raiting"];
    $user[signature]  = $data["signature"];
    $user[info]       = $data["info"];
    $user[expired]    = "0000-00-00";
    $BILL->AddUser($user);
    return true;
  /*
   $fp=fopen("$this->data_dir/".$user['id'],"w+");
   if(!$fp)return false;
    $string=   $user['login'].$this->chr1.
               md5($user['passwd']).$this->chr1.
               $user['nick'].$this->chr1.
               $user['gender'].$this->chr1.
               $user['email'].$this->chr1.
               $user['url'].$this->chr1.
               $user['icq'].$this->chr1.
               $user['regdate'].$this->chr1.
               $user['rang'].$this->chr1.
               $user['group'].$this->chr1.
               $user['raiting'].$this->chr1.
               $user['country'].$this->chr1.
               $user['city'].$this->chr1.
               $user['signature'].$this->chr1.
               $user["info"].$this->chr1;
   fwrite($fp,$string);
   fclose($fp);
  include $this->list_file;
  $USERS_IDS[$user['login']]=$user['id'];
  $idx=count($USERS);
  $USERS[$idx]["login"]=$user['login'];
  $USERS[$idx]["id"]=$user['id'];
  $string="<?php\r\n";
  for($i=0;$i<count($USERS);++$i)
    {
    $string.="\$USERS[".$i."][\"id\"]=\"".$USERS[$i]["id"]."\";\r\n";
    $string.="\$USERS[".$i."][\"login\"]=\"".$USERS[$i]["login"]."\";\r\n";
    $string.="\$USERS_IDS[\"".$USERS[$i]["login"]."\"]=\"".$USERS[$i]["id"]."\";\r\n";
    }
  $string.="?>";
  $fp=fopen($this->list_file,"w+");
  if(!$fp)return false;
  fwrite($fp,$string);
  fclose($fp);
  return true;       */
  }

//-----------------------------------------------------------------------

 function IsUserExists($login)
  {
   global $MDL,$GV,$CURRENT_USER;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
    return $BILL->IsUserExists($login);
  /*include $this->list_file;
  if(!isset($USERS_IDS[$login]))return false;
  return (file_exists($this->data_dir."/".$USERS_IDS[$login]) && is_file($this->data_dir."/".$USERS_IDS[$login]));*/
  }

//-----------------------------------------------------------------------

 function IsUserExistsById($id)
  {
   global $MDL,$GV,$CURRENT_USER;
   $MDL->Load("smadbis");
    $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
    return $BILL->IsUserExistsByUid($id);
  /*include $this->list_file;
  return (file_exists($this->data_dir."/".$id) && is_file($this->data_dir."/".$id));*/
  }

//-----------------------------------------------------------------------

  function AddUser($login,$passwd,$nick,$gender)
  {
       $user['id']=                 time();
       $user['login']=              $login;
       $user['passwd']=             $passwd;
       $user['nick']=               $nick;
       $user['gender']=             $gender;
       $user['email']=              "";
       $user['url']=                "";
       $user['icq']=                "";
       $user['regdate']=            time();
       $user['rang']=               "";
       $user['group']=              "";
       $user['raiting']=            "";
       $user['country']=            "";
       $user['city']=               "";
       $user['signature']=          "";
       $user["info"]=               "";
       return $this->AddUserWithData($user);
  }
//-----------------------------------------------------------------------
  function AddUserEx($login,$passwd,$nick,$gender,$email,$url,$icq,$regdate,$rang,$group,$raiting,$country,$city,$signature,$info)
  {
       $user['id']=                 time();
       $user['login']=              $login;
       $user['passwd']=             $passwd;
       $user['nick']=               $nick;
       $user['gender']=             $gender;
       $user['email']=              $email;
       $user['url']=                $url;
       $user['icq']=                $icq;
       $user['regdate']=            $regdate;
       $user['rang']=               $rang;
       $user['group']=              $group;
       $user['raiting']=            $raiting;
       $user['country']=            $country;
       $user['city']=               $city;
       $user['signature']=          $signature;
       $user["info"]=               $info;
       return $this->AddUserWithData($user);
  }
};
}
/////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

 if($_INSTALL)
  {
  }
  elseif($_UNINSTALL)
  {
  }
  elseif($_MENU)
  {
  if(!file_exists(SK_DIR."/user_menu.php"))
    {
    ?>
    <div align=center><b>Личное меню:</b><br>
     <a href="?p=users&act=profile">Профиль</a><br>
     <a href="?p=users&act=private">Приват</a><br>
     <a href="?act=logout">Выход</a>
    </div>

    <?
    }
    else
      include(SK_DIR."/user_menu.php");

  }
  elseif($_ADMIN)
  {
  if(!_isroot() || !check_auth()){$page="403";$MDL->LoadModule("error");return;}

  if(!file_exists(SK_DIR."/users_admin.php"))
    {
    // skin doesn't support this module admin, let's draw all by ourself
    // {

      //we'll need for our own extract $GLOBALS
      include "config.php";
      $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
      $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
      global $FLTR;

      if(isset($mod))
      {
       switch($mod)
        {
        case "groups":
         if(isset($action))
           {
           switch($action)
             {
             case "showusers":
                  $gdata=$USR->GetGroupData($gid);
                  ?><br><br>
                  <div align=center>Список пользователей группы "<? OUT($gdata["name"]) ?>":</div><br>
                  <?
                  $list=$USR->GetUsers();
                  for($i=0;$i<count($list);++$i)
                  if($list[$i]["group"]==$gid)
                  {
                  ?>
                  <table width=100% align=center class=tbl1>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$list[$i]["id"]) ?>">
                      <b><? OUT($list[$i]["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$list[$i]['id']))
                        OUT("<img border=0 src=\"".$DIRS["users_avatars"]."/".$list[$i]['id']."\">");
                      ?></a>
                      </td></tr><tr><td align=center>
                      <? OUT($list[$i]["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($list[$i]["raiting"])) ?>
                      </td></tr>
                      </table>
                    </td><td width=70%>
                      <table  width=100%>
                        <tr><td width=50%>
                        E-mail:
                        </td><td width=50%><? OUT(make_email_str($list[$i]["email"])) ?></td></tr>
                        <tr><td width=50%>
                        URL:
                        </td><td width=50%><? OUT(make_url_str($list[$i]["url"])) ?></td></tr>
                        <tr><td width=50%>
                        ICQ:
                        </td><td width=50%><? OUT(make_icq_str($list[$i]["icq"])) ?></td></tr>
                      </table>
                    </tr></td>
                  </table>
                  <?
                  }

                 ?>
                   <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod") ?>">назад</a></div>
                 <?
             break;
             case "delete":
              if(isset($do) && $do=="delete")
                 {
                 if($USR->IsGroupExists($gid))
                  {$USR->DeleteGroup($gid);
                   OUT("Группа $gid удалена!<br>");}
                 else OUT("Такой группы не существует!");
                 }
                 else
                 {
                 ?>
                 <br><b><font color=red>ВНИМАНИЕ!</font> Вы собираетесь удалить группу пользователей.
                     Для продолжения нажмите "я уверен". Для отмены нажмите "назад".<br>
                     При удалении группы все пользователи, состоящие в ней потеряют права!</b><br>
                     <br><div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=delete&gid=$gid&do=delete") ?>">я уверен, что хочу удалить "<? OUT($gid) ?>"</a></div><br><br>
                 <?
                 }
                 ?>
                   <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod") ?>">назад</a></div>
                 <?
             break;
             case "save":
             $new_list=array();
             for($i=0;$i<count($ids);++$i)
               {
               $new_list[$i]["id"]=$FLTR->CutString($FLTR->DirectProcessString($ids[$i],1),"id");
               $new_list[$i]["name"]=$FLTR->CutString($FLTR->DirectProcessString($names[$i],1),"title");
               $new_list[$i]["descr"]=$FLTR->CutString($FLTR->DirectProcessString($descrs[$i],1),"title");
               $new_list[$i]["level"]=$FLTR->CutString($FLTR->DirectProcessString($levels[$i],1),"number");
               if($new_list[$i]["level"]>$CURRENT_USER["level"])$new_list[$i]["level"]=$CURRENT_USER["level"];
               if($new_list[$i]["level"]<-1)$new_list[$i]["level"]=-1;
               if(!$new_list[$i]["id"] || !$new_list[$i]["name"])
                  {OUT("<b>Ошибка! Необходимо ввести название группы</b>");return;}
               }
             $USR->SaveGroups($new_list);
             ?>
             Сохранено!<br>
             <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod") ?>">назад</a></div>
             <?
             break;
             case "add":
               if(!isset($level))$level=0;
               if(!isset($name))$name="";
               if(!isset($descr))$descr="";

               if(isset($do) && $do=="add")
                 {
                 $error="";
                 if($level>$CURRENT_USER["level"])$level=$CURRENT_USER["level"];
                 if($level<-1)$level=-1;
                 if(!$name)$error.="<br><b>Ошибка: </b> Необходимо ввести название группы!<br>";
                 if(!$descr)$error.="<br><b>Ошибка: </b> Необходимо ввести описание группы!<br>";
                 if(!$error)
                   {
                   $USR->AddGroup($name,$descr,$level);
                   OUT("Группа добавлена!<br>");
                   }
                 else {echo($error);$do="";}
                 }

                 if(!isset($do) || $do!="add")
                 {
                 $rightssel="<select name=level class=inputbox style=\"width:100%\">\r\n";
                 for($j=-1;$j<=$CURRENT_USER["level"];++$j)
                   {
                   if($level==$j)$sel=" selected";else $sel="";
                   $rightssel.="<option value=\"$j\"$sel>$j</option>\r\n";
                   }
                 ?>
                 <form action="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=add&do=add") ?>" method=post>
                 <table width=100% align=center class=tbl1>
                  <tr>
                   <td width=50%>
                   Название новой группы:
                   </td>
                   <td width=50%>
                   <input name=name type=text maxlength=120 value="<? OUT($name) ?>" style="width:100%">
                   </td>
                  </tr>
                  <tr>
                   <td width=50%>
                   Описание новой группы:
                   </td>
                   <td width=50%>
                   <input name=descr type=text maxlength=120 value="<? OUT($descr) ?>" style="width:100%">
                   </td>
                  </tr>
                  <tr>
                   <td width=50%>
                   Права новой группы:
                   </td>
                   <td width=50%>
                   <? OUT($rightssel) ?>
                   </td>
                  </tr>
                  </table>
                  <div align=center><input type=submit class=button value="Сохранить"></div>
                 </form> <br>
                 <?
                 }
                 ?>
                 <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod") ?>">назад</a></div>
                 <?
             break;
             };
           }
           else
           {
           ?>
           <a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=add") ?>">Добавить группу</a>
           <form action="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=save") ?>" method=post>
           <?
           $list=$USR->GetGroups();
           for($i=0;$i<count($list);++$i)
             {
             $rightssel="<select class=inputbox name=levels[] style=\"width:100%\">\r\n";
             for($j=-1;$j<=$CURRENT_USER["level"];++$j)
               {
               if($list[$i]["level"]==$j)$sel=" selected";else $sel="";
               $rightssel.="<option value=\"$j\"$sel>$j</option>\r\n";
               }
             $rightssel.="</select>";
             ?><br>
             <div align=center>Группа "<? OUT($list[$i]["id"]) ?>"</div>
             <input type=hidden name=ids[] value="<? OUT($list[$i]["id"]) ?>">
             <table width=100% align=center class=tbl1>
               <tr>
                <td width=50%>
                Название группы:
                </td>
                <td width=50%>
                <input name="names[]" type=text maxlength=125 value="<? OUT($list[$i]["name"]) ?>" style="width:100%">
                </td>
               </tr>
               <tr>
                <td width=50%>
                Описание группы:
                </td>
                <td width=50%>
                <input name="descrs[]" type=text maxlength=125 value="<? OUT($list[$i]["descr"]) ?>" style="width:100%">
                </td>
               </tr>
               <tr>
                <td width=50%>
                Права группы:
                </td>
                <td width=50%>
                <? OUT($rightssel) ?>
                </td>
               </tr>
              </table>
              <table align=center width=100%><tr><td width=50% align=left>
              <a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=showusers&gid=".$list[$i]["id"]) ?>">список пользователей</a>
              </td><td align=right width=50%>
              <a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=delete&gid=".$list[$i]["id"]) ?>">удалить</a>
              </td></tr></table>
             <?
             }
             ?>
             <div align=center><input type=submit class=button value="Сохранить"></div>
             </form> <br>

            <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id") ?>">назад</a></div>
             <?


           }
         break;
        case "users":
         global  $PDIV;
         if(!isset($page))$page=1;
         $form=1;

         if(isset($action) && $action=="edit")
           if(isset($do) && $do=="edit")
            {
            $ok=false;
            if(isset($sure) && $sure=="true")
              {

              for($i=0;$i<count($ids);++$i)
               {
               if(!$USR->IsUserExistsById($ids[$i])){
               OUT("Пользователя с таким идентификатором не существует!"); break;}

               global $GV_USERS;
               $pass_error=false;
               $user=$USR->GetUserData($ids[$i]);
               /*$user=array();
               $user["id"]=$data["id"];
               $user["login"]=$data["login"];
               $user["passwd"]=$data["passwd"];
               $user["gender"]=$data["gender"];
               $user["regdate"]=$data["regdate"];
               $user['group']=$data['group'];
               $user['raiting']=$data['raiting'];
               $user['rang']=$data['rang']; */

               //for($j=0;$j<count($new_data);++$j)OUT("$j: ".$new_data[$j]."<br>");
               $user['login']=             $FLTR->DirectProcessString($new_data[$i*15+0],1);
               //$user['date']=              $FLTR->DirectProcessString($new_data[$i*15+1],1);
               $user['rang']=              $FLTR->DirectProcessString($new_data[$i*15+2],1);
               $user['group']=             $FLTR->DirectProcessString($new_data[$i*15+3],1);
               $user['gender']=            $FLTR->DirectProcessString($new_data[$i*15+4],1);
               $user['nick']=              $FLTR->DirectProcessString($new_data[$i*15+5],1);
               $user['email']=             $FLTR->DirectProcessString($new_data[$i*15+6],1);
               $user['url']=               $FLTR->DirectProcessString($new_data[$i*15+7],1);
               $user['icq']=               $FLTR->DirectProcessString($new_data[$i*15+8],1);
               $user['country']=           $FLTR->DirectProcessString($new_data[$i*15+9],1);
               $user['city']=              $FLTR->DirectProcessString($new_data[$i*15+10],1);
               if($new_data[$i*15+11]==$new_data[$i*15+12] && strlen($new_data[$i*15+11])>=3)
                $user['passwd']=            $FLTR->DirectProcessString($new_data[$i*15+11],1);
               else $pass_error=true;
               $user['signature']=         $FLTR->DirectProcessText($new_data[$i*15+13],1,1);
               $user["info"]=              $FLTR->DirectProcessText($new_data[$i*15+14],1,1);
               $error="";
               if(isset($GV_USERS))
               {
               $user["login"]=substr($user["login"],0,$GV_USERS["max_login_len"]);
               $user["nick"]=substr($user["nick"],0,$GV_USERS["max_nick_len"]);
               $user["email"]=substr($user["email"],0,$GV_USERS["max_email_len"]);
               $user["url"]=substr($user["url"],0,$GV_USERS["max_url_len"]);
               $user["icq"]=substr($user["icq"],0,$GV_USERS["max_icq_len"]);
               $user["country"]=substr($user["country"],0,$GV_USERS["max_country_len"]);
               $user["city"]=substr($user["city"],0,$GV_USERS["max_city_len"]);
               $user["info"]=substr($user["info"],0,$GV_USERS["max_info_len"]);
               $user["signature"]=substr($user["signature"],0,$GV_USERS["max_sign_len"]);
               if(!$USR->IsGroupExists($user["group"]))$error.="<br><b>Ошибка: </b> Группа ".$user["group"]." не существует!<br>";
               if(strlen($user["login"])<$GV_USERS["min_login_len"])$error.="<br><b>Ошибка: </b> Логин не может быть короче ".$GV_USERS["min_login_len"]." символов<br>";
               //if(strlen($user["passwd"])<$GV_USERS["min_password_len"])$error.="<br><b>Ошибка: </b> Пароль не может быть короче ".$GV_USERS["min_password_len"]." символов<br>";
               if(strlen($user["nick"])<$GV_USERS["min_nick_len"])$error.="<br><b>Ошибка: </b> Ник не может быть короче ".$GV_USERS["min_nick_len"]." символов<br>";
               }

               //die($user['passwd']."==".md5("123"));
               if(!$error)
                {$USR->SaveUser($user);$ok=true;}
               else {echo($error);$ok=false;}

               if($pass_error)
               {
               ?><br>
               <strong>Пароль не изменён!</strong>(Это можно игнорировать) Это могло произойти по следующим причинам:<br>
               <li>Вы не ввели пароль (вероятно, вы не хотели его изменять).
               <li>Слишком короткий пароль. Пароль должен быть не короче 4-х символов</li>
               <li>Введённые пароли не совпадают. Вы неверно ввели подтверждение пароля!</li>
               <?
               }

               }
              }

              if(!$ok)
              {
              ?>
              <form action="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=edit&do=$do&page=$page") ?>&sure=true" method=post>
              <?
              for($i=0;$i<count($ids);++$i)
               {
               $data=$USR->GetUserData($ids[$i]);
               $sign=$FLTR->ReverseProcessText($data["signature"]);
               $info=$FLTR->ReverseProcessText($data["info"]);
               if(!$USR->IsUserExistsById($ids[$i])){
               OUT("Пользователя с таким идентификатором не существует!"); return;}
               $list=$USR->GetGroups();
               $groupsselect="<select class=inputbox name=\"new_data[]\" style=\"width:100%\">";
                for($j=0;$j<count($list);++$j)
                  {
                  if($data["group"]==$list[$j]["id"])$sel=" selected";else $sel="";
                  $groupsselect.="<option value=\"".$list[$j]["id"]."\"$sel>".$list[$j]["name"]."</option>";}
               $groupsselect.="</select>";
               $genderselect="<select class=inputbox name=\"new_data[]\" style=\"width:100%\">";
               if($data["gender"]){$msel="selected";$wsel="";}else{$msel="";$wsel=" selected";}
               $genderselect.="<option value=1 $msel>мужской</option><option value=0 $wsel>женский</option>";
               $genderselect.="</select>";
                ?>
                 <br><br>
                 <div align=center><b>Пользователь: <? OUT($data["id"]) ?></b></div>
                 <input type=hidden name=ids[] value="<? OUT($data["id"]) ?>">
               <table width=100%>

                 <tr>
                  <td width=50% class="td_tnd_link">Логин:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["login"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Дата регистрации:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=hidden name="new_data[]"><? OUT(norm_date($data["regdate"])) ?></td>
                  </tr>
                   <tr>
                   <td width=50% class="td_tnd_link">Ранг:</td>
                   <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["rang"]) ?>" style="width:100%"></td>
                   </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Группа:</td>
                  <td width=50% class="td_tnd_link"><? OUT($groupsselect) ?></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Пол:</td>
                  <td width=50% class="td_tnd_link"><? OUT($genderselect) ?></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Ник:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["nick"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">E-mail:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["email"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">URL:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["url"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">ICQ:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["icq"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Страна:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["country"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Город:</td>
                  <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["city"]) ?>" style="width:100%"></td>
                  </tr>
                  <tr>
                  <td width=50% class="td_tnd_link">Пароль(и подтверждение):</td>
                  <td width=50% class="td_tnd_link"><input type=password name="new_data[]" style="width:100%"><br>
                  <input type=password name="new_data[]" style="width:100%"></td>
                  </tr>
                  </table>
                  <table width=100%>
                  <tr><td class="td_tnd_link" width=100%>Подпись:
                  </td></tr>
                  <tr><td>
                  <textarea class=inputbox name="new_data[]" style="width:100%" rows=5><? OUT($sign) ?></textarea>
                  </td></tr>
                  <tr><td class="td_tnd_link" width=100%>Дополнительно:
                  </td></tr>
                  <tr><td>
                  <textarea class=inputbox name="new_data[]" style="width:100%" rows=5><? OUT($info) ?></textarea>
                  </td></tr>
                  </table>
               <?
               }
               ?>
                <div align=center><input type=submit class=button value="Сохранить"></div>
               </form>
               <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&page=$page") ?>">назад</a></div>
               <?

              $form=0;
              }
            }
            if(isset($do) && $do=="delete")
            {
            if(isset($sure) && $sure=="true")
             {
              for($i=0;$i<count($ids);++$i)
               {
               $data=$USR->GetUserData($ids[$i]);
               if(!$USR->IsUserExistsById($ids[$i])){
               OUT("Пользователя с таким идентификатором не существует!");return;}
               $USR->DeleteUser($ids[$i]);
               }
             }
             else
             {
             $form=0;
              ?>
              <br><br><font color=red size=6px><b>ВНИМАНИЕ:</b></font> Вы собираетесь удалить пользователей. Вы уверены, что хотите это сделать?<br>
              Удаление может повлечь необратимые последствия, пользователи потеряют идентификаторы и не смогут входить на сайт!<br>
              Если вы действительно хотите удалить следующих пользователей, нажмите "Удалить". В противном случае - "Назад".
              <form action="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=edit&do=$do&page=$page&sure=true") ?>&sure=true" method=post>
              <?
              for($i=0;$i<count($ids);++$i)
               {
               $data=$USR->GetUserData($ids[$i]);
               if(!$USR->IsUserExistsById($ids[$i])){
               OUT("Пользователя с таким идентификатором не существует!");break;}
               ?>
               <input type=hidden name=ids[] value="<? OUT($data["id"]) ?>">
                  <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["id"]) ?>">
                      <b><? OUT($data["nick"]) ?></b>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>
                      </table>
                    <td width=70%>
                      <table  width=100%>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($data["login"]) ?></td></tr>
                        <tr><td width=50%>
                        E-mail:
                        </td><td width=50%><? OUT(make_email_str($data["email"])) ?></td></tr>
                        <tr><td width=50%>
                        URL:
                        </td><td width=50%><? OUT(make_url_str($data["url"])) ?></td></tr>
                        <tr><td width=50%>
                        ICQ:
                        </td><td width=50%><? OUT(make_icq_str($data["icq"])) ?></td></tr>
                      </table>
                    </td></tr>
                   </table>
               <?
               }
              ?><br><br>
                <div align=center><input type=submit class=button value="Удалить!"></div>
              </form>
               <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&page=$page") ?>">назад</a></div>
              <?
             }
            }




         if($form)
         {
	    $list=$USR->GetUsersList();
         $pagestext="";
         $pgcnt= $PDIV->GetPagesCount($list);
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=$i+1)$pagestext.="<a href=\"?p=$p&act=$act&id=$id&mod=$mod&page=".($i+1)."\">".($i+1)."</a>";
	   else $pagestext.="".($i+1)."";
           if($i<$pgcnt-1)$pagestext.=", ";
           }
         $ulist=$PDIV->GetPage($list,$page);


         OUT("Страница: ".$pagestext);

         ?>
         <form action="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=edit&page=$page") ?>" method=post>
         <?
         for($i=0;$i<count($ulist);++$i)
             {
             $data=$USR->GetUserData($ulist[$i]);
             ?>
                  <div align=center><b><? OUT($data["id"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["id"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$data['id']))
                        OUT("<img border=0 src=\"".$DIRS["users_avatars"]."/".$data['id']."\">");
                      ?></a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>
                      </table>
                    </td><td width=70%>
                      <table  width=100%>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($data["login"]) ?></td></tr>
                        <tr><td width=50%>
                        E-mail:
                        </td><td width=50%><? OUT(make_email_str($data["email"])) ?></td></tr>
                        <tr><td width=50%>
                        URL:
                        </td><td width=50%><? OUT(make_url_str($data["url"])) ?></td></tr>
                        <tr><td width=50%>
                        ICQ:
                        </td><td width=50%><? OUT(make_icq_str($data["icq"])) ?></td></tr>
                      </table>
                    </tr></td>
                  </table>
                  <table width=100%><td width=50% class=tbl1 align=left>
                  <input type=checkbox name=ids[] value="<? OUT($data['id']) ?>">выбрать</td>
                  <td align=right class=tbl1 width=50%><a href="<? OUT("?p=$p&act=$act&id=$id&mod=$mod&action=edit&do=delete&page=$page&ids[]=".$data["id"]) ?>">удалить</a>&nbsp;&nbsp; <a href="<? OUT("?p=$p&act=$act&id=$id&action=edit&do=edit&mod=$mod&page=$page&ids[]=".$data["id"]) ?>">редактировать</a>
                  </td></table><br>
             <?
             }
             ?>
             Страница: <? OUT($pagestext) ?><br><br>
             Выберите действие: <input type=radio name=do value=delete >удалить <input type=radio name=do value=edit checked>редактировать<br>
             <input type=submit class=button value="Выполнить!">
             </form>
         <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id") ?>">назад</a></div>
             <?
          }

         break;
        case "online":
         global  $PDIV;
         if(!isset($page) || $page<1)$page=1;
         $list=$USR->GetOnlineUsersList();
         $pagestext="";
         $pgcnt= $PDIV->GetPagesCount($list);
         if($page>=$pgcnt)$page=$pgcnt-1;
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=$i+1)$pagestext.="<a href=\"?p=$p&act=$act&id=$id&mod=$mod&page=".($i+1)."\">".($i+1)."</a>";
	   else $pagestext.="".($i+1)."";
           if($i<$pgcnt-1)$pagestext.=", ";
           }
         $ulist=$PDIV->GetPage($list,$page);
         ?>
         <br><div align=center><u>Сейчас на сайте:</u></div><br>
         Страница: <? OUT($pagestext) ?><br>
         <?
         for($i=0;$i<count($ulist);++$i)
             {
             $ue=$USR->IsUserExistsById($ulist[$i]["id"]);
             if($USR->IsUserExistsById($ulist[$i]["id"]))
               $data=$USR->GetUserData($ulist[$i]["id"]);
             elseif($ulist[$i]["id"]=="!ROOT!")
               {
               global $_root_login;
               $data=NULL;
               $data["rang"]="root";
               $data["id"]="суперпользователь";
               $data["login"]=$_root_login;
               }
             else
               {
               $data=NULL;
               $data["rang"]="гость";
               $data["id"]="гость";
               $data["login"]="";
               }


             ?>
                  <div align=center><b><? OUT($data["id"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["id"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$data['id']))
                        OUT("<img border=0 src=\"".$DIRS["users_avatars"]."/".$data['id']."\">");
                      ?></a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>
                      </table>
                    </td><td width=70%>
                      <table  width=100%>
                       <?
                       if($ue)
                        {
                        ?>
                        <tr><td width=50% class=tbl1>
                        Login:
                        </td><td width=50% class=tbl1><? OUT($data["login"]) ?></td></tr>
                        <tr><td width=50% class=tbl1>
                        E-mail:
                        </td><td width=50% class=tbl1><? OUT(make_email_str($data["email"])) ?></td></tr>
                        <tr><td width=50% class=tbl1>
                        URL:
                        </td><td width=50% class=tbl1><? OUT(make_url_str($data["url"])) ?></td></tr>
                        <tr><td width=50% class=tbl1>
                        ICQ:
                        </td><td width=50% class=tbl1><? OUT(make_icq_str($data["icq"])) ?></td></tr>
                        <?
                        }
                        ?>
                        <tr><td width=50% class=tbl1>
                        IP:
                        </td><td width=50% class=tbl1><? OUT($ulist[$i]["ip"]) ?></td></tr>
                        <tr><td width=50% class=tbl1>
                        Browser:
                        </td><td width=50% class=tbl1><? OUT($ulist[$i]["browser"]) ?></td></tr>
                        <tr><td width=50% class=tbl1>
                        Последнее обновление:
                        </td><td width=50% class=tbl1><? OUT(norm_date($ulist[$i]["time"])) ?></td></tr>

                      </table>
                    </tr></td>
                  </table>
             <?
             }
             ?>
             Страница: <? OUT($pagestext) ?><br><br>

         <div align=center><a href="<? OUT("?p=$p&act=$act&id=$id") ?>">назад</a></div>
         <?
         break;
        };
      ?>
      <?
      }
      else
      {
      ?>
      <br><br>
      <div align=center>
        <a href="<? OUT("?p=$p&act=$act&id=$id&mod=groups") ?>">Редактор групп</a><br><br>
        <a href="<? OUT("?p=$p&act=$act&id=$id&mod=users") ?>">Редактор пользователей</a><br><br>
        <a href="<? OUT("?p=$p&act=$act&id=$id&mod=online") ?>">Кто просматривает сайт?</a><br>
      </div>
      <?
      }
    // }
    }
  else
    include(SK_DIR."/users_admin.php");
  }
  else
  {
 if($_MODULE)
  if($_NOTBAR)
    if(!file_exists(SK_DIR."/users.php"))
      {
      // skin doesn't support this module admin, let's draw all by ourself
      // {

      include "config.php";
      $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
      $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
      global $FLTR;
      $list=$USR->GetUsersList();

      if(isset($CURRENT_USER["id"]) && is_user_exists($CURRENT_USER["id"]))
        $data=$USR->GetUserData($CURRENT_USER["id"]);
      switch($act)
      {

      case "userinfo":


      if(isset($id) && is_user_exists($id))
        {
        $udata=$USR->GetUserData($id);
        if($USR->IsUserOnline($id))$tonline="Он-лайн";else $tonline="Офлайн";
        ?>
        <div align=center><b>Информация о пользователе '<? OUT($udata['nick']) ?>'</b></div>
        <table width=100%><tr><td width=100%>
        <table width=30% height=100% align=left class=tbl1>
          <tr><td width=100% align=center>
          <b><? OUT($udata["nick"]) ?>
          </td></tr>
          <? if(file_exists($DIRS["users_avatars"]."/".$udata['id'])){ ?>
          <tr><td align=center>
           <img src="<? OUT($DIRS["users_avatars"]."/".$udata['id']) ?>" border=0>
          </td></tr>
          <? } ?>
          <tr><td align=center>
          <? OUT($udata["rang"]) ?>
          </td></tr>
          <tr><td align=center height=100% valign=top>
          <? OUT(make_raiting_str($udata["raiting"])) ?>
          </td></tr>
        </table>
        <table width=70% height=100% align=right class=tbl1>
        <tr><td align=center><b>Статус:</b></td></tr>
        <tr><td width=100%>
          <table width=100%>
          <tr><td width=50%>
          ID:</td><td width=50%><? OUT($udata["id"]) ?>
          </td></tr>
          <tr><td width=50% height=100% valign=top>
          группа:</td><td ><? $gdata=$USR->GetGroupData($udata["group"]); OUT($gdata["name"]) ?>
          </td></tr>
          <tr><td width=50% height=100% valign=top>
          На сайте:</td><td ><? OUT($tonline) ?>
          </td></tr>
          </table>
        </td></tr>
        </table>
        </td></tr>
        <tr><td>
        <table width=100% class=tbl1>
        <tr><td align=center><b>Личные данные:</b></td></tr>
        <tr><td>
          <table width=100%>
          <tr><td width=50%>
          ФИО:</td><td ><? OUT($udata["fio"]) ?>
          </td></tr>
          <tr><td width=50%>
          Ник:</td><td ><? OUT($udata["nick"]) ?>
          </td></tr>
          <tr><td width=50%>
          Пол:</td><td><? OUT(make_gender_str($udata["gender"])) ?>
          </td></tr>

          <tr><td width=50%>
          E-mail:</td><td><? OUT(make_email_str($udata["email"])) ?>
          </td></tr>

          <tr><td width=50%>
          URL:</td><td><? OUT(make_url_str($udata["url"])) ?>
          </td></tr>

          <tr><td width=50%>
          ICQ:</td><td><? OUT(make_icq_str($udata["icq"])) ?>
          </td></tr>

          <tr><td width=50%>
          Зарегистрирован:</td><td><? OUT(date_dmy($udata["regdate"])) ?>
          </td></tr>

          <? if($udata["country"]){ ?>
          <tr><td width=50%>
          Страна:</td><td><? OUT($udata["country"]) ?>
          </td></tr>
          <? } ?>

          <? if($udata["city"]){ ?>
          <tr><td width=50%>
          Город:</td><td><? OUT($udata["city"]) ?>
          </td></tr>
          <? } ?>

          <? if($udata["address"]){ ?>
          <tr><td width=50%>
          Адрес:</td><td><? OUT($udata["address"]) ?>
          </td></tr>
          <? } ?>
          </table>

          <table width=100%>
          <? if($udata["signature"]){ ?>
          <tr><td width=100% align=center><br>
          Подпись:
          </tr></td>
          <tr><td width=100%>
          <? OUT($udata["signature"]) ?>
          </td></tr>
          <? } ?>
          <? if($udata["info"]){ ?>
          <tr><td width=100% align=center><br>
          Дополнительная информация:
          </tr></td>
          <tr><td width=100%>
          <? OUT($udata["info"]) ?>
          </td></tr>
          <? } ?>
          </table>

        </td></tr>
        </table>
        </td></tr>
        <? if(($CURRENT_USER["level"]>$USR->GetUserLevel($udata["id"]) || $CURRENT_USER["id"]==$udata["id"]) && $CURRENT_USER["level"]>=5)
        {
        $MDL->Load("smadbis");
        $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
        ?>
        <tr><td width=100%>
        <table class=tbl2 width=100%><td width=20% align=center>
        <a href="?p=smadbis&act=users&action=delete&mod=delete&ids[]=<? OUT($udata["id"]) ?>">удалить</a></td><td width=20% align=center>
        <a href="?p=smadbis&act=users&action=add&mode=edit&uid=<? OUT($udata["id"]) ?>">редактировать</a></td><td width=20% align=center>
        <a href="?p=smadbis&act=users&action=block&uid=<? OUT($udata["id"]) ?>">
        <? if($BILL->IsUserActivated($udata["id"])){ ?>блокировать<? }else{ ?><font color=green>разблокировать<? }?></font></a><br>
        </a></td>
        <td width=20% align=center>
        <a href="?p=smadbis&act=stats&action=sessions&user=<? OUT($udata["login"]) ?>">статистика</a></td>
        </td>
        <td width=20% align=center>
        <a href="?p=smadbis&act=stats&action=urls&uid=<? OUT($udata["uid"]) ?>">Топ сайтов</a></td></table>
        </td>
        </tr>
        <? } ?>

        </table>
        <?
        }
        else
        {
        ?>
        <div align=center><b>ОШИБКА!</b> Пользователя с таким идентификатором не существует!</div>
        <?
        }

      break;

      case "private":
      ?>
      <div align=center><b>Приват</b></div>
      <?

      break;


      case "register":
      ?>
      <div align=center><b>Регистрация на сайте "<? OUT($GV["site_title"]) ?>"</b></div>
      <?
      global $FLTR,$GV_USERS;
       $REGOK=false;
        if(isset($a) && $a=="reg")
         {
         if($gender!=0 && $gender!=1)$gender=1;
         $login=$FLTR->DirectProcessString($login,1);
         $nick=$FLTR->DirectProcessString($nick,1);
         $email=$FLTR->DirectProcessString($email,1);
         $url=$FLTR->DirectProcessString($url,1);
         $icq=$FLTR->DirectProcessString($icq,1);
         $country=$FLTR->DirectProcessString($country,1);
         $city=$FLTR->DirectProcessString($city,1);
         $info=$FLTR->DirectProcessText($info,1,1);
         $sign=$FLTR->DirectProcessText($sign,1,1);

         $login=substr($login,0,$GV_USERS["max_login_len"]);
         $nick=substr($nick,0,$GV_USERS["max_nick_len"]);
         $email=substr($email,0,$GV_USERS["max_email_len"]);
         $url=substr($url,0,$GV_USERS["max_url_len"]);
         $icq=substr($icq,0,$GV_USERS["max_icq_len"]);
         $country=substr($country,0,$GV_USERS["max_country_len"]);
         $city=substr($city,0,$GV_USERS["max_city_len"]);
         $info=substr($info,0,$GV_USERS["max_info_len"]);
         $sign=substr($sign,0,$GV_USERS["max_sign_len"]);

         $error="";
         if($password!=$password2)$error.="<br><b>Ошибка: </b> Введённые пароли не совпадают!<br>";
         if(strlen($login)<$GV_USERS["min_login_len"])$error.="<br><b>Ошибка: </b> Логин не может быть короче ".$GV_USERS["min_login_len"]." символов<br>";
         if(strlen($password)<$GV_USERS["min_password_len"])$error.="<br><b>Ошибка: </b> Пароль не может быть короче ".$GV_USERS["min_password_len"]." символов<br>";
         if(strlen($nick)<$GV_USERS["min_nick_len"])$error.="<br><b>Ошибка: </b> Ник не может быть короче ".$GV_USERS["min_nick_len"]." символов<br>";
         if(!$error)
           if(!$USR->IsUserExists($login))
             if($USR->AddUserEx($login,$password,$nick,$gender,$email,$url,$icq,time(),"",$GV_USERS["start_group"],0,$country,$city,$sign,$info))
               $REGOK=true;
             else $error.="<br><b>Ошибка: </b> Не удалось добавить пользователя! Попробуйте позже.<br>";
           else $error.="<br><b>Ошибка: </b> Данный логин занят! Выберите другой<br>";
         if($error)
           echo($error);
         }

       if(!$REGOK)
         {

         ?><br>
         <form action="<? OUT("?p=$p&act=$act&a=reg") ?>" method=post>
         <table align=center width=100%>
          <tr><td width=100%>
           <table width=100%>
           <tr><td width=50%>
           Логин<font color=red>*</font>:
           </td><td>
           <input class=inputbox name=login type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_login_len"]) ?>" value="<? OUT($login) ?>">
           </td></tr>
           <tr><td width=50%>
           Пароль<font color=red>*</font>:</td>
           </td><td>
           <input class=inputbox name=password type=password style="width:100%" maxlength="<? OUT($GV_USERS["max_password_len"]) ?>">
           </td></tr>
           <tr><td width=50%>
           Подтверждение<font color=red>*</font>:</td>
           </td><td>
           <input class=inputbox name=password2 type=password style="width:100%" maxlength="<? OUT($GV_USERS["max_password_len"]) ?>">
           </td></tr>
           <tr><td width=50%>
           Ник<font color=red>*</font>:</td>
           <td>
           <input class=inputbox name=nick type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_nick_len"]) ?>" value="<? OUT($nick) ?>">
           </td></tr>
           <tr><td width=50%>
           Пол:</td>
           <td>
           <input type=radio name=gender value="1"<? if((isset($gender) && $gender) || !isset($gender))OUT(" checked"); ?>>мужской
           <input type=radio name=gender value="0"<? if(isset($gender) && !$gender)OUT(" checked") ?>>женский
           </td></tr>
           <tr><td width=50%>
           E-mail:</td>
           <td>
           <input class=inputbox name=email type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_email_len"]) ?>" value="<? OUT($email) ?>">
           <tr><td width=50%>
           URL:</td>
           <td>
           <input class=inputbox name=url type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_url_len"]) ?>" value="<? OUT($url) ?>">
           <tr><td width=50%>
           ICQ:</td>
           <td>
           <input class=inputbox name=icq type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_icq_len"]) ?>" value="<? OUT($icq) ?>">
           </td></tr>
           <tr><td width=50%>
           Страна:</td>
           <td>
           <input class=inputbox name=country type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_country_len"]) ?>" value="<? OUT($country) ?>">
           </td></tr>
           <tr><td width=50%>
           Город:</td>
           <td>
           <input class=inputbox name=city type=text style="width:100%" maxlength="<? OUT($GV_USERS["max_city_len"]) ?>" value="<? OUT($city) ?>">
           </td></tr>
          </table>
         <tr><td width=100%>
           <table width=100%>
           <tr><td width=100% align=center>
           Подпись:
           </td></tr>
           <tr><td width=100% align=center>
           <textarea class=inputbox name=signature rows=5 style="width:100%"><? OUT($icq) ?></textarea>
           </td></tr>
           <tr><td width=100% align=center>
           Дополнительная информация:
           </td></tr>
           <tr><td width=100% align=center>
           <textarea class=inputbox name=info rows=5 style="width:100%"><? OUT($icq) ?></textarea>
           </td></tr>
           </table>
         </td></tr>
         </table>
         Поля отмеченные звёздочкой (<font color=red>*</font>) обязательны для заполнения
         <br><Br><div align=center><input type=submit value="Зарегистрироваться!"></div>
         </form>
         <?
         }
         else
         {
         ?>
         Вы успешно зарегистрированы на сайте!<Br>
         Вот ваши регистрационные данные:<br>
           <table width=100%>
           <tr><td width=50%>
           Логин:
           </td><td>
           <? OUT($login) ?>
           </td></tr>
           <tr><td width=50%>
           Пароль:</td>
           </td><td>
           ***
           </td></tr>
           <tr><td width=50%>
           Ник:</td>
           <td>
           <? OUT($nick) ?>
           </td></tr>
           <tr><td width=50%>
           Пол:</td>
           <td>
           <? OUT(make_gender_str($gender)) ?>
           </td></tr>
           <tr><td width=50%>
           E-mail:</td>
           <td>
           <? OUT($email) ?>
           <tr><td width=50%>
           URL:</td>
           <td>
           <? OUT($url) ?>
           <tr><td width=50%>
           ICQ:</td>
           <td>
           <? OUT($icq) ?>
           </td></tr>
           <tr><td width=50%>
           Страна:</td>
           <td>
           <? OUT($country) ?>
           </td></tr>
           <tr><td width=50%>
           Город:</td>
           <td>
           <? OUT($city) ?>
           </td></tr>
          </table>
          <table width=100%>
           <tr><td width=100% align=center>
           Подпись:
           </td></tr>
           <tr><td width=100% align=center>
           <? OUT($icq) ?>
           </td></tr>
           <tr><td width=100% align=center>
           Дополнительная информация:
           </td></tr>
           <tr><td width=100% align=center>
           <? OUT($icq) ?>
           </td></tr>
           </table>
           <br><Br>
           <div align=center><a href="?p=<? OUT($GV['default_page']) ?>">На главную страницу сайта</a></div>
         <?
         }

      break;

      case "profile":

      if(!check_auth())
        {
        ?>
        ОШИБКА ДОСТУПА! ВЫ НЕ АВТОРИЗОВАНЫ!
        <?
        return;
        }
      if(isset($a) && $a=="save")
      {
      global $GV_USERS;

      $pass_error=false;

      $user=array();
      $user["id"]=$CURRENT_USER["id"];
      $user["login"]=$CURRENT_USER["login"];
      $user["passwd"]=$CURRENT_USER["passwd"];
      $user["gender"]=$data["gender"];
      $user["regdate"]=$data["regdate"];
      $user['group']=$data['group'];
      $user['raiting']=$data['raiting'];
      $user['rang']=$data['rang'];
      $user['nick']=              $FLTR->DirectProcessString($new_data[0],1);
      $user['email']=             $FLTR->DirectProcessString($new_data[1],1);
      $user['url']=               $FLTR->DirectProcessString($new_data[2],1);
      $user['icq']=               $FLTR->DirectProcessString($new_data[3],1);
      $user['country']=           $FLTR->DirectProcessString($new_data[4],1);
      $user['city']=              $FLTR->DirectProcessString($new_data[5],1);
      if($new_data[6]==$new_data[7] && strlen($new_data[6])>=4)
        $user['passwd']=            $FLTR->DirectProcessString($new_data[6],1);
      else $pass_error=true;
      $user['signature']=         $FLTR->DirectProcessText($new_data[8],1,1);
      $user["info"]=              $FLTR->DirectProcessText($new_data[9],1,1);

      $error="";
      if(isset($GV_USERS))
      {
      $user["login"]=substr($user["login"],0,$GV_USERS["max_login_len"]);
      $user["nick"]=substr($user["nick"],0,$GV_USERS["max_nick_len"]);
      $user["email"]=substr($user["email"],0,$GV_USERS["max_email_len"]);
      $user["url"]=substr($user["url"],0,$GV_USERS["max_url_len"]);
      $user["icq"]=substr($user["icq"],0,$GV_USERS["max_icq_len"]);
      $user["country"]=substr($user["country"],0,$GV_USERS["max_country_len"]);
      $user["city"]=substr($user["city"],0,$GV_USERS["max_city_len"]);
      $user["info"]=substr($user["info"],0,$GV_USERS["max_info_len"]);
      $user["sign"]=substr($user["sign"],0,$GV_USERS["max_sign_len"]);
      if(strlen($user["login"])<$GV_USERS["min_login_len"])$error.="<br><b>Ошибка: </b> Логин не может быть короче ".$GV_USERS["min_login_len"]." символов<br>";
      if(strlen($user["passwd"])<$GV_USERS["min_password_len"])$error.="<br><b>Ошибка: </b> Пароль не может быть короче ".$GV_USERS["min_password_len"]." символов<br>";
      if(strlen($user["nick"])<$GV_USERS["min_nick_len"])$error.="<br><b>Ошибка: </b> Ник не может быть короче ".$GV_USERS["min_nick_len"]." символов<br>";
      }

      if(!$error)
      $USR->SaveUser($user);
      else echo($error);

      if($pass_error)
       {
       ?><br>
       <strong>Пароль не изменён!</strong>(Это можно игнорировать) Это могло произойти по следующим причинам:<br>
       <li>Вы не ввели пароль (вероятно, вы не хотели его изменять).
       <li>Слишком короткий пароль. Пароль должен быть не короче 4-х символов</li>
       <li>Введённые пароли не совпадают. Вы неверно ввели подтверждение пароля!</li>
       <?
       }

      OUT("<br>сохранено!<br>");
      ?>
         <div align=center> <a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
      <?
     }
      elseif(isset($a) && $a=="avdelete")
      {
      include "config.php";
      if(file_exists($DIRS['users_avatars']."/".$CURRENT_USER['id']))
        {
          if($sure=="true")
            {
            unlink($DIRS['users_avatars']."/".$CURRENT_USER['id']);
            ?>
            <b>удалено!</b>
            <?
            }
          else
            {
            ?>
            <br>Вы уверены, что хотите удалить аватар?<br><br>
            <div align=center> <a href="<? OUT("?p=$p&act=$act&a=avdelete&sure=true") ?>">удалить аватар!</a></div>
            <?
            }

        }
        else
        {
        ?>
         У вас нет аватара!
        <?
        }
        ?>
        <Br><div align=center> <a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
        <?

      }
      elseif(isset($a) && $a=="avload")
      {
      include "config.php";
       global $GV_USERS;
       $max_av_width=$GV_USERS["max_av_width"];
       $max_av_height=$GV_USERS["max_av_height"];
       $max_av_size=$GV_USERS["max_av_size"];;
       $upl_tmpname=$avatar["tmp_name"];
       list($width, $height, $type, $attr) = getimagesize($upl_tmpname);
       $error="";
       if ($type != IMAGETYPE_GIF && $type != IMAGETYPE_JPEG)
         $error.="<br><b>Ошибка:</b> Картинка должна быть корректным GIF или JPEG изображением! <br>";
       if(!$error && (filesize($upl_tmpname)<$max_av_size*1024)&& (($width<=$max_av_width)&&($height<=$max_av_height)))
      	{
        if(file_exists($DIRS['users_avatars']."/".$CURRENT_USER['id']))unlink($DIRS['users_avatars']."/".$CURRENT_USER['id']);
	if(!copy($upl_tmpname,$DIRS['users_avatars']."/".$CURRENT_USER['id']))
	  $error="<br><b>Ошибка:</b> Не удалось скопировать файл.<br>";
        }
        elseif(!$error) $error.="<br><b>Ошибка:</b> Размер картинки не должен превышать ".$max_av_width."x".$max_av_height." пкс, а объём $max_av_size Kb<br>";
       if($error) //НЕ УДАЛОСЬ ЗАГРУЗИТЬ АВАТАР
        echo($error);

      if(!$error)OUT("<br>сохранено!<br>");
      ?>
         <div align=center> <a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
      <?
      }
      else
      {

      $sign=$FLTR->ReverseProcessText($data["signature"]);
      $info=$FLTR->ReverseProcessText($data["info"]);
      global $GV_USERS;
      ?><div align=center><b>Профиль</b></div>
       <form action="<? OUT("?p=$p&act=$act") ?>&a=save" method=post>
       <table width=100%>
         <tr>
          <td width=50% class="td_tnd_link">Идентификатор:</td>
          <td width=50% class="td_tnd_link"><? OUT($data["id"]) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Логин:</td>
          <td width=50% class="td_tnd_link"><? OUT($data["login"]) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">ФИО:</td>
          <td width=50% class="td_tnd_link"><? OUT($data["fio"]) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Дата регистрации:</td>
          <td width=50% class="td_tnd_link"><? OUT(norm_date($data["regdate"])) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Ранг:</td>
          <td width=50% class="td_tnd_link"><? OUT($data["rang"]) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Группа:</td>
          <td width=50% class="td_tnd_link"><? $gdata=$USR->GetGroupData($data["group"]); OUT($gdata["name"])  ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Пол:</td>
          <td width=50% class="td_tnd_link"><? OUT(make_gender_str($data["gender"])) ?></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Ник:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["nick"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">E-mail:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["email"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">URL:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["url"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">ICQ:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["icq"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Страна:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["country"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Город:</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=text name="new_data[]" value="<? OUT($data["city"]) ?>" style="width:100%"></td>
         </tr>
         <tr>
          <td width=50% class="td_tnd_link">Пароль(и подтверждение):</td>
          <td width=50% class="td_tnd_link"><input class=inputbox type=password name="new_data[]" style="width:100%"><br>
          <input class=inputbox type=password name="new_data[]" style="width:100%"></td>
         </tr>
       </table>
       <table width=100%>
       <tr><td class="td_tnd_link" width=100%>Подпись:
       </td></tr>
       <tr><td>
       <textarea class=inputbox name=new_data[] style="width:100%" rows=5><? OUT($sign) ?></textarea>
       </td></tr>
       <tr><td class="td_tnd_link" width=100%>Дополнительно:
       </td></tr>
       <tr><td>
       <textarea class=inputbox name=new_data[] style="width:100%" rows=5><? OUT($info) ?></textarea>
       </td></tr>
       </table>
       <div align=center><input type=submit class=button value="Сохранить"></div>
       </form>


       <table align=center width=100%>
       <tr><td width=100% align=center>
       <b>Загрузить аватар:</b>
       </td></tr>
          <? if(file_exists($DIRS["users_avatars"]."/".$data['id'])){ ?>
          <tr><td align=center>
           <img src="<? OUT($DIRS["users_avatars"]."/".$data['id']) ?>" border=0>
           <a href="<? OUT("?p=$p&act=$act&a=avdelete") ?>">удалить</a>
          </td></tr>
          <? } ?>
       <tr><td width=100% align=center>
       <form enctype="multipart/form-data" action="<? OUT("?p=$p&act=$act&a=avload") ?>" method=post>
       <input style="width:70%" name=avatar type=file class="button" accept="image/gif">
       <input class="button" value="Загрузить" type=submit style="width:20%"><br>
        Размер картинки не должен превышать <? OUT($GV_USERS["max_av_width"]) ?>x<? OUT($GV_USERS["max_av_height"]) ?> пкс, а объём <? OUT($GV_USERS["max_av_size"]) ?> Kb<br>
       </form></td></table>

      <?
      }
      break;
      default:
      // skin doesn't support this module admin, let's draw all by ourself
      // {
      $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);

      $list=$USR->GetUsersList();
      for($i=0;$i<count($list);++$i)
      {
      ?>
      <? OUT("$i: ".$list[$i]["login"]."<br>") ?>
      <?
      }
      // }

      };


      // }
      }
    else
      include(SK_DIR."/users.php");
  else
    if(!file_exists(SK_DIR."/userssbar.php"))
      {
      // skin doesn't support this module admin, let's draw all by ourself
      // {
      $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);


      $list=$USR->GetUsers();
      $week=0;$month=0;$day=0;
      for($i=0;$i<count($list);++$i)
      {
      if(time()-24*60*60<$list[$i]["regdate"])$day++;
      if(time()-24*60*60*7<$list[$i]["regdate"])$week++;
      if(time()-24*60*60*30<$list[$i]["regdate"])$month++;
      }
      $MDL->Load("smadbis");
      $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
      $inlist=$BILL->GetOnlineUsersList();
      $onlist=$USR->GetOnlineUsersList();
      ?>
      <table width=100%>
      <tr><td width=80%><font style="font-size:9px">всего пользователей:</td><td style="font-size:9px"><? OUT(count($list)) ?></td></tr>
      <tr><td width=80%><font style="font-size:9px">новых сегодня:</td><td style="font-size:9px"><? OUT($day) ?></td></tr>
      <tr><td width=80%><font style="font-size:9px">новых за неделю:</td ><td style="font-size:9px"><? OUT($week) ?></td></tr>
      <tr><td width=80%><font style="font-size:9px">новых за месяц:</td><td style="font-size:9px"><? OUT($month) ?></td></tr>
      </table><br>
      <? if($CURRENT_USER["level"]>=8){ ?><a style="font-size:10px" href="?p=user_page&act=root&id=users&mod=online"><? } ?>
      сейчас на сайте: <? OUT(count($onlist)) ?>
      <? if($CURRENT_USER["level"]>=8){ ?></a><? } ?>
      <br>
      <? if($CURRENT_USER["level"]>=5){ ?><a style="font-size:10px" href="?p=smadbis&act=online"><? } ?>
      сейчас он-лайн: <? OUT(count($inlist)) ?>
      <? if($CURRENT_USER["level"]>=5){ ?></a><? } ?>
      <?
      // }

      }
    else
      include(SK_DIR."/userssbar.php");
  }

}?>