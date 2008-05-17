<h2>Конфликты ключевых слов для сайтов:</h2>
<form action="<?=cadbisnewurl('admin_cats_urlconflicts')?>&resolve=true" method="post"/>
	<? $ajaxbuffer->start(); ?>	
		<?=$grid->render(); ?>
	<? $ajaxbuffer->end(); ?>
<br/>
<?if($datasource->get_rows_count()>0){ ?>
<b>Выберите действие для выбранных конфликтных ключевых слов:</b><br/>
	<input type="submit" name="btnLeave" value="Оставить"/>
	<input type="submit" name="btnReplace" value="Заменить"/>
	<input type="submit" name="btnDelete" value="Удалить"/>
	<input type="submit" name="btnUnsense" value="Несмысловые"/>
<?} ?>
</form>
<br/><br/>

<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>