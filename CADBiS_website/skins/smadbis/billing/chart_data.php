<?php
//session_start();
error_reporting (0);
require_once("DrClass.php");
require_once("restore_confs.php");
require_once("funcs.php");
require_once("cadbisnew/graph/charts.php");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
//include charts.php in your script



if(isset($chart_type))
switch($chart_type)
{
	case "loading":
		$title = "Нагрузка";
		if(!isset($_SESSION['graph_prev_values']))
		{
			$_SESSION['graph_prev_values'] = array($title,0);
		}

		if(!isset($_SESSION['graph_prev_indexes']))
		{
			$_SESSION['graph_prev_indexes'] = array(0,1);
		}

		//��� �������
		$chart [ 'chart_type' ] = "line";
		//����� �������
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma");

		$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
		$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
		$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"none", 'fill_shape'=>false );
		//������ ��� �������
		$_SESSION['graph_prev_values'][]=$BILL->getChannelLoading() ;
		$_SESSION['graph_prev_indexes'][]=$_SESSION['graph_prev_indexes'][count($_SESSION['graph_prev_indexes'])-1]+1;


		if(count($_SESSION['graph_prev_values'])==15)
		{
			array_shift($_SESSION['graph_prev_values']);
			array_shift($_SESSION['graph_prev_indexes']);
			$_SESSION['graph_prev_values'][0] = $title;
		}
		$chart [ 'chart_data' ] = array ( 	$_SESSION['graph_prev_indexes'],
		$_SESSION['graph_prev_values']
		);
		$chart [ 'draw' ] = array ( array ( 'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => -70, 
                                    'y'          => 35, 
                                    'width'      => 250,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => -90, 
                                    'text'       => "Число потоков",  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 12, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		),
		array ( 'type'       => 'text',
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 170, 
                                    'y'          => 275, 
                                    'width'      => 250,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => -90, 
                                    'text'       => "Время",  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 12, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		)

		);
		$chart [ 'live_update' ] = array (   'url'    =>  $_SERVER['REQUEST_URI'], 'delay'  =>  2);
		SendChartData ( $chart );
		break;
//--------------------
	case "topurl":
	$chart [ 'chart_type' ] = "3d pie";
	$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'	=> 10);
	/*����� �� admin_draw.php*/
	if($uid==null || $uid=="null")
	 $uid = null;
	 if($uid)
	 {$user = $BILL->GetUserData($uid);
	 $tmpname = explode(" ", $user['fio']);
	 $surname = $tmpname[0];
	 $byuser = " пользователя ".iconv('cp1251', 'utf-8', $surname);
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
	 /*/����� �� admin_draw.php*/
	 $title="Top $limit посещённых сайтов".$byuser;
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
	 $labels = array("");
	 $data = array("");
	 foreach($urls as $url)
	 {
	 	$tmp = $url[$key];
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
	
	 
	 
	 $chart [ 'draw' ] = array ( array ( 'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 0, 
                                    'y'          => 10, 
                                    'width'      => 600,  
                                    'height'     => 200, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => $title,  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 16, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
	 )
	 );
	 $prefix = "";
	 if($sort===">count" || $sort==="<count")
	 $prefix = " Пакетов";
	 else
	 { 
	 	for($i=0;$i<count($data);$i++)
	 		$data[$i]=bytes2mb($data[$i]);
	 $prefix = " Mb";
	 }
	 $chart [ 'chart_data' ] = array ($labels, $data);
	 $chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'        =>  $prefix,
                                    'position'	=>  "cursor"
                                    );
     

                                    SendChartData ( $chart );
                                    break;
//--------------------
	case "today": 
		$chart [ 'chart_type' ] = "pie";
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'	=> 10);
		$accts=$BILL->GetTodayUsersAccts(0,1,$gid);
		$title = "Статистика на ".date("d")." ".iconv('cp1251', 'utf-8', $monthsof[date("n")-1]).", ".date("H:i:s")."";
		$labels = array("");
	 	$data = array("");
		for($i=0;$i<count($accts);++$i)
		{
			$data[]=bytes2mb($accts[$i]["traffic"]);
			$tmp=explode(" ",iconv('cp1251', 'utf-8', $accts[$i]["fio"]));
			$fio=$tmp[0];
			$labels[]=$fio." (".bytes2mb($accts[$i]["traffic"])." Мб)";
		}
		$chart [ 'chart_data' ] = array ($labels, $data);
		$chart [ 'draw' ] = array ( array ( 
									'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 0, 
                                    'y'          => 10, 
                                    'width'      => 600,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => $title,  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 16, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		)
		);
	 $chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'        =>  " Mb",
                                    'position'	=>  "cursor"
                                    );		
		
	SendChartData ( $chart );
	 break;
	 //--------------------
	case "month":
		$accts=$BILL->GetMonthUsersAccts(0,1,$gid);
		$title="Статистика по пользователям на ".iconv('cp1251', 'utf-8', $months[date("n")-1])." ".date("Y")." года ";
		$labels = array("");
	 	$data = array("");
		for($i=0;$i<count($accts);++$i)
		{
			$data[$i+1]=bytes2mb($accts[$i]["traffic"]);
			$tmp=explode(" ",$accts[$i]["fio"]);
			$fio=$tmp[0];
			$labels[$i+1]=iconv('cp1251', 'utf-8', $fio." (".make_fsize_str($accts[$i]["traffic"])." )");
		}
		$chart [ 'chart_type' ] = "3D pie";
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'	=> 10);
		$chart [ 'chart_data' ] = array ($labels, $data);
		$chart [ 'draw' ] = array ( array ( 
									'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 0, 
                                    'y'          => 10, 
                                    'width'      => 600,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => $title,  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 16, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		)
		);
		$chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'	=>  " Mb"
                                    );
		SendChartData ( $chart );
	 break;
