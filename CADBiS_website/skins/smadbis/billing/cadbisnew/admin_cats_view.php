<div align=center>
	<b><font class=fontheader>Настройки контентного фильтра:</font></b>
</div>
<br/>
   <table width="100%" id="menu">
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_list'),
				'Список категорий',
				'#F0F6F8',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор списка категорий.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_denied'),
				'Запрещённые категории',
				'#DDEEF3',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор запрещённых категорий для тарифов.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_unsensewords'),
				'Неучитываемые слова',
				'#F0F6F8',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор слов, не несущих смысловой нагрузки.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_match'),
				'Соответствие URL категориям',
				'#DDEEF3',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор соответствий.') ?>
	</table>				
	
<br/><br/>
<a href="javascript:history.back(1);">Назад</a>