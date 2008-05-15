<?php
//----------------------------------------------------------------------//
//    TITLE: Photoalbum mega-module                                     //
//    MAKER: DS                                                         //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Фотоальбом";
$MDL_DESCR="Фотоальбом";
$MDL_UNIQUEID="photoalbum";
$MDL_MAKER="DSISS";

if(!$_GETINFO)
{
//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for projects ****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists(CFileSystem))
{

 class CFileSystem
 {
  
  // Gets All Files and Directories in folder.
  function GetDir($dir)
  {
   $res=NULL;

   if(is_dir($dir))
   {
    $hnd=opendir($dir);
    while($rec=readdir($hnd))
     $res[]=$rec;
    closedir($hnd);
   }

   if(sizeof($res)>0)
   {
    asort($res); 
    return array_values($res);
   }
   else return $res;
  }


  // Gets all Directories in folder.
  function GetDirDirs($dir)
  {
   $res=NULL;
   if(is_dir($dir))
   {
    $dirres=$this->GetDir($dir);
    for($i=0; $i<sizeof($dirres); $i++)
    {
     if(is_dir($dir.$dirres[$i]) && $dirres[$i]!="." && $dirres[$i]!="..") 
      $res[]=$dirres[$i];
    }
   }

   if(sizeof($res)>0)
   {
    asort($res); 
    return array_values($res);
   }
   else return $res;
  }

   
  //Returns all files in directory.
  function GetDirFiles($dir)
  {
   $res=NULL;
   if(is_dir($dir))
   {
    $dirres=$this->GetDir($dir);
    for($i=0; $i<sizeof($dirres); $i++)
    {
     if(!is_dir($dir.$dirres[$i]) && $dirres[$i]!="." && $dirres[$i]!="..")
      $res[]=$dirres[$i];
    }
   }

   if(sizeof($res)>0)
   {
    asort($res); 
    return array_values($res);
   }
   else return $res;
  }

};


}


if(!class_exists("CPhotoAlbum"))
{
//---------------------------------------
// Мега-класс фотоальбома.
//---------------------------------------

class CPhotoAlbum
{
   var $level1_delim;
   var $level2_delim;
   var $deps_file;
   var $data_dir;
   var $img_dir;
   var $img_per_part;
   
 //---------------------------------------
 //Конструктор
 //---------------------------------------  
  function CPhotoAlbum($deps_file,$data_dir,$img_dir,$delim1,$delim2,$img_per_part)
   {
   $this->level1_delim=$delim1;
   $this->level2_delim=$delim2;
   $this->img_dir=$img_dir;
   $this->data_dir=$data_dir;
   $this->deps_file=$deps_file;  
   $this->img_per_part=$img_per_part;
   } 


 //---------------------------------------
 //Функция разбора двухуровневого текста.
 //---------------------------------------
 function Parse2Levels($text)
 { 
  $res=array();
  $lvl1=array();
  $lvl1=explode($this->level1_delim,$text);

  for($i=0; $i<sizeof($lvl1); $i++)
  {
   $lvl2=array();
   $lvl2=explode($this->level2_delim,$lvl1[$i]);
   {
    for($j=0; $j<sizeof($lvl2); $j++)
    {
     $res[$i][$j]=$lvl2[$j];
    }
   }
  }
  return $res;
 }

 //---------------------------------------
 //Добавление двухуровневого текста в массив.
 //---------------------------------------
 function Add2Levels($source,$arr)
 {
  $sz=sizeof($source);

  for($i=0; $i<sizeof($arr); $i++)
   $source[$sz][$i]=$arr[$i];

  return $source;
 }

 //---------------------------------------
 //Удаление двухуровневого элемента из массива.
 //---------------------------------------
 function Move2Levels($arr,$id)
 {
  $res=array();

  for($i=0; $i<sizeof($arr); $i++)
  {
   if($i==$id) continue;
    
   $sz=sizeof($res);
   for($j=0; $j<sizeof($arr[$i]); $j++)
    $res[$sz][$j]=$arr[$i][$j];
  }

  //echo("<br><br>ARR:<br>");
  //print_r($arr);
  //echo("<br><br>RES:<br>");
  //print_r($res);

  return $res;
 }


 //---------------------------------------
 //Сохранение двухуровневого массива в файл.
 //---------------------------------------
 function Save2Levels($filename,$arr)
 {
  //echo("<br>Открываем файл [".$filename."]");
  if($hnd=fopen($filename,"w"))
  {
   //echo("<br>Файл $filename открыт");
   for($i=0; $i<sizeof($arr); $i++)
   {
    for($j=0; $j<sizeof($arr[$i]); $j++)
    {
     fwrite($hnd,$arr[$i][$j]);
     if(($j+1)<sizeof($arr[$i])) fwrite($hnd,$this->level2_delim);
    }
    if(($i+1)<sizeof($arr)) fwrite($hnd,$this->level1_delim);
   }
 
   fclose($hnd);
   return true;
  }

  return false;
 }

 //---------------------------------------
 //Получает все разделы фотоальбома.
 //---------------------------------------
 function GetAllDeps()
 {
  $res=array();
  $hdl=fopen($this->deps_file,"r");
  if($hdl && filesize($this->deps_file)>0)
  {
   $text=fread($hdl,filesize($this->deps_file));
   $res=$this->Parse2Levels($text);
   fclose($hdl);
  }
  return $res;
 }

 //---------------------------------------
 //Получает раздел фотоальбома.
 //---------------------------------------
 function GetDep($dep)
 {
  $res=array();
  
  $deps=array();
  $deps=$this->GetAllDeps();

  if((sizeof($deps)-$dep)>0)
  {
   for($i=0; $i<sizeof($deps[$dep]); $i++)
    $res[$i]=$deps[$dep][$i];
  }

  return $res;
 }

 //---------------------------------------
 //Получает все галереи раздела.
 //---------------------------------------
 function GetAllDepGals($dep)
 {
  $res=array();
  
  $department=$this->GetDep($dep);

  $gals_file=$this->data_dir.$department[1]; 
  $hdl=fopen($gals_file,"r");
  if($hdl && filesize($gals_file)>0)
  {
   $text=fread($hdl,filesize($gals_file));
   $res=$this->Parse2Levels($text);
   fclose($hdl);
  }

  return $res;
 }
 

 //---------------------------------------
 //Получает галерею раздела.
 //---------------------------------------
 function GetDepGal($dep,$gal)
 {
  $res=NULL;
  
  $gals=NULL;
  $gals=$this->GetAllDepGals($dep);

  if((sizeof($gals)-$gal)>0)
  {
   for($i=0; $i<sizeof($gals[$gal]); $i++)
    $res[$i]=$gals[$gal][$i];
  }

  return $res;
 }


 //---------------------------------------
 //Получает все галереи раздела.
 //---------------------------------------
 function GetGalImages($dep,$gal)
 {
  $res=NULL;
  $galary=$this->GetDepGal($dep,$gal);

  $gal_file=$this->data_dir.$galary[1];
  $hdl=fopen($gal_file,"r");
  if($hdl && filesize($gal_file)>0)
  {
   $text=fread($hdl,filesize($gal_file));
   $res=$this->Parse2Levels($text);
   fclose($hdl);
  }

  return $res;
 }


 //---------------------------------------
 //Получает раздел фотоальбома.
 //---------------------------------------
 function GetGalComments($dep,$gal)
 {
  $res=array();
  $galary=$this->GetDepGal($dep,$gal);

  $gal_file=$this->data_dir.$galary[4];
  
  if(file_exists($gal_file))
  {
   if(filesize($gal_file)>0)
   {
    $hdl=fopen($gal_file,"r");
    $text=fread($hdl,filesize($gal_file));
    $res=$this->Parse2Levels($text);
    fclose($hdl);
   }
  }
  else
  {
   $hdl=fopen($gal_file,"w");
   fclose(f);
  }

  return $res;
 }


 //---------------------------------------
 //Удаляет файл изображения.
 //---------------------------------------
 function DelGalImage($dep,$gal,$img,$flag_del_file=false)
 {
  
  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);
 
  echo("<br>Удаляем файл [".$im[$img][0]."] галереи [".$g[0]."]");
  
  //Удаление изображений с диска.
  if(is_file($this->img_dir.$im[$img][0]) && $flag_del_file)
  {
   unlink($this->img_dir.$im[$img][0]);
   if(!is_file($this->img_dir.$im[$img][0])) echo("<br>Файл удалён с диска");
   else echo("<br>Файл НЕ удалён с диска");
  }

  //Удаляем запись $img из файла галереи.
  $new_img=array();
  for($i=0; $i<sizeof($im); $i++)
  {
   if($i!=$img)
   {
    $sz=sizeof($new_img);
    for($j=0; $j<sizeof($im[$i]); $j++)
     $new_img[$sz][$j]=$im[$i][$j];
   }
  } 

  //Перезаписываем файл галереи.
  //echo("<br>Обновляем файл галереи [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$new_img)) echo("<br>Галерея обновлена"); 
  else echo("<br>Галерея не обновлена");
 }


 
 //---------------------------------------
 //Удаляет галерею альбома.
 //---------------------------------------
 function DelGal($dep,$gal,$flag_del_files)
 {

  $d=$this->GetDep($dep);
  $g=$this->GetAllDepGals($dep);
   
  echo("<br>Удаляем галерею [".$g[$dep][0]."]");

  //Удаление изображений из галереи.
  if($flag_del_files)
  {
   echo("<br>Удаляем изображения из галереи");
   //print_r($im);
   
   $im=$this->GetGalImages($dep,$gal);
   for($j=0; $j<sizeof($im); $j++)
   {
    if($im[$j][0]!="" && is_file($this->img_dir.$im[$j][0]))
    {
     echo("<br>Удаляем файл [".$im[$j][0]."]");
     unlink($this->img_dir.$im[$j][0]);
     
     if(is_file($this->img_dir.$im[$j][0]))
      echo("<br>Файл [".$im[$j][0]."] не удалён!");
    }
   }
  }
  
  
  echo("<br>Удаляем файл галереи [".$g[$gal][1]."]");
  if(is_file($this->data_dir.$g[$gal][1]))
  {
   unlink($this->data_dir.$g[$gal][1]);
   if(is_file($this->data_dir.$g[$gal][1]))
      echo("<br>Файл [".$g[$gal][1]."] не удалён!");
  }

  echo("<br>Удаляем комментарии галереи [".$g[$gal][4]."]");
  if(is_file($this->data_dir.$g[$gal][4]))
  {
   unlink($this->data_dir.$g[$gal][4]);
   if(is_file($this->data_dir.$g[$gal][4]))
      echo("<br>Файл [".$g[$gal][4]."] не удалён!");
  }


  //Удаляем галерею из списка галерей.
  $new_gals=array();
  for($i=0; $i<sizeof($g); $i++)
  {
   if($i!=$gal)
   {
    $sz=sizeof($new_gals);
    for($j=0; $j<sizeof($g[$i]); $j++)
     $new_gals[$sz][$j]=$g[$i][$j];
   }
  } 

  //Перезаписываем файл раздела.
  //echo("<br>Обновляем файл раздела [".$d[1]."]");
  if($this->Save2Levels($this->data_dir.$d[1],$new_gals)) echo("<br>Раздел обновлён"); 
  else echo("<br>Раздел не обновлён");

 }
 

 //---------------------------------------
 //Удаляет раздел альбома.
 //---------------------------------------
 function DelDep($dep, $flag_del_gals=false, $flag_del_files=false)
 {
  $d=$this->GetAllDeps();
  $g=$this->GetAllDepGals($dep);

  if($flag_del_gals)
  {
   echo("<br>Удаляем галереи");
   for($i=0; $i<sizeof($g); $i++)
    $this->DelGal($dep,0,$flag_del_files);
  }
  
  echo("<br>Удаляем файл с описанием галерей раздела [".$d[$dep][1]."]");
  if(is_file($this->data_dir.$d[$dep][1]))
  {
   unlink($this->data_dir.$d[$dep][1]);
   if(is_file($this->data_dir.$d[$dep][1]))
    echo("<br>Файл [".$d[$dep][1]."] не удалён!");
  }


  //Удаляем раздел из списка разделов.
  $new_deps=array();
  for($i=0; $i<sizeof($d); $i++)
  {
   if($i!=$dep)
   {
    $sz=sizeof($new_deps);
    for($j=0; $j<sizeof($d[$i]); $j++)
     $new_deps[$sz][$j]=$d[$i][$j];
   }
  } 

  //echo("<br>Теперь разделов: ".sizeof($new_deps)."<br>");
  //print_r($new_deps);
  if($this->Save2Levels($this->deps_file,$new_deps)) echo("<br>Разделы обновлены");
  else echo("<br>Разделы не обновлены");
 }
 


 //---------------------------------------
 //Добавляет отдел в альбом последним в списке.
 //---------------------------------------
 function AddDep($name,$desc,$img)
 {
  //Создаём файл для раздела.
  $filename="";
  while(true)
  {
   $filename="dep_".time().".txt";
   if(!file_exists($this->data_dir.$filename)) break;
  }

  $hnd=fopen($this->data_dir.$filename,"w");
  if($hnd)
  {
   fclose($hnd);

   $res=$this->GetAllDeps();
   //echo("<br>Всего разделов: ".sizeof($res));
     
   $sz=sizeof($res);
   $res[$sz][0]=$name;
   $res[$sz][1]=$filename;
   $res[$sz][2]=$desc;
   $res[$sz][3]=$img;

   //echo("<br>Теперь разделов: ".sizeof($res));
   if($this->Save2Levels($this->deps_file,$res)) return true;
   
  }
  return false;
 }




 //---------------------------------------
 //Добавляет галерею в раздел альбома последней в списке.
 //---------------------------------------
 function AddGal($dep,$name,$desc,$img)
 {
  $d=$this->GetDep($dep);
  $res=$this->GetAllDepGals($dep);


  //Создаём файл для комментариев.
  $comm_filename="";
  while(true)
  {
   $comm_filename="comment_".time().".txt";
   if(!file_exists($this->data_dir.$comm_filename)) break;
  }
  $hnd=fopen($this->data_dir.$comm_filename,"w");
  if($hnd) fclose($hnd);


  //Создаём файл для галереи.
  $gal_filename="";
  while(true)
  {
   $gal_filename="gal_".time().".txt";
   if(!file_exists($this->data_dir.$gal_filename)) break;
  }


  $hnd=fopen($this->data_dir.$gal_filename,"w");
  if($hnd)
  {
   fclose($hnd);

   //echo("<br>Всего галерей: ".sizeof($res));
     
   $sz=sizeof($res);
   $res[$sz][0]=$name;
   $res[$sz][1]=$gal_filename;
   $res[$sz][2]=$desc;
   $res[$sz][3]=$img;
   $res[$sz][4]=$comm_filename;

   //echo("<br>Теперь галерей: ".sizeof($res));
   echo("<br>Обновляем файл раздела [".$d[1]."]");
   if($this->Save2Levels($this->data_dir.$d[1],$res)) return true;
  }
  return false;
 }



 //---------------------------------------
 //Добавляет изображение в галерею последним в списке.
 //---------------------------------------
 function AddImg($dep,$gal,$desc,$path)
 {
  //echo("<br>dep=$dep; gal=$gal; path=$path; desc=$desc");  

  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);

  //echo("<br>Всего изображений: ".sizeof($im));
     
  $sz=sizeof($im);
  $im[$sz][0]=$path;
  $im[$sz][1]=$desc;

  //echo("<br>Теперь изображений: ".sizeof($im));

  //echo("<br>Обновляем файл галереи [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$im)) return true;

  return false;
 }




 //---------------------------------------
 //Обмен значениями двух элементов массива.
 //---------------------------------------
 function Swap2Levels($arr, $i, $j)
 {
  //echo("<br>Обмен значений элементов $i и $j <br>");

  $sz=sizeof($arr[$i]);
  for($k=0; $k<$sz; $k++)
  {
   $temp=$arr[$i][$k];
   $arr[$i][$k]=$arr[$j][$k];
   $arr[$j][$k]=$temp;
  }

  return $arr;
 }

 //---------------------------------------
 //Перемещает изображение вверх или вниз по списку.
 //---------------------------------------
 function DragImg($dep,$gal,$img,$up=true)
 {

  $im=$this->GetGalImages($dep,$gal);
  $sz=sizeof($im);
  if($img==0 && $up==true || ($img+1)==$sz && $up==false) return;

  if($up) { echo("<br>Перемещаем вверх"); $n_im=$this->Swap2Levels($im,$img,$img-1); }
  else    { echo("<br>Перемещаем вниз");  $n_im=$this->Swap2Levels($im,$img,$img+1); }

  $g=$this->GetDepGal($dep,$gal);

  //Перезаписываем файл галереи.
  echo("<br>Обновляем файл галереи [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$n_im)) echo("<br>Галерея обновлена"); 
  else echo("<br>Галерея не обновлена");
 }


 //---------------------------------------
 //Перемещает галерею вверх или вниз по списку.
 //---------------------------------------
 function DragGal($dep,$gal,$up=true)
 {

  $d=$this->GetDep($dep);
  $g=$this->GetAllDepGals($dep);
  $sz=sizeof($g);
  if($gal==0 && $up==true || ($gal+1)==$sz && $up==false) return;

  if($up) { echo("<br>Перемещаем вверх"); $n_gal=$this->Swap2Levels($g,$gal,$gal-1); }
  else    { echo("<br>Перемещаем вниз");  $n_gal=$this->Swap2Levels($g,$gal,$gal+1); }

  //Перезаписываем файл раздела.
  echo("<br>Обновляем файл раздела [".$d[1]."]");
  if($this->Save2Levels($this->data_dir.$d[1],$n_gal)) echo("<br>Раздел обновлён"); 
  else echo("<br>Раздел не обновлён");
 }


 //---------------------------------------
 //Перемещает раздел вверх или вниз по списку.
 //---------------------------------------
 function DragDep($dep,$up=true)
 {

  $d=$this->GetAllDeps($dep,$gal);
  $sz=sizeof($d);
  if($dep==0 && $up==true || ($dep+1)==$sz && $up==false) return;

  if($up) { echo("<br>Перемещаем вверх"); $n_dep=$this->Swap2Levels($d,$dep,$dep-1); }
  else    { echo("<br>Перемещаем вниз");  $n_dep=$this->Swap2Levels($d,$dep,$dep+1); }

  //Перезаписываем файл разделов.
  echo("<br>Обновляем файл разделов [".$this->deps_file."]");
  if($this->Save2Levels($this->deps_file,$n_dep)) echo("<br>Разделы обновлены"); 
  else echo("<br>Разделы не обновлены");
 }
 

 //---------------------------------------
 //Обновляем данные изображения.
 //---------------------------------------
 function UpdateImg($dep,$gal,$img,$path,$desc)
 {
  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);  

  $im[$img][0]=$path;
  $im[$img][1]=$desc;

  //Перезаписываем файл галереи.
  echo("<br>Обновляем файл галереи [".$g[1]."]");
  return $this->Save2Levels($this->data_dir.$g[1],$im);
 }
 

 //---------------------------------------
 //Обновляем данные галереи.
 //---------------------------------------
 function UpdateGal($dep,$gal,$name,$desc,$img)
 {

  $d=$this->GetDep($dep);  
  $g=$this->GetAllDepGals($dep,$gal);

  $g[$gal][0]=$name;
  $g[$gal][2]=$desc;
  $g[$gal][3]=$img;

  //Перезаписываем файл галереи.
  echo("<br>Обновляем файл галереи [".$d[1]."]");
  return $this->Save2Levels($this->data_dir.$d[1],$g);
 }


 //---------------------------------------
 //Обновляем данные раздела.
 //---------------------------------------
 function UpdateDep($dep,$name,$desc,$img)
 {
  $d=$this->GetAllDeps($dep);  

  $d[$dep][0]=$name;
  $d[$dep][2]=$desc;
  $d[$dep][3]=$img;

  //Перезаписываем файл разделов.
  echo("<br>Обновляем файл разделов [".$this->deps_file."]");
  return $this->Save2Levels($this->deps_file,$d);
 }

  
 //---------------------------------------
 //Обновляем данные раздела.
 //---------------------------------------
 function GenImgSelector($sel_img="")
 {

  $fs=new CFileSystem();
  $this->img_dir;
  $scan=$fs->GetDirDirs($this->img_dir);
  echo("<select name=img_path>");
  for($i=0; $i<sizeof($scan); $i++)
  {
   echo("<optgroup label=\"Папка [".$scan[$i]."]\">");
   $f=$fs->GetDirFiles($this->img_dir.$scan[$i]);
   for($j=0; $j<sizeof($f); $j++)
   {
    echo("<option value=\"".$scan[$i]."/".$f[$j]."\"");
    if(($scan[$i]."/".$f[$j])==$sel_img) echo(" selected=true ");
    echo(">".$scan[$i]." / ".$f[$j]."</option>");
   }
  }

  echo("<optgroup label=\"ROOT FOLDER\">");
  $f=$fs->GetDirFiles($$this->img_dir);
  for($j=0; $j<sizeof($f); $j++)
  {
   echo("<option value=\"".$f[$j]."\"");
   if($f[$j]==$sel_img) echo(" selected=true ");
   echo(">".$f[$j]."</option>");
  }
  echo("</select>");

  echo("<script>function foo(val){document.all.dep_pre.src='smaller.php?img=".$this->img_dir."'+val;}</script>");
  echo("<button name=btn onClick=\"foo(document.all.img_path.value)\">show</button>");
  echo("<br><center><img name=dep_pre src='smaller.php?img=".$this->img_dir.$sel_img."'></center>");

 }

 function GenDepSelector($sel_dep="")
 {
  echo("<select name=dest_dep>");
  $d=$this->GetAllDeps();
  for($i=0; $i<sizeof($d); $i++)
  {
   echo("<option value=$i");
   if($i==$sel_dep) echo(" selected=true ");
   echo(">");
   echo($d[$i][0]);
   echo("</option>");
  }
  echo("</select>");
 }
  
  

 function GenGalSelector($dep,$gal)
 {
  echo("<select name=dest_gal>");
  echo("</select>");
 }



 function GenFolderSelector($root_folder)
 {
  echo("<select name=folder_path>");

  $fs=new CFileSystem();
  $f=$fs->GetDirDirs($root_folder);

  for($i=0; $i<sizeof($f); $i++)
  {
   echo("<option value=".$f[$i].">");
   echo($f[$i]);
   echo("</option>");
  }
  echo("</select>");

 }

};
}
/////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
 
 
//================================================
// ИНСТАЛЛЯЦИЯ
//================================================
  if($_INSTALL)
  {
   //спрашиваем пользователя об основных переменных 
  }
