<?php

 //.............................................................//
//.........................FUNCTIONS...........................//
//.............................................................//

//-----------------------------------------------------------------------
  // returns user data by his id
  function is_user_exists($usr_id)
   {
   global $MDL,$DIRS,$GV;
   if( $MDL->IsModuleExists("users"))
    {
    $MDL->Load("users");
    $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
    $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
    if(!$USR->IsUserExistsById($usr_id))return false;
    $data=$USR->GetUserData($usr_id);
    return true;
    }
   return false; 
   }

//-----------------------------------------------------------------------
  // returns user data by his id
  function get_user_data($usr_id)
   {
   global $MDL,$DIRS,$GV;
   if( $MDL->IsModuleExists("users"))
    {
    $MDL->Load("users");
    $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
    $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
    if(!$USR->IsUserExistsById($usr_id))return array("nick"=>$usr_id);
    $data=$USR->GetUserData($usr_id);
    $data["level"]=$USR->GetUserLevel($usr_id);
    return $data;
    }
   return $usr_id;   
   }
//-----------------------------------------------------------------------
  // returns unix timestamp for specifyed date
  function makeunixtime($year,$month,$day,$hour,$min,$sec)
   {
   return mktime($hour,$min,$sec,$month,$day,$year);
   }
 
//-----------------------------------------------------------------------

  //returns size of file in format XX Mb or XX Kb or XX bytes
  function make_fsize_str($size)
   {
   if($size<1024)return $size." bytes";
   if($size<1024*1024) return round($size/1024,2)." Kb";
   if($size<1024*1024*1024) return round($size/1024/1024,2)." Mb";
   if($size<1024*1024*1024*1024) return round($size/1024/1024/1024,2)." Gb";
   if($size<1024*1024*1024*1024*1024) return round($size/1024/1024/1024/1024,2)." Tb";
   }

//-----------------------------------------------------------------------
  //returns full address of request
  function getfullurl()
   {
   global $HTTP_ENV_VARS;
   return "http://".$HTTP_ENV_VARS["SERVER_ADDR"].$HTTP_ENV_VARS["REQUEST_URI"];
   }
   
//-----------------------------------------------------------------------
  
  //forwards user to index location
  function resetpage()
   {
   global $GV;
   //header("Location: "."http://".$HTTP_ENV_VARS["SERVER_ADDR"].$HTTP_ENV_VARS["REQUEST_URI"]);   
   die("<script>document.location.href='?p=".$GV["default_page"]."';</script>");
   }
   
//-----------------------------------------------------------------------
  
  //forwards user to page
  function setpage($page)
   {
   die("<script>document.location.href='$page';</script>");
   } 
   
//-----------------------------------------------------------------------   

  //returns IP adress in string (Ex: "ip: 192.168.0.1; fwdf: n/a" )
  function get_ip_address()
   {
   global $_SERVER,$HTTP_X_FORWARDED_FOR; 
   if(!isset($HTTP_X_FORWARDED_FOR))$HTTP_X_FORWARDED_FOR=NULL;
   if(!isset($_SERVER["HTTP_X_FORWARDED_FOR"]))$_SERVER["HTTP_X_FORWARDED_FOR"]=NULL;
   
   $xfor=$_SERVER["HTTP_X_FORWARDED_FOR"];
   if(!$xfor)$xfor=$HTTP_X_FORWARDED_FOR;
   if(!$xfor)$xfor="n/a";
   return "ip:".$_SERVER["REMOTE_ADDR"]."; fwdf:".$xfor;
   }

//-----------------------------------------------------------------------   
     
  function get_just_ip()
   {
   global $_SERVER; 
   return $_SERVER["REMOTE_ADDR"];   
   }
   
//-----------------------------------------------------------------------   
     
  function ip_to_just_ip($ip)
   {
   $ip=str_replace("ip:","",$ip);
   $res="";
   for($i=0;$i<strlen($ip) && $ip[$i]!=";";++$i)
    $res.=$ip[$i];
   return $res;
   }

//-----------------------------------------------------------------------   
   
  //returns names of all files in specifyed directory  
  function read_dir($dir)
   {
   $files=array();
   $dirct=opendir($dir);
   while($file=readdir($dirct))
	{
	if(($file!=".")&&($file!="..")&&is_file($dir."/".$file))
		{
		$files[]=$file;
		}
	}
   return $files;
   }

