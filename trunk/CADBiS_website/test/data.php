<?php 
session_start();
//include charts.php in your script
include "charts.php";


if(!isset($_SESSION['graph_prev_values']))
  {
   $_SESSION['graph_prev_values'] = array(0);
  }

if(!isset($_SESSION['graph_prev_indexes']))
{
 $_SESSION['graph_prev_indexes'] = array("нагрузка");
}

//change the chart to a bar chart
$chart [ 'chart_type' ] = "line";

  $_SESSION['graph_prev_values'][]=rand(5, 100)  ;
  array_push($_SESSION['graph_prev_indexes'],count($_SESSION['graph_prev_indexes']));
$chart [ 'chart_data' ] = array ( $_SESSION['graph_prev_indexes'],
                                  $_SESSION['graph_prev_values']
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
