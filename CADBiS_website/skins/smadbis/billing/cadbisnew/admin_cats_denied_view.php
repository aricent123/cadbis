<h2>Запрещённые категории:</h2>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>
	<form action="" method="post">
	<input type="submit" name="btnSave" value="Сохранить"/><br/>
	<? for($i=0;$i<count($packets);++$i){ ?>
		<b><?=utils::cp2utf($packets[$i]['packet']) ?></b>:<br/>
		<? $cats_checked = $BILL->GetUrlCategoriesDenied($packets[$i]['gid']) ?>
		<? for($j=0;$j<count($cats);++$j) {?>
			<input type="checkbox" 
				name="deniedcats[<?=$packets[$i]['gid'] ?>][<?=$cats[$j]['cid'] ?>]" 
				<?=(in_array($cats[$j]['cid'],$cats_checked))?'checked':'' ?>/>
			<?=$cats[$j]['title'] ?><br/>
		<?} ?><br/><br/>
	<? } ?>
	<br/>
	<input type="submit" name="btnSave" value="Сохранить"/>
	</form>
<br/><br/>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>