//-----------------------------------------------------------------------   
   
 //returns extension of file (Ex: file.txt, res: ".txt")
 function get_file_type($file)
  {
  $i=0;
  while(substr($file,-$i-1,-$i)!='.')$i++;
   return substr($file,-$i-1);
  }   
   
//-----------------------------------------------------------------------   
   
 //returns filename without it's extension (Ex: file.txt, res: "file");
 function get_file_name($file)
  {
  $type=get_file_type($file);
  return substr($file,0,-strlen($type));
  
  }      
   
//-----------------------------------------------------------------------   

  //returns names of all files in specifyed directory, which have extension $ext
  function read_dir_ext($dir,$ext)
   {
   $files=array();
   $dirct=opendir($dir);
   while($file=readdir($dirct))
	{
	if(($file!=".")&&($file!="..")&&is_file($dir."/".$file) && get_file_type($file)==$ext)
		{
		$files[]=$file;
		}
	}
   return $files;
   }   
   
//-----------------------------------------------------------------------   

  //returns content of file
  function get_file($file)
   {
    $file=file($file);
    return implode($file,"");
   }

//-----------------------------------------------------------------------

  //returns serial (date&time) in format DDMMYYhhmmss
  function get_serial()
	{
	$date=getdate(time());
	if($date['mday']<10)$date['mday']="0".$date['mday'];
	if($date['mon']<10)$date['mon']="0".$date['mon'];
	if($date['year']<10)$date['year']="0".$date['year'];	
	if($date['hours']<10)$date['hours']="0".$date['hours'];
	if($date['minutes']<10)$date['minutes']="0".$date['minutes'];
	if($date['seconds']<10)$date['seconds']="0".$date['seconds'];        	
	$date=$date['mday'].$date['mon'].$date['year'].$date['hours'].$date['minutes'].$date['seconds'];
	return $date;
	}
	
//-----------------------------------------------------------------------
  //returns date in format DD/MM/YY, hh:mm:ss
  function norm_date($time)
	{
	$date=getdate($time);
	if($date['mday']<10)$date['mday']="0".$date['mday'];
	if($date['mon']<10)$date['mon']="0".$date['mon'];
	if($date['year']<10)$date['year']="0".$date['year'];	
	if($date['hours']<10)$date['hours']="0".$date['hours'];
	if($date['minutes']<10)$date['minutes']="0".$date['minutes'];
	if($date['seconds']<10)$date['seconds']="0".$date['seconds'];        	
	$date=$date['mday']."/".$date['mon']."/".$date['year'].", ".$date['hours'].":".$date['minutes'].":".$date['seconds'];
	return $date;
	}
	
//-----------------------------------------------------------------------	
  //returns date in format DD/MM/YY
  function date_dmy($time)
        {
	$date=getdate($time);
	if($date['mday']<10)$date['mday']="0".$date['mday'];
	if($date['mon']<10)$date['mon']="0".$date['mon'];
	if($date['year']<10)$date['year']="0".$date['year'];	
	$date=$date['mday']."/".$date['mon']."/".$date['year'];
	return $date;
	}
	
//-----------------------------------------------------------------------
 
  //returns link, such <a href="mailto:myname@myorg.myzone">myemail</a>
  function make_email_str($email)
   {
   $email=str_replace("style=","[HACK DETECT]",strtolower($email));
    if($email!="")
      return "<a href=\"mailto:".$email."\">".$email."</a>";
    else
     return "нет";
   }
 
//----------------------------------------------------------------------- 

function make_raiting_str($rait)
 {
 $res="";
 for($i=0;$i<$rait;++$i)
  {$res.="<img src=\"img/star.gif\">";}
 return $res;
 }

//----------------------------------------------------------------------- 

function make_gender_str($gender)
 {
    switch($gender){
    case 0: return "женский"; 
    case 1: return "мужской"; 
    default:  return $gender;        
    }; 
 } 

//-----------------------------------------------------------------------


