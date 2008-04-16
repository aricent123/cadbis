<?php

//include charts.php in your script
include "charts.php";

//change the chart to a bar chart

$chart [ 'chart_type' ] = "stacked 3d column";
$chart [ 'chart_data' ] = array ( array ( "",	"2004",	"2005",	"2006",	"2007" ),
                                  array ( "צנאגûמצאנפûכג",	10,     	30,		63,		100  ),
 					    array ( "b",	20,     	50,		83,		30  )
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
$chart [ 'live_update' ] = array (   'url'    =>  "data.php", 
                                     'delay'  =>  2
                                ); 
SendChartData ( $chart );

?>