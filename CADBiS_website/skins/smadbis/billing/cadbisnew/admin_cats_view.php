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
				cadbisnewurl('admin_cats_urlconflicts'),
				'Конфликтные категории',
				'#DDEEF3',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Обнаруженные конфликтные категории, разрешение конфликтов.') ?>				
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_denied'),
				'Запрещённые категории',
				'#F0F6F8',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор запрещённых категорий для тарифов.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_unsensewords'),
				'Неучитываемые слова',
				'#DDEEF3',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор слов, не несущих смысловой нагрузки.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_match'),
				'Соответствие URL категориям',
				'#F0F6F8',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Редактор соответствий.') ?>
	<?=CADBiSNew::instance()->render_menu_item(
				cadbisnewurl('admin_cats_recognize'),
				'Распознаватель категорий',
				'#DDEEF3',
				SK_DIR.'/img/bill_ctfilter.gif',
				'Распознаватель категорий Интернет-сайтов.') ?>								
	</table>				
	
<br/><br/>
<a href="?p=smadbis">Назад</a>