<?php

//include charts.php in your script
include "charts.php";
/*require_once "skins/smadbis/billing/DrClass.php";
require_once "/modules_conf/smadbis.conf.php";
*/
/*switch($_GET['type'])
{
	case 'pie':
		$chart [ 'chart_type' ] = "pie";
		break;
	default:
		$chart [ 'chart_type' ] = "stacked 3d column";		
};*/


/*$DB = new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"]);
$user_data =  $DB->GetUsersOfTarif($_GET['gid']);*/

/*
 * 
 * 
 */
//change the chart to a bar chart
$chart [ 'chart_type' ] = "line";
$X = array ( "",	"0",	"1",	"2",	"3",	"4",	"5",	"6" );
$y = array ( "a",	rand(5, 100),		rand(5, 100),		rand(5, 100),		rand(5, 100),		rand(5, 100),		rand(5, 100),		rand(5, 100)   );
  $y[] = rand(5, 100);
  $X[] = ""+count($X)-1;
$chart [ 'chart_data' ] = array ( $X,
                                  $y
                         		);
                         		
/*$chart [ 'chart_grid_h' ] = array (   'thickness'  =>  2,
                                      'color'      =>  "FF0000",
                                      'alpha'      =>  15,
                                      'type'       =>  "dashed"
                                   );*/
/*$chart [ 'chart_grid_v' ] = array (   'thickness'  =>  2,
                                      'color'      =>  "FF0000",
                                      'alpha'      =>  15,
                                      'type'       =>  "dashed"
                                   );*/
$chart[ 'draw' ] = array ( array ( 'type'=>"text", 'color'=>"ffffff", 'alpha'=>15, 'font'=>"arial", 'rotation'=>-90, 'bold'=>true, 'size'=>50, 'x'=>-10, 'y'=>600, 'width'=>300, 'height'=>150, 'text'=>"a", 'h_align'=>"center", 'v_align'=>"top" ),
                           array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>15, 'font'=>"arial", 'rotation'=>0, 'bold'=>true, 'size'=>60, 'x'=>300, 'y'=>-150, 'width'=>320, 'height'=>300, 'text'=>"b", 'h_align'=>"left", 'v_align'=>"bottom" ) );
$chart [ 'live_update' ] = array (   'url'    =>  "data.php", 
                                     'delay'  =>  2
                                ); 
SendChartData ( $chart );

?>
