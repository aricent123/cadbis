<?php
 //--- Starting session.
 session_start();

 //--- Generating text.
 $text="";
$HOR_LINES_COUNT = 50;
$PIX_COUNT = 50;
$ANGLES_RAND = 25;



 for($i=0; $i<5; $i++)
  $text=$text.rand(0,9); 

 $_SESSION['IMG']=$text;


 //--- Generating picture. 
 header ("Content-type: image/png");

 $im_w=60; $im_h=18;
 $im = imagecreate ($im_w,$im_h);

 if($im)
 {
  
  $background_color = imagecolorallocate ($im, 235, 235, 235);
  $text_color = imagecolorallocate ($im, 91, 14, 233);
  $lines_color =  imagecolorallocate ($im, 140, 190, 140); 

  $font="./century.ttf";

  //Outputing text.
  if(file_exists($font))
  {
   for($i=0; $i<strlen($text); $i++)
   {
    $pos=$i*10+4;
    $ang=rand(-$ANGLES_RAND,$ANGLES_RAND);
    imagettftext($im,13,$ang,$pos,14,$text_color,$font,$text[$i]);    
   }
  }
  
  for($i=0; $i<rand(0,$HOR_LINES_COUNT); ++$i)
  {
  	$sx = rand(0,$im_w/2);
  	$sy = rand(0,$im_h);  	
  	imageline($im,$sx,$sy,rand($sx,$im_w),$sy,$lines_color);
  }
  //Noise.
  for($i=0; $i<$PIX_COUNT; $i++)
  {
   $color = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255)); 
   imagesetpixel($im, rand(0,$im_w), rand(0,$im_h), $color); 
  }

  //Outputing image.
  imagepng ($im);
 }
?>