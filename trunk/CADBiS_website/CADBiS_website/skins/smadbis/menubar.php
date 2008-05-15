<?php
$MNU=new CMenu();
$items= $MNU->GetItems();

for($i=0;$i<count($items);++$i)
 {
 if($items[$i]["level"]<=$CURRENT_USER["level"])
  {
  $LINK=$items[$i]["link"];
  $LINKTITLE=$items[$i]["title"];
  $TITLE=$items[$i]["title"];   
  ?>
	   <table width=206px cellspacing=0 cellpadding=0>
	    <tr><td background="<? OUT(SK_DIR) ?>/img/menu_item.gif" style="cursor:hand" onclick="document.location.href='<? OUT($LINK) ?>';">
	     <table width=206px height=42px><td align=center valign=center width=100% height=100%>
		<A href="<? OUT($LINK) ?>" title="<? OUT($LINKTITLE) ?>">
		<font class=menuitem>
		<? OUT($TITLE) ?>	
		</font>
		</a>
	     </td></table>
	    </td></tr>
	   </table>
  <?php
  }
 }

 ?>
  
