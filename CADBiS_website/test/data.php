<?php
session_start();
//session_destroy();//раскоментить, чтобы очистить сессию
//include charts.php in your script
include "charts.php";

//название графика
$title = "Нагрузка";
if(isset($_GET['chart_type']))
switch($_GET['chart_type'])
{
	case "loading":
		if(!isset($_SESSION['graph_prev_values']))
		{
			$_SESSION['graph_prev_values'] = array($title,0);
		}

		if(!isset($_SESSION['graph_prev_indexes']))
		{
			$_SESSION['graph_prev_indexes'] = array(0,1);
		}

		//change the chart to a bar chart
		$chart [ 'chart_type' ] = "line";
		
		$chart [ 'legend_label' ] = array ( 'font'    =>  "Tahoma"); 

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
                                    'text'       => "Число потоков",  
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
                                    'text'       => "Время",  
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
	default: break;
};


?>
