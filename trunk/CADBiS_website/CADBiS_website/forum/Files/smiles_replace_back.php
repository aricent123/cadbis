<?php
for($i=1;$i<=$vars['smiles_count'];$i++)
	{
	if($i<10)
		{
		$message=str_replace("<img src=\"".$vars['dir_smiles']."/"."sml0$i.gif\">","[SML0$i]",$message);
		}
	else
		{
		$message=str_replace("<img src=\"".$vars['dir_smiles']."/"."sml$i.gif\">","[SML$i]",$message);
		}

	}
$message=str_replace("<table width=100% style=\"border: 1px solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;\"><td><SMALL><u>".$language['quote']."</u><tr><td><font size=1><SMALL>","[QUOTE]",$message);
if (strstr($message," ".$language['quote_man']."</u><tr><td><font size=1><SMALL>")==true)
	{
	$message=str_replace("<table width=100% style=\"border: 1px solid; padding-left: 4px; padding-right: 4px; padding-top: 1px; padding-bottom: 1px;\"><td><SMALL><u>","[QUOTE=",$message);
	$message=str_replace(" ".$language['quote_man']."</u><tr><td><font size=1><SMALL>","=QUOTE]",$message);
	}

$message=str_replace("</font></SMALL></table>","[/QUOTE]",$message);
if((strstr($message,"<font color=")==true)&&(strstr($message,"</font>")==true))
	{
	$message=str_replace("<font color=","[COLOR=",$message);
	$message=str_replace(" color>","=COLOR]",$message);
	$message=str_replace("</font>","[/COLOR]",$message);
	}
$message=str_replace("<font size=15>","[SIZE=BIG]",$message);
$message=str_replace("<font size=10>","[SIZE=AVG]",$message);
$message=str_replace("<font size=6>","[SIZE=SML]",$message);
$message=str_replace("</font>","[/SIZE]",$message);

$message=str_replace("<img border=0 src=\"","[IMG]",$message);
$message=str_replace("\" img>","[/IMG]",$message);
$message=str_replace("<b>","[B]",$message);
$message=str_replace("</b>","[/B]",$message);
$message=str_replace("<i>","[I]",$message);
$message=str_replace("</i>","[/I]",$message);
$message=str_replace("<u>","[U]",$message);
$message=str_replace("</u>","[/U]",$message);
$message=str_replace("<center>","[CENTER]",$message);
$message=str_replace("</center>","[/CENTER]",$message);

?>