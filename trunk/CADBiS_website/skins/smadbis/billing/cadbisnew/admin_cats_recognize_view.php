<div align=center>
	<b><font class=fontheader>Распознание категории Интернет-сайта:</font></b>
</div>
<br/>
	<form action="<?=cadbisnewurl('admin_cats_recognize') ?>" method="post">
		<b>URL:</b><input type="text" style="width:350px" name="url" value="<?=$url ?>"/>
		<input type="submit" name="btnSubmit" value="Распознать"/>
	</form>
	Текущая категория данного URL: <?=$cats[$cat_by_cid[$current_cid]]['title'] ?><br/>
	<? if(!empty($result))
	{ ?>
		<?if(isset($set)){ ?>
			Добавление <?=$url ?> к категории "<?=$cats[$cat_by_cid[$setcid]]['title'] ?>"<br/><br/>
			<b>Обнаруженные конфликты:</b>
			<form action="<?=cadbisnewurl('admin_cats_recognize') ?>" method="post">
				<input type="hidden" name="setcid" value="<?=$setcid ?>"/>
				<table width="100%">
				<tr>
					<td class="tbl1"><b>Ключевое слово</b></td>
					<td class="tbl1"><b>Конфликтная категория</b></td>
					<td class="tbl1"><b>Действия</b></td></tr>
				<? foreach($conflict_cats as $cid=>$cwords) { ?>
					<? foreach($cwords as $cword=>$wcount){ ?>
						<tr>
							<td class="tbl1">							
									<?=$cword ?> (<?= $wcount?>)<br/>							
							</td>
							<td class="tbl1">
								<?=$cats[$cat_by_cid[$cid]]['title'] ?>
							</td>
							<td class="tbl1">
								<label><input type="radio" name="actionfor[<?=$cword ?>]" value="noaction" checked>Оставить</label>
								<label><input type="radio" name="actionfor[<?=$cword ?>]" value="delete">Удалить</label>
								<label><input type="radio" name="actionfor[<?=$cword ?>]" value="replace">Заменить</label>
								<label><input type="radio" name="actionfor[<?=$cword ?>]" value="unsense">Несмысловое</label>
							</td>
						</tr>
					<?}?>
				<?}?>
				<? if(count($conflict_cats) == 0){?>
					<tr><td colspan="3">Конфликтов нет, все ключевые слова добавлены к категории <?=$cats[$cat_by_cid[$setcid]]['title'] ?></td></tr>
				<?} ?>
				</table>
				<div align="right" style="padding-right:30px;">
					<input type="submit" name="btnResolveConflicts" value="OK"/>
				</div>
			</form>
		<?}else{ ?>
			<b>Предполагаемые категории:</b><br/>
			<table width="100%">
			<tr>
				<td class="tbl1"><b>Категория</b></td>
				<td class="tbl1"><b>Слова</b></td>
				<td class="tbl1"><b>Баллы</b></td>
				<td class="tbl1"><b>Действия</b></td>
			</tr>
			<? foreach($result['ordcoefs'] as $ccoef)
				if($ccoef['coef']>0){?>
				<tr>
					<td class="tbl1" valign="top">
					<?=$cats[$cat_by_cid[$ccoef['cid']]]['title'] ?>(<?=$ccoef['cid'] ?>)
					</td>
					<td class="tbl1" valign="top">
						<? foreach($ccoef['keywords'] as $keyword=>$count){ ?>
							<?=$keyword ?>(<?=$count ?>)<br/>
						<? } ?>
					</td>				
					<td class="tbl1" valign="top">
						<?=$ccoef['coef']?>
					</td>
					<td class="tbl1" valign="top">
					<a href="<?=cadbisnewurl('admin_cats_recognize') ?>&manualcheck=true&set=true&setcid=<?=$ccoef['cid'] ?>&url=<?=$url ?>">Назначить</a>
					</td>
				</tr>		
			<?} ?>		
			</table>
			<form action="<?=cadbisnewurl('admin_cats_recognize') ?>&manualcheck=true&set=true&url=<?=$url ?>" method="post">
				<select name="setcid">
					<? foreach($cats as $cat) {?>
					<option value="<?=$cat['cid'] ?>"<?=$cat['cid']?><?=($result['ordcoefs'][0]['cid'] == $cat['cid'])?' selected':'' ?>>
						<?=$cat['title'] ?>
					</option>
					<?} ?>
				</select>
				<input type="submit" name="btnAttach" value="Назначить категорию"/>
			</form>
			<b>Частые слова контента:</b>
			<? foreach($result['cwordord'] as $cword){?>
				<?if($cword['wcount']>Recognizer::MINIMAL_CWORD_COEF){ ?>
					<?=$cword['cword'] ?>(<?=$cword['wcount'] ?>),
				<?} ?>
			<?} ?>
		<?} ?>
	<?} ?>		
<br/><br/>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>