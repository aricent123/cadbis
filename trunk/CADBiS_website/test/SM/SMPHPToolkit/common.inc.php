<?php
class type{
	const STRING = 0x0001;
	const TEXT = 0x0002;
	const DATE = 0x0003;
	const HTML = 0x0004;
	const DATE_TIME = 0x0005;
	const LINK = 0x0006;
	const ARR_OF_STR = 0x0007;
	const ARR_OF_LINK = 0x0008;
};

class spchr{
	const endl = "\r\n";
	const tab = "\t";
	const tab2 = "\t\t";
	const space = "&nbsp;&nbsp;";
};

class tags{
	const body = '<body>';
	const head = '<head>';
	const head_e = '</head>';
};


class utils{
	public static function format_value($data, $type)
	{
		switch($type)
		{
			case type::STRING:
				return $data;
			case type::TEXT:
				return $data;
			case type::DATE:
				return $data;
			case type::HTML:
				return $data;
			case type::DATE_TIME:
				return norm_date($data);
			case type::LINK:
				return make_url_str($data);
		}			
	}
	
	//-----------------------------------------------------------------------
	  // returns unix timestamp for specifyed date
	public static function makeunixtime($year,$month,$day,$hour,$min,$sec)
	   {
	   return mktime($hour,$min,$sec,$month,$day,$year);
	   }
	 
	//-----------------------------------------------------------------------
	
	  //returns size of file in format XX Mb or XX Kb or XX bytes
	 public static function make_fsize_str($size)
	   {
	   if($size<1024)return $size." bytes";
	   if($size<1024*1024) return round($size/1024,2)." Kb";
	   if($size<1024*1024*1024) return round($size/1024/1024,2)." Mb";
	   if($size<1024*1024*1024*1024) return round($size/1024/1024/1024,2)." Gb";
	   if($size<1024*1024*1024*1024*1024) return round($size/1024/1024/1024/1024,2)." Tb";
	   }
	
	//-----------------------------------------------------------------------
	  //returns full address of request
	public static function getfullurl()
	   {
	   global $HTTP_ENV_VARS,$HTTP_SERVER_VARS;
	   $request=$HTTP_SERVER_VARS["REQUEST_URI"];
	   //$request="http://".$HTTP_SERVER_VARS['SERVER_NAME'].$HTTP_SERVER_VARS['PHP_SELF']."?".$HTTP_SERVER_VARS['QUERY_STRING'];
	   $res=strpos($request,"fwdto=");
	   if($res!=FALSE)
	     {
	     $res1=substr($request,0,$res-1);
	     ++$res;
	     while($request[$res]!="&" && $res<strlen($request))$res++;
	     $res2=substr($request,$res,strlen($request)-$res);
	     $request=$res1.$res2;
	     }                                              
	   return "http://".$HTTP_SERVER_VARS['HTTP_HOST'].$request;
	   //return $request;
	   }
	      
	//-----------------------------------------------------------------------
	  
