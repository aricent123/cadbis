<h2>Статус системы:</h2>
<? require_once(dirname(__FILE__)."/CADBiS/cadbis_statistic_view.php"); ?>
<br/>
	Месячный порог трафика:
	<input type="text" value="<?=$max_month_traffic->get_value()?>"
		onchange="<?= $ajaxbuf->client_id()?>.set_var('<?=$max_month_traffic->client_id() ?>',this.value)"> (Мб)
	<input class="button" type="button" onclick="<?=$ajaxbuf->client_id() ?>.update()" value="Пересчитать"/>
	<? $ajaxbuf->start(); ?>
	<br/>
	<? include dirname(__FILE__)."/../month_stats.php" ?>
		<table class="wide-table" cellpadding="0" cellspacing="0">
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
		<? for($i=0;$i<count($packets);++$i){
			$sum_of_users_count+=$packets[$i]['users_count']; 
			$sum_of_simuluse_sum+=$packets[$i]['simuluse_sum']; 
			$limit = $daylimits->getPacketDayTrafficLimit($packets[$i]['gid']);
			$used = $packets[$i]['accts']['traffic'];
			$pused = round(($used/$limit*100.0));
			$prest = round(100-$pused);
			$sum_of_day_limits+=$daylimits->getPacketDayTrafficLimit($packets[$i]['gid']);
			$sum_of_exceed_times+=$packets[$i]['exceed_times']; 
			$sum_of_rangs+=$packets[$i]['rang'];
		?>		
		
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
				<?=make_fsize_str($used) ?>/<?=make_fsize_str($limit)?>				
				<br/>
				<table class="bar-used-rest" cellspacing="0" cellpadding="0">
				<tr>
					<td class="bar-used" style="width:<?=$pused ?>%"></td>
					<td class="bar-rest" style="width:<?=$prest ?>%"></td>
				</tr>
				</table>				
			</td>
		</tr>
		<?} ?>
		<tr>
			<td>
				<b><?=$sum_of_users_count?>/<?=$sum_of_simuluse_sum ?></b>
			</td>
			<td>
				<b><?=$sum_of_rangs?></b>
			</td>		
			<td>
				<b><?=$sum_of_exceed_times?></b>
			</td>
			<td>
				<b><?=make_fsize_str($sum_of_day_limits)?>/
					<?=make_fsize_str($daylimits->getAllowedDayTraffic()) ?>/
					<?=make_fsize_str($daylimits->getRestMonthTraffic()) ?>
				</b>
			</td>
		</tr>
		</table>
	Стоимость 1 балла ранга равна <?=make_fsize_str($daylimits->getOnePointCost()) ?> на человека
	<? $ajaxbuf->end(); ?>
	<input class="button" type="button" onclick="<?=$ajaxbuf->client_id() ?>.update()" value="Пересчитать"/>
	<br/><br/>
<a href="javascript:history.back(1);">Назад</a>