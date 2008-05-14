<h2>Запрещённые категории:</h2>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>
	<form action="" method="post">
		Выбрать тариф:
		<select name="selPacket">
			<? for($i=0;$i<count($packets);++$i){ ?>
				<option value="<?=$packets[$i]['gid']?>"<?=($gid==$packets[$i]['gid'])?' selected':''?>>
					<?=utils::cp2utf($packets[$i]['packet']) ?>
				</option>
			<? } ?>
		</select>
		<input type="submit" name="btnFilter" value="Фильтровать"/>
	</form>
<? if($packet != null){ ?>
	<form action="" method="post">
	<input type="hidden" name="hdnGid" value="<?=$packet['gid'] ?>"/>
	<input type="submit" name="btnSave" value="Сохранить"/><br/>
		<b><?=utils::cp2utf($packet['packet']) ?></b>:<br/>
		<? $cats_checked = $BILL->GetUrlCategoriesDenied($packet['gid']) ?>
		<? for($j=0;$j<count($cats);++$j) {?>
			<input type="checkbox" 
				name="deniedcats[<?=$packet['gid'] ?>][<?=$cats[$j]['cid'] ?>]" 
				<?=(in_array($cats[$j]['cid'],$cats_checked))?'checked':'' ?>/>
			<?=$cats[$j]['title'] ?><br/>
		<?} ?><br/><br/>

	<br/>
	<input type="submit" name="btnSave" value="Сохранить"/>
	</form>
<? } ?>
<br/><br/>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>