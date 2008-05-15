<div align=center>
	<b><font class=fontheader>Распознание категории Интернет-сайта:</font></b>
</div>
<br/>
	<form action="" method="post">
		<input type="text" style="width:350px" name="tbUrl" value="<?=$url ?>"/>
		<input type="submit" name="btnSubmit" value="Распознать"/>
	</form>

	<? if(!empty($result)){ ?>
	<?=$result ?>
	<?} ?>
<br/><br/>
<a href="?p=smadbis">Назад</a>