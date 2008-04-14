<?php
//Обязательное включение
include "$file_read_statistic";
include "$file_read_online";
error_reporting(E_ALL);
//Ширина и высота картинки.
$imgw=850;
$imgh=150;

//Создаём картинку.
$im=imagecreate($imgw,$imgh);

//Создаём градиентный фон.
$reds=rand(3,9).rand(3,9);
$greens=rand(3,9).rand(3,9);
$blues=rand(3,9).rand(3,9);
$reds=hexdec($reds);
$greens=hexdec($greens);
$blues=hexdec($blues);

$redf="FF";
$greenf="FF";
$bluef="FF";
$redf=hexdec($redf);
$greenf=hexdec($greenf);
$bluef=hexdec($bluef);

$koefr=($redf-$reds+1)/$imgh;
$koefg=($greenf-$greens+1)/$imgh; $koefb=($bluef-$blues+1)/$imgh;

for($i=0;$i<=$imgh;$i++)
{
 $gradr=$koefr*$i+$reds;
 $gradg=$koefg*$i+$greens;
 $gradb=$koefb*$i+$blues;  $col=imagecolorallocate($im,$gradr,$gradg,$gradb);
 imageline($im,0,$i,$imgw,$i,$col);
}

$black=imagecolorallocate($im,0,0,0);

//Создаём текст.
$title_text="SMnDSForum";
$date_text=date("d-m-Y, H:i");
$users_text="Total registered users: ".$total_users."."; $themes_text="Total themes: ".count($all_themes)." - most created by ".$max_themes." (".$max_themes_c.")."; $messages_text="Total messages: ".$total_messages." - most written by ".$max_mess." (".$max_mess_c.")."; $respect_text="The greatest respect has ".$max_raiting." (".$max_raiting_c.")."; $online_users=$regs+$guests; $online_text="Total users online: ".$online_users." - registered: ".$regs.", guests: ".$guests.".";

//Выводим текст.
if (file_exists("verdana.ttf"))
{
 imagettftext($im,15,0,200,20,$black,"verdana.ttf","::SM and DS Forum Information::");  imagettftext($im,17,90,30,135,$black,"verdana.ttf",$title_text);
 imagettftext($im,13,90,832,135,$black,"verdana.ttf",$date_text);
 imagettftext($im,13,0,80,55,$black,"verdana.ttf",$users_text);
 imagettftext($im,13,0,80,75,$black,"verdana.ttf",$themes_text);
 imagettftext($im,13,0,80,95,$black,"verdana.ttf",$messages_text);
 imagettftext($im,13,0,80,115,$black,"verdana.ttf",$respect_text);
 imagettftext($im,13,0,80,135,$black,"verdana.ttf",$online_text);
}
else
{
 //Unable to find Font file!  
 imagestring($im,5,200,10,"::SM and DS Forum Information::",$black);  imagestringup($im,5,30,143,$title_text,$black);
 imagestringup($im,4,820,143,$date_text,$black);
 imagestring($im,5,80,50,$users_text,$black);
 imagestring($im,5,80,70,$themes_text,$black);
 imagestring($im,5,80,90,$messages_text,$black);
 imagestring($im,5,80,110,$respect_text,$black);
 imagestring($im,5,80,130,$online_text,$black);
}

//Рисуем линии.
imageline($im,50,$imgh,50,0,$black);
imageline($im,800,$imgh,800,0,$black);
imageline($im,50,30,800,30,$black);

//--- border start---
imageline($im,0,0,$imgw-1,0,$black);
imageline($im,$imgw-1,0,$imgw-1,$imgh-1,$black-1);
imageline($im,$imgw-1,$imgh-1,0,$imgh-1,$black-1);
imageline($im,0,$imgh-1,0,0,$black);
//--- border end ---

//Сохраняем картинку.
$file="info-banner.png";
imagepng($im,$file);

//Выводим картинку по центру.
echo("<p align=center><img src=$file></p>");

//Разрушаем картинку.
imagedestroy($im);
error_reporting(E_PARSE);
?>
