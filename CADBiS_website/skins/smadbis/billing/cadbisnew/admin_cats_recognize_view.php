<div align=center>
	<b><font class=fontheader>Распознание категории Интернет-сайта:</font></b>
</div>
<br/>
	<form action="<?=cadbisnewurl('admin_cats_recognize') ?>" method="post">
		<b>URL:</b><input type="text" style="width:350px" name="url" value="<?=$url ?>"/>
		<input type="submit" name="btnSubmit" value="Распознать"/>
	</form>

	<? if(!empty($result)){ ?>
		<?if(isset($set)){
			$c_count = 0; 
			?>
			Добавление <?=$url ?> к категории "<?=$cats[$cat_by_cid[$setcid]]['title'] ?>"<br/><br/>
			<b>Обнаруженные конфликты:</b>
			<form action="<?=cadbisnewurl('admin_cats_recognize') ?>" method="post">
				<table width="100%">
				<tr>
					<td class="tbl1"><b>Ключевое слово</b></td>
					<td class="tbl1"><b>Конфликтная категория</b></td>
					<td class="tbl1"><b>Действия</b></td></tr>
				<? foreach($result['cwords'] as $word=>$wcount) {
					if($wcount<Recognizer::MINIMAL_CWORD_COEF)
						continue;
					$c_cid = $BILL->GetUrlCategoryKeyword($word);
					if($c_cid>0 && $c_cid != $setcid){
						$c_count++;				
					?>
					<tr>
						<td class="tbl1"><?=$word ?>(<?=$wcount ?>)</td>
						<td class="tbl1">
							<?=$cats[$cat_by_cid[$c_cid]]['title'] ?>
						</td>
						<td class="tbl1">
							<label><input type="radio" name="actionfor[<?=$word ?>]" value="noaction" checked>Оставить</label>
							<label><input type="radio" name="actionfor[<?=$word ?>]" value="delete">Заменить</label>
							<label><input type="radio" name="actionfor[<?=$word ?>]" value="unsense">Несмысловое</label>
						</td>
					</tr>
					<? }?>
				<?}
				if($c_count == 0){
				?>
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
					<td class="tbl1">
					<?=$cats[$cat_by_cid[$ccoef['cid']]]['title'] ?>(<?=$ccoef['cid'] ?>)
					</td>
					<td class="tbl1">
						<? foreach($ccoef['keywords'] as $keyword=>$count){ ?>
							<?=$keyword ?>(<?=$count ?>)<br/>
						<? } ?>
					</td>				
					<td class="tbl1">
						<?=$ccoef['coef']?>
					</td>
					<td class="tbl1">
					<a href="<?=cadbisnewurl('admin_cats_recognize') ?>&manualcheck=true&set=true&setcid=<?=$ccoef['cid'] ?>&url=<?=$url ?>">Назначить</a>
					</td>
				</tr>		
			<?} ?>		
			</table>
			<form action="<?=cadbisnewurl('admin_cats_recognize') ?>&manualcheck=true&set=true&url=<?=$url ?>" method="post">
				<select name="setcid">
					<? for($i=0;$i<count($cats);++$i) {?>
					<option value="<?=$cats[$i]['cid'] ?>"><?=$cats[$i]['title'] ?></option>
					<?} ?>
				</select>
				<input type="submit" name="btnAttach" value="Назначить категорию"/>
			</form>
			<b>Частые слова контента:</b>
			<? foreach($result['cwords'] as $cword => $wcount){?>
				<?if($wcount>Recognizer::MINIMAL_CWORD_COEF){ ?>
					<?=$cword ?>(<?=$wcount ?>),
				<?} ?>
			<?} ?>
		<?} ?>
	<?} ?>
<br/><br/>
<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>