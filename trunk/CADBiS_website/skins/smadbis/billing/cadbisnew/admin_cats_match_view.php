<h2>Редактирование URL-ов:</h2>
<?=$emanager->render_client_side() ?>
		<div id="window-form-add" style="display:none">
		<table>
		<tr>
			<td>
				URL
			</td>
			<td>
				<input type="text" id="txtURL" value=""/>
			</td>
		</tr>
		<tr>
			<td>
				Категория
			</td>
			<td>
				<select id="selCid">
					<? for($i=0;$i<count($cats);++$i) {?>
					<option value="<?=$cats[$i]['cid'] ?>"><?=$cats[$i]['title'] ?></option>
					<?} ?>
				</select>
			</td>
		</tr>
		
		</table>
		</div>
		<div id="window-form-delete" style="display:none">
			Вы уверены что хотите удалить URL?
		</div>		
<script type="text/javascript">
function GridsInitialized()
 {
	<?=$ajaxbuf_url_matched_cats->client_id()?>.onSuccess = function()
		{
			<?=$ajaxbuf_url_cats->client_id()?>.update();
			<?=$ajaxbuf_url_matched_cats->client_id()?>.onSuccess = function(){};
		};
	<?=$ajaxbuf_url_cats->client_id()?>.onSuccess = function()
		{
			<?=$ajaxbuf_url_matched_cats->client_id()?>.update();
			<?=$ajaxbuf_url_cats->client_id()?>.onSuccess = function(){};
		};
 }
Event.observe(window,'load',GridsInitialized);
function addURL(manager){
	Dialog.confirm($('window-form-add').innerHTML,{
		className:"alphacube", width:300, 
		okLabel: "Добавить", 
		cancelLabel: "Отмена", 
		onOk: function(win){  
				GridsInitialized();
				manager.addItem(
							'{"url":"'+$('txtURL').value+'",'+
							'"cid":"'+$('selCid').value+'"}');				
				win.hide();
			}
		});	
}
function editURL(manager,id,url,cid){
	Dialog.confirm($('window-form-add').innerHTML,{
		className:"alphacube", width:300, 
		okLabel: "Сохранить", 
		cancelLabel: "Отмена", 
		onOk: function(win){ 
				GridsInitialized();
				manager.updateItem(
						'{"u2cid":'+id+',"url":"'+$('txtURL').value+'",'+
						'"cid":"'+$('selCid').value+'"}');				
				win.hide();
			}
		});
	$('txtURL').value = url;
	$('selCid').value = cid;
}
function deleteURL(manager,id){
	Dialog.confirm($('window-form-delete').innerHTML,{
		className:"alphacube", width:250, 
		okLabel: "Удалить", 
		cancelLabel: "Отмена", 
		onOk: function(win){
				GridsInitialized();
				manager.deleteItem('{"u2cid":'+id+'}');  				
				win.hide();
			}
		});		
}
</script>
<h3>Сайты с неопознанным контентом:</h3>
<? $ajaxbuf_url_cats->start(); ?>	
	<?=$url_cats_unmatched_grid->render(); ?>
<? $ajaxbuf_url_cats->end(); ?>
<h3>Сайты с назначенной категорией:</h3>
<? $ajaxbuf_url_matched_cats->start(); ?>	
	<?=$url_cats_matched_grid->render(); ?>
<? $ajaxbuf_url_matched_cats->end(); ?>
<a href="javascript:addURL(<?=$emanager->client_id() ?>);">Добавить URL</a>
<br/><br/>

<a href="javascript:history.back(1);">Назад</a>