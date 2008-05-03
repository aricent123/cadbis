<?php 
session_start();
//session_destroy();//раскоментить, чтобы очистить сессию
//include charts.php in your script
include "charts.php";

//название графика
$title = "statistic";

if(!isset($_SESSION['graph_prev_values']))
  {
   $_SESSION['graph_prev_values'] = array($title,120);
  }

if(!isset($_SESSION['graph_prev_indexes']))
{
 $_SESSION['graph_prev_indexes'] = array(0,1);
}

//change the chart to a bar chart
$chart [ 'chart_type' ] = "line";

$chart[ 'chart_grid_h' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_grid_v' ] = array ( 'alpha'=>10, 'color'=>"000000", 'thickness'=>1, 'type'=>"solid" );
$chart[ 'chart_pref' ] = array ( 'line_thickness'=>2, 'point_shape'=>"none", 'fill_shape'=>false );

  $_SESSION['graph_prev_values'][]=rand(5, 100)  ;
  $_SESSION['graph_prev_indexes'][]=$_SESSION['graph_prev_indexes'][count($_SESSION['graph_prev_indexes'])-1]+1;
  

  
  
  if(count($_SESSION['graph_prev_values'])==15)
  {
  array_shift($_SESSION['graph_prev_values']);
  array_shift($_SESSION['graph_prev_indexes']);
  $_SESSION['graph_prev_values'][0] = $title;
  //die("ппц");
  }
$chart [ 'chart_data' ] = array ( $_SESSION['graph_prev_indexes'],
                                  $_SESSION['graph_prev_values']
                         		);
                         		

/*$chart[ 'draw' ] = array ( array ( 'type'=>"text", 'color'=>"ffffff", 'alpha'=>15, 'font'=>"arial", 'rotation'=>-90, 'bold'=>true, 'size'=>50, 'x'=>-10, 'y'=>600, 'width'=>300, 'height'=>150, 'text'=>"a", 'h_align'=>"center", 'v_align'=>"top" ),
                           array ( 'type'=>"text", 'color'=>"000000", 'alpha'=>15, 'font'=>"arial", 'rotation'=>0, 'bold'=>true, 'size'=>60, 'x'=>300, 'y'=>-150, 'width'=>320, 'height'=>300, 'text'=>"b", 'h_align'=>"left", 'v_align'=>"bottom" ) );
*/
$chart [ 'live_update' ] = array (   'url'    =>  "data.php", 
                                     'delay'  =>  2
                                ); 
SendChartData ( $chart );

?>