//================================================
// ДЕИНСТАЛЛЯЦИЯ
//================================================
  elseif($_UNINSTALL)
  {
   //удаляем файлы конфигурации модуля
  }
//================================================
// МОДУЛЬ ДЛЯ АДМИНИСТРАТОРА
// необходимо проверить наличие админских прав (проверяется
// строкой $CURRENT_USER["level"]>=5, где 5- уровень админа
// (5-модератор, 6-админ, 7-суперадмин)
// check_auth() проверяет просто авторизован ли юзер
// в принципе эта проверка может быть просто такая: 
// elseif($_ADMIN) 
// тогда нужно будет проверять права далее 
//================================================
  elseif($_ADMIN && check_auth() && $CURRENT_USER["level"]>=5)
  {
    if(!file_exists(SK_DIR."/photoalbum_admin.php"))
     {
     include "config.php";
     /*
     здесь нужно вставить код админской странички    
     P.S. 
       - Лучше не используй переменные: 
       $act,$id
       - Ни в коем случае не используй следующие перменные:
       $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$p
       - Следующие переменные в принципе не заняты... (можно юзать) 
       $page,$type,$topic,$art,$a,     
     */
     
     
     /*
      любая ссылка в это место кода будет начинаться с:
      ?p=$p&act=admin
      Это нужно учитывать! То есть, если нужно сделать форму, то

      в action надо писать: 
      <form action="<? OUT("?p=$p&act=admin") ?>&[любые твои параметры]" ...>
      Например:
        <form action="<? OUT("?p=$p&act=admin") ?>&myvar1=true&myvar2=12&myvar3=blablabla" method=post>

      если же просто ссылка, то так:
      <a href="<? OUT("?p=$p&act=admin") ?>&[любые твои параметры]" ...>...</a> 
      Например:
        <a href="<? OUT("?p=$p&act=admin") ?>&myvar1=true&myvar2=12&myvar3=blablabla">сцылка</a>   
     */

      echo("<center><fieldset style='width:400px'><legend>Администрирование</legend>");
      echo("Поиск потерянных файлов/галерей/разделов");
      echo("<br>Загрузка файлов в фотоальбом");
      echo("<br>Настройка параметров фотоальбома");
      echo("<br><a href=?p=$p&act=admin&task=srch>Поиск изображений по имени/описанию</a>");

      echo("</fieldset></center>");

         
     }
    else
      include(SK_DIR."/photoalbum_admin.php");  
  }