function make_icq_str($icq)
 {
   $icq=str_replace("style=","[HACK DETECT]",strtolower($icq));
 if($icq)
 return "<img valign=top src='http://wwp.icq.com/scripts/online.dll?icq=".$icq."&img=5'>
   <a href='http://wwp.icq.com/scripts/search.dll?to=".$icq."'>".$icq."</a>";
 else
 return "нет";
 }

//-----------------------------------------------------------------------

  //returns link, such as <a href="link">link</a>
function make_url_str($url,$newwindow=false)
 {
 $url=str_replace("style=","[HACK DETECT]",strtolower($url));
 if($url!="")
   {
   if(strtolower(substr($url,0,7))!="http://")
     return "<a ".(($newwindow)?"target=_blank":"")." href=\"http://$url\">$url</a>";
   else
     return "<a ".(($newwindow)?"target=_blank":"")." href=\"$url\">$url</a>";
   }
 return "нет"; 
 }

//-----------------------------------------------------------------------

 //replaces all links by highreferences
function make_links($string)
 {
 $string = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",
                     "<a href=\"\\0\">\\0</a>", $string);
 return $string;
 }	

//-----------------------------------------------------------------------
 
 //just echo string
function OUT($str)
 {
 echo $str;
 }

 //just alert string
 function trace($str){
   echo "<script>alert('".$str."')</script>";
 }
 
 
 

 
/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//*************************** timer ***********************************//
/////////////////////////////////////////////////////////////////////////
class CTimer 
 {
 var $startTime;
 var $endTime;
 function start() 
  {
  $this->startTime = gettimeofday();
  }
 function stop()         
  {
  $this->endTime = gettimeofday();
  }
 function elapsed() 
  {
  return (($this->endTime["sec"] - $this->startTime["sec"]) * 1000000 + ($this->endTime["usec"] - $this->startTime["usec"])) / 1000000;
  }
 };
/////////////////////////////////////////////////////////////////////////

