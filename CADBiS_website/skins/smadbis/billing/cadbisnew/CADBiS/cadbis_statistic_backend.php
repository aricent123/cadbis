<?
require_once(dirname(__FILE__)."/../SMPHPToolkit/SMAjax.php");

$channelbuf = new ajax_buffer("channelbuf");
$channelbuf->set_method(ajax_buffer_method::APPEND_AFTER);
$channel_count = new ajax_var('channel_count',0);
$channelbuf->register_var($channel_count);

$memorybuf = new ajax_buffer("memorybuf");
$memorybuf->set_method(ajax_buffer_method::APPEND_AFTER);
$memory_count = new ajax_var('memory_count',0);
$memorybuf->register_var($memory_count);


if(isset($BILL)){
	$channel_loading = $BILL->getChannelLoading();
	$memory_usage = round($BILL->getMemoryUsage()/1024/1024,1);
}
else
{
	$channel_loading = rand(3,10);
	$memory_usage = rand(0,10);
}

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