<?php
global $_funcsPHP;
$_funcsPHP="defined";

$months=array("январь","февраль","март","апрель","май","июнь","июль","август","сентябрь","октябрь","ноябрь","декабрь");
$monthsof=array("января","февраля","марта","апреля","мая","июня","июля","августа","сентября","октября","ноября","декабря");
$wdaysto=array("понедельник","вторник","среду","четверг","пятницу","субботу","воскресенье");
$wdays=array("понедельник","вторник","среда","четверг","пятница","суббота","воскресенье");

$alphabet=array('а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п','р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я');
$alphabet_tr=array('a','b','v','g','d','e','yo','j','z','i','i','k','l','m','n','o','p','r','s','t','u','f','h','c','ch','sh','sch','\'','y','\'','e','yu','ya');


function array_max_value($arr)
{
$res = -999999999999.0;
foreach($arr as $el)
  if($el>$res)
    $res = $el;
return $res;
}

 function _urls_sort_functorURL_asc($a,$b)
  {
  if($a['url']>$b['url'])return 1;
  return ($a['url']==$b['url'])?0:-1;
  }
 function _urls_sort_functorURL_desc($a,$b)
  {
  if($a['url']>$b['url'])return -1;
  return ($a['url']==$b['url'])?0:1;
  }
 function _urls_sort_functorDATE_asc($a,$b)
  {
  if($a['date']>$b['date'])return 1;
  return ($a['date']==$b['date'])?0:-1;
  }
 function _urls_sort_functorDATE_desc($a,$b)
  {
  if($a['date']>$b['date'])return -1;
  return ($a['date']==$b['date'])?0:1;
  }
 function _urls_sort_functorLEN_asc($a,$b)
  {
  if($a['length']>$b['length'])return 1;
  return ($a['length']==$b['length'])?0:-1;
  }
 function _urls_sort_functorLEN_desc($a,$b)
  {
  if($a['length']>$b['length'])return -1;
  return ($a['length']==$b['length'])?0:1;
  }
 function _urls_sort_functorCNT_asc($a,$b)
  {
  if($a['count']>$b['count'])return 1;
  return ($a['count']==$b['count'])?0:-1;
  }
 function _urls_sort_functorCNT_desc($a,$b)
  {
  if($a['count']>$b['count'])return -1;
  return ($a['count']==$b['count'])?0:1;
  }
 function _urls_sort_functorUCNT_asc($a,$b)
  {
  if($a['ucount']>$b['ucount'])return 1;
  return ($a['count']==$b['ucount'])?0:-1;
  }
 function _urls_sort_functorUCNT_desc($a,$b)
  {
  if($a['ucount']>$b['ucount'])return -1;
  return ($a['ucount']==$b['ucount'])?0:1;
  }
  
 function template_header_sort($paramstr,$sort,$fname,$ftitle)
 {
    global $p,$act,$action,$draw;

	if($act=="smadbisrept")
		{OUT($ftitle);return;}
	?>
    <a href="<? ($sort==">$fname")?$ssort="<$fname":$ssort=">$fname"; OUT("?p=$p&act=$act&action=$action&draw=$draw&$paramstr&sort=$ssort") ?>"><?=$ftitle?></a>
 	<?
    	if($sort=="<$fname")
    		OUT("<img src=\"".SK_DIR."/img/asc.gif\">");
    	elseif($sort==">$fname")
    		OUT("<img src=\"".SK_DIR."/img/desc.gif\">");
 }


function accts_compare_traffic_desc($a, $b) {
    if($a["traffic"]==$b["traffic"])
        return 0;
    return ($a["traffic"] > $b["traffic"]) ? -1 : 1;
}

function accts_compare_traffic_asc($a, $b) {
    if($a["traffic"]==$b["traffic"])
        return 0;
    return ($a["traffic"] > $b["traffic"]) ? 1 : -1;
}

function accts_compare_time_asc($a, $b) {
    if($a["time"]==$b["time"])
        return 0;
    return ($a["time"] > $b["time"]) ? 1 : -1;
}

function accts_compare_time_desc($a, $b) {
    if($a["time"]==$b["time"])
        return 0;
    return ($a["time"] > $b["time"]) ? -1 : 1;
}

function transliterate($str)
 {
  global $alphabet,$alphabet_tr;
  $str=strtolower($str);
  return str_replace($alphabet,$alphabet_tr,$str);
 }

