<?
if((!check_auth() || $CURRENT_USER['level']<7) && $BILLEVEL != 2){	
	die("Access denied!");
}
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
require_once(dirname(__FILE__)."/CADBiS/PacketsTodayLimits.php");
CADBiSNew::instance()->script_src('js/ajax/buffer.js');


$ajaxbuf = new ajax_buffer("update_buffer");
$ajaxbuf->show_progress(true);
$ajaxbuf->set_postback_url($_SERVER['REQUEST_URI']);

$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

require_once(dirname(__FILE__)."/CADBiS/cadbis_statistic_backend.php");
CADBiSNew::instance()->script_src('js/jquery/jquery.js');
CADBiSNew::instance()->script_src('js/jquery/jquery.scrollTo-min.js');
CADBiSNew::instance()->register_script('<script type="text/javascript">jQuery.noConflict();</script>');

if((isset($channelbuf) && isset($memorybuf)) && !($channelbuf->is_post_back() || $memorybuf->is_post_back()))
{
	$packets = $BILL->GetTarifs();
	$config = $BILL->GetCADBiSConfig();
	$packets_confs = array();
	foreach($packets as &$packet)
	{
		$accts = $BILL->GetTarifTodayAccts($packet['gid']);
		$packet['accts']['traffic'] = $accts['traffic'];
		$packet['accts']['time'] = $accts['time'];
		$packets_confs[$packet['gid']]['rang'] = new ajax_var('rng'.$packet['gid'],$packet['rang']);
		$packets_confs[$packet['gid']]['exceed_times'] = new ajax_var('et'.$packet['gid'],$packet['exceed_times']);
		$ajaxbuf->register_vars($packets_confs[$packet['gid']]);
	}
	$max_month_traffic = new ajax_var('max_month_traffic', $config['max_month_traffic']/1024/1024);
	$ajaxbuf->register_var($max_month_traffic);
	if($ajaxbuf->is_post_back())
	{
		foreach($packets as &$packet)
		{
			$packet['rang'] = $packets_confs[$packet['gid']]['rang']->get_value();
			$packet['exceed_times'] = $packets_confs[$packet['gid']]['exceed_times']->get_value();
			$BILL->UpdateTarif($packet['gid'], $packet);
		}   
		$BILL->UpdateConfigVar('max_month_traffic',$max_month_traffic->get_value()*1024*1024);
	}
	$daylimits = new PacketsTodayLimits($BILL);
}