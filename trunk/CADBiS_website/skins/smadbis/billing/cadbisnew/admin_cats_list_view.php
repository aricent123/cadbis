<h2>Настройка категорий:</h2>
<?=$emanager->render_client_side() ?>
		<div id="window-form-add" style="display:none">
		<table>
		<tr>
			<td>
				Название категории
			</td>
			<td>
				<input type="text" id="txtCatTitle" value=""/>
			</td>
		</tr>
		<tr>
			<td>
				Ключевые слова
			</td>
			<td>
				<textarea cols="25" rows="5" id="txtCatKeywords"></textarea>
			</td>
		</tr>
		
		</table>
		</div>
		<div id="window-form-delete" style="display:none">
			Вы уверены что хотите удалить категорию?
		</div>		
<script type="text/javascript">
function addCat(){
	var manager = <?=$emanager->client_id() ?>;
	Dialog.confirm($('window-form-add').innerHTML,{
		className:"alphacube", width:300, 
		okLabel: "Добавить", 
		cancelLabel: "Отмена", 
		onOk: function(win){  
				manager.addItem(
							'{"title":"'+$('txtCatTitle').value+'",'+
							'"keywords":"'+$('txtCatKeywords').value+'"}');
				win.hide();
			}
		});	
}
function editCat(id,title){
	var manager = <?=$emanager->client_id() ?>;	
	new Ajax.Request('<?=cadbisnewurl('admin_cats_list') ?>&renderkwdsfor='+id, {
		method: 'get',
		onSuccess: function(data) 
		{			
			Dialog.confirm($('window-form-add').innerHTML,{
				className:"alphacube", width:300, 
				okLabel: "Сохранить", 
				cancelLabel: "Отмена", 
				onOk: function(win){ 
						manager.updateItem(
								'{"cid":'+id+',"title":"'+$('txtCatTitle').value+'",'+
								'"keywords":"'+$('txtCatKeywords').value+'"}');
						win.hide();
					}
				});
			$('txtCatTitle').value = title;
			$('txtCatKeywords').value = data.responseText;			
		}});	
	

}
function deleteCat(id){
	var manager = <?=$emanager->client_id() ?>;
	Dialog.confirm($('window-form-delete').innerHTML,{
		className:"alphacube", width:250, 
		okLabel: "Удалить", 
		cancelLabel: "Отмена", 
		onOk: function(win){
				manager.deleteItem('{"cid":'+id+'}');  
				win.hide();
			}
		});		
}
</script>
<? $ajaxbuf_cats->start(); ?>	
	<?=$cats_grid->render(); ?>
<? $ajaxbuf_cats->end(); ?>
<a href="javascript:addCat();">Добавить запись</a>
<br/><br/>
<a href="javascript:history.back(1);">Назад</a>