//************************** modules **********************************//
/////////////////////////////////////////////////////////////////////////
class CModules 
 { 
 //-----------------------------------------------------------------------  
  function Load($str)
  {
  //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$page,$type,$id,$act,$topic,$art,$act,$a,$p; 
    if(file_exists($GV["modules_dir"]."/".$str.$GV["module_ext"]))
     {
     $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
     include($GV["modules_dir"]."/".$str.$GV["module_ext"]); 
     }
     else
       OUT("<p><b>Warning!</b> module '$str' not found!</p>");     
  }
 //-----------------------------------------------------------------------
 function LoadModule($str,$bar=0)
  {
  //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$page,$type,$id,$act,$topic,$art,$act,$a,$p; 
      
   //this is full enough, but there's more risk of hack.
   //extract($GLOBALS); 
 
     $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
   $_MODULE=1;
   //root has individual pages:
   if(_isroot())
    switch($str)
     {
     case "user_menu": include "root/menu.php";  break;
     case "user_page": include "root/page.php"; break;
     };

    if($str=="user_menu" && $this->IsModuleExists('users'))
    {
    if(_isrootdef())return;
    $_MENU=true;
    if(_isroot())OUT("<br>");
    include($GV["modules_dir"]."/users".$GV["module_ext"]);
    $_MENU=false;
    return;
    }
    elseif($str=="user_page")
    {
    if(_isrootdef())return;
    if(_isroot())return;
    include($GV["modules_dir"]."/users".$GV["module_ext"]);
    return;
    }
    
    if(file_exists($GV["modules_dir"]."/".$str.$GV["module_ext"]))
     {
     $_NOTBAR=true;
     if($bar){$_NOTBAR=false;} 
     include($GV["modules_dir"]."/".$str.$GV["module_ext"]); 
     }
     else
       OUT("<p><b>Warning!</b> module '$str' not found!</p>");
    $_MODULE=false;
  }
 //----------------------------------------------------------------------- 
 function LoadMenu($str,$bar=0)
  {
  //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$page,$type,$id,$act,$topic,$art,$act,$a,$p; 
      
   //this is full enough, but there's more risk of hack.
   //extract($GLOBALS); 
 
     $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
    $_MENU=true;
    if(file_exists($GV["modules_dir"]."/".$str.$GV["module_ext"]))
     {    
    include($GV["modules_dir"]."/$str".$GV["module_ext"]);
    $_MENU=false;
     }
     else
       OUT("<p><b>Warning!</b> module '$str' not found!</p>");
    $_MODULE=false;
  }
  
 //----------------------------------------------------------------------- 
 function LoadAdminPage($mdl)
  {
  //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$page,$type,$id,$act,$topic,$art,$act,$a,$p; 
   
     $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
   if(!check_auth())
    {
    $page="403";
    $this->LoadModule("error",false);
    return;
    }

   if(file_exists($GV["modules_dir"]."/".$mdl.$GV["module_ext"]))
   {
   $_ADMIN=true;
   include($GV["modules_dir"]."/".$mdl.$GV["module_ext"]); 
   $_ADMIN=false;  
   }
   else
    OUT("<p><b>Warning!</b> module '$mdl' not found!</p>");   
  } 
 //-----------------------------------------------------------------------  
 function IsModuleExists($str)
  {
  global $GV;
  return file_exists($GV["modules_dir"]."/".$str.$GV["module_ext"]);
  }     
 //-----------------------------------------------------------------------  
 function GetModuleInfo($mdl)
  {
  global $GV;
    $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
    $_GETINFO=1;
    include $GV["modules_dir"]."/".$mdl.$GV["module_ext"];
    $_GETINFO=0;     
  $info=array();
  $info["page"]= $mdl;    
  $info["title"]=$MDL_TITLE;
  $info["descr"]=$MDL_DESCR;
  $info["id"]=   $MDL_UNIQUEID;    
  $info["maker"]=$MDL_MAKER;
  return $info;  
  }
 //-----------------------------------------------------------------------  
 function GetInstalledModules()
  {
   global $INDMDS,$GV;  
   $info=array(array());
   for($i=0;$i<count($INDMDS);++$i)
    {
    $_GETINFO=1;
    include $GV["modules_dir"]."/".$INDMDS[$i].$GV["module_ext"];
    $_GETINFO=0;     
    $info[$i]["page"]= $INDMDS[$i];    
    $info[$i]["title"]=$MDL_TITLE;
    $info[$i]["descr"]=$MDL_DESCR;
    $info[$i]["id"]=   $MDL_UNIQUEID;    
    $info[$i]["maker"]=$MDL_MAKER;                    
    }  
   return $info;
   return array();
  }  
 //----------------------------------------------------------------------- 
 function GetNotInstalledModulesList()
  {
   global $GV;
  $ml=$this->GetModulesList();
  $res=array();
  for($i=0;$i<count($ml);++$i)
   if(!$this->IsModuleInstalled($ml[$i]))$res[]=$ml[$i];
  return $res;
  }
 //-----------------------------------------------------------------------
 function GetInstalledModulesList()
  {
   require("conf/modules.php");
   return $INDMDS;
  }    
 //----------------------------------------------------------------------- 
 function GetModules()
  {
   global $GV;
   $info=array(array());
   $list=read_dir_ext($GV["modules_dir"],$GV["module_ext"]);
   for($i=0;$i<count($list);++$i)
    {
    $_GETINFO=1;
    include $GV["modules_dir"]."/".$list[$i];
    $_GETINFO=0;     
    $info[$i]["page"]= get_file_name($list[$i]);    
    $info[$i]["title"]=$MDL_TITLE;
    $info[$i]["descr"]=$MDL_DESCR;
    $info[$i]["id"]=   $MDL_UNIQUEID;    
    $info[$i]["maker"]=$MDL_MAKER;                    
    }  
   return $info;
  }
 //----------------------------------------------------------------------- 
 function IsModuleInstalled($mdl)
  {
  global $INDMDS;
  foreach($INDMDS as $md){if($md==$mdl) return true;}
  return false; 
  }
 //----------------------------------------------------------------------- 
 function GetModulesList()
  {
   global $GV;
  $list=read_dir_ext($GV["modules_dir"],$GV["module_ext"]);
  for($i=0;$i<count($list);++$i)$list[$i]=get_file_name($list[$i]);
  return $list; 
  }
 //----------------------------------------------------------------------- 
 function ConfigureModule($mdl)
  {
   //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$type,$id,$act,$topic,$art; 
   $_NOTBAR=false; $_CONFIGURE=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
   if(file_exists($GV["modules_dir"]."/".$mdl.$GV["module_ext"]))
   {
   $_CONFIGURE=true;
   include($GV["modules_dir"]."/".$mdl.$GV["module_ext"]); 
   $_CONFIGURE=false; 
   }
   else
    OUT("<p><b>Warning!</b> module '$mdl' not found!</p>");     
  }  
 //----------------------------------------------------------------------- 
 function InstallModule($mdl)
  {
   //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$type,$id,$act,$topic,$art; 
   $_NOTBAR=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
   if(file_exists($GV["modules_dir"]."/".$mdl.$GV["module_ext"]))
   {
   $_INSTALL=true;
   include($GV["modules_dir"]."/".$mdl.$GV["module_ext"]); 
   $_INSTALL=false; 
   if($this->IsModuleInstalled($mdl))return;
   require ("conf/modules.php");
   $res="<?php\r\n";
   for($i=0;$i<count($INDMDS);++$i)
     $res.="\$INDMDS[$i]=\"".$INDMDS[$i]."\";\r\n";
   $res.="\$INDMDS[".count($INDMDS)."]=\"$mdl\";\r\n";
   $res.="?>";
   $fp=fopen("conf/modules.php","w+");
   fwrite($fp,$res);
   fclose($fp); 
   }
   else
    OUT("<p><b>Warning!</b> module '$mdl' not found!</p>");     
  }
 //-----------------------------------------------------------------------
 function UninstallModule($mdl)
  {
   //this are the trusted globals:
   global $GV,$MDL,$DIRS,$ERR,$type,$id,$act,$topic,$art; 
   $_NOTBAR=false;$_ADMIN=false;$_MENU=false;$_MODULE=false;$_INSTALL=false;$_UNINSTALL=false;$_GETINFO=false;
   if(file_exists($GV["modules_dir"]."/".$mdl.$GV["module_ext"]))
   {
   $_UNINSTALL=true;
   include($GV["modules_dir"]."/".$mdl.$GV["module_ext"]); 
   $_UNINSTALL=false; 
   if(!$this->IsModuleInstalled($mdl))return;
   require ("conf/modules.php");
   $res="<?php\r\n";
   $k=0;
   for($i=0;$i<count($INDMDS);++$i)
     if($INDMDS[$i]!=$mdl)
       {
       $res.="\$INDMDS[$i]=\"".$INDMDS[$i]."\";\r\n";
       $k++;
       }
   $res.="?>";
   $fp=fopen("conf/modules.php","w+");
   fwrite($fp,$res);
   fclose($fp);     
   }
   else
    OUT("<p><b>Warning!</b> module '$mdl' not found!</p>");     
  }
 };
