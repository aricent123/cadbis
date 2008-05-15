<?php

/*DSISS CGraph v.1.2 (pie graphtype included) class for simple PHP_GD2-drawing*/

class CGraph
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
	13 - font filename for axes labels (string)
	14 - graph header (string)
	15 - axe X label (string)
	16 - axe Y label (string)
	17 - graph type (int)
	18 - output file name (string)
	19 - draw values label (bool)
	20 - only maximum drawing (bool)
	21 - font filename for title label (string)
	22 - Axes font size (int)
	23 - Title font size (int)
	24 - Draw border (bool)
	25 - Pie radius (int)
	26 - Outline length (int)
	*/

 function SetDefaults()
 {
	$my_params[0]=450;
	$my_params[1]=300;
	$my_params[2]=225;
	$my_params[3]=225;
	$my_params[4]=225;
	$my_params[5]=0;
	$my_params[6]=0;
	$my_params[7]=0;
	$my_params[8]=150;
	$my_params[9]=150;
	$my_params[10]=150;
	$my_params[11]=50;
	$my_params[12]=50;
	$my_params[13]="./times.ttf";
	$my_params[14]="Sample.";
	$my_params[15]="Axe Y.";
	$my_params[16]="Axe X.";
	$my_params[17]=1;
	$my_params[18]="image.jpeg";
	$my_params[19]=true;
	$my_params[20]=false;
	$my_params[21]="./times.ttf";
	$my_params[22]=12;
	$my_params[23]=12;
	$my_params[24]=true;
	$my_params[25]=0.5*$param[0];
	$my_params[26]=40;
	return $my_params;
 }

 function Draw($param,$data,$label)
 {

	header ("Content-type: image/png");

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

	
	if(file_exists($param[13]))
	{
	imagettftext($im,$param[23],0,$x_left,$y_top-20,$axe_color,$param[21],$param[14]);
	imagettftext($im,$param[22],90,$x_left-10,$imgh-$y_top,$axe_color,$param[13],$param[15]);
	imagettftext($im,$param[22],0,$x_left,$imgh-$y_top+35,$axe_color,$param[13],$param[16]);
	}
	else
	{
	imagestring($im,5,$x_left,$y_top-35,$param[14],$axe_color);
	imagestringup($im,4,$x_left-20,$y_top+$y_height,$param[15],$axe_color);
	imagestring($im,4,$x_left,$y_top+$y_height+20,$param[16],$axe_color);
	}

		
	if($param[17]!=6)
	{
	imageline($im,$x_left,$y_height+$y_top,$x_left+$x_width,$y_height+$y_top,$axe_color);
	imageline($im,$x_left,$y_height+$y_top,$x_left,$y_top,$axe_color);
	}


	//Построение графиков разных типов.
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
			if($param[20]==true)
			{
				if($data[$i]==$maxy)
				imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);						}
			else imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
		}
		imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
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
			
		if ($data[$i]!=0 && $param[19]==true)
		{
			if($param[20]==true)
			{
				if($data[$i]==$maxy) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);					
			}
			else imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
		}
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
			
		if ($data[$i]!=0 && $param[19]==true)
		{
			if($param[20]==true)
			{
				if($data[$i]==$maxy) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);					
			}
			else imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
		}
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
			
		if ($data[$i]!=0 && $param[19]==true)
		{
			if($param[20]==true)
			{
				if($data[$i]==$maxy) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);					
			}
			else imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
		}
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

		if ($data[$i]!=0 && $param[19]==true)
		{
			if($param[20]==true)
			{
				if($data[$i]==$maxy) imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);					
			}
			else imagestring($im,4,$x+5,$y-20,$data[$i],$axe_color);
		}
		imagestring($im,3,$x+5,$imgh-$y_top+5,$label[$i],$axe_color);
	}
	}



	//--------------- PIE GRAPH  --------------------------
 	elseif($param[17]==6)
	{

	//sum of data.
	$sum=0;
	for($i=0; $i<$maxx; $i++)
	 $sum=$sum+$data[$i];

	//radius of pie.
	$r=$param[25];	
		
	//center of pie.
	$cx=$param[0]/2;
	$cy=$param[1]/2;


	$s=0; //start angle of pie
	for($i=0; $i<$maxx; $i++)
	{
		$e=$s+($data[$i]/$sum)*360; //end angle of pie
		//$ptx=$cx+$r/2*sin($s*3.1415/180.0);
		//$pty=$cy-$r/2*cos($s*3.1415/180.0);
		
		//drawing filled arc
		$col=imagecolorallocate($im,rand(50,255),rand(50,255),rand(50,255));
		imagefilledarc($im,$cx,$cy,$r,$r,$s+270,$e+270,$col, IMG_ARC_PIE);

		//calculating outline points
		$rr=$param[26];
		$mdl=($s+$e)/2; //middle angle
		$ptx=$cx+$r/2*sin($mdl*3.1415/180.0);
		$pty=$cy-$r/2*cos($mdl*3.1415/180.0);
		$ptxx=$ptx+$rr*sin($mdl*3.1415/180.0);
		$ptyy=$pty-$rr*cos($mdl*3.1415/180.0);
		
		//outline
		imageline($im,$ptx,$pty,$ptxx,$ptyy,$axe_color);
		
		//Printing labels with outlines.
		if($ptxx>$cx)
		{
			if(file_exists($param[21]))
			{
				$res=imagettfbbox($param[23],0,$param[21],$label[$i]);
				$W=$res[2]-$res[0];
				$H=$res[5]-$res[3];
				
				imagettftext($im,$param[23],0,$ptxx,$ptyy+0.2*$H,$axe_color,$param[21],$label[$i]);
				imageline($im,$ptxx,$ptyy,$ptxx+$W,$ptyy,$axe_color);
			}
			else
			{
				imagestring($im,3,$ptxx,$ptyy-14,$label[$i],$axe_color);
				imageline($im,$ptxx,$ptyy,$ptxx+100,$ptyy,$axe_color);
			}
		}
		else
		{
			if(file_exists($param[21]))
			{
				$res=imagettfbbox($param[23],0,$param[21],$label[$i]);
				$W=$res[2]-$res[0];
				$H=$res[5]-$res[3];
				imagettftext($im,$param[23],0,$ptxx-$W,$ptyy+0.2*$H,$axe_color,$param[21],$label[$i]);
				imageline($im,$ptxx,$ptyy,$ptxx-$W,$ptyy,$axe_color);
			}
			else
			{
				imagestring($im,3,$ptxx-100,$ptyy-14,$label[$i],$axe_color);
				imageline($im,$ptxx,$ptyy,$ptxx-100,$ptyy,$axe_color);
			}
		}
		
		//Setting start angle for next pie like end ahngle of previous.		
		$s=$e;
	}
	imageellipse($im,$cx,$cy,$r,$r,$axe_color);
	}
	//--------------- PIE GRAPH  --------------------------

	//drawing border
	if($param[24])
	{
		imageline($im,0,0,$imgw-1,0,$axe_color);
		imageline($im,$imgw-1,0,$imgw-1,$imgh-1,$axe_color);
		imageline($im,$imgw-1,$imgh-1,0,$imgh-1,$axe_color);
		imageline($im,0,$imgh-1,0,0,$axe_color);
	}
	


	//outputing image in file or browser
	if($param[18]=="")	imagepng($im);
	else			imagepng($im,$param[18]);

	return true;
 }
}

?>