<?php
session_start();
error_reporting (0); 
require_once("../skins/smadbis/billing/DrClass.php");
require_once("../modules_conf/smadbis.conf.php");
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
//session_destroy();//СЂР°СЃРєРѕРјРµРЅС‚РёС‚СЊ, С‡С‚РѕР±С‹ РѕС‡РёСЃС‚РёС‚СЊ СЃРµСЃСЃРёСЋ
//include charts.php in your script
include "charts.php";


if(isset($_GET['chart_type']))
switch($_GET['chart_type'])
{
	case "loading":
		$title = "РќР°РіСЂСѓР·РєР°";
		if(!isset($_SESSION['graph_prev_values']))
		{
			$_SESSION['graph_prev_values'] = array($title,0);
		}

		if(!isset($_SESSION['graph_prev_indexes']))
		{
			$_SESSION['graph_prev_indexes'] = array(0,1);
		}

		//Тип графика
		$chart [ 'chart_type' ] = "line";
		//Шрифт легенды
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma"); 

		$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
		$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
		$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"none", 'fill_shape'=>false );
		//данные для графика
		$_SESSION['graph_prev_values'][]=rand(5, 100)  ;
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
                                    'x'          => -20, 
                                    'y'          => 50, 
                                    'width'      => 250,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => 90, 
                                    'text'       => "Р§РёСЃР»Рѕ РїРѕС‚РѕРєРѕРІ",  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 14, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
                                  ),
                            array ( 'type'       => 'text',
                                    'transition' => 'slide_left',
                                    'delay'      => 0, 
                                    'duration'   => 0,
                                    'x'          => 400, 
                                    'y'          => 370, 
                                    'width'      => 250,  
                                    'height'     => 100, 
                                    'h_align'    => "center", 
                                    'v_align'    => "top", 
                                    'rotation'   => -90, 
                                    'text'       => "Р’СЂРµРјСЏ",  
                                    'font'       => "Tahoma", 
                                    'bold'       => true, 
                                    'size'       => 14, 
                                    'color'      => "4400ff", 
                                    'alpha'      => 90
                                  )
                                  
                          );
		$chart [ 'live_update' ] = array (   'url'    =>  $_SERVER['REQUEST_URI'], 'delay'  =>  2);
		SendChartData ( $chart );
		break;
//--------------------
	//--------------------
	case "topurl":
		$chart [ 'chart_type' ] = "3d pie";
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma", 'size'       => 10);
		/*взято из admin_draw.php*/
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
	  /*/взято из admin_draw.php*/
	
	 
	 
	 
	 //$params[14]="Top $limit посещённых сайтов".$byuser;
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
	 $chart [ 'chart_data' ] = array ($labels, $data);
	 $chart [ 'chart_value' ] = array ('font'             =>  "Tahoma", 
                                    'bold'             =>  true, 
                                    'size'             =>  12,
                                    'color'             =>  "4400ff",
                                    'position'             =>  "cursor"
                                	); 
     SendChartData ( $chart );
	 break;
//--------------------
	default: break;
};


?>
