<?php 
global $HTTP_SERVER_VARS;
$Browser=$HTTP_SERVER_VARS['HTTP_USER_AGENT'];
if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Gecko")==false)&&(strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
{
$user_browser="Internet Explorer";
}
elseif((strstr($Browser,"Gecko")==true)&&(strstr($Browser,"Netscape")==false))
{
$user_browser="Mozilla";
}
elseif((strstr($Browser,"Netscape")==true)&&(strstr($Browser,"Gecko")==false))
{
$user_browser="Netscape";
}
elseif(strstr($Browser,"Opera")==true)
{
$user_browser="Opera";
}
elseif((strstr($Browser,"Netscape")==true)&&(strstr($Browser,"Gecko")==true))
{
$user_browser="Netscape/Mozilla";
}

if((strstr($Browser,"IE")==true)&&((strstr($Browser,"Gecko")==false)&&(strstr($Browser,"Netscape")==false)&&(strstr($Browser,"Opera")==false)))
{
echo("
<script language=JavaScript> 
selectedtext=0;
form1.message.focus();
function do_text(ths,pole,first,end) {
var s;	
if (first == '' && end == \"".$smiles[1]['code']."\") {
s = end;
selectedtext.text = s;
}
");
for($i=2;$i<=$vars['smiles_count'];$i++)
	{
	echo("
	else if (first == '' && end == \"".$smiles[$i]['code']."\") {
	s = end;
	selectedtext.text = s;
	}");

	}
echo("
else 
{
txt = document.selection.createRange().text;
lasttext = pole.value;
if (txt == \"\")
	{
	if (first == '' && end == \"[END]\") 
		{
		selectedtext.text = s;
		}
		else 
		{
		alert('".$language['smiles_selectfirst']."');
		}	
	}
	else 
	{
	pos1 = pole.value.indexOf(txt);
	pos2 = txt.length + pos1;
	s = first + txt + end;
	selectedtext.text = s;
	}
}
pole.focus();
}

function do_text2(sml) {
s=form1.message.value;
s=s+sml;
form1.message.value=s;
selectedtext = s;
form1.message.focus();
}

</script>
<center>
<script language=JavaScript> 
function quote_man()
	{
	name=prompt('".$language['smiles_entername']."',name) || '';
	if(name!='')do_text(form1,form1.message,'[QUOTE='+name+'=QUOTE]','[/QUOTE]');
	}
</script>
<font class='inputs1' style='border:0px;'>
<center>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[CENTER]','[/CENTER]');\" class='inputs1' style=\"$input_button\" type='button' value='".$language['smiles_center']."'>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[U]','[/U]');\" style='text-decoration:underline;$input_button' class='inputs1' type='button' value='".$language['smiles_underline']."'>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[B]','[/B]');\" style='font-weight:bold;$input_button' class='inputs1' type='button' value='".$language['smiles_bold']."'>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[I]','[/I]');\" style='font-style:italic;$input_button' class='inputs1' type='button' value='".$language['smiles_italic']."'><br>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[QUOTE]','[/QUOTE]');\" style=\"$input_button\" class='inputs1' type='button' value='".$language['smiles_quote']."'>
<input TABINDEX=-1 onclick=\"do_text(form1,form1.message,'[IMG]','[/IMG]');\" class='inputs1'style=\"$input_button\" type='button' value='".$language['smiles_image']."'>
<input TABINDEX=-1 onclick=\"quote_man()\" class='inputs1' type='button' style=\"$input_button\" value='".$language['smiles_quoteman']."'><br>
<select name=fcolor TABINDEX=-1 onchange=\"do_text(form1,form1.message,'[COLOR='+fcolor.value+'=COLOR]','[/COLOR]');\" class='inputs1'style=\"$input_button\">
<option style=\"Background:black;color:white\" value=\"black\">-- COLOR --
<option style=\"Background:white;\" value=\"white\">
<option style=\"Background:red;\" value=\"red\">
<option style=\"Background:orange;\" value=\"orange\">
<option style=\"Background:yellow;\" value=\"yellow\">
<option style=\"Background:green;\" value=\"green\">
<option style=\"Background:LightBlue;\" value=\"LightBlue\">
<option style=\"Background:blue;\" value=\"blue\">
<option style=\"Background:SlateBlue;\" value=\"SlateBlue\">
</select><br>

");

//for($i=1;$i<=$vars['smiles_count'];$i++)
for($i=1;$i<=$vars['smls_count'];$i++)
	{
	echo("
	<img TABINDEX=-1 onclick=\"do_text(form1,form1.message,'','".$smiles[$i]['code']."');\" src='".$vars['dir_smiles']."/".$smiles[$i]['img']."' style='position:relative;top:2px;cursor:hand;'>
	");
	}
if($vars['smls_count']<$vars['smiles_count'])
	echo("<CENTER><SMALL><a href=\"javascript:window.open('?p=$p&forum_ext=$forum_ext&id=smiles1','smiles1','scrollbars,width=750,height=150,resizable');void(0); \">".$language['smiles_moresmiles']."</a></CENTER></SMALL>");
}
else
{
echo("<font size=2>
<SMALL>".$language['smiles_jsoff1']." ($user_browser) ".$language['smiles_jsoff2']."</SMALL><Br>

".$language['smiles_tagsinfo']."<br>


</table><CENTER><table><td><CENTER><SMALL><font size=2>
");
echo("<br>".$language['smiles_writenext']."<br><table><td><table><Td><SMALL><font size=2>");
//for($i=1;$i<=$vars['smiles_count'];$i++)
for($i=1;$i<=$vars['smls_count'];$i++)
	{echo("
	".$smiles[$i]['code']."<td><img src='".$vars['dir_smiles']."/".$smiles[$i]['img']."'><td><SMALL><font size=2>
	");
	if($i/7==round($i/7))echo("</table><tr><td><table><td><SMALL><font size=2>");
	}
	echo("</table>");
	if($vars['smls_count']<$vars['smiles_count'])
	echo("<CENTER><SMALL><a href=\"javascript:window.open('?p=$p&forum_ext=$forum_ext&id=smiles1','smiles1','scrollbars,width=750,height=150,resizable');void(0); \">".$language['smiles_moresmiles']."</a></CENTER></SMALL>");
	echo("
	</table>
	");
	
}
?>
