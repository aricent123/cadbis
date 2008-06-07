<?
require_once(dirname(__FILE__)."/../SMPHPToolkit/SMAjax.php");
$update_url = './skins/smadbis/billing/cadbisnew/CADBiS/cadbis_statistic_update.php';

$channelbuf = new ajax_buffer("channelbuf");
$channelbuf->set_method(ajax_buffer_method::APPEND_AFTER);
$channel_count = new ajax_var('channel_count',0);
$channelbuf->register_var($channel_count);
$channelbuf->set_postback_url($update_url);

$memorybuf = new ajax_buffer("memorybuf");
$memorybuf->set_method(ajax_buffer_method::APPEND_AFTER);
$memory_count = new ajax_var('memory_count',0);
$memorybuf->register_var($memory_count);
$memorybuf->set_postback_url($update_url);

if(isset($BILL)){
	$channel_loading = $BILL->getChannelLoading();
	$memory_usage = round($BILL->getMemoryUsage()/1024/1024,1);
}else{die('ERROR');}

function calc_height($value, $start_coef=10,$limit = 200)
{
	$coef = $start_coef; 
	$height = $value*$coef; 
	while($height>$limit){
		if(--$coef<=1)
			$coef /= 2.0;
		$height=($value*$coef);
	}
	return $height;
}