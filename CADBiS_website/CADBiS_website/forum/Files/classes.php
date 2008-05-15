<?php
//Основной класс с графиками
class TGraph
{
 function Draw($param,$data,$label)
 {
		/*
		$param - means of parameters array elements.
	  	0 - image width (int)
		1 - image height (int)
		2 - red of background (int)
		3 - green of background (int)
		4 - blue of background (int)
		5 - red of axe (int)
		6 - green of axe (int)
		7 - blue of axe (int)
		8 - red of graph (int)
		9 - green of graph (int)
		10 - blue of graph (int)
		11 - x_border (int)
		12 - y_border (int)
		13 - font filename (string)
		14 - graph header (string)
		15 - axe X label (string)
		16 - axe Y label (string)
		17 - graph type (int)
		18 - output file name (string)
		19 - draw values label (bool)
		*/
		
		$imgw=$param[0];
		$imgh=$param[1];
		$im=imagecreate($imgw,$imgh);
		$bgcolor=imagecolorallocate($im,$param[2],$param[3],$param[4]);
		$axe_color=imagecolorallocate($im,$param[5],$param[6],$param[7]);
		$graph_color=imagecolorallocate($im,$param[8],$param[9],$param[10]);
		
		$x_left=$param[11];
		$x_width=$imgw-2*$x_left;
		$y_top=$param[12];
		$y_height=$imgh-2*$y_top;
		
		$font=$param[13];
		if(file_exists($font))
			{
			imagettftext($im,18,0,$x_left,$y_top-20,$axe_color,$font,$param[14]);
			imagettftext($im,14,90,$x_left-10,$imgh-$y_top,$axe_color,$font,$param[15]);
			imagettftext($im,14,0,$x_left,$imgh-$y_top+35,$axe_color,$font,$param[16]);
			}
			else
			{
			imagestring($im,5,$x_left,$y_top-35,$param[14],$axe_color);
			imagestring($im,4,$x_left,$y_top+$y_height+15,$param[15],$axe_color);
			imagestringup($im,4,$x_left-25,$imgw-$y_top,$param[16],$axe_color);
			}
			
		$maxy=max($data);
		$maxx=sizeof($data);
		if($param[17]==1)
		{
		for($i=0; $i<=$maxx-1; $i++)
			{
			$x=$x_left+$i*$x_width/($maxx);
			$x1=$x_left+$i*$x_width/($maxx);
			$x2=$x_left+($i+1)*$x_width/($maxx);
			$y=$imgh-$y_top-$data[$i]*($imgh-2*$y_top)/$maxy;
			
			imagefilledrectangle($im,$x,$y,$x+$x_width/($maxx),$imgh-$y_top,$graph_color);
			
			imageline($im,$x1,$imgh-$y_top,$x1,$y,$axe_color);
			imageline($im,$x1,$y,$x2,$y,$axe_color);
			imageline($im,$x2,$imgh-$y_top,$x2,$y,$axe_color);
			imageline($im,$x2,$imgh-$y_top,$x2,$y,$axe_color);
			
			if ($data[$i]!=0 && $param[19]==true) 
				{
				imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
				}
			if(($i)/$param[19]==round(($i)/$param[19]))imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
			}
		}
		elseif($param[17]==2)
		{
		$rebro=5;
		for($i=0; $i<=$maxx-1; $i++)
			{
			$x=$x_left+$i*$x_width/($maxx-1);
			$y=$imgh-$y_top-$data[$i]*($imgh-2*$y_top)/$maxy;
			imageline($im,$x-$rebro,$y-$rebro,$x-$rebro,$y+$rebro,$graph_color);
			imageline($im,$x-$rebro,$y+$rebro,$x+$rebro,$y+$rebro,$graph_color);
			imageline($im,$x+$rebro,$y+$rebro,$x+$rebro,$y-$rebro,$graph_color);
			imageline($im,$x+$rebro,$y-$rebro,$x-$rebro,$y-$rebro,$graph_color);
			
			imageline($im,$x-$rebro,$y-$rebro,$x+$rebro,$y+$rebro,$graph_color);
			imageline($im,$x-$rebro,$y+$rebro,$x+$rebro,$y-$rebro,$graph_color);
			
			if ($data[$i]!=0 && $param[19]==true) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
			imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
			}
		}
		elseif($param[17]==3)
		{
		$rebro=5;
		for($i=0; $i<=$maxx-1; $i++)
			{
			$x=$x_left+($i)*$x_width/($maxx-1);
			$y=$imgh-$y_top-$data[$i]*($imgh-2*$y_top)/$maxy;
			imagearc($im,$x,$y,10,10,0,360,$graph_color);
			
			if ($data[$i]!=0 && $param[19]==true) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
			imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
			}
		}
		elseif($param[17]==4)
		{
		$rebro=5;
		for($i=0; $i<=$maxx-1; $i++)
			{
			$x=$x_left+$i*$x_width/($maxx-1);
			$y=$imgh-$y_top-$data[$i]*($imgh-2*$y_top)/$maxy;
			imageline($im,$x-$rebro,$y-$rebro,$x+$rebro,$y+$rebro,$graph_color);
			imageline($im,$x-$rebro,$y+$rebro,$x+$rebro,$y-$rebro,$graph_color);
			
			if ($data[$i]!=0 && $param[19]==true) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
			imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
			}
		}
		elseif($param[17]==5)
		{
		$rebro=5;
		for($i=0; $i<=$maxx-1; $i++)
			{
			$x=$x_left+$i*$x_width/($maxx-1);
			$y=$imgh-$y_top-$data[$i]*($imgh-2*$y_top)/$maxy;
			imageline($im,$x-$rebro,$y,$x+$rebro,$y,$graph_color);
			imageline($im,$x,$y-$rebro,$x,$y+$rebro,$graph_color);
			
			if ($data[$i]!=0 && $param[19]==true) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
			imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
			}
		}
		
		imageline($im,$x_left,$y_height+$y_top,$x_left+$x_width,$y_height+$y_top,$axe_color);
		imageline($im,$x_left,$y_height+$y_top,$x_left,$y_top,$axe_color);
				
		imageline($im,0,0,$imgw-1,0,$axe_color);
		imageline($im,$imgw-1,0,$imgw-1,$imgh-1,$axe_color);
		imageline($im,$imgw-1,$imgh-1,0,$imgh-1,$axe_color);
		imageline($im,0,$imgh-1,0,0,$axe_color);
		
		imagejpeg($im,$param[18]);
		return true;
 }
}
//Конец описания класса

//---------------------------------------------------------\\

$DSGraphics= new TGraph;
?>