//================================================
// МОДУЛЬ ДЛЯ ВСЕХ
// Здесь надо вставить код, доступный всем и каждому
// этот код будет выполнен, если пользователь увидит страничку
//================================================
 elseif($_MODULE) 
if($_NOTBAR)
  if(!file_exists(SK_DIR."/photoalbum.php"))
   {  
   if(isset($act)&& $act=="admin"){$MDL->LoadAdminPage("$p"); return;}
     include "config.php";   
     /*
     здесь нужно вставить код для всех пользователей
     P.S. 
       - Лучше не используй переменные: 
       $act,$id
       - Ни в коем случае не используй следующие перменные:
       $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$p
       - Следующие переменные в принципе не заняты... (можно юзать) 
       $page,$type,$topic,$art,$a,             
     */
     
     /*
      любая ссылка в это место кода будет начинаться с:
      ?p=$p
      Это нужно учитывать! То есть, если нужно сделать форму, тов action надо писать: 
      <form action="<? OUT("?p=$p") ?>&[любые твои параметры]" ...>
      Например:
        <form action="<? OUT("?p=$p") ?>&myvar1=true&myvar2=12&myvar3=blablabla"  method=post>

      если же просто ссылка, то так:
      <a href="<? OUT("?p=$p") ?>&[любые твои параметры]" ...>...</a> 
      Например:
        <a href="<? OUT("?p=$p") ?>&myvar1=true&myvar2=12&myvar3=blablabla">сцылка</a> 
          
     в любом месте этого кода можно сделать return;
     это остановит выполнение этой части и никак не отразится на интерфейсе
     
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     
     ИСПОЛЬЗУЙ ЭТО:      
     1) Все глобальные переменные (например пути к файлам или директориям) выноси в файл
       modules_conf/MYMODULE.conf.php
       где MYMODULE - название твоего модуля. 
       пути к файлам и папкам лучше хранить в массиве $DIRS
       например, мне нужно знать путь к директории со списком новостей, я пишу в файле
       моего модуля:
       $DIRS["dir_newslist"]="data/news/list";
        (!) Помни, что всё что здесь - относительно расположения index.php а не modules/MYMODULE.php
       Теперь создаю объект и передаю ему путь к списку:
       $MYMDL = new $MYCLASS($DIRS["dir_newslist"]);
       
     2) Если надо проверить залогинен ли пользователь
       функция check_auth() возвращает true если он авторизован
       в этом случае:
        $CURRENT_USER["id"] -идентификатор
        $CURRENT_USER["nick"]  - ник
        $CURRENT_USER["ip"]  - ip адрес
        $CURRENT_USER["login"] - логин
        $CURRENT_USER["email"] - емайл
        $CURRENT_USER["url"] - урл
        $CURRENT_USER["level"] - уровень (1-4 - пользователи, 5-модератор, 6-админ, >7-root)
        в файле вместо name надо хранить именно id. То есть, если добавляет текущий
        пользователь, то вписывай в поле name $CURRENT_USER["id"]
        при выводе записей делай так:
        (например у нас массив данных хранится в $data, и $data["name"] - идентификатор юзера)
        
        $ud=get_user_data($data["name"]);
        //теперь выводишь не просто $data["name"], а $ud["nick"] (то есть ник пользователя)
        //и желательно с ссылкой на инфу о нём:
        <a href="?p=users&act=userinfo&id=<? OUT($data["name"]) ?>"><? OUT($ud["nick"]) ?></a>
                               
     3) Чтобы разбить на страницы используй объект $PDIV:
       передаёшь ему список, получаешь только список на определённой странице:
       $array_on_page=$PDIV->GetPage($array,$page_num);
       
     4) При фильтрации полей используй объект $FLTR:
       //при добавлении или изменении записей:
       $string=$FLTR->DirectProcessString($string);
       $text=$FLTR->DirectProcessText($text);
       
       //при обратном преобразовании (для редактирования)
       $string=$FLTR->ReverseProcessString($string);
       $text=$FLTR->ReverseProcessText($text);
       
     5) Если нужно обрашаться к другим модулям (например, этот модуль зависимый),
       можно использовать объект $MDL
       Но это на будущее.. 
       
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)       
     */
  

 echo("<center><h3>Фотоальбом</h3></center>");

 $album = new CPhotoAlbum($GV["album_deps_file"],$DIRS["album_data_dir"],$DIRS["album_img_dir"],$GV["album_level1_delim"],$GV["album_level2_delim"],$GV["album_img_per_part"]);
 
 if(!isset($mode)) $mode="view_deps";
 if(!isset($_SESSION['ADMIN'])) $_SESSION['ADMIN']=false;


 //- просмотр всех разделов -----------------------------
 if($mode=="view_deps")
 {

  $deps=array();
  $deps=$album->GetAllDeps();
  $sz=sizeof($deps);

  if(!$sz) echo("<br><center>Нет разделов</center><br>");
  else
  {
  echo("<table border=1 width=70% align=center cellpadding=0 cellspacing=0><tbody>");
  for($i=0; $i<$sz; $i++)
  {
   $sum=0;
   $gals=$album->GetAllDepGals($i);
   
   for($jj=0; $jj<sizeof($gals); $jj++)
   {
    $images=$album->GetGalImages($i,$jj);
    $sum+=sizeof($images);
   }

   echo("<tr valign=top>");
   echo("<td width=10%><center><a href=?p=$p&mode=view_dep&dep=$i><img border=0 src='smaller.php?img=".$DIRS["album_img_dir"].$deps[$i][3]."'></a>");
   if($deps[$i][0]!="") echo("<br>[".$deps[$i][0]."]");
   echo("<br>[галерей - ".sizeof($gals)."]<br>[фоток - ".$sum."]</center></td>");
   echo("<td>".$deps[$i][2]);

   if(check_auth() && $CURRENT_USER["level"]>=5)
   {
    echo("<p align=right><a href=?p=$p&mode=admin&task=edit_dep&dep=$i><img border=0 title='редактирование' src=img/edit.bmp></a>");
    if($i>0) echo("<a href=?p=$p&mode=admin&task=move_dep_up&dep=$i><img border=0 title='вверх' src=img/up.bmp></a>");
    if($i<($sz-1))echo("<a href=?p=$p&mode=admin&task=move_dep_down&dep=$i><img border=0 title='вниз' src=img/down.bmp></a>");
    echo("<a href=?p=$p&mode=admin&task=del_dep&dep=$i><img border=0 title='удаление' src=img/del.bmp></a></p>");
   }

   echo("</td></tr>");
   }
   echo("</tbody></table>");
  }
  
  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<center><a href=?p=$p&mode=admin&task=new_dep><img border=0 src=img/new.bmp> новый раздел</a></center>");


  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<br><center><a href=?p=$p&act=admin>Администрирование</a></center>");

 }


 //- просмотр конкретного раздела --------------------------
 elseif($mode=="view_dep")
 {
  $department=array();
  $department=$album->GetDep($dep);

  $gals=array();
  $gals=$album->GetAllDepGals($dep);
  $sz=sizeof($gals);

  echo("<center>Раздел [".$department[0]."]</center>");

  if(!$sz) echo("<br><center>Нет галерей</center><br>");
  else
  {
  echo("<table border=1 width=70% align=center cellpadding=0 cellspacing=0><tbody>");
  for($i=0; $i<sizeof($gals); $i++)
  {
   $im=$album->GetGalImages($dep,$i);
   echo("<tr valign=top>");
   echo("<td width=10%><center>");
   echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$i&gal_part=0><img border=0 src='smaller.php?img=".$DIRS["album_img_dir"].$gals[$i][3]."'></a>");
   if($gals[$i][0]!="") echo("<br>[".$gals[$i][0]."]");
   echo("<br>[фоток - ".sizeof($im)."]</td>");
   echo("<td>".$gals[$i][2]);
   if(check_auth() && $CURRENT_USER["level"]>=5)
   {
    echo("<p align=right><a href=?p=$p&mode=admin&task=edit_gal&dep=$dep&gal=$i><img border=0 title='редактирование' src=img/edit.bmp></a><a href=?p=$p&mode=admin&task=swap_gal&dep=$dep&gal=$i><img border=0 title='перемещение' src=img/swap.bmp></a>");
    if($i>0) echo("<a href=?p=$p&mode=admin&task=move_gal_up&dep=$dep&gal=$i><img border=0 title='вверх' src=img/up.bmp></a>");
    if($i<($sz-1)) echo("<a href=?p=$p&mode=admin&task=move_gal_down&dep=$dep&gal=$i><img border=0 title='вниз' src=img/down.bmp></a>");
    echo("<a href=?p=$p&mode=admin&task=del_gal&dep=$dep&gal=$i><img border=0 title='удаление' src=img/del.bmp></a></p>");
   }
   echo("</td></tr>");
  }
  echo("</tbody></table>");
  }

  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<center><a href=?p=$p&mode=admin&task=new_gal&dep=$dep><img border=0 src=img/new.bmp> новая галерея</a><br><a href=?p=$p&mode=admin&task=folder2gal&dep=$dep><img border=0 src='img/folder.bmp'> галерея из папки</a></center>");

  echo("<center><a href=?p=$p&mode=view_deps>список разделов</a></center>");
 }



 //- просмотр галереи раздела -------------------------------
 elseif($mode=="view_gal")
 {        
  $department=array();
  $department=$album->GetDep($dep);

  if(!isset($gal_part)) $gal_part=0;
  
  $galary=array();  
  $galary=$album->GetDepGal($dep,$gal);
  echo("<center>Галерея [".$galary[0]."]</center>");

  $images=$album->GetGalImages($dep,$gal);
  if(sizeof($images)==0) echo("<br><center>Нет изображений</center><br>");

  else
  {
   if(!isset($type)) $type="by_one";

   if($type=="by_part")
   {
    $pic=$gal_part*$album->img_per_part;
    $end_pic=$pic+$album->img_per_part;
    echo("<table border=1 width=70% align=center cellpadding=0 cellspacing=0><tbody>");

    while(true)
    {
     if($pic>=sizeof($images) || $pic>=$end_pic) break;

     echo("<tr valign=top><td width=10%><center><a href='".$DIRS["album_img_dir"].$images[$pic][0]."'><img border=0 alt=№".$pic." src='smaller.php?img=".$DIRS["album_img_dir"].$images[$pic][0]."'></a></center></td>");
     echo("<td>".$images[$pic][1]);

     if(check_auth() && $CURRENT_USER["level"]>=5)
     {
      echo("<p align=right><a href=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='редактирование' src=img/edit.bmp></a>");
      //echo("<a href=?p=$p&mode=admin&task=swap_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='перемещение' src=img/swap.bmp></a>");
      if($pic!=0) echo("<a href=?p=$p&mode=admin&task=move_img_up&dep=$dep&gal=$gal&img=$pic><img border=0 title='вверх' src=img/up.bmp></a>");
      if($pic<(sizeof($images)-1)) echo("<a href=?p=$p&mode=admin&task=move_img_down&dep=$dep&gal=$gal&img=$pic><img border=0 title='вниз' src=img/down.bmp></a>");
      echo("<a href=?p=$p&mode=admin&task=rotate&dep=$dep&gal=$gal&img=$pic><img title='поворот' border=0 src=img/rotate.bmp></a>");
      echo("<a href=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='удаление' src=img/del.bmp></a>");
      //echo("<br><a href=?p=$p&mode=admin&task=rot_left&dep=$dep&gal=$gal&img=$pic><img title='поворот влево' border=0 src=img/rot_l.bmp></a><a href=?p=$p&mode=admin&task=rot_right&dep=$dep&gal=$gal&img=$pic><img title='поворот вправо' border=0 src=img/rot_r.bmp></a><a href=?p=$p&mode=admin&task=rot_left2&dep=$dep&gal=$gal&img=$pic><img title='поворот на 180°' border=0 src=img/rot_left2.bmp></a></p>");
     }

     echo("</td></tr>");
     $pic++;
    }
    echo("</tbody></table>");



 if(sizeof($images)>$album->img_per_part)
 {
  $part=0;
  $num=0;
  echo("<center>");
  if($gal_part!=0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".($gal_part-1)."&type=$type><- НАЗАД</a> ");

  while(true)
  {
   if(($part+1)>sizeof($images)) break;
   
   if($num!=$gal_part) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".$num."&type=$type>[".($num+1)."]</a> ");
   else  echo("".($num+1)." ");
   
   $num++;
   $part+=$album->img_per_part;
  }
  if((($gal_part+1)*$album->img_per_part)<sizeof($images)) echo(" <a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".($gal_part+1)."&type=$type>ВПЕРЁД -></a>");
  echo("</center>");
 }



   }




 else
 {
  if(!isset($img)) $img=0;

  echo("<table border=0 align=center><tbody>");

  echo("<tr><td><center>");
  if($img>0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img-1)."&type=$type>предыдущая</a> ");
  if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img+1)."&type=$type>следующая</a>");
  echo("</center></td></tr>");
  
  echo("<tr><td><center><a href=".$album->img_dir.$images[$img][0]."><img border=0 alt=№".$img." src=smaller.php?img=".$album->img_dir.$images[$img][0]."&own=400></a></center></td></tr>");


  if($images[$img][1]!="") echo("<tr><td><center>".$images[$img][1]."</center></td></tr>");
  echo("<tr><td><center>");
  for($i=0; $i<sizeof($images); $i++)
  {
   if($img==$i) echo(($i+1)." ");
   else echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($i)."&type=$type>[".($i+1)."]</a> ");
  }
  echo("</center></td></tr>");

  echo("<tr><td><center>");
  if($img>0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img-1)."&type=$type>предыдущая</a> ");
  if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img+1)."&type=$type>следующая</a>");
  echo("</center></td></tr>");

  if(check_auth() && $CURRENT_USER["level"]>=5)
  {
   echo("<tr><td><center>");

   echo("<a href=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$img><img title='редактирование' border=0 src=img/edit.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=swap_img&dep=$dep&gal=$gal&img=$img><img border=0 title='перемещение' src=img/swap.bmp></a>");
   if($img>0) echo("<a href=?p=$p&mode=admin&task=move_img_up&dep=$dep&gal=$gal&img=$img><img border=0 title='вверх' src=img/up.bmp></a>");
   if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=admin&task=move_img_down&dep=$dep&gal=$gal&img=$img><img border=0 title='вниз' src=img/down.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=rotate&dep=$dep&gal=$gal&img=$img><img title='поворот' border=0 src=img/rotate.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$img><img border=0 title='удаление' src=img/del.bmp></a>");
   echo("</center></td></tr>");
   
   //echo("<tr><td><center><a href=?p=$p&mode=admin&task=rot_left&dep=$dep&gal=$gal&img=$img><img title='поворот влево' border=0 src=img/rot_l.bmp></a><a href=?p=$p&mode=admin&task=rot_right&dep=$dep&gal=$gal&img=$img><img title='поворот вправо' border=0 src=img/rot_r.bmp></a><a href=?p=$p&mode=admin&task=rot_left2&dep=$dep&gal=$gal&img=$img><img title='поворот на 180°' border=0 src=img/rot_left2.bmp></a></center></td></tr>");
  }


  echo("</tbody></table>");
 }

 }


  echo("<br><center><a href=?p=$p&mode=view_dep&dep=$dep>другие галереи</a></center>");
  echo("<center><a href=?p=$p&mode=view_deps>разделы альбома</center>");
  
  if($type=="by_part") echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&type=by_img>показывать по-одной</a></center>");
  else echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&type=by_part>показывать по-группам</a></center>");

  $cmt=$album->GetGalComments($dep,$gal);
  echo("<center><a href=?p=$p&mode=comment_gal&dep=$dep&gal=$gal>комментарии (".sizeof($cmt).")</a></center>");

  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<br><center><a href=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal><img border=0 src=img/new.bmp> новая картинка</a></center>");
 }


 //---- комментирование галереи -------------------
 elseif($mode=="comment_gal")
 {

 $galery=$album->GetDepGal($dep,$gal);

  echo("
   <center>
   <fieldset style='width:300px'>
   <legend>Комментарий к галерее [".$galery[0]."]</legend>
   <form method=post action=?p=$p&mode=add_comment&dep=$dep&gal=$gal>
   <br>Nick: <input name=nick type=text>
   <br>URL: <input name=url type=text>
   <br>ICQ: <input name=icq type=text>
   Comment:
   <br><textarea name=comment_txt></textarea>
   <br><center><input type=submit value=comment></center>
   </form>
   </fieldset>
   </center>
  ");


  $cmt=$album->GetGalComments($dep,$gal);
  echo("<table align=center border=1><tbody>");
  for($i=0; $i<sizeof($cmt); $i++) 
  {
   echo("<tr>
     <td>".$cmt[$i][0]."</td>
     <td>".$cmt[$i][1]."</td>
     <td>".$cmt[$i][2]."</td>
     <td>".$cmt[$i][3]."</td>
   </tr>");
  }
  echo("</tbody></table>");
  echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Вернуться в галерею</a></center>");
 }


 //---- добавление комментария ----------
 elseif($mode=="add_comment")
 {
  $galery=$album->GetDepGal($dep,$gal);
  $comm=array($nick, $url, $icq, $comment_txt);
  $cnt=$album->GetGalComments($dep,$gal);
  $cnt=$album->Add2Levels($cnt,$comm);

  if($album->Save2Levels($album->data_dir.$galery[4],$cnt)) echo("<br>Комментарии сохранены");
  else echo("<br>Комментарии не сохранены");

  echo("<br><a href=?p=$p&mode=comment_gal&dep=$dep&gal=$gal>Посмотреть комментарии</a>");
  echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>вернуться в галерею</a>");
  echo("<br><a href=?p=$p&mode=view_deps>к списку разделов</a>");
  echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>к списку галерей</a>");
}



 //- администрирование ---------------------------------------
 elseif($mode=="admin" && check_auth() && $CURRENT_USER["level"]>=5)
 {

   if(!isset($task)) $task="";

   //---- добавление раздела ------------------------------------------
   if($task=="new_dep")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>создание раздела</legend>
      <form method=post action=?p=$p&mode=admin&task=new_dep&step=2>
      Название раздела <input type=text name=dep_name><br>
      Описание раздела <textarea name=dep_desc></textarea><br>
      Фотография раздела");

     $album->GenImgSelector($g[3]);

     echo("<input type=submit value=\"Создать раздел\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     echo("<center>Добавляем раздел.</center>");
     if($album->AddDep($dep_name,$dep_desc,$img_path)) echo("Раздел добавлен");
     else echo("Не могу добавить раздел");
     echo("<br><a href=?p=$p>список разделов</a>");
    }
    else echo("Error");
   }

   //---- добавление галереи  ------------------------------------------
   elseif($task=="new_gal")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>создание галереи</legend>
      <form method=post action=?p=$p&mode=admin&task=new_gal&dep=$dep&step=2>
      Название галереи <input type=text name=gal_name><br>
      Описание галереи <textarea name=gal_desc></textarea><br>
      Фотография галереи ");

     $album->GenImgSelector();

     echo("<input type=submit value=\"Создать галерею\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     echo("<center>Добавляем галерею.</center>");
     if($album->AddGal($dep,$gal_name,$gal_desc,$img_path)) echo("<br>Галерея добавлена");
     else echo("<br>Не могу добавить галерею");
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>вернуться в раздел</a>");
    }
    else echo("Error");
   }


   //---- добавление изображения  ------------------------------------------
   elseif($task=="new_img")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>добавление картинки</legend>
      <form method=post action=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal&step=2>
      Описание картинки <textarea name=img_desc></textarea><br>
      Изображение");

     $album->GenImgSelector();
    
     echo("<input type=submit value=\"Добавить картинку\"></form></fieldset></center>");
     echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Вернуться в галерею</a></center>");
    }
    elseif($step==2)
    {
     echo("Добавляем изображение.");
     if($album->AddImg($dep,$gal,$img_desc,$img_path)) echo("<br>Изображение добавлено");
     else echo("<br>Не могу добавить изображение");

     echo("<br><a href=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal>Добавить ещё...</a>");
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Посмотреть галерею</a>");
    }
    else echo("Error");
   }

   //--- удаление раздела --------------------------------
   elseif($task=="del_dep")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $d=$album->GetDep($dep);
     echo("<center><fieldset style='width:300px'><legend>Удаление раздела [".$d[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_dep&dep=$dep&step=2>
           <input type=radio name=flag value=0 checked=true id=v1><label for=v1>не удалять галереи и файлы раздела</label><br>
           <input type=radio name=flag value=1 id=v2><label for=v2>удалить галереи, не удалять файлы<br></label>
           <input type=radio name=flag value=2 id=v3><label for=v3>удалить галереи, физически удалить изображения<br></label>
           <br><input type=submit value=удалить></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
  
     $sum=0;
     $g=$album->GetAllDepGals($dep);
   
     for($i=0; $i<sizeof($g); $i++)
     {
      $im=$album->GetGalImages($dep,$i);
      $sum+=sizeof($im);
     }
     if($sum==0) {echo("Файлов в разделе нет. Меняем флаг."); $flag=2;}


     if(!isset($flag)) $flag=0;
     echo("<br>flag=[".$flag."]");

     if($flag==1)	$album->DelDep($dep,true,false);	//Удаляем галереи.
     elseif($flag==2)	$album->DelDep($dep,true,true);		//Удаляем галереи и файлы.
     else		$album->DelDep($dep,false,false);	//Удаляем запись о разделе.

     echo("<br><a href=?p=$p>список галерей</a>");
    }
    else echo("Error.");
   }


   //--- удаление файла из галереи ------------------------------
   elseif($task=="del_img")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);
     $im=$album->GetGalImages($dep,$gal);

     echo("<center><fieldset style='width:300px'><legend>Удаление файла [".$im[$img][0]."] из галереи [".$g[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$img&step=2>
           <input type=checkbox name=flag_del_file>удалить физически
           <input type=submit value=удалить></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $album->DelGalImage($dep,$gal,$img,$flag_del_file);
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Назад в галерею</a>");
    }
    else echo("Error");
   }
 
   //--- удаление галереи ------------------------------
   elseif($task=="del_gal")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);

     echo("<center><fieldset style='width:300px'><legend>Удаление галереи [".$g[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_gal&dep=$dep&gal=$gal&step=2>
           <input type=checkbox name=flag_del_files>удалить изображения
           <input type=submit value=удалить></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $album->DelGal($dep,$gal,$flag_del_files);
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>вернуться в раздел</a>");
    }
    else echo("Error");
   }
   

   //--- перемещение изображения вверх по списку -------------------------------------
   elseif($task=="move_img_up"){$album->DragImg($dep,$gal,$img,true); echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Назад в галерею</a>");}
   //--- перемещение галереи вверх по списку -------------------------------------
   elseif($task=="move_gal_up"){$album->DragGal($dep,$gal,true); echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>К списку галерей</a>");}
   //--- перемещение галереи вверх по списку -------------------------------------
   elseif($task=="move_dep_up"){$album->DragDep($dep,true); echo("<br><a href=?p=$p&mode_view_deps>К списку разделов</a>");}
   //--- перемещение изображения вниз по списку -------------------------------------
   elseif($task=="move_img_down"){$album->DragImg($dep,$gal,$img,false); echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Назад в галерею</a>");}
   //--- перемещение галереи вниз по списку -------------------------------------
   elseif($task=="move_gal_down"){$album->DragGal($dep,$gal,false); echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>К списку галерей</a>");}
   //--- перемещение галереи вниз по списку -------------------------------------
   elseif($task=="move_dep_down"){$album->DragDep($dep,false); echo("<br><a href=?p=$p&mode_view_deps>К списку разделов</a>");}


   //--- редактирование данных изображения -------------------------------------
   elseif($task=="edit_img")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $im=$album->GetGalImages($dep,$gal);

     echo("<center><fieldset style='width:300px'>
     <legend>Редактирование изображения [".$im[$img][0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$img&step=2>
     Описание картинки <textarea COLS=30 ROWS=10  name=img_desc>".$im[$img][1]."</textarea><br>
     Изображение");

     $album->GenImgSelector($im[$img][0]);

     echo("<input type=submit value=\"Обновить данные\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateImg($dep,$gal,$img,$img_path,$img_desc)) echo("<br>Изображение обновлено");
     else echo("<br>Изображение НЕ обновлено");
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>Вернуться в галерею</a>");
    }
    else echo("Error");
   }


   //--- редактирование данных галереи -------------------------------------
   elseif($task=="edit_gal")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);

     echo("<center><fieldset style='width:300px'>
     <legend>Редактирование галереи [".$g[0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_gal&dep=$dep&gal=$gal&step=2>
     Название галереи <input type=text name=gal_name value=\"".$g[0]."\"><br>
     Описание галереи <textarea COLS=30 ROWS=10 name=gal_desc>".$g[2]."</textarea><br>
     Изображение");

     $album->GenImgSelector($g[3]);

     echo("<input type=submit value=\"Обновить данные\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateGal($dep,$gal,$gal_name,$gal_desc,$img_path)) echo("<br>Галерея обновлена");
     else echo("<br>Галерея НЕ обновлена");
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>Веруться в раздел</a>");
    }
    else echo("Error");
   }


   //--- редактирование данных раздела -------------------------------------
   elseif($task=="edit_dep")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $d=$album->GetDep($dep);

     echo("<center><fieldset style='width:300px'>
     <legend>Редактирование раздела [".$d[0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_dep&dep=$dep&step=2>
     Название раздела <input type=text name=dep_name value=\"".$d[0]."\"><br>
     Описание раздела <textarea COLS=30 ROWS=10 name=dep_desc>".$d[2]."</textarea><br>
     Изображение");

     $album->GenImgSelector($d[3]);

     echo("<input type=submit value=\"Обновить данные\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateDep($dep,$dep_name,$dep_desc,$img_path)) echo("<br>Раздел обновлён");
     else echo("<br>Раздел НЕ обновлён");
     echo("<br><a href=?p=$p>список разделов</a>");
    }
    else echo("Error");
   }

   //--- перемещение галереи в другой раздел --------------------
   elseif($task=="swap_gal")
   {
    if(!isset($step)) $step=1;
 
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);
     echo("<center><fieldset style='width:300px'>
     <legend>Премещение галереи [".$g[0]."]</legend>");

     $d=$album->GetAllDeps();
     echo("<br>Текущий раздел: [".$d[$dep][0]."]");
     echo("<br>Переместить в раздел:<br><br>");

     echo("<form action=?p=$p&mode=admin&task=swap_gal&step=2&dep=$dep&gal=$gal method=post>");

     $album->GenDepSelector($dep);

     echo("<br><br><input type=submit value='Переместить'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $d=$album->GetAllDeps();
     $g=$album->GetDepGal($dep,$gal); //перемещаемая галерея
     $g2=$album->GetAllDepGals($dest_dep); //галереи конечного раздела

     echo("<br>Перемещаем галерею [".$g[0]."] из раздела [".$d[$dep][0]."] в раздел [".$d[$dest_dep][0]."]");
     $g2=$album->Add2Levels($g2,$g);

     if($album->Save2Levels($album->data_dir.$d[$dest_dep][1],$g2))
     {
      echo("<br>Галерея перемещена");

      $g1=$album->GetAllDepGals($dep); //галереи исходного раздела
      $new_g1=$album->Move2Levels($g1,$gal);

      if($album->Save2Levels($album->data_dir.$d[$dep][1],$new_g1)) echo("<br>Запись удалена");
      else echo("<br>Запись не удалена");
     }
     else echo("<br>Галерея не перемещена!");


     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>вернуться в раздел</a>");
    }
    else echo("Error");
   }
   


   //--- перемещение файла в другую галерею --------------------
/*   elseif($task=="swap_img")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $im=$album->GetGalImages($dep,$gal);
     echo("<center><fieldset style='width:300px'>
     <legend>Премещение файла [".$im[$img][0]."]</legend>");
     echo("<br>Переместить в галерею:");
     echo("<form method=post action=?p=$p&mode=admin&task=swap_img&step=2&dep=$dep&gal=$gal&img=$img>");
     $album->GenGalSelector($dep,$gal);
     echo("<br><br><input type=submit value='Переместить'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     //$album->DelGalImage($dep,$gal,$img,false);
     //if($album->AddImg($dest_dep,$dest_gal,$img_desc,$img_path)) echo("<br>Изображение добавлено");
     echo("Under construction");
    }
    else echo("Error");
   }
*/
   
   
   //--- создание галереи из папки --------------------
   elseif($task=="folder2gal")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $d=$album->GetDep($dep);

     echo("<center><fieldset style='width:300px'>
     <legend>Галерея в раздел $d[0] из папки</legend>");
     echo("<br>Доступные папки:");
     echo("<form method=post action=?p=$p&mode=admin&task=folder2gal&step=2&dep=$dep>");
     $album->GenFolderSelector($album->img_dir);
     echo("<br>Название: <input type=text name=gal_name>");
     echo("<br>Описание: <textarea name=gal_desc COLS=30 ROWS=10></textarea>");
     echo("<br><br><input type=submit value='Создать галерею'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $d=$album->GetDep($dep);
     $fs=new CFileSystem();

     $res=$fs->GetDirFiles($album->img_dir.$folder_path);
     echo("<br>В папке [".$album->img_dir.$folder_path."] найдено [".sizeof($res)."] файлов");

     echo("<br>Добавлем галерею");

     $g=$album->GetAllDepGals($dep);

     $album->AddGal($dep,$gal_name,$gal_desc,$folder_path."/".$res[0]);

     $new_gal=sizeof($g);
     for($i=0; $i<sizeof($res); $i++)
     {
      echo("<br>Добавление файла [".$folder_path."/".$res[$i]."]");
      $album->AddImg($dep,$new_gal,"",$folder_path."/".$res[$i]);
     }
   

     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$new_gal>посмотреть галерею</a>");
    }
    else echo("Error");
   }



   //--- поворот изображения -------------------------------------
   elseif($task=="rotate")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     echo("<center><fieldset style='width:300px'><legend>Поворот изображения</legend>");
     echo("<form action=?p=$p&mode=admin&task=rotate&step=2&dep=$dep&gal=$gal&img=$img method=post>");
     echo("Угол поворота: <select name=grad>");
     echo("<option value=0>90°</option>");
     echo("<option value=1>180°</option>");
     echo("<option value=2>270°</option>");
     echo("</select>");
     echo("<center><br><input type=radio name=dir checked=true id=var1 value=0><label for=var1>повернуть влево</label></center>");
     echo("<center><input type=radio name=dir id=var2 value=1><label for=var2>повернуть вправо</label></center><br>");
     echo("</select>");
     echo("<input type=submit value=Повернуть></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     if($grad==0) $grad=90;
     elseif($grad==1) $grad=180;
     elseif($grad==2) $grad=270;
     else $grad=0;

     if($dir==1) $grad=-$grad;

    $im=$album->GetGalImages($dep,$gal);
     echo("Поворачиваем изображение [".$im[$img][0]."]");
     $imaga=imagecreatefromjpeg($album->img_dir.$im[$img][0]);
     $clr=imagecolorallocate($imaga,0,0,0);
     $imaga=imagerotate($imaga,$grad,$clr);
     imagejpeg($imaga,$album->img_dir.$im[$img][0]);
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>вернуться в галерею</a>");

    }
    else echo("Error");
   }

   //--- неизвестная задача -------------------------------------
   else echo("<center>Undefined administration task!</center>");

 }

 //---- неверный параметр --------
 else echo("НЕВЕРНЫЙ ПАРАМЕТР");
  
echo("<br><center><font style='font-size:1px; color=white;'>© DSISS, 2002-2006 [dsiss['dog']mail.ru]</font></center>");
     
 }
  else
    include(SK_DIR."/photoalbum.php");

else
//================================================
// МОДУЛЬ ДЛЯ ВСЕХ В ВИДЕ КОЛОНКИ (НАПРИМЕР, Последние новости)
// Здесь надо вставить код, доступный всем и каждому
//================================================
  if(!file_exists(SK_DIR."/photoalbum_bar.php"))
    {
     include "config.php"; 
     /*
     здесь нужно вставить код для всех пользователей для
     отображения последних записей или какой-либо статистики
     */         
    }
  else
    include(SK_DIR."/photoalbum_bar.php");
    
}

?>