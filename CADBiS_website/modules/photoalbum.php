<?php
//----------------------------------------------------------------------//
//    TITLE: Photoalbum mega-module                                     //
//    MAKER: DS                                                         //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="����������";
$MDL_DESCR="����������";
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
// ����-����� �����������.
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
 //�����������
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
 //������� ������� �������������� ������.
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
 //���������� �������������� ������ � ������.
 //---------------------------------------
 function Add2Levels($source,$arr)
 {
  $sz=sizeof($source);

  for($i=0; $i<sizeof($arr); $i++)
   $source[$sz][$i]=$arr[$i];

  return $source;
 }

 //---------------------------------------
 //�������� �������������� �������� �� �������.
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
 //���������� �������������� ������� � ����.
 //---------------------------------------
 function Save2Levels($filename,$arr)
 {
  //echo("<br>��������� ���� [".$filename."]");
  if($hnd=fopen($filename,"w"))
  {
   //echo("<br>���� $filename ������");
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
 //�������� ��� ������� �����������.
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
 //�������� ������ �����������.
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
 //�������� ��� ������� �������.
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
 //�������� ������� �������.
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
 //�������� ��� ������� �������.
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
 //�������� ������ �����������.
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
 //������� ���� �����������.
 //---------------------------------------
 function DelGalImage($dep,$gal,$img,$flag_del_file=false)
 {
  
  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);
 
  echo("<br>������� ���� [".$im[$img][0]."] ������� [".$g[0]."]");
  
  //�������� ����������� � �����.
  if(is_file($this->img_dir.$im[$img][0]) && $flag_del_file)
  {
   unlink($this->img_dir.$im[$img][0]);
   if(!is_file($this->img_dir.$im[$img][0])) echo("<br>���� ����� � �����");
   else echo("<br>���� �� ����� � �����");
  }

  //������� ������ $img �� ����� �������.
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

  //�������������� ���� �������.
  //echo("<br>��������� ���� ������� [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$new_img)) echo("<br>������� ���������"); 
  else echo("<br>������� �� ���������");
 }


 
 //---------------------------------------
 //������� ������� �������.
 //---------------------------------------
 function DelGal($dep,$gal,$flag_del_files)
 {

  $d=$this->GetDep($dep);
  $g=$this->GetAllDepGals($dep);
   
  echo("<br>������� ������� [".$g[$dep][0]."]");

  //�������� ����������� �� �������.
  if($flag_del_files)
  {
   echo("<br>������� ����������� �� �������");
   //print_r($im);
   
   $im=$this->GetGalImages($dep,$gal);
   for($j=0; $j<sizeof($im); $j++)
   {
    if($im[$j][0]!="" && is_file($this->img_dir.$im[$j][0]))
    {
     echo("<br>������� ���� [".$im[$j][0]."]");
     unlink($this->img_dir.$im[$j][0]);
     
     if(is_file($this->img_dir.$im[$j][0]))
      echo("<br>���� [".$im[$j][0]."] �� �����!");
    }
   }
  }
  
  
  echo("<br>������� ���� ������� [".$g[$gal][1]."]");
  if(is_file($this->data_dir.$g[$gal][1]))
  {
   unlink($this->data_dir.$g[$gal][1]);
   if(is_file($this->data_dir.$g[$gal][1]))
      echo("<br>���� [".$g[$gal][1]."] �� �����!");
  }

  echo("<br>������� ����������� ������� [".$g[$gal][4]."]");
  if(is_file($this->data_dir.$g[$gal][4]))
  {
   unlink($this->data_dir.$g[$gal][4]);
   if(is_file($this->data_dir.$g[$gal][4]))
      echo("<br>���� [".$g[$gal][4]."] �� �����!");
  }


  //������� ������� �� ������ �������.
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

  //�������������� ���� �������.
  //echo("<br>��������� ���� ������� [".$d[1]."]");
  if($this->Save2Levels($this->data_dir.$d[1],$new_gals)) echo("<br>������ �������"); 
  else echo("<br>������ �� �������");

 }
 

 //---------------------------------------
 //������� ������ �������.
 //---------------------------------------
 function DelDep($dep, $flag_del_gals=false, $flag_del_files=false)
 {
  $d=$this->GetAllDeps();
  $g=$this->GetAllDepGals($dep);

  if($flag_del_gals)
  {
   echo("<br>������� �������");
   for($i=0; $i<sizeof($g); $i++)
    $this->DelGal($dep,0,$flag_del_files);
  }
  
  echo("<br>������� ���� � ��������� ������� ������� [".$d[$dep][1]."]");
  if(is_file($this->data_dir.$d[$dep][1]))
  {
   unlink($this->data_dir.$d[$dep][1]);
   if(is_file($this->data_dir.$d[$dep][1]))
    echo("<br>���� [".$d[$dep][1]."] �� �����!");
  }


  //������� ������ �� ������ ��������.
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

  //echo("<br>������ ��������: ".sizeof($new_deps)."<br>");
  //print_r($new_deps);
  if($this->Save2Levels($this->deps_file,$new_deps)) echo("<br>������� ���������");
  else echo("<br>������� �� ���������");
 }
 


 //---------------------------------------
 //��������� ����� � ������ ��������� � ������.
 //---------------------------------------
 function AddDep($name,$desc,$img)
 {
  //������ ���� ��� �������.
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
   //echo("<br>����� ��������: ".sizeof($res));
     
   $sz=sizeof($res);
   $res[$sz][0]=$name;
   $res[$sz][1]=$filename;
   $res[$sz][2]=$desc;
   $res[$sz][3]=$img;

   //echo("<br>������ ��������: ".sizeof($res));
   if($this->Save2Levels($this->deps_file,$res)) return true;
   
  }
  return false;
 }




 //---------------------------------------
 //��������� ������� � ������ ������� ��������� � ������.
 //---------------------------------------
 function AddGal($dep,$name,$desc,$img)
 {
  $d=$this->GetDep($dep);
  $res=$this->GetAllDepGals($dep);


  //������ ���� ��� ������������.
  $comm_filename="";
  while(true)
  {
   $comm_filename="comment_".time().".txt";
   if(!file_exists($this->data_dir.$comm_filename)) break;
  }
  $hnd=fopen($this->data_dir.$comm_filename,"w");
  if($hnd) fclose($hnd);


  //������ ���� ��� �������.
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

   //echo("<br>����� �������: ".sizeof($res));
     
   $sz=sizeof($res);
   $res[$sz][0]=$name;
   $res[$sz][1]=$gal_filename;
   $res[$sz][2]=$desc;
   $res[$sz][3]=$img;
   $res[$sz][4]=$comm_filename;

   //echo("<br>������ �������: ".sizeof($res));
   echo("<br>��������� ���� ������� [".$d[1]."]");
   if($this->Save2Levels($this->data_dir.$d[1],$res)) return true;
  }
  return false;
 }



 //---------------------------------------
 //��������� ����������� � ������� ��������� � ������.
 //---------------------------------------
 function AddImg($dep,$gal,$desc,$path)
 {
  //echo("<br>dep=$dep; gal=$gal; path=$path; desc=$desc");  

  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);

  //echo("<br>����� �����������: ".sizeof($im));
     
  $sz=sizeof($im);
  $im[$sz][0]=$path;
  $im[$sz][1]=$desc;

  //echo("<br>������ �����������: ".sizeof($im));

  //echo("<br>��������� ���� ������� [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$im)) return true;

  return false;
 }




 //---------------------------------------
 //����� ���������� ���� ��������� �������.
 //---------------------------------------
 function Swap2Levels($arr, $i, $j)
 {
  //echo("<br>����� �������� ��������� $i � $j <br>");

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
 //���������� ����������� ����� ��� ���� �� ������.
 //---------------------------------------
 function DragImg($dep,$gal,$img,$up=true)
 {

  $im=$this->GetGalImages($dep,$gal);
  $sz=sizeof($im);
  if($img==0 && $up==true || ($img+1)==$sz && $up==false) return;

  if($up) { echo("<br>���������� �����"); $n_im=$this->Swap2Levels($im,$img,$img-1); }
  else    { echo("<br>���������� ����");  $n_im=$this->Swap2Levels($im,$img,$img+1); }

  $g=$this->GetDepGal($dep,$gal);

  //�������������� ���� �������.
  echo("<br>��������� ���� ������� [".$g[1]."]");
  if($this->Save2Levels($this->data_dir.$g[1],$n_im)) echo("<br>������� ���������"); 
  else echo("<br>������� �� ���������");
 }


 //---------------------------------------
 //���������� ������� ����� ��� ���� �� ������.
 //---------------------------------------
 function DragGal($dep,$gal,$up=true)
 {

  $d=$this->GetDep($dep);
  $g=$this->GetAllDepGals($dep);
  $sz=sizeof($g);
  if($gal==0 && $up==true || ($gal+1)==$sz && $up==false) return;

  if($up) { echo("<br>���������� �����"); $n_gal=$this->Swap2Levels($g,$gal,$gal-1); }
  else    { echo("<br>���������� ����");  $n_gal=$this->Swap2Levels($g,$gal,$gal+1); }

  //�������������� ���� �������.
  echo("<br>��������� ���� ������� [".$d[1]."]");
  if($this->Save2Levels($this->data_dir.$d[1],$n_gal)) echo("<br>������ �������"); 
  else echo("<br>������ �� �������");
 }


 //---------------------------------------
 //���������� ������ ����� ��� ���� �� ������.
 //---------------------------------------
 function DragDep($dep,$up=true)
 {

  $d=$this->GetAllDeps($dep,$gal);
  $sz=sizeof($d);
  if($dep==0 && $up==true || ($dep+1)==$sz && $up==false) return;

  if($up) { echo("<br>���������� �����"); $n_dep=$this->Swap2Levels($d,$dep,$dep-1); }
  else    { echo("<br>���������� ����");  $n_dep=$this->Swap2Levels($d,$dep,$dep+1); }

  //�������������� ���� ��������.
  echo("<br>��������� ���� �������� [".$this->deps_file."]");
  if($this->Save2Levels($this->deps_file,$n_dep)) echo("<br>������� ���������"); 
  else echo("<br>������� �� ���������");
 }
 

 //---------------------------------------
 //��������� ������ �����������.
 //---------------------------------------
 function UpdateImg($dep,$gal,$img,$path,$desc)
 {
  $g=$this->GetDepGal($dep,$gal);
  $im=$this->GetGalImages($dep,$gal);  

  $im[$img][0]=$path;
  $im[$img][1]=$desc;

  //�������������� ���� �������.
  echo("<br>��������� ���� ������� [".$g[1]."]");
  return $this->Save2Levels($this->data_dir.$g[1],$im);
 }
 

 //---------------------------------------
 //��������� ������ �������.
 //---------------------------------------
 function UpdateGal($dep,$gal,$name,$desc,$img)
 {

  $d=$this->GetDep($dep);  
  $g=$this->GetAllDepGals($dep,$gal);

  $g[$gal][0]=$name;
  $g[$gal][2]=$desc;
  $g[$gal][3]=$img;

  //�������������� ���� �������.
  echo("<br>��������� ���� ������� [".$d[1]."]");
  return $this->Save2Levels($this->data_dir.$d[1],$g);
 }


 //---------------------------------------
 //��������� ������ �������.
 //---------------------------------------
 function UpdateDep($dep,$name,$desc,$img)
 {
  $d=$this->GetAllDeps($dep);  

  $d[$dep][0]=$name;
  $d[$dep][2]=$desc;
  $d[$dep][3]=$img;

  //�������������� ���� ��������.
  echo("<br>��������� ���� �������� [".$this->deps_file."]");
  return $this->Save2Levels($this->deps_file,$d);
 }

  
 //---------------------------------------
 //��������� ������ �������.
 //---------------------------------------
 function GenImgSelector($sel_img="")
 {

  $fs=new CFileSystem();
  $this->img_dir;
  $scan=$fs->GetDirDirs($this->img_dir);
  echo("<select name=img_path>");
  for($i=0; $i<sizeof($scan); $i++)
  {
   echo("<optgroup label=\"����� [".$scan[$i]."]\">");
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
// �����������
//================================================
  if($_INSTALL)
  {
   //���������� ������������ �� �������� ���������� 
  }
//================================================
// �������������
//================================================
  elseif($_UNINSTALL)
  {
   //������� ����� ������������ ������
  }
//================================================
// ������ ��� ��������������
// ���������� ��������� ������� ��������� ���� (�����������
// ������� $CURRENT_USER["level"]>=5, ��� 5- ������� ������
// (5-���������, 6-�����, 7-����������)
// check_auth() ��������� ������ ����������� �� ����
// � �������� ��� �������� ����� ���� ������ �����: 
// elseif($_ADMIN) 
// ����� ����� ����� ��������� ����� ����� 
//================================================
  elseif($_ADMIN && check_auth() && $CURRENT_USER["level"]>=5)
  {
    if(!file_exists(SK_DIR."/photoalbum_admin.php"))
     {
     include "config.php";
     /*
     ����� ����� �������� ��� ��������� ���������    
     P.S. 
       - ����� �� ��������� ����������: 
       $act,$id
       - �� � ���� ������ �� ��������� ��������� ���������:
       $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$p
       - ��������� ���������� � �������� �� ������... (����� �����) 
       $page,$type,$topic,$art,$a,     
     */
     
     
     /*
      ����� ������ � ��� ����� ���� ����� ���������� �:
      ?p=$p&act=admin
      ��� ����� ���������! �� ����, ���� ����� ������� �����, ��

      � action ���� ������: 
      <form action="<? OUT("?p=$p&act=admin") ?>&[����� ���� ���������]" ...>
      ��������:
        <form action="<? OUT("?p=$p&act=admin") ?>&myvar1=true&myvar2=12&myvar3=blablabla" method=post>

      ���� �� ������ ������, �� ���:
      <a href="<? OUT("?p=$p&act=admin") ?>&[����� ���� ���������]" ...>...</a> 
      ��������:
        <a href="<? OUT("?p=$p&act=admin") ?>&myvar1=true&myvar2=12&myvar3=blablabla">������</a>   
     */

      echo("<center><fieldset style='width:400px'><legend>�����������������</legend>");
      echo("����� ���������� ������/�������/��������");
      echo("<br>�������� ������ � ����������");
      echo("<br>��������� ���������� �����������");
      echo("<br><a href=?p=$p&act=admin&task=srch>����� ����������� �� �����/��������</a>");

      echo("</fieldset></center>");

         
     }
    else
      include(SK_DIR."/photoalbum_admin.php");  
  }
//================================================
// ������ ��� ����
// ����� ���� �������� ���, ��������� ���� � �������
// ���� ��� ����� ��������, ���� ������������ ������ ���������
//================================================
 elseif($_MODULE) 
if($_NOTBAR)
  if(!file_exists(SK_DIR."/photoalbum.php"))
   {  
   if(isset($act)&& $act=="admin"){$MDL->LoadAdminPage("$p"); return;}
     include "config.php";   
     /*
     ����� ����� �������� ��� ��� ���� �������������
     P.S. 
       - ����� �� ��������� ����������: 
       $act,$id
       - �� � ���� ������ �� ��������� ��������� ���������:
       $GV,$MDL,$DIRS,$ERR,$FLTR,$PDIV,$CURRENT_USER,$p
       - ��������� ���������� � �������� �� ������... (����� �����) 
       $page,$type,$topic,$art,$a,             
     */
     
     /*
      ����� ������ � ��� ����� ���� ����� ���������� �:
      ?p=$p
      ��� ����� ���������! �� ����, ���� ����� ������� �����, ��� action ���� ������: 
      <form action="<? OUT("?p=$p") ?>&[����� ���� ���������]" ...>
      ��������:
        <form action="<? OUT("?p=$p") ?>&myvar1=true&myvar2=12&myvar3=blablabla"  method=post>

      ���� �� ������ ������, �� ���:
      <a href="<? OUT("?p=$p") ?>&[����� ���� ���������]" ...>...</a> 
      ��������:
        <a href="<? OUT("?p=$p") ?>&myvar1=true&myvar2=12&myvar3=blablabla">������</a> 
          
     � ����� ����� ����� ���� ����� ������� return;
     ��� ��������� ���������� ���� ����� � ����� �� ��������� �� ����������
     
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     
     ��������� ���:      
     1) ��� ���������� ���������� (�������� ���� � ������ ��� �����������) ������ � ����
       modules_conf/MYMODULE.conf.php
       ��� MYMODULE - �������� ������ ������. 
       ���� � ������ � ������ ����� ������� � ������� $DIRS
       ��������, ��� ����� ����� ���� � ���������� �� ������� ��������, � ���� � �����
       ����� ������:
       $DIRS["dir_newslist"]="data/news/list";
        (!) �����, ��� �� ��� ����� - ������������ ������������ index.php � �� modules/MYMODULE.php
       ������ ������ ������ � ������� ��� ���� � ������:
       $MYMDL = new $MYCLASS($DIRS["dir_newslist"]);
       
     2) ���� ���� ��������� ��������� �� ������������
       ������� check_auth() ���������� true ���� �� �����������
       � ���� ������:
        $CURRENT_USER["id"] -�������������
        $CURRENT_USER["nick"]  - ���
        $CURRENT_USER["ip"]  - ip �����
        $CURRENT_USER["login"] - �����
        $CURRENT_USER["email"] - �����
        $CURRENT_USER["url"] - ���
        $CURRENT_USER["level"] - ������� (1-4 - ������������, 5-���������, 6-�����, >7-root)
        � ����� ������ name ���� ������� ������ id. �� ����, ���� ��������� �������
        ������������, �� �������� � ���� name $CURRENT_USER["id"]
        ��� ������ ������� ����� ���:
        (�������� � ��� ������ ������ �������� � $data, � $data["name"] - ������������� �����)
        
        $ud=get_user_data($data["name"]);
        //������ �������� �� ������ $data["name"], � $ud["nick"] (�� ���� ��� ������������)
        //� ���������� � ������� �� ���� � ��:
        <a href="?p=users&act=userinfo&id=<? OUT($data["name"]) ?>"><? OUT($ud["nick"]) ?></a>
                               
     3) ����� ������� �� �������� ��������� ������ $PDIV:
       �������� ��� ������, ��������� ������ ������ �� ����������� ��������:
       $array_on_page=$PDIV->GetPage($array,$page_num);
       
     4) ��� ���������� ����� ��������� ������ $FLTR:
       //��� ���������� ��� ��������� �������:
       $string=$FLTR->DirectProcessString($string);
       $text=$FLTR->DirectProcessText($text);
       
       //��� �������� �������������� (��� ��������������)
       $string=$FLTR->ReverseProcessString($string);
       $text=$FLTR->ReverseProcessText($text);
       
     5) ���� ����� ���������� � ������ ������� (��������, ���� ������ ���������),
       ����� ������������ ������ $MDL
       �� ��� �� �������.. 
       
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)
     (!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)(!)       
     */
  

 echo("<center><h3>����������</h3></center>");

 $album = new CPhotoAlbum($GV["album_deps_file"],$DIRS["album_data_dir"],$DIRS["album_img_dir"],$GV["album_level1_delim"],$GV["album_level2_delim"],$GV["album_img_per_part"]);
 
 if(!isset($mode)) $mode="view_deps";
 if(!isset($_SESSION['ADMIN'])) $_SESSION['ADMIN']=false;


 //- �������� ���� �������� -----------------------------
 if($mode=="view_deps")
 {

  $deps=array();
  $deps=$album->GetAllDeps();
  $sz=sizeof($deps);

  if(!$sz) echo("<br><center>��� ��������</center><br>");
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
   echo("<br>[������� - ".sizeof($gals)."]<br>[����� - ".$sum."]</center></td>");
   echo("<td>".$deps[$i][2]);

   if(check_auth() && $CURRENT_USER["level"]>=5)
   {
    echo("<p align=right><a href=?p=$p&mode=admin&task=edit_dep&dep=$i><img border=0 title='��������������' src=img/edit.bmp></a>");
    if($i>0) echo("<a href=?p=$p&mode=admin&task=move_dep_up&dep=$i><img border=0 title='�����' src=img/up.bmp></a>");
    if($i<($sz-1))echo("<a href=?p=$p&mode=admin&task=move_dep_down&dep=$i><img border=0 title='����' src=img/down.bmp></a>");
    echo("<a href=?p=$p&mode=admin&task=del_dep&dep=$i><img border=0 title='��������' src=img/del.bmp></a></p>");
   }

   echo("</td></tr>");
   }
   echo("</tbody></table>");
  }
  
  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<center><a href=?p=$p&mode=admin&task=new_dep><img border=0 src=img/new.bmp> ����� ������</a></center>");


  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<br><center><a href=?p=$p&act=admin>�����������������</a></center>");

 }


 //- �������� ����������� ������� --------------------------
 elseif($mode=="view_dep")
 {
  $department=array();
  $department=$album->GetDep($dep);

  $gals=array();
  $gals=$album->GetAllDepGals($dep);
  $sz=sizeof($gals);

  echo("<center>������ [".$department[0]."]</center>");

  if(!$sz) echo("<br><center>��� �������</center><br>");
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
   echo("<br>[����� - ".sizeof($im)."]</td>");
   echo("<td>".$gals[$i][2]);
   if(check_auth() && $CURRENT_USER["level"]>=5)
   {
    echo("<p align=right><a href=?p=$p&mode=admin&task=edit_gal&dep=$dep&gal=$i><img border=0 title='��������������' src=img/edit.bmp></a><a href=?p=$p&mode=admin&task=swap_gal&dep=$dep&gal=$i><img border=0 title='�����������' src=img/swap.bmp></a>");
    if($i>0) echo("<a href=?p=$p&mode=admin&task=move_gal_up&dep=$dep&gal=$i><img border=0 title='�����' src=img/up.bmp></a>");
    if($i<($sz-1)) echo("<a href=?p=$p&mode=admin&task=move_gal_down&dep=$dep&gal=$i><img border=0 title='����' src=img/down.bmp></a>");
    echo("<a href=?p=$p&mode=admin&task=del_gal&dep=$dep&gal=$i><img border=0 title='��������' src=img/del.bmp></a></p>");
   }
   echo("</td></tr>");
  }
  echo("</tbody></table>");
  }

  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<center><a href=?p=$p&mode=admin&task=new_gal&dep=$dep><img border=0 src=img/new.bmp> ����� �������</a><br><a href=?p=$p&mode=admin&task=folder2gal&dep=$dep><img border=0 src='img/folder.bmp'> ������� �� �����</a></center>");

  echo("<center><a href=?p=$p&mode=view_deps>������ ��������</a></center>");
 }



 //- �������� ������� ������� -------------------------------
 elseif($mode=="view_gal")
 {        
  $department=array();
  $department=$album->GetDep($dep);

  if(!isset($gal_part)) $gal_part=0;
  
  $galary=array();  
  $galary=$album->GetDepGal($dep,$gal);
  echo("<center>������� [".$galary[0]."]</center>");

  $images=$album->GetGalImages($dep,$gal);
  if(sizeof($images)==0) echo("<br><center>��� �����������</center><br>");

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

     echo("<tr valign=top><td width=10%><center><a href='".$DIRS["album_img_dir"].$images[$pic][0]."'><img border=0 alt=�".$pic." src='smaller.php?img=".$DIRS["album_img_dir"].$images[$pic][0]."'></a></center></td>");
     echo("<td>".$images[$pic][1]);

     if(check_auth() && $CURRENT_USER["level"]>=5)
     {
      echo("<p align=right><a href=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='��������������' src=img/edit.bmp></a>");
      //echo("<a href=?p=$p&mode=admin&task=swap_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='�����������' src=img/swap.bmp></a>");
      if($pic!=0) echo("<a href=?p=$p&mode=admin&task=move_img_up&dep=$dep&gal=$gal&img=$pic><img border=0 title='�����' src=img/up.bmp></a>");
      if($pic<(sizeof($images)-1)) echo("<a href=?p=$p&mode=admin&task=move_img_down&dep=$dep&gal=$gal&img=$pic><img border=0 title='����' src=img/down.bmp></a>");
      echo("<a href=?p=$p&mode=admin&task=rotate&dep=$dep&gal=$gal&img=$pic><img title='�������' border=0 src=img/rotate.bmp></a>");
      echo("<a href=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$pic><img border=0 title='��������' src=img/del.bmp></a>");
      //echo("<br><a href=?p=$p&mode=admin&task=rot_left&dep=$dep&gal=$gal&img=$pic><img title='������� �����' border=0 src=img/rot_l.bmp></a><a href=?p=$p&mode=admin&task=rot_right&dep=$dep&gal=$gal&img=$pic><img title='������� ������' border=0 src=img/rot_r.bmp></a><a href=?p=$p&mode=admin&task=rot_left2&dep=$dep&gal=$gal&img=$pic><img title='������� �� 180�' border=0 src=img/rot_left2.bmp></a></p>");
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
  if($gal_part!=0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".($gal_part-1)."&type=$type><- �����</a> ");

  while(true)
  {
   if(($part+1)>sizeof($images)) break;
   
   if($num!=$gal_part) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".$num."&type=$type>[".($num+1)."]</a> ");
   else  echo("".($num+1)." ");
   
   $num++;
   $part+=$album->img_per_part;
  }
  if((($gal_part+1)*$album->img_per_part)<sizeof($images)) echo(" <a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&gal_part=".($gal_part+1)."&type=$type>���Ш� -></a>");
  echo("</center>");
 }



   }




 else
 {
  if(!isset($img)) $img=0;

  echo("<table border=0 align=center><tbody>");

  echo("<tr><td><center>");
  if($img>0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img-1)."&type=$type>����������</a> ");
  if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img+1)."&type=$type>���������</a>");
  echo("</center></td></tr>");
  
  echo("<tr><td><center><a href=".$album->img_dir.$images[$img][0]."><img border=0 alt=�".$img." src=smaller.php?img=".$album->img_dir.$images[$img][0]."&own=400></a></center></td></tr>");


  if($images[$img][1]!="") echo("<tr><td><center>".$images[$img][1]."</center></td></tr>");
  echo("<tr><td><center>");
  for($i=0; $i<sizeof($images); $i++)
  {
   if($img==$i) echo(($i+1)." ");
   else echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($i)."&type=$type>[".($i+1)."]</a> ");
  }
  echo("</center></td></tr>");

  echo("<tr><td><center>");
  if($img>0) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img-1)."&type=$type>����������</a> ");
  if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&img=".($img+1)."&type=$type>���������</a>");
  echo("</center></td></tr>");

  if(check_auth() && $CURRENT_USER["level"]>=5)
  {
   echo("<tr><td><center>");

   echo("<a href=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$img><img title='��������������' border=0 src=img/edit.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=swap_img&dep=$dep&gal=$gal&img=$img><img border=0 title='�����������' src=img/swap.bmp></a>");
   if($img>0) echo("<a href=?p=$p&mode=admin&task=move_img_up&dep=$dep&gal=$gal&img=$img><img border=0 title='�����' src=img/up.bmp></a>");
   if($img<(sizeof($images)-1)) echo("<a href=?p=$p&mode=admin&task=move_img_down&dep=$dep&gal=$gal&img=$img><img border=0 title='����' src=img/down.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=rotate&dep=$dep&gal=$gal&img=$img><img title='�������' border=0 src=img/rotate.bmp></a>");
   echo("<a href=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$img><img border=0 title='��������' src=img/del.bmp></a>");
   echo("</center></td></tr>");
   
   //echo("<tr><td><center><a href=?p=$p&mode=admin&task=rot_left&dep=$dep&gal=$gal&img=$img><img title='������� �����' border=0 src=img/rot_l.bmp></a><a href=?p=$p&mode=admin&task=rot_right&dep=$dep&gal=$gal&img=$img><img title='������� ������' border=0 src=img/rot_r.bmp></a><a href=?p=$p&mode=admin&task=rot_left2&dep=$dep&gal=$gal&img=$img><img title='������� �� 180�' border=0 src=img/rot_left2.bmp></a></center></td></tr>");
  }


  echo("</tbody></table>");
 }

 }


  echo("<br><center><a href=?p=$p&mode=view_dep&dep=$dep>������ �������</a></center>");
  echo("<center><a href=?p=$p&mode=view_deps>������� �������</center>");
  
  if($type=="by_part") echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&type=by_img>���������� ��-�����</a></center>");
  else echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal&type=by_part>���������� ��-�������</a></center>");

  $cmt=$album->GetGalComments($dep,$gal);
  echo("<center><a href=?p=$p&mode=comment_gal&dep=$dep&gal=$gal>����������� (".sizeof($cmt).")</a></center>");

  if(check_auth() && $CURRENT_USER["level"]>=5) echo("<br><center><a href=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal><img border=0 src=img/new.bmp> ����� ��������</a></center>");
 }


 //---- ��������������� ������� -------------------
 elseif($mode=="comment_gal")
 {

 $galery=$album->GetDepGal($dep,$gal);

  echo("
   <center>
   <fieldset style='width:300px'>
   <legend>����������� � ������� [".$galery[0]."]</legend>
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
  echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>��������� � �������</a></center>");
 }


 //---- ���������� ����������� ----------
 elseif($mode=="add_comment")
 {
  $galery=$album->GetDepGal($dep,$gal);
  $comm=array($nick, $url, $icq, $comment_txt);
  $cnt=$album->GetGalComments($dep,$gal);
  $cnt=$album->Add2Levels($cnt,$comm);

  if($album->Save2Levels($album->data_dir.$galery[4],$cnt)) echo("<br>����������� ���������");
  else echo("<br>����������� �� ���������");

  echo("<br><a href=?p=$p&mode=comment_gal&dep=$dep&gal=$gal>���������� �����������</a>");
  echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>��������� � �������</a>");
  echo("<br><a href=?p=$p&mode=view_deps>� ������ ��������</a>");
  echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>� ������ �������</a>");
}



 //- ����������������� ---------------------------------------
 elseif($mode=="admin" && check_auth() && $CURRENT_USER["level"]>=5)
 {

   if(!isset($task)) $task="";

   //---- ���������� ������� ------------------------------------------
   if($task=="new_dep")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>�������� �������</legend>
      <form method=post action=?p=$p&mode=admin&task=new_dep&step=2>
      �������� ������� <input type=text name=dep_name><br>
      �������� ������� <textarea name=dep_desc></textarea><br>
      ���������� �������");

     $album->GenImgSelector($g[3]);

     echo("<input type=submit value=\"������� ������\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     echo("<center>��������� ������.</center>");
     if($album->AddDep($dep_name,$dep_desc,$img_path)) echo("������ ��������");
     else echo("�� ���� �������� ������");
     echo("<br><a href=?p=$p>������ ��������</a>");
    }
    else echo("Error");
   }

   //---- ���������� �������  ------------------------------------------
   elseif($task=="new_gal")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>�������� �������</legend>
      <form method=post action=?p=$p&mode=admin&task=new_gal&dep=$dep&step=2>
      �������� ������� <input type=text name=gal_name><br>
      �������� ������� <textarea name=gal_desc></textarea><br>
      ���������� ������� ");

     $album->GenImgSelector();

     echo("<input type=submit value=\"������� �������\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     echo("<center>��������� �������.</center>");
     if($album->AddGal($dep,$gal_name,$gal_desc,$img_path)) echo("<br>������� ���������");
     else echo("<br>�� ���� �������� �������");
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>��������� � ������</a>");
    }
    else echo("Error");
   }


   //---- ���������� �����������  ------------------------------------------
   elseif($task=="new_img")
   {
    if(!isset($step)) $step=1;
    if($step==1)
    {
     echo("<center><fieldset style='width:300px'>
      <legend>���������� ��������</legend>
      <form method=post action=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal&step=2>
      �������� �������� <textarea name=img_desc></textarea><br>
      �����������");

     $album->GenImgSelector();
    
     echo("<input type=submit value=\"�������� ��������\"></form></fieldset></center>");
     echo("<center><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>��������� � �������</a></center>");
    }
    elseif($step==2)
    {
     echo("��������� �����������.");
     if($album->AddImg($dep,$gal,$img_desc,$img_path)) echo("<br>����������� ���������");
     else echo("<br>�� ���� �������� �����������");

     echo("<br><a href=?p=$p&mode=admin&task=new_img&dep=$dep&gal=$gal>�������� ���...</a>");
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>���������� �������</a>");
    }
    else echo("Error");
   }

   //--- �������� ������� --------------------------------
   elseif($task=="del_dep")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $d=$album->GetDep($dep);
     echo("<center><fieldset style='width:300px'><legend>�������� ������� [".$d[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_dep&dep=$dep&step=2>
           <input type=radio name=flag value=0 checked=true id=v1><label for=v1>�� ������� ������� � ����� �������</label><br>
           <input type=radio name=flag value=1 id=v2><label for=v2>������� �������, �� ������� �����<br></label>
           <input type=radio name=flag value=2 id=v3><label for=v3>������� �������, ��������� ������� �����������<br></label>
           <br><input type=submit value=�������></form>");
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
     if($sum==0) {echo("������ � ������� ���. ������ ����."); $flag=2;}


     if(!isset($flag)) $flag=0;
     echo("<br>flag=[".$flag."]");

     if($flag==1)	$album->DelDep($dep,true,false);	//������� �������.
     elseif($flag==2)	$album->DelDep($dep,true,true);		//������� ������� � �����.
     else		$album->DelDep($dep,false,false);	//������� ������ � �������.

     echo("<br><a href=?p=$p>������ �������</a>");
    }
    else echo("Error.");
   }


   //--- �������� ����� �� ������� ------------------------------
   elseif($task=="del_img")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);
     $im=$album->GetGalImages($dep,$gal);

     echo("<center><fieldset style='width:300px'><legend>�������� ����� [".$im[$img][0]."] �� ������� [".$g[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_img&dep=$dep&gal=$gal&img=$img&step=2>
           <input type=checkbox name=flag_del_file>������� ���������
           <input type=submit value=�������></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $album->DelGalImage($dep,$gal,$img,$flag_del_file);
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>����� � �������</a>");
    }
    else echo("Error");
   }
 
   //--- �������� ������� ------------------------------
   elseif($task=="del_gal")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);

     echo("<center><fieldset style='width:300px'><legend>�������� ������� [".$g[0]."]</legend>");
     echo("<form method=post action=?p=$p&mode=admin&task=del_gal&dep=$dep&gal=$gal&step=2>
           <input type=checkbox name=flag_del_files>������� �����������
           <input type=submit value=�������></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $album->DelGal($dep,$gal,$flag_del_files);
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>��������� � ������</a>");
    }
    else echo("Error");
   }
   

   //--- ����������� ����������� ����� �� ������ -------------------------------------
   elseif($task=="move_img_up"){$album->DragImg($dep,$gal,$img,true); echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>����� � �������</a>");}
   //--- ����������� ������� ����� �� ������ -------------------------------------
   elseif($task=="move_gal_up"){$album->DragGal($dep,$gal,true); echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>� ������ �������</a>");}
   //--- ����������� ������� ����� �� ������ -------------------------------------
   elseif($task=="move_dep_up"){$album->DragDep($dep,true); echo("<br><a href=?p=$p&mode_view_deps>� ������ ��������</a>");}
   //--- ����������� ����������� ���� �� ������ -------------------------------------
   elseif($task=="move_img_down"){$album->DragImg($dep,$gal,$img,false); echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>����� � �������</a>");}
   //--- ����������� ������� ���� �� ������ -------------------------------------
   elseif($task=="move_gal_down"){$album->DragGal($dep,$gal,false); echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>� ������ �������</a>");}
   //--- ����������� ������� ���� �� ������ -------------------------------------
   elseif($task=="move_dep_down"){$album->DragDep($dep,false); echo("<br><a href=?p=$p&mode_view_deps>� ������ ��������</a>");}


   //--- �������������� ������ ����������� -------------------------------------
   elseif($task=="edit_img")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $im=$album->GetGalImages($dep,$gal);

     echo("<center><fieldset style='width:300px'>
     <legend>�������������� ����������� [".$im[$img][0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_img&dep=$dep&gal=$gal&img=$img&step=2>
     �������� �������� <textarea COLS=30 ROWS=10  name=img_desc>".$im[$img][1]."</textarea><br>
     �����������");

     $album->GenImgSelector($im[$img][0]);

     echo("<input type=submit value=\"�������� ������\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateImg($dep,$gal,$img,$img_path,$img_desc)) echo("<br>����������� ���������");
     else echo("<br>����������� �� ���������");
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>��������� � �������</a>");
    }
    else echo("Error");
   }


   //--- �������������� ������ ������� -------------------------------------
   elseif($task=="edit_gal")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);

     echo("<center><fieldset style='width:300px'>
     <legend>�������������� ������� [".$g[0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_gal&dep=$dep&gal=$gal&step=2>
     �������� ������� <input type=text name=gal_name value=\"".$g[0]."\"><br>
     �������� ������� <textarea COLS=30 ROWS=10 name=gal_desc>".$g[2]."</textarea><br>
     �����������");

     $album->GenImgSelector($g[3]);

     echo("<input type=submit value=\"�������� ������\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateGal($dep,$gal,$gal_name,$gal_desc,$img_path)) echo("<br>������� ���������");
     else echo("<br>������� �� ���������");
     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>�������� � ������</a>");
    }
    else echo("Error");
   }


   //--- �������������� ������ ������� -------------------------------------
   elseif($task=="edit_dep")
   {
    if(!isset($step)) $step=1;
    
    if($step==1)
    {
     $d=$album->GetDep($dep);

     echo("<center><fieldset style='width:300px'>
     <legend>�������������� ������� [".$d[0]."]</legend>
     <form method=post action=?p=$p&mode=admin&task=edit_dep&dep=$dep&step=2>
     �������� ������� <input type=text name=dep_name value=\"".$d[0]."\"><br>
     �������� ������� <textarea COLS=30 ROWS=10 name=dep_desc>".$d[2]."</textarea><br>
     �����������");

     $album->GenImgSelector($d[3]);

     echo("<input type=submit value=\"�������� ������\"></form></fieldset></center>");
    }
    elseif($step==2)
    {
     if($album->UpdateDep($dep,$dep_name,$dep_desc,$img_path)) echo("<br>������ �������");
     else echo("<br>������ �� �������");
     echo("<br><a href=?p=$p>������ ��������</a>");
    }
    else echo("Error");
   }

   //--- ����������� ������� � ������ ������ --------------------
   elseif($task=="swap_gal")
   {
    if(!isset($step)) $step=1;
 
    if($step==1)
    {
     $g=$album->GetDepGal($dep,$gal);
     echo("<center><fieldset style='width:300px'>
     <legend>���������� ������� [".$g[0]."]</legend>");

     $d=$album->GetAllDeps();
     echo("<br>������� ������: [".$d[$dep][0]."]");
     echo("<br>����������� � ������:<br><br>");

     echo("<form action=?p=$p&mode=admin&task=swap_gal&step=2&dep=$dep&gal=$gal method=post>");

     $album->GenDepSelector($dep);

     echo("<br><br><input type=submit value='�����������'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $d=$album->GetAllDeps();
     $g=$album->GetDepGal($dep,$gal); //������������ �������
     $g2=$album->GetAllDepGals($dest_dep); //������� ��������� �������

     echo("<br>���������� ������� [".$g[0]."] �� ������� [".$d[$dep][0]."] � ������ [".$d[$dest_dep][0]."]");
     $g2=$album->Add2Levels($g2,$g);

     if($album->Save2Levels($album->data_dir.$d[$dest_dep][1],$g2))
     {
      echo("<br>������� ����������");

      $g1=$album->GetAllDepGals($dep); //������� ��������� �������
      $new_g1=$album->Move2Levels($g1,$gal);

      if($album->Save2Levels($album->data_dir.$d[$dep][1],$new_g1)) echo("<br>������ �������");
      else echo("<br>������ �� �������");
     }
     else echo("<br>������� �� ����������!");


     echo("<br><a href=?p=$p&mode=view_dep&dep=$dep>��������� � ������</a>");
    }
    else echo("Error");
   }
   


   //--- ����������� ����� � ������ ������� --------------------
/*   elseif($task=="swap_img")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $im=$album->GetGalImages($dep,$gal);
     echo("<center><fieldset style='width:300px'>
     <legend>���������� ����� [".$im[$img][0]."]</legend>");
     echo("<br>����������� � �������:");
     echo("<form method=post action=?p=$p&mode=admin&task=swap_img&step=2&dep=$dep&gal=$gal&img=$img>");
     $album->GenGalSelector($dep,$gal);
     echo("<br><br><input type=submit value='�����������'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     //$album->DelGalImage($dep,$gal,$img,false);
     //if($album->AddImg($dest_dep,$dest_gal,$img_desc,$img_path)) echo("<br>����������� ���������");
     echo("Under construction");
    }
    else echo("Error");
   }
*/
   
   
   //--- �������� ������� �� ����� --------------------
   elseif($task=="folder2gal")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     $d=$album->GetDep($dep);

     echo("<center><fieldset style='width:300px'>
     <legend>������� � ������ $d[0] �� �����</legend>");
     echo("<br>��������� �����:");
     echo("<form method=post action=?p=$p&mode=admin&task=folder2gal&step=2&dep=$dep>");
     $album->GenFolderSelector($album->img_dir);
     echo("<br>��������: <input type=text name=gal_name>");
     echo("<br>��������: <textarea name=gal_desc COLS=30 ROWS=10></textarea>");
     echo("<br><br><input type=submit value='������� �������'></form>");
     echo("</fieldset></center>");
    }
    elseif($step==2)
    {
     $d=$album->GetDep($dep);
     $fs=new CFileSystem();

     $res=$fs->GetDirFiles($album->img_dir.$folder_path);
     echo("<br>� ����� [".$album->img_dir.$folder_path."] ������� [".sizeof($res)."] ������");

     echo("<br>�������� �������");

     $g=$album->GetAllDepGals($dep);

     $album->AddGal($dep,$gal_name,$gal_desc,$folder_path."/".$res[0]);

     $new_gal=sizeof($g);
     for($i=0; $i<sizeof($res); $i++)
     {
      echo("<br>���������� ����� [".$folder_path."/".$res[$i]."]");
      $album->AddImg($dep,$new_gal,"",$folder_path."/".$res[$i]);
     }
   

     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$new_gal>���������� �������</a>");
    }
    else echo("Error");
   }



   //--- ������� ����������� -------------------------------------
   elseif($task=="rotate")
   {
    if(!isset($step)) $step=1;

    if($step==1)
    {
     echo("<center><fieldset style='width:300px'><legend>������� �����������</legend>");
     echo("<form action=?p=$p&mode=admin&task=rotate&step=2&dep=$dep&gal=$gal&img=$img method=post>");
     echo("���� ��������: <select name=grad>");
     echo("<option value=0>90�</option>");
     echo("<option value=1>180�</option>");
     echo("<option value=2>270�</option>");
     echo("</select>");
     echo("<center><br><input type=radio name=dir checked=true id=var1 value=0><label for=var1>��������� �����</label></center>");
     echo("<center><input type=radio name=dir id=var2 value=1><label for=var2>��������� ������</label></center><br>");
     echo("</select>");
     echo("<input type=submit value=���������></form>");
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
     echo("������������ ����������� [".$im[$img][0]."]");
     $imaga=imagecreatefromjpeg($album->img_dir.$im[$img][0]);
     $clr=imagecolorallocate($imaga,0,0,0);
     $imaga=imagerotate($imaga,$grad,$clr);
     imagejpeg($imaga,$album->img_dir.$im[$img][0]);
     echo("<br><a href=?p=$p&mode=view_gal&dep=$dep&gal=$gal>��������� � �������</a>");

    }
    else echo("Error");
   }

   //--- ����������� ������ -------------------------------------
   else echo("<center>Undefined administration task!</center>");

 }

 //---- �������� �������� --------
 else echo("�������� ��������");
  
echo("<br><center><font style='font-size:1px; color=white;'>� DSISS, 2002-2006 [dsiss['dog']mail.ru]</font></center>");
     
 }
  else
    include(SK_DIR."/photoalbum.php");

else
//================================================
// ������ ��� ���� � ���� ������� (��������, ��������� �������)
// ����� ���� �������� ���, ��������� ���� � �������
//================================================
  if(!file_exists(SK_DIR."/photoalbum_bar.php"))
    {
     include "config.php"; 
     /*
     ����� ����� �������� ��� ��� ���� ������������� ���
     ����������� ��������� ������� ��� �����-���� ����������
     */         
    }
  else
    include(SK_DIR."/photoalbum_bar.php");
    
}

?>