/////////////////////////////////////////////////////////////////////////

//************************** errors ***********************************//
/////////////////////////////////////////////////////////////////////////
class CErrors 
 { 
  function Critical($str)
   {
    die("<div align=center style=\"font-size:34px;font-weight:900px;font-color:red;\"><b>Error:</b>$str</div>");
   } 

  function Warning($str)
   {
    echo("<div align=center style=\"font-size:20px;font-weight:500px;\"><b>Warning:</b>$str</div>");
   }
  function Forbidden($str)
   {
   global $MDL;
   $page="403";
   if($MDL->IsModuleExists("error"))
    $MDL->LoadModule("error","false");
   else
     OUT("<BIG><br>This page is denied for you!</BIG><br>Cause: '$str';<br>");
   exit;    
   }
 
 }; 
/////////////////////////////////////////////////////////////////////////////

//************************  page divider ******************************//
/////////////////////////////////////////////////////////////////////////
class CPageDivider 
 { 
 var $POP;
 protected $count = 0;
 protected $current = 0;
 
 //-----------------------------------------------------------------------
 function CPageDivider($POP)
   {$this->POP=$POP;}
 //-----------------------------------------------------------------------
 function SetPostsOnPage($POP)
   {$this->POP=$POP;}
 //-----------------------------------------------------------------------
 function GetPage($posts,$pnum)
   {
   if(!count($posts))
   	 return NULL;
   	$this->current = $pnum;
   	$this->count = count($posts);
   //rsort($posts);
    $ptot=ceil(count($posts)/$this->POP);
    if($pnum>$ptot)
      {$pnum=$ptot;}
    if($pnum<=0)$pnum=1;
    $res=array();
    //echo("<br>".$ptot."<br>".$pnum."<br>".($pnum-1)*$this->POP."<br>".$pnum*$this->POP);
    for($i=($pnum-1)*$this->POP;$i<count($posts) && $i<$pnum*$this->POP;++$i)
     {$res[]=$posts[$i];}
    return $res;
   }
 //-----------------------------------------------------------------------     
 function GetPagesCount($posts = null)
   {
   return ceil($this->count/$this->POP);
   }
 //-----------------------------------------------------------------------
 public function GetCurrentPageNum()
 {
 	return $this->current;
 }
 //-----------------------------------------------------------------------     
 function GetPOP()
   {
   return $this->POP;
   }
     
 }; 