function is_gid_allowed($gid,$BILLEVEL)
 {
 global $GV,$CURRENT_USER;
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
 $data=$BILL->GetTarifData($gid);
 if(!$data)return true;
 return($data[level]<=$BILLEVEL);
 }

function is_group_allowed($group,$uid)
 {
 global $GV,$CURRENT_USER,$MDL,$DIRS;
 if(_isroot())return true;
 $MDL->Load("users");
 $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
 $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
 $gdata=$USR->GetGroupData($group);
 return($CURRENT_USER["level"]>=$gdata["level"] || $CURRENT_USER["id"]==$uid);
 }



function get_current_week(&$b,&$a,$withh)
 {
        $year=date("Y");
        $month=date("m");
        $day=date("d");
        $wday=date("w");
        if($wday==0)$wday=7;
        $bwday=(int)$day-(int)$wday+1;
        $byear=$year;
        $bmonth=$month;
        if($bwday<=0)
          if((int)$month<2)
            {
            $bmonth=12;
            $bwday+=date("d",mktime(0,0,0,0,0,$byear--));
            }
            else
            $bwday+=date("d",mktime(0,0,0,$bmonth--,0,$byear));
        if($bwday<10)$bwday="0".$bwday;
        $str=($withh)?" 00:00:00":"";
        $bdate=$byear."-".$bmonth."-".($bwday).$str;
        $str=($withh)?date(" H:i:s"):"";
        $adate=$year."-".$month."-".($day).$str;
 $b=$bdate;
 $a=$adate;
 }

function getbillevel($usr_level)
 {
 $BILLEVEL=0;
 if($usr_level>6)return 5;
 else $BILLEVEL=$usr_level-2;
 if($BILLEVEL<0)$BILLEVEL=0;
 return $BILLEVEL;
 }

function timeinsec($hour,$min,$sec)
 {
 return $hour*3600+$min*60+$sec;
 }

function mb2bytes($mbs)
 {
 return $mbs*1024*1024;
 }

function bytes2mb($bts,$q=0)
 {
 return round($bts/1024/1024,$q);
 }

function bytes2gb($bts)
 {
 return round($bts/1024/1024/1024,2);
 }

function bytes2kb($bts,$q=0)
 {
 return round($bts/1024,$q);
 }


function addlzeroes($time)
 {
 if(strlen($time)<2)return "0".$time;
 //if(strlen($time)>2)return substr(0,2,$time);
 return $time;
 }

 /**
  * Generates the URL to new cadbis page
  *
  * @param string $action
  */
 function cadbisnewurl($action)
 {
 	return "?act=noskin&page=smadbis&noskinact=tarifs&action=cadbisnew&newact=".$action;
 }
 
 
function gethours($time)
 {
 return addlzeroes("".floor((float)$time/3600.0));
 }

function getmins($time)
 {
 return addlzeroes(floor($time/60.0-gethours((float)$time)*60.0));
 }

function getsecs($time)
 {
 return addlzeroes(floor((float)$time-(float)getmins($time)*60.0-gethours((float)$time)*3600.0));
 }

 function makelogintimestr($times_d,$times_hf,$times_ht,$times_mf,$times_mt)
 {
 $logintime="";
 for($i=0;$i<count($times_d);++$i)
  {
  $end=($i<count($times_d)-1)?",":"";
  $logintime.=$times_d[$i].addlzeroes($times_hf[$i]).addlzeroes($times_mf[$i])."-".addlzeroes($times_ht[$i]).addlzeroes($times_mt[$i]).$end;
  }
 return $logintime;
 }

 function makelogintimearrays($str,$times_d,$times_hf,$times_ht,$times_mf,$times_mt)
 {
 if(!$str)return NULL;
 $arr=explode(",",$str);
 for($i=0;$i<count($arr);++$i)
   {
   $times_d[]=substr($arr[$i],0,2);
   $times_hf[]=substr($arr[$i],2,2);
   $times_mf[]=substr($arr[$i],4,2);
   $times_ht[]=substr($arr[$i],7,2);
   $times_mt[]=substr($arr[$i],9,2);
   //echo(substr($arr[$i],0,2)."/".substr($arr[$i],2,2)."/".substr($arr[$i],4,2)."/".substr($arr[$i],7,2)."/".substr($arr[$i],9,2)."<br>");
   }
 }

