<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>
<h2>Конфликты ключевых слов для сайтов:</h2>
<script type="text/javascript">
	function checkAll()
	{
		var els = document.getElementsByTagName('input');
		for(var i=0;i<els.length;++i)
			if(els[i].type=='checkbox')
				els[i].checked=!els[i].checked;
	}
</script>
<?function draw_controls($datasource){
if($datasource->get_rows_count()>0){ ?>
<a href="javascript:checkAll()">Отметить все</a>
<b>Выберите действие для выбранных конфликтных ключевых слов:</b><br/>
	<input type="submit" name="btnLeave" value="Оставить"/>
	<input type="submit" name="btnReplace" value="Заменить"/>
	<input type="submit" name="btnDelete" value="Удалить"/>
	<input type="submit" name="btnUnsense" value="Несмысловые"/>
<?} 
}?>

<form action="<?=cadbisnewurl('admin_cats_urlconflicts')?>&resolve=true" method="post"/>
<?=draw_controls($datasource) ?>
	<? $ajaxbuffer->start(); ?>	
		<?=$grid->render(); ?>
	<? $ajaxbuffer->end(); ?>
<br/>
<?=draw_controls($datasource) ?>
</form>
<br/><br/>

<a href="<?=cadbisnewurl('admin_cats') ?>">Назад</a>