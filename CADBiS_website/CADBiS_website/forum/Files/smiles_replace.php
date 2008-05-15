<?php
for($i=1;$i<=$vars['smiles_count'];$i++)
	{
	if($i<10)
		{
		$message=str_replace("[SML0$i]","<img src=\"".$vars['dir_smiles']."/"."sml0$i.gif\">",$message);
		}
	else
		{
		$message=str_replace("[SML$i]","<img src=\"".$vars['dir_smiles']."/"."sml$i.gif\">",$message);
		}

	}
$message=str_replace("[B]","<b>",$message);
$message=str_replace("[/B]","</b>",$message);
$message=str_replace("[I]","<i>",$message);
$message=str_replace("[/I]","</i>",$message);
$message=str_replace("[U]","<u>",$message);
$message=str_replace("[/U]","</u>",$message);
$message=str_replace("[CENTER]","<center>",$message);
$message=str_replace("[/CENTER]","</center>",$message);


if(((	strstr($message,"[QUOTE]")==true)||((strstr($message,"[QUOTE=")==true)&&(strstr($message,"]")==true)))&&(strstr($message,"[/QUOTE]")==true))
	{
$message=str_replace("[QUOTE]","<table width=100% style=\"border: 1px solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;\"><td><SMALL><u>".$language['quote']."</u><tr><td><font size=1><SMALL>",$message);
if (strstr($message,"[QUOTE=")==true)
	{
	$message=str_replace("[QUOTE=","<table width=100% style=\"border: 1px solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;\"><td><SMALL><u>",$message);
	$message=str_replace("=QUOTE]"," ".$language['quote_man']."</u><tr><td><font size=1><SMALL>",$message);
	}
$message=str_replace("[/QUOTE]","</font></SMALL></table>",$message);
	}

if((strstr($message,"[COLOR=")==true)&&(strstr($message,"[/COLOR]")==true))
	{
	$message=str_replace("[COLOR=","<font color=",$message);
	$message=str_replace("=COLOR]"," color>",$message);
	$message=str_replace("[/COLOR]","</font>",$message);
	}
$message=str_replace("[SIZE=BIG]","<font size=15>",$message);
$message=str_replace("[SIZE=AVG]","<font size=10>",$message);
$message=str_replace("[SIZE=SML]","<font size=6>",$message);
$message=str_replace("[/SIZE]","</font>",$message);

$message=str_replace("[COLOR=","<font color",$message);
$message=str_replace("[IMG]","<img border=0 src=\"",$message);
$message=str_replace("[/IMG]","\" img>",$message);

?>