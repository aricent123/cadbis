<?
if($BILLEVEL<4) return;
?>
<table width=100%>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=tarifs&action=add';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_add_tarif.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=tarifs&action=add">Добавить тариф</a></b></div><br>
              Добавление нового тарифа.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=tarifs&action=delete';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_delete_tarif.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?p=smadbis&act=tarifs&action=delete">Удалить тариф</a></b></div><br>
              Удаление существующих тарифов.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?p=smadbis&act=tarifs&action=edit';">
      <td height=100px width=30% align=center bgcolor=#DDEEF3><img src="<? OUT(SK_DIR) ?>/img/bill_edit_tarif.gif"></td>
      <td bgcolor=#DDEEF3><div align=center><b><a href="?p=smadbis&act=tarifs&action=edit">Список тарифов</a></b></div><br>
              Вывести список всех тарифов с возможностью просмотра пользователей или редактирования.
      </td>
      </table>
     </td></tr>
     <tr><td width=50% class=tbl1>
     <table width=100% class=tbl2 style="cursor:hand;" cellspacing=0 cellpadding=0 onclick="document.location.href='?act=noskin&page=smadbis&noskinact=tarifs&action=cadbisnew';">
      <td height=100px width=30% align=center bgcolor=#F0F6F8><img src="<? OUT(SK_DIR) ?>/img/bill_delete_tarif.gif"></td>
      <td bgcolor=#F0F6F8><div align=center><b><a href="?act=noskin&page=smadbis&noskinact=tarifs&action=cadbisnew">Потребление трафика</a></b></div><br>
              Настройки месячного потребления трафика
      </td>
      </table>
     </td></tr>
</table>
 <br>
 <div align=center><a href="<? OUT("?p=$p") ?>">назад</a></div>