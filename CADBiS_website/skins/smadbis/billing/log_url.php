<?
if($BILLEVEL<3)return;
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

 function log_url_user_template($data,$onlinedata=null)
 {
  global $DIRS;
     ?>
     <table width=100% align=center class=tbl2>
                    <tr><td width=100% colspan=2>
                    <div align=center><b><? OUT($data["fio"]) ?></b></div>
                    </td></tr>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$data["uid"]) && is_file($DIRS["users_avatars"]."/".$data["uid"]))
                        OUT("<img border=0 src=\"".$DIRS["users_avatars"]."/".$data["uid"]."\">");
                      ?></a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>
                      </table>
                    </td><td width=70%>
                      <table  width=100% class=tbl1>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($onlinedata["user"]) ?></td></tr>
                        <tr><td width=50%>
                        VIP:
                        </td><td width=50%><? OUT($onlinedata["ip"]) ?></td></tr>
                        <tr><td width=50%>
                        IP:
                        </td><td width=50%><? OUT($onlinedata["call_from"]) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($onlinedata["out_bytes"])." Mb (".bytes2kb($onlinedata["out_bytes"])." Kb)") ?></td></tr>
                        <tr><td width=50%>
                        Время:
                        </td><td width=50%><? OUT(gethours($onlinedata["time_on"]).":".getmins($onlinedata["time_on"]).":".getsecs($onlinedata["time_on"])) ?></td></tr>
                        <tr><td width=50%>
                        Начало сессии:
                        </td><td width=50%><? OUT(norm_date(strtotime($onlinedata["start_time"]))) ?></td></tr>
                        <tr><td width=50%>
                        Последнее изменение:
                        </td><td width=50%><? OUT(norm_date($onlinedata["last_change"])) ?></td></tr>
                        <? if($onlinedata["terminate_cause"]!="Online"){?>
                        <tr><td width=50%>
                        Конец сессии:
                        </td><td width=50%><? OUT(norm_date(strtotime($onlinedata["stop_time"]))) ?></td></tr>
						<? }?>
                      </table>
                    </td>
                  </table>
    <?

 }

 function log_url_template_header_sort($paramstr,$sort,$fname,$ftitle)
 {
    global $p,$act,$action,$draw;
	?>
    <a href="<? ($sort==">$fname")?$ssort="<$fname":$ssort=">$fname"; OUT("?p=$p&act=$act&action=$action&draw=$draw&$paramstr&sort=$ssort") ?>"><?=$ftitle?></a>
 	<?
    	if($sort=="<$fname")
    		OUT("<img src=\"".SK_DIR."/img/asc.gif\">");
    	elseif($sort==">$fname")
    		OUT("<img src=\"".SK_DIR."/img/desc.gif\">");
 }

 function log_url_table_template_header($paramstr,$sort)
 {
 global $unique_id;
 ?>
	<table width=100% border=0 borderwidth=1px>
    <tr>
    	<td class=tbl1>№</td>
    	<td class=tbl1><?=log_url_template_header_sort($paramstr,$sort,"url","URL");?></td>
    	<td class=tbl1>Content-type</td>
    	<td class=tbl1><?=log_url_template_header_sort($paramstr,$sort,"date","Date");?></td>
    	<td class=tbl1><?=log_url_template_header_sort($paramstr,$sort,"length","Bytes");?></td>
    	<td class=tbl1>ip</td>
    	<td class=tbl1>action</td>
    </tr>
   <?

 
}


 function log_url_table_template($paramstr,$urls,$sort,&$BILL)
 {
 global $p,$act,$action,$unique_id;
 
  log_url_table_template_header($paramstr,$sort);
   $total = 0;
   for($i=0;$i<count($urls);++$i)
     {
   	$total += $urls[$i]['length'];
   	
      if($country=$BILL->GetCountry($urls[$i]['ip']))
       $country = "<img src=\"img/flags/".$country['ctry'].".gif\"/ alt=\"".$country['country']."\"> ".$urls[$i]['ip'];
       else 
        $country = $urls[$i]['ip'];   	
     ?>
     <tr>
     	<td class=tbl1><?=$i?></td>
     	<td class=tbl1><?=make_url_str($urls[$i]['url'],true)?></td>
     	<td class=tbl1><?= $urls[$i]['content_type']?></td>
     	<td class=tbl1><?=norm_date_yymmddhhmmss($urls[$i]['date'])?></td>
     	<td class=tbl1><?=make_fsize_str($urls[$i]['length'])?></td>
     	<td class=tbl1><?=$country?></td>
     	<td class=tbl1><a href="<? echo("?p=$p&act=$act&action=trace&unique_id=$unique_id&oldaction=$action&url=".$urls[$i]['url']."&ip=".$urls[$i]['ip']);?>">trace</a></td>                  	
     </tr>
     <?
     }
  	?>
     <tr>
     	<td class=tbl1><b>TOTAL</td>
     	<td class=tbl1></td>
     	<td class=tbl1></td>
     	<td class=tbl1></td>
     	<td class=tbl1><?=make_fsize_str($total)?></td>
     	<td class=tbl1></td>
     	<td class=tbl1></td>                  	
     </tr>
 	</table>
 	<?
 }

 function log_url_protocol_template($session)
 {
 	?>
		<table class=tbl1 width=100%>
		<tr>
			<td width=100% class=tbl1>
			<div align=center><b><STRONG>Protocol:</STRONG></b></div>
			<?
			OUT(nl2br($session['protocol']['data']));
			?>
			</td>
		</tr>
		<tr>
			<td width=100% class=tbl1><div align=center><b>Total data recieved:
			<?
			OUT(make_fsize_str($session['protocol']['length']));
			?>
			</b></div></td>
		</tr>
		</table>
 	<?
 }


 if(!isset($action))$action="";
 if(!isset($sort))$sort="<date";

 switch($action)
 {
 case "trace":
 $result = shell_exec("traceroute $ip");  
 preg_match_all("/(\([0-9\.]*\))/", $result, $matches);
 for ($i=0; $i< count($matches[0]); $i++)
  $ips[] = $matches[0][$i];
 $ips = str_replace(array("(",")"),"",$ips);
 $out = "<b>Traceroute до URL: <a href=\"$url\" target=_blank>$url</a></b><br />\r\n";
 $out .= "<table width=100%>";
 $i=0;
 
 
 $flparams  = "";
 $j=-1;
 $last_ctry = "";
 $flctrys = array();
 
 foreach($ips as  $ip){
 
    if($cntry=$BILL->GetCountry($ip))
       $country = "<img src=\"img/flags/".$cntry['ctry'].".gif\"/ alt=\"".$cntry['country']."\"> ";
       else 
        $country = $ip;
  
   if(strtoupper($last_ctry)!=strtoupper($cntry['ctry']))
     {$j++;$last_ctry=strtoupper($cntry['ctry']);}   
     
   $flctrys[$j]['count']++;
   $flctrys[$j]['ctry']=strtoupper($cntry['ctry']);
     
   $out .= "<tr><td class=tbl1>".($i++)."</td><td class=tbl1>$ip</td><td class=tbl1>$country</td></tr>";   
 }
 $out .= "</table>";
 $out .= "<a href=\"?p=$p&act=$act&action=$oldaction&unique_id=$unique_id\">назад</a><br/>";
 
 $flparams="RU:1:0xFFFFFF";
 foreach($flctrys as $flctry){
    $length = array('length'=>$flctry['count']*100000000);
    $flparams .= (($flparams)?"#":"").strtoupper($flctry['ctry']).":".ctry_calc_linesize($length).":".ctry_calc_linecolor($length);
 }
 ?>
   <object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0" width="200" height="100" id="map4" align="middle">
     <param name="allowScriptAccess" value="sameDomain" />
     <param name="movie" value="map4.swf?line1=<?=$flparams?>" />
     <param name="quality" value="high" />
     <param name="bgcolor" value="#ffffff" />
     <embed src="map4.swf?line1=<?=$flparams?>" quality="high" bgcolor="#ffffff" width="800" height="500" name="map4" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />
   </object> 
 <?
 
 echo($out);

 break;
 case "online":
 $data = $BILL->GetUserDataOfUnique($unique_id);
 $onlinedata = $BILL->GetOnlineUserDataOfUnique($unique_id);
 $urls = $BILL->GetUserLastUrls($unique_id,$sort);
 break;
 case "session_protocol":
  $session = $BILL->GetSessionData($unique_id);
  $urls = array();
  $strs = explode("{@}",$session['protocol']['data']);
  for($i=0;$i<count($strs)-1;++$i)
   {
    $data = explode("{*}",$strs[$i]);
    $urls[] = array('date'=>strtotime($data[0]),'url'=>$data[1],'count'=>$data[2],'length'=>$data[3],'ip'=>$data[4],'content_type'=>$data[5]);
   }
   switch($sort)
    {
     case "<url":usort($urls,"_urls_sort_functorURL_asc");break;
     case ">url":usort($urls,"_urls_sort_functorURL_desc");break;
     case "<date":usort($urls,"_urls_sort_functorDATE_asc");break;
     case ">date":usort($urls,"_urls_sort_functorDATE_desc");break;
     case "<length":usort($urls,"_urls_sort_functorLEN_asc");break;
     case ">length":usort($urls,"_urls_sort_functorLEN_desc");break;
     default:usort($urls,"_urls_sort_functorDATE_asc");break;
    }
 break;
 }

  if($action=="online")
   {
   log_url_user_template($data,$onlinedata);
   log_url_table_template("unique_id=$unique_id",$urls,$sort,$BILL);
   ?><div align=center><a href="<? OUT("?p=$p&act=online") ?>">назад на онлайн-лист</a></div><?
   }
  elseif($action=="session_protocol")
   {
   log_url_user_template($session['user'],$session['session']);
   log_url_table_template("unique_id=$unique_id",$urls,$sort,$BILL);
   ?><div align=center><a href="<? OUT("?p=$p&act=stats&action=sessions") ?>">назад к статистике</a></div><?
   }
  else
   {
   }
 if($BILL->IsProtocolExists($unique_id))
	OUT("<a href=\"?p=$p&act=log_url&action=session_protocol&unique_id=".$unique_id."\">Аггрегированная информация</a>");
