<?php

echo("<CENTER><table>");
if ($user_rights<1)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[0]."(0).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][10]."</font></td><tr>");
	}
elseif ($user_rights<2)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[1]."(1).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][10]."</font></td><tr>");
	}
elseif ($user_rights<3)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[2]."(2).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][10]."</font></td><tr>");
	}
elseif ($user_rights<4)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[3]."(3).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][10]."</font></td><tr>");
	}
elseif ($user_rights<5)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[4]."(4).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][10]."</font></td><tr>");
	}

elseif ($user_rights<6)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[5]."(5).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][10]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][11]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][12]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][13]."</font></td><tr>");	
	}
elseif ($user_rights<7)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[6]."(6).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][10]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][11]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][12]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][2]." ".$language['showrights'][13]."</font></td><tr>");	
	}
elseif ($user_rights<8)
	{
	echo("<td><td>".$language['showrights'][0]." ".$levels[7]."(7).</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][3]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][4]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][5]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][6]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][7]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][8]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][9]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][10]."</font></td><tr>");
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][11]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][12]."</font></td><tr>");	
	echo("<td><li><td><font style=\"font-size=10;\">".$language['showrights'][1]." ".$language['showrights'][13]."</font></td><tr>");	
	}
echo("</table></CENTER>");
?>