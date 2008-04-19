<?php
include("restore_confs.php");
error_reporting(E_PARSE);
if($BILLEVEL<3 && $action!="topurl")die($CURRENT_USER["level"]."");
elseif($BILLEVEL<=1)die("");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);


include("graph_class.php");
$gr = new CGraph();
//настройки графика
$params=array();
$params[0]=600;
$params[1]=500;
$params[2]=255;
$params[3]=255;
$params[4]=255;
$params[5]=0;
$params[6]=0;
$params[7]=0;
$params[8]=155;
$params[9]=155;
$params[10]=155;
$params[11]=50;
$params[12]=50;
$params[13]="./GOST_A.TTF";
$params[15]="";
$params[16]="";
$params[17]=6;
$params[18]="";
$params[19]=true;
$params[20]=false;
$params[21]="./GOST_A.TTF";
$params[22]=15;
$params[23]=14;
$params[24]=true;
$params[25]=200;
$params[26]=60;

//данные для прорисовки
$data=array();
//метки для данных
$labels=array();
if(!isset($gid))$gid="all";
switch($action)
{
	case "topurl":
		if($uid==null || $uid=="null")
	 $uid = null;
	 if($uid)
	 {$user = $BILL->GetUserData($uid);
	 $byuser = " пользователя ".$user['fio'];
	 }
	 else
	 $byuser = "";
	 if(!isset($groupby) || !$groupby)
	 $groupby=null;
	 if(!isset($sort))
	 $sort=">count";
	 if(!isset($limit) || !$limit)
	 $limit=25;
	 if(!strstr($sort,"count") && !strstr($sort,"url")&& !strstr($sort,"length"))
	 $sort=">count";

	  
	 if(!isset($hideother) || $hideother=="false")
	 $hideother = false;
	 $urls = $BILL->GetUrlsPopularity($sort,$uid,$limit,$gid,$groupby,$hideother);
	 $params[14]="Top $limit посещённых сайтов".$byuser;
	 $sum_data=array('count'=>0,'length'=>0,'ucount'=>0);
	 $avg_data=$sum_data;
	 $min_data =array('count'=>9999999,'length'=>9999999,'ucount'=>999999);

	 foreach($urls as $url){
	 	$sum_data['count'] +=(int)$url['count'];
	 	$sum_data['length'] += (int)$url['length'];
	 	$sum_data['ucount'] = ($url['ucount']>$sum_data['ucount'])?$url['ucount']:$sum_data['ucount'];
	 	$avg_data['count'] =($avg_data['count']+(int)$url['count'])/2;
	 	$avg_data['length'] =($avg_data['length']+(int)$url['length'])/2;
	 	$min_data['count'] =($url['count']<$min_data['count'])?$url['count']:$min_data['count'];
	 	$min_data['length'] =($url['length']<$min_data['length'])?$url['length']:$min_data['length'];
	 	$min_data['ucount'] = ($url['ucount']<$min_data['ucount'])?$url['ucount']:$min_data['ucount'];
	 }
	 $avg_data['ucount'] = $sum_data['ucount']/2;

	 $data_other=array('data'=>0,'label'=>'Другие');
	 $key = substr($sort,1,strlen($sort)-1);
	 foreach($urls as $url)
	 {
	 	$tmp = $url[$key];
	 	//echo($tmp.">".$avg_data[$key]."<br>");//(($sum_data[$key]/100.0)*($avg_data[$key]/($sum_data[$key]/$min_data[$key])))."<br>");
	 	$g=($sort[0]==">")?$tmp>$avg_data[$key]:$tmp<$avg_data[$key];
	 	if(($g && count($data)<12)){
	 		$data[]=$tmp;
	 		$tmpstr=($key=="length")?make_fsize_str($tmp):$tmp;
	 		$labels[]=(($groupby=='cid')?$url['cattitle']:$url['url'])." - ".$tmpstr;
	 	}
	 	else
	 	$data_other['data'] += $tmp;
	 }
	 if($data_other['data'])
	 {
	 	$data[]=$data_other['data'];
	 	$tmpstr=($key=="length")?make_fsize_str($data_other['data']):$data_other['data'];
	 	$labels[]=$data_other['label']." - ".$tmpstr;
	 }

	 break;
	case "today":
		$accts=$BILL->GetTodayUsersAccts(0,1,$gid);
		$params[14]="Статистика на ".date("d")." ".$monthsof[date("n")-1].", ".date("H:i:s")."";
		for($i=0;$i<count($accts);++$i)
		{
			$data[$i]=$accts[$i]["traffic"];
			$tmp=explode(" ",$accts[$i]["fio"]);
			$fio=$tmp[0];
			$labels[$i]=$fio." (".bytes2mb($accts[$i]["traffic"])." Мб)";
		}
		break;
	case "month":
		$accts=$BILL->GetMonthUsersAccts(0,1,$gid);
		$params[14]="Статистика по пользователям за ".$months[date("n")-1]." ".date("Y")." года";
		for($i=0;$i<count($accts);++$i)
		{
			$data[$i]=$accts[$i]["traffic"];
			$tmp=explode(" ",$accts[$i]["fio"]);
			$fio=$tmp[0];
			$labels[$i]=$fio." (".bytes2mb($accts[$i]["traffic"])." Мб)";
		}
		break;
	case "week":
		$accts=$BILL->GetWeekUsersAccts(0,1,$gid);
		$params[14]="Статистика за неделю:";
		for($i=0;$i<count($accts);++$i)
		{
			$data[$i]=$accts[$i]["traffic"];
			$tmp=explode(" ",$accts[$i]["fio"]);
			$fio=$tmp[0];
			$labels[$i]=$fio." (".bytes2mb($accts[$i]["traffic"])." Мб)";
		}
		break;
	case "tarifs":
		if(!isset($fdate))$fdate="";
		if(!isset($tdate))$tdate="";
		if(!isset($tarif))$tarif="!all!";

		if($tarif=="!all!")
		$accts=$BILL->GetTarifsAccts($fdate,$tdate,1);
		else
		{
			$data=$BILL->GetTarifAccts($tarif,$fdate,$tdate,1);
			$tdata=$BILL->GetTarifData($tarif);
			$accts=NULL;
			$accts[0]["traffic"]=$data["traffic"];
			$accts[0]["time"]=$data["time"];
			$accts[0]["packet"]=$tdata["packet"];
		}
		$cnt=count($accts);
		if(!isset($param))$param="";
		if($param=="traffic")
		for($k=0;$k<$cnt;++$k)
		{
			$data[$k]=$accts[$k]["traffic"];
			$labels[$k]=$accts[$k]["packet"]." (".bytes2mb($accts[$k]["traffic"])." Мб)";
			$fdate_s=date_dmy(strtotime($fdate));
			$tdate_s=date_dmy(strtotime($tdate));
			$params[14]="Статистика тарифов по траффику за период ".$fdate_s." - ".$tdate_s;
		}
		else
		for($k=0;$k<$cnt;++$k)
		{
			$data[$k]=$accts[$k]["time"];
			$labels[$k]=$accts[$k]["packet"]." (".gethours($accts[$k]["time"]).":".getmins($accts[$k]["time"]).":".getsecs($accts[$k]["time"]).")";
			$fdate_s=date_dmy(strtotime($fdate));
			$tdate_s=date_dmy(strtotime($tdate));
			$params[14]="Статистика тарифов по времени за период ".$fdate_s." - ".$tdate_s;
		}

		break;
};
$gr->Draw($params,$data,$labels);
?>