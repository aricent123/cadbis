<?php
session_start();
date_default_timezone_set("Europe/Moscow");
require_once("lib/seminars.inc.php");
require_once("lib/ajax/grid.inc.php");
header("Content-Type: text/html;charset=UTF-8");
/* .. Initialization .. */
$PPP = 15;
$seminars = new seminars();
$grid = new ajax_grid('grid');
$pager = new ajax_grid_pager("pager",$seminars->get_count(),$PPP);
$grid->attach_pager($pager);
/* .. Data binding .. */
$src = new grid_data_source(new grid_header_item_array(
											new grid_header_item('title',utils::cp2utf('Название'),type::STRING, true),
											new grid_header_item('desc',utils::cp2utf('Описание'),type::STRING),
											new grid_header_item('time',utils::cp2utf('Дата'),type::DATE_TIME, true, new sem_formatter())
											));			
$data = $seminars->get_data($pager->get_curpage(),$PPP,$grid->get_current_sorting(),$grid->get_sort_direction());
foreach($data as $seminar)
{
	$src->add_row(array(utils::cp2utf($seminar->get_title()),utils::cp2utf($seminar->get_desc()),utils::cp2utf($seminar->get_time())));	
}
$grid->set_datasource($src);

/* .. Output .. */
?>
<html>
<head>
<link href="skins/partner4/css/grid.css" rel="stylesheet"/>
<link href="skins/partner4/css/ajax.css" rel="stylesheet"/>
<script type="text/javascript" src="js/scriptaculous/prototype.js"></script>
<script type="text/javascript" src="js/window/window.js"> </script> 
<script type="text/javascript" src="js/ajax/engine.js"></script>
</head>
<body>
<div id="test_content" style="background: white;">
<?$grid->render();?>
</div>
</body>
</html> 