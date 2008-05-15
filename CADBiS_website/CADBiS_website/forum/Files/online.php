<?php

include "$file_read_online";

echo("<CENTER>".$language['online_total']." ".($regs+$guests)."; ".$language['online_guests']." $guests; ".$language['online_registered']." ".($regs)."");
if ($vars['show_online']=="true")
	{
	echo("<BR>".$language['online_whois']."");
	$h=0;
	for($i=0;$i<count($online['type']);$i++)
		{
		if($online['type'][$i]=="reg")
			{
			if (($h>0)&&($h<count($online['type'])-1)) echo(", ");
			echo("".$online['nick'][$i]."");
			$h++;
			}
		}
	}
echo("</CENTER>");
?>