function get_terminate_cause_str($str)
 {
 $ud=get_user_data(substr($str,9,strlen($str)-9));
 if(stristr($str,"KilledBy-")){return "Сброшен администратором '".$ud["nick"]."'";}
 switch($str)
   {
   case "User-Request":return "По запросу";
//    case "NAS-Request":return "По запросу NAS";
   case "Online":return "[НА ЛИНИИ]";
   case "NAS-Request":return "Сброшен администратором";
   case "Port-Error":return "Произошла ошибка";
   case "User-Error":return "Произошла ошибка";
   case "NAS-Reboot":return "Перезагрузка системы";
   case "Inactive-Request":return "'Подвисшая' сессия";
   default: return "[неизвестна]";
   };
 }


function getyearsel($start_year,$stop_year,$year)
 {
  global $GV;
   $yearsel="<select style=\"width:100%\" name=yearsel[]><option value=\"null\">не важно</option>";
   for($i=$start_year;$i<=$stop_year;++$i)
     {
     $sel=($year==$i)?" selected":"";
     $yearsel.="<option value=\"".($i)."\"$sel>".$i."</option>\r\n";
     }
   $yearsel.="</select>";
  return $yearsel;
 }

function getdaysel($day)
 {
   $dcnt=date("t");
   $daysel="<select style=\"width:100%\" name=daysel[]><option value=\"null\">не важно</option>";
   for($i=0;$i<31;++$i)
     {
     $d=($i+1<10)?"0".($i+1):"".($i+1);
     $sel=($day==$i+1)?" selected":"";
     $daysel.="<option value=\"".$d."\"$sel>".$d."</option>\r\n";
     }
   $daysel.="</select>";
   return $daysel;
 }

function getmonthsel($month,$monthsof)
 {
   $monsel="<select style=\"width:100%\" name=monsel[]><option value=\"null\">не важно</option>";
   for($i=0;$i<12;++$i)
     {
     $m=($i+1<10)?"0".($i+1):"".($i+1);
     $sel=($month==$i+1)?" selected":"";
     $monsel.="<option value=\"".($m)."\"$sel>".$monthsof[$i]."</option>\r\n";
     }
   $monsel.="</select>";
   return $monsel;
 }

function format_ctry($ctry){
  $ctry = strtolower($ctry);
  return "<img src=\"img/flags/".$ctry.".gif\"/ alt=\"".$ctry."\"> ($ctry)";
}


function prehex($dhex){
  $EPS = 0.0000001;
  if($dhex>=16.0)
    return 16.0-$EPS;
  elseif($dhex<=0.0)
    return $EPS;
  return $dhex;    
}

function val2hexcolor($value, $maxvalue, &$first, &$second){
     $s1 = (float)$maxvalue/16.0;
     $s2 = (float)$maxvalue/16.0/16.0;  
     $first = prehex(round($value/$s1));
     $second = prehex(round(($value - $first*$s1)/$s2));     
}

function genmapcol($value,$maxvalue){
     $EPS = 0.0000001; 
     if($value>=$maxvalue)
       $value = $maxvalue-$EPS;
     if($value<=0)
       $value = $EPS;
  
     $blueval = ((float)$maxvalue-(float)$value); 
     $redval = (float)$value;
    val2hexcolor($blueval,$maxvalue,$b1,$b2);
    val2hexcolor($redval,$maxvalue,$r1,$r2);
   return dechex($r1).dechex($r2)."00".dechex($b1).dechex($b2);
}

function ctry_calc_linecolor($url, $max = 1024000000.0){
  if(is_null($url))
   return "0x0000FF";  
  return "0x".strtoupper(genmapcol($url['length'],$max));
}

function ctry_calc_linesize($url, $max = 724000000.0){
  if(is_null($url))
    return 1;

  return ceil((float)$url['length']/($max/2.0));
}


function value2ip($value){
  $ip[0] = (int)((float)$value/256.0/256.0/256.0);
  $value -= $ip[0]*256*256*256;
  $ip[1] = (int)((float)$value/256.0/256.0);
  $value -= $ip[1]*256*256;
  $ip[2] = (int)((float)$value/256.0);
  $value -= $ip[2]*256;
  $ip[3] = (int)((float)$value);
  return "{$ip[0]}.{$ip[1]}.{$ip[2]}.{$ip[3]}";    
}

function ip2value($ip){
$ip = explode(".",$ip); 
return $ip[0]*256*256*256 + $ip[1]*256*256 + $ip[2]*256 + $ip[3];
}