/////////////////////////////////////////////////////////////////////////////

//*************************** filtration ******************************//
/////////////////////////////////////////////////////////////////////////
class CFiltration 
 { 
 //-----------------------------------------------------------------------
 function KillTags($text)
  {
  return strip_tags($text);    
  }
 //----------------------------------------------------------------------- 
 function KillSpecialChars($text)
  {
  } 
 //-----------------------------------------------------------------------  
 function ProcessString($text)
  {
  } 
 //----------------------------------------------------------------------- 
 function DirectProcessString($str,$kt=1)
  {
  global $GV;
  if($kt)//kill tags?
    {
    $str=htmlspecialchars($str);
    $str=strip_tags($str);
    }
  $str=str_replace($GV["sep1"],"",$str);
  $str=str_replace($GV["sep2"],"",$str);
  $str=str_replace($GV["sep3"],"",$str); 
  $str=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($str)))));
  return $str;  
  }
 //-----------------------------------------------------------------------   
 function DirectProcessText($str,$nb=1,$kt=1,$ml=0)
  {
  global $GV;
  if($kt)//kill tags?
    {
    $str=htmlspecialchars($str);
    $str=strip_tags($str);
    }
  if($nb)//new line to <br>
    {
    $str=str_replace("\n","",$str);
    $str=nl2br($str);
    }
  if($ml)
    {
    $str=make_links($str);
    }
  $str=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($str)))));
  $str=str_replace($GV["sep1"],"",$str);
  $str=str_replace($GV["sep2"],"",$str);
  $str=str_replace($GV["sep3"],"",$str); 
  return $str;  
  }
 //-----------------------------------------------------------------------   
 function DirectProcessHTML($str)
  {
  global $GV;
  $str=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($str)))));
  $str=str_replace($GV["sep1"],"",$str);
  $str=str_replace($GV["sep2"],"",$str);
  $str=str_replace($GV["sep3"],"",$str); 
  return $str;  
  }
 //----------------------------------------------------------------------- 
 function ReverseProcessString($str)
  {
  $str=strip_tags($str);
  $str=str_replace("\&amp;quot;","\"",$str);
  $str=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($str)))));
  return $str;
  }    
 //----------------------------------------------------------------------- 
 function ReverseProcessText($text)
  {
  $text=ereg_replace(" +"," ",trim(stripslashes(stripslashes(addslashes($text)))));  
  return strip_tags($text);
  }                       
 //----------------------------------------------------------------------- 
 function CutString($text,$type)
  {
  switch($type)
   {
   case "article": $text=substr($text,0,12000);break;
   case "title": $text=substr($text,0,125);break;
   case "description": $text=substr($text,0,300);break;
   case "login": $text=substr($text,0,35);break; 
   case "id": $text=substr($text,0,15);break; 
   case "number": $text=substr($text,0,10);break; 
   //etc...
   };
  return $text;
  }
  
     
 }; 
/////////////////////////////////////////////////////////////////////////////
 
 
 //............................................................//
