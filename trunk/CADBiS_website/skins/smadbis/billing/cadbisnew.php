<?php
error_reporting(E_PARSE);
require_once(dirname(__FILE__)."/../../../test/SM/SMPHPToolkit/SMAjax.php");
require_once(dirname(__FILE__)."/../../../test/SM/CADBiS/PacketsTodayLimits.php");
header("Content-Type: text/html;charset=UTF-8");
$ajaxbuf = new ajax_buffer("update_buffer");
$ajaxbuf->set_postback_url($_SERVER['REQUEST_URI']);
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}


$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
$packets = $BILL->GetTarifs();

$config = $BILL->GetCADBiSConfig();

$packets_confs = array();
foreach($packets as $packet)
{
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



?>
<html>
<head>
<style type="text/css">
	@IMPORT url("skins/smadbis/css/ajax.css");
	.wide-table{
		width: 100%;
		border: 1px solid black;
	}
</style>
  <script type="text/javascript" src="js/scriptaculous/prototype.old.js"></script>
  <script type="text/javascript" src="js/ajax/engine.js"></script>
</head>
<body>
<h1>Настройки рейтинга групп:</h1>
	Максимальное месячное количество трафика:
	<input type="text" value="<?=$max_month_traffic->get_value()?>"
		onchange="<?= $ajaxbuf->client_id()?>.set_var('<?=$max_month_traffic->client_id() ?>',this.value)"> (Мб)
	<input type="button" onclick="<?=$ajaxbuf->client_id() ?>.update()" value="Пересчитать"/>
	<? $ajaxbuf->start(); ?>
	<br/>
	<table>
	<tr>
		<td>Потреблённый трафик:</td><td><?=make_fsize_str($daylimits->getUsedMonthTraffic()) ?></td>
	</tr>
	<tr>
		<td>Оставшееся число дней:</td><td><?=$daylimits->getRestDaysCount()?></td>
	</tr>
	<tr>
		<td>Оставшийся трафик:</td><td><?=make_fsize_str($daylimits->getRestMonthTraffic()) ?></td>
	</tr>
	<tr>
		<td>Дневная норма трафика:</td><td><?=make_fsize_str($daylimits->getAllowedDayTraffic()) ?></td>
	</tr>
	</table>
	<? include "month_stats.php" ?>
		<table class="wide-table">
		<tr>
			<td>
				Тариф (к-во юзеров)
			</td>
			<td>
				Ранг
			</td>
			<td>
				Разрешённое превышение (раз)
			</td>
			<td>
				Пересчитанный дневной максимум на группу (Мб)
			</td>		
		</tr>
		<? $sum_of_day_limits = 0; ?>	
		<? for($i=0;$i<count($packets);++$i){ ?>
		<tr>
			<td>
				<?=utils::cp2utf($packets[$i]['packet'])?>
				(<?=$packets[$i]['users_count'] ?>/<?=$packets[$i]['simuluse_sum'] ?>)
			</td>
			<td>
				<input type="text" value="<?=$packets[$i]['rang']?>" 
					onchange="<?= $ajaxbuf->client_id()?>.set_var('<?=$packets_confs[$packets[$i]['gid']]['rang']->client_id() ?>',this.value)"/>
			</td>		
			<td>
				<input type="text" value="<?=$packets[$i]['exceed_times']?>" 
					onchange="<?= $ajaxbuf->client_id()?>.set_var('<?=$packets_confs[$packets[$i]['gid']]['exceed_times']->client_id() ?>',this.value)"/>
			</td>
			<td>
				<?=make_fsize_str($daylimits->getPacketDayTrafficLimit($packets[$i]['gid']))?>
				<? $sum_of_day_limits+=$daylimits->getPacketDayTrafficLimit($packets[$i]['gid']); ?>
			</td>
		</tr>
		<?} ?>
		<tr>
			<td>
			</td>
			<td>
			</td>		
			<td>
			</td>
			<td>
				<b><?=make_fsize_str($sum_of_day_limits)?></b>
			</td>
		</tr>
		</table>
	Стоимость 1 балла ранга равна <?=make_fsize_str($daylimits->getOnePointCost()) ?> на человека
	<? $ajaxbuf->end(); ?>
</body>
</html>