	  //forwards user to index location
	 public static function resetpage($fwdto="")
	   {
	   global $GV,$FLTR;
	   if(($fwdto) && !strstr($fwdto,"?p=user") && strstr($fwdto,"?p="))
	     {
	     $fwdto=$FLTR->ReverseProcessURL($fwdto);
	     die("<meta http-equiv='Refresh' content=\"0;URL='$fwdto'\">
	     <script>document.location.href='".$fwdto."';</script>");
	     }
	   else 
	    die("<meta http-equiv='Refresh' content=\"0;URL='".$GV["default_page"]."'\">
	    <script>document.location.href='?p=".$GV["default_page"]."';</script>");
	   }
	   
	//-----------------------------------------------------------------------
	  
	  //forwards user to page
	 public static function setpage($page)
	   {
	   die(" <meta http-equiv='Refresh' content=\"0;URL='$page'\">
	   <script>document.location.href='$page';</script>");
	   } 
	   
	//-----------------------------------------------------------------------   
	
	  //returns IP adress in string (Ex: "ip: 192.168.0.1; fwdf: n/a" )
	 public static function get_ip_address()
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
	     
	public static function get_just_ip()
	   {
	   global $_SERVER; 
	   return $_SERVER["REMOTE_ADDR"];   
	   }
	   
	//-----------------------------------------------------------------------   
	     
	public static function ip_to_just_ip($ip)
	   {
	   $ip=str_replace("ip:","",$ip);
	   $res="";
	   for($i=0;$i<strlen($ip) && $ip[$i]!=";";++$i)
	    $res.=$ip[$i];
	   return $res;
	   }
	
	//-----------------------------------------------------------------------   
	   
	  //returns names of all files in specifyed directory  
	 public static function read_dir($dir)
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
	public static function get_file_type($file)
	  {
	  $i=0;
	  while(substr($file,-$i-1,-$i)!='.' && $i<strlen($file))$i++;
	   return substr($file,-$i-1);
	  }   
	   
	//-----------------------------------------------------------------------   
	   
	 //returns filename without it's extension (Ex: file.txt, res: "file");
	public static function get_file_name($file)
	  {
	  $type=get_file_type($file);
	  return substr($file,0,-strlen($type));
	  
	  }      
	   
	//-----------------------------------------------------------------------   
	
	  //returns names of all files in specifyed directory, which have extension $ext
	public static function read_dir_ext($dir,$ext)
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
	public static function get_file($file)
	   {
	   	if(!file_exists($file))
	   		return null;
	   	return file_get_contents($file);
	   }
	
	//-----------------------------------------------------------------------
	
	  //returns serial (date&time) in format DDMMYYhhmmss
	 public static function get_serial()
		{
		$date=getdate(time());
		if($date['mday']<10)$date['mday']="0".$date['mday'];
		if($date['mon']<10)$date['mon']="0".$date['mon'];
		if($date['year']<10)$date['year']="0".$date['year'];	
		if($date['hours']<10)$date['hours']="0".$date['hours'];
		if($date['minutes']<10)$date['minutes']="0".$date['minutes'];
		if($date['seconds']<10)$date['seconds']="0".$date['seconds'];
		list($usec, $sec) = explode(" ", microtime());        	
		$date=$date['year'].$date['mon'].$date['mday'].$date['hours'].$date['minutes'].$date['seconds'].$usec;
		return $date;
		}
		
	//-----------------------------------------------------------------------
	  //returns date in format DD/MM/YY, hh:mm:ss
	public static function norm_date($time)
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
	public static function date_dmy($time)
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
	 public static function make_email_str($email)
	   {
	   $email=str_replace("style=","[HACK DETECT]",strtolower($email));
	    if($email!="")
	      return "<a href=\"mailto:".$email."\">".$email."</a>";
	    else
	     return "нет";
	   }
	 
	//----------------------------------------------------------------------- 
	
	public static function make_raiting_str($rait)
	 {
	 $res="";
	 for($i=0;$i<$rait;++$i)
	  {$res.="<img src=\"img/star.gif\">";}
	 return $res;
	 }
	
	//----------------------------------------------------------------------- 
	
	public static function make_gender_str($gender)
	 {
	    switch($gender){
	    case 0: return "женский"; 
	    case 1: return "мужской"; 
	    default:  return $gender;        
	    }; 
	 } 
	
	//-----------------------------------------------------------------------
	
	
	public static function make_icq_str($icq)
	 {
	   $icq=str_replace("style=","[HACK DETECT]",strtolower($icq));
	   $icq=str_replace("-","",strtolower($icq));
	 if($icq)
	 return "<img valign=top src='http://wwp.icq.com/scripts/online.dll?icq=".$icq."&img=5'>
	   <a href='http://wwp.icq.com/scripts/search.dll?to=".$icq."'>".$icq."</a>";
	 else
	 return "нет";
	 }
	
	//-----------------------------------------------------------------------
	
	  //returns link, such as <a href="link">link</a>
	public static function make_url_str($url)
	 {
	 $url=str_replace("style=","[HACK DETECT]",strtolower($url));
	 if($url!="")
	   {
	   if(strtolower(substr($url,0,7))!="http://")
	     return "<a href=\"http://$url\">$url</a>";
	   else
	     return "<a href=\"$url\">$url</a>";
	   }
	 return "нет"; 
	 }
	
	//-----------------------------------------------------------------------
	
	 //replaces all links by highreferences
	public static function make_links($string)
	 {
	 $string = ereg_replace("[[:alpha:]]+://[^<>[:space:]]+[[:alnum:]/]",
	                     "<a href=\"\\0\">\\0</a>", $string);
	 return $string;
	 }	
	 
	//-----------------------------------------------------------------------
		 
    function cp2utf($str,$isTo = true)
    {
        $arr = array('ё'=>'&#x451;','Ё'=>'&#x401;');
        for($i=192;$i<256;$i++)
            $arr[chr($i)] = '&#x4'.dechex($i-176).';';
        $str =preg_replace(array('@([а-я]) @i','@ ([а-я])@i'),array('$1&#x0a0;','&#x0a0;$1'),$str);
        return strtr($str,$isTo?$arr:array_flip($arr));
    }	 
	 
};

////////////////////////////////////////////////////
/**
 * Type Formatter
 */
class basic_formatter{
	public function format($data, $type)
	{
		return utils::format_value($data, $type);
	}
};

class cacher{
	protected $content;
	public function get_content()
	{
		return $this->content;
	}
	
	public function set_content($value)
	{
	 	$this->content = $value;
	}
};

class sorting{
	const DEFAULT_SORT = 'default';
	const SORT_DIR_DESC = 'desc';
	const SORT_DIR_ASC = 'asc';
	const SORT_DIR_DEFAULT = sorting::SORT_DIR_ASC;
}