//......................FUNCTIONS AGAIN........................//
//.............................................................//
// AUTENTIFICATION CODE
//{
/////////////////////////////////////////////////////
function _logout()
{ 
  global $MDL,$DIRS,$GV,$CURRENT_USER,$_SESSION;
 if($MDL->IsModuleExists('users') ){
 $MDL->Load("users"); 
 $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
 $USR->SetSeparators($GV["sep1"],$GV["sep2"]); 
 }
 else $USR=NULL;
 
 $_SESSION["login"]="";
 session_unregister("login");
 if($USR)
   {
   $CURRENT_USER["ip"]=get_ip_address();
   $USR->SetOffline($CURRENT_USER);
   }
} 
/////////////////////////////////////////////////////
function _login($login,$passwd)
{
  global $MDL,$DIRS,$GV,$CURRENT_USER,$_SESSION,$_root_login,$_root_passwd;  
 if($MDL->IsModuleExists('users') ){
 $MDL->Load("users"); 
 $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
 $USR->SetSeparators($GV["sep1"],$GV["sep2"]); 
 }
 else $USR=NULL;
   if($_root_login==$login && $_root_passwd==md5($passwd))
     {  
     $_SESSION["login"]="$login";
     $_SESSION["passwd"]="$passwd";   
     $_SESSION["rootacc"]=true;
     $_SESSION["defroot"]=true;
     $CURRENT_USER["ip"]=get_ip_address();
     $USR->SetOffline($CURRENT_USER);   
     return true;
     }
     elseif($USR)
     {
     if($USR->CheckAuth($login,$passwd))
       {
       $_SESSION["login"]="$login";
       $_SESSION["passwd"]="$passwd";
       $_SESSION["defroot"]=false;       
       $usr_data=$USR->GetUserData($USR->GetUserId($login));
       //this user also have root access
       if($usr_data["level"]>=10)$_SESSION["rootacc"]=true;
       $CURRENT_USER["ip"]=get_ip_address();
       $USR->SetOffline($CURRENT_USER);       
       return true;
       }
     }
  return false;
}
/////////////////////////////////////////////////////
function _isroot()
 {
 global $_SESSION;
 return (isset($_SESSION["rootacc"]) && $_SESSION["rootacc"]!="");
 }
/////////////////////////////////////////////////////
function _isrootdef()
 {
 global $_SESSION;
 return (isset($_SESSION["defroot"]) && $_SESSION["defroot"]!="");
 }
/////////////////////////////////////////////////////
function _logoutroot()
 {
 global $_SESSION,$p;
 $_SESSION["rootacc"]=false;
 _logout();
 resetpage();
 }
///////////////////////////////////////////////////// 
function check_auth()
 {
 global $_SESSION;
 return(isset($_SESSION["login"]) && $_SESSION["login"]!="");
 }
/////////////////////////////////////////////////////
//}
// AUTHENTIFICATION CODE
function authenticate()
{
 global $MDL,$DIRS,$GV,$CURRENT_USER,$_SESSION;
 if($MDL->IsModuleExists('users') ){
 $MDL->Load("users"); 
 $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
 $USR->SetSeparators($GV["sep1"],$GV["sep2"]); 
 }
 else $USR=NULL;

 if(check_auth() && $USR && !_isrootdef())
 {
 $CURRENT_USER["login"]=$_SESSION["login"];
 $CURRENT_USER["passwd"]=$_SESSION["passwd"];
 $CURRENT_USER["id"]=$USR->GetUserId($_SESSION["login"]);
 $data=$USR->GetUserData($CURRENT_USER["id"]);
 $CURRENT_USER["nick"]=$data["nick"];
 $CURRENT_USER["email"]=$data["email"];
 $CURRENT_USER["url"]=$data["url"]; 
 $CURRENT_USER["level"]=$USR->GetUserLevel($CURRENT_USER["id"]);
 if($CURRENT_USER["level"]>=8)$_SESSION["rootacc"]=true;
 else $_SESSION["rootacc"]=false;         
 }
 elseif(_isrootdef())
  {
  $CURRENT_USER["level"]=10;
  $CURRENT_USER["nick"]=$GV["site_owner"];
  $CURRENT_USER["id"]="!ROOT!";
  }
 else
  {
  $CURRENT_USER["level"]=0;  
  $CURRENT_USER["id"]="!GUEST!";
  } 
  $CURRENT_USER["ip"]=get_ip_address();
  $CURRENT_USER["browser"]=$_SERVER['HTTP_USER_AGENT'];
if($USR)
 {
 $MDL->Load("users");
 $USR->UpdateOnline($CURRENT_USER);
 }  
}
//} 
///////////////////////////////////////////////////// 
 
?>