//--------------------
	case "week":
		$accts=$BILL->GetWeekUsersAccts(0,1,$gid);
		$title = "Статистика за неделю:";
		$labels = array("");
	 	$data = array("");
		for($i=0;$i<count($accts);++$i)
		{
			$data[$i]=bytes2mb($accts[$i]["traffic"]);
			$tmp=explode(" ",$accts[$i]["fio"]);
			$fio=iconv('cp1251', 'utf-8', $tmp[0]);
			$labels[$i]=$fio." (".make_fsize_str($accts[$i]["traffic"])." )";
		}
		$chart [ 'chart_type' ] = "3D pie";
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'	=> 10);
		$chart [ 'chart_data' ] = array ($labels, $data);
		$chart [ 'draw' ] = array ( array ( 
									'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 0, 
                                    'y'          => 10, 
                                    'width'      => 600,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => $title,  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 16, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		)
		);
		$chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'	=>  " Mb"
                                    );
		SendChartData ( $chart );
		break;
//--------------------
	case "tarifs":
		if(!isset($fdate))$fdate="";
		else $fdate.=" 00:00:00";
		if(!isset($tdate))$tdate="";
		else $fdate.=" 23:59:59";
		if(!isset($tarif))$tarif="!all!";
		$labels = array("");
	 	$data = array("");
	 	$title = null;
		if($tarif=="!all!")
		{
		 $accts=$BILL->GetTarifsAccts($fdate,$tdate,1);
		}
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
		$tmpfdate=explode(" ",$fdate);
		$ffdate=$tmpfdate[0];
		$tmptdate=explode(" ",$tdate);
		$ttdate=$tmptdate[0];
		if(!isset($param))$param="";
		if($param=="traffic")
		for($k=0;$k<$cnt;++$k)
		{
			$data[$k+1]=bytes2mb($accts[$k]["traffic"]);
			$labels[$k+1]=iconv('cp1251', 'utf-8', $accts[$k]["packet"])." (".bytes2mb($accts[$k]["traffic"])." Мб)";
			$fdate_s=date_dmy(strtotime($fdate));
			$tdate_s=date_dmy(strtotime($tdate));
			$title = "Статистика тарифов по траффику за период ".$fdate_s." - ".$tdate_s;
		}
		else
		for($k=0;$k<$cnt;++$k)
		{
			$data[$k+1]=gethours($accts[$k]["time"]);
			$labels[$k+1]=iconv('cp1251', 'utf-8', $accts[$k]["packet"])." (".gethours($accts[$k]["time"]).":".getmins($accts[$k]["time"]).":".getsecs($accts[$k]["time"]).")";
			$fdate_s=date_dmy(strtotime($fdate));
			$tdate_s=date_dmy(strtotime($tdate));
			$title = "Статистика тарифов по времени за период ".$ffdate." - ".$ttdate;
		}
		$chart [ 'chart_type' ] = "3D pie";
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'	=> 10);
		$chart [ 'chart_data' ] = array ($labels, $data);
		$chart [ 'draw' ] = array ( array ( 
									'type'       => "text",
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 0, 
                                    'y'          => 10, 
                                    'width'      => 600,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => $title,  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 16, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
		)
		);
		if($param=="traffic"){
		$chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'	=>  " Mb"
                                    );
		}else{		$chart [ 'chart_value' ] = array ('font'	=>  "Tahoma",
                                    'bold'	=>  true, 
                                    'size'	 =>  16,
                                    'color'	=>  "4400ff",
	  								'suffix'	=>  " ч."
                                    );}
		
		SendChartData ( $chart );
		break;	
//--------------------	
	default: break;
};


?>
