<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete annoucements                //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Annoucements";
$MDL_DESCR="For annoucements";
$MDL_UNIQUEID="annoucements";
$MDL_MAKER="SMStudio";


if(!$_GETINFO)
{
////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for projects ****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CAnnoucements"))
{
class CAnnoucements
{
 var $m_dir_anns;
 var $m_ch1;
 var $m_ch2;
 var $m_ch3; 

 //-----------------------------------------------------------------------

 function CAnnoucements($dir_anns)
  {
  $this->m_dir_anns=$dir_anns;
  }

  //-----------------------------------------------------------------------
  
 function SetSeparators($ch1,$ch2,$ch3) 
   {
    $this->m_ch1=$ch1;
    $this->m_ch2=$ch2;
    $this->m_ch3=$ch3;   
   }
  
  //-----------------------------------------------------------------------
  
  function ReadDir($dir)
   {
   $files=array();
   $dirct=opendir($dir);
   while($file=readdir($dirct))
	{
	if(($file!=".")&&($file!=".."))
		{
		$files[]=$file;
		}
	}
   return $files;
   }
   
 //-----------------------------------------------------------------------
   
   function GetAnnsCount()
   {
   $task_files=array();
   $task_files=$this->ReadDir($this->m_dir_anns);
   return count($task_files);   
   }

 //-----------------------------------------------------------------------
   
   function GetAnnsList()
   {
   $task_files=array();
   $task_files=$this->ReadDir($this->m_dir_anns);
   return $task_files;   
   }


 //-----------------------------------------------------------------------
   
   function GetAnnData($id)
   {
   $file=file($this->m_dir_anns."/".$id);
   $file=implode("",$file);
   $vars=explode($this->m_ch1,$file);
   $anns['id']=     $id;
   $anns['login']=  $vars[0];
   $anns['title']=  $vars[1];
   $anns['descr']=  $vars[2];
   $anns['date']=   $vars[3];
   $anns['attached']= explode($this->m_ch2,$vars[4]);
   return $anns;
   }

 //-----------------------------------------------------------------------

   function GetAnns()
   {
   $anns_files=array();
   $anns_files=$this->ReadDir($this->m_dir_anns);
   $anns=array();
   $k=0;
   $cnt=count($anns_files);
   for($i=$cnt-1;$i>=0;$i--)
     {
       $id=$anns_files[$i];
       $file=file($this->m_dir_anns."/".$id);
       $file=implode("",$file);
       $vars=explode($this->m_ch1,$file);
       $anns[$k]['id']=     $id;
       $anns[$k]['login']=  $vars[0];
       $anns[$k]['title']=  $vars[1];
       $anns[$k]['descr']=  $vars[2];
       $anns[$k]['date']=   $vars[3];
       $anns[$k]['attached']= explode($this->m_ch2,$vars[4]);

       $k++;
     } 
   return $anns;
   }   

 //-----------------------------------------------------------------------

 function SaveAnns($anns,$ann_cnt)
  {
   for($i=0;$i<$ann_cnt;$i++)
     {  
     $fp=fopen("$this->m_dir_anns/".$anns[$i]['id'],"w+");
      $string=$tasks[$i]['login'].$this->m_ch1.
               $anns[$i]['title'].$this->m_ch1.
               $anns[$i]['descr'].$this->m_ch1.
               $anns[$i]['date'].$this->m_ch1;
      fwrite($fp,$string);     
      fclose($fp);
     }
  }   
  
 //-----------------------------------------------------------------------

 function DeleteAnn($id)
  {
  if(!file_exists($this->m_dir_anns."/".$id))return 0;     
  unlink("$this->m_dir_anns/".$id);
  } 
  
//-----------------------------------------------------------------------

 function AddAnn($ann)
  {
     $fp=fopen("$this->m_dir_anns/".time(),"w+");
      $string=$ann['login'].$this->m_ch1.
               $ann['title'].$this->m_ch1.               
               $ann['descr'].$this->m_ch1.
               $ann['date'].$this->m_ch1;
      for($i=0;$i<count($ann['attached']);++$i)
         {
         $string.=$ann['attached'][$i];
         if($i<count($ann['attached'])-1)
         $string.=$this->m_ch2;
         }                   
       $string.=$this->m_ch1;     
      fwrite($fp,$string);
      fclose($fp);
  } };
}
/////////////////////////////////////////////////////////////////////////

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////
  if($_INSTALL)
  {
  //установка модуля
  }
  elseif($_UNINSTALL)
  {
  //удаление модуля
  }                 
  elseif($_CONFIGURE)
  {
  //настройка модуля
  } 
  elseif($_MENU)
  {
  //меню администратора
  }  
 elseif($_ADMIN)
  {
   if(!file_exists(SK_DIR."/annoucements_admin.php")) 
     {
     include "config.php";
    
     global $PDIV;
     global $DIRS,$p;

     $ANN = new CAnnoucements($DIRS["anns_list"]);
     $ANN->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);     
     
      if(isset($a))
       {
       if($a=="delete")
         {
         ?>
           <center><big><b>Удаление объявлений</b></big></center><br><br>
         <?         
         if(isset($do) && $do=="true")
           for($i=0;$i<count($ids);++$i)
             {      
             $data=$ANN->GetAnnData($ids[$i]);
             if(is_user_exists($data['login']))$ud=get_user_data($data['login']);
             else {$ud["level"]=0;}
             if($ud["level"]<$CURRENT_USER["level"] || $ud["id"]==$CURRENT_USER["id"])
               $ANN->DeleteAnn($ids[$i]);
             }

         if(!isset($page) || $page<1)$page=1;
         $list=$ANN->GetAnnsList();
         $pgcnt=$PDIV->GetPagesCount($list);
         $alist=$PDIV->GetPage($list,$page);
         $navig="";
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=($i+1))$navig.="<a href=\"?p=$p&act=$act&a=$a&page=".($i+1)."\">".($i+1)."</a>";
           else $navig.=($i+1);
           if($i<$pgcnt-1)$navig.=", ";
           }
         OUT("Страница: ".$navig);
         ?><br>
         <form action="<? OUT("?p=$p&act=$act&a=$a") ?>&do=true" method=post>
         <?
          for($i=0;$i<count($alist);++$i)
           {
           $data=$ANN->GetAnnData($alist[$i]);
           if(is_user_exists($data['login']))$ud=get_user_data($data['login']);
           else {$ud["level"]=0;$ud["nick"]=$data["login"];}
           if($ud["level"]<$CURRENT_USER["level"] || $ud["id"]==$CURRENT_USER["id"])
             {
             ?>
             <table width=100%>
             <td class=tbl1 width=100%>
             <table class=tbl2 width=100%><td class=tbl1 width=100%>
             <? if(is_user_exists($data['login'])){ ?>
             <a href="?p=users&act=userinfo&id=<? OUT($ud["id"]) ?>"><? OUT($ud["nick"]) ?></a>, <? OUT(norm_date($data['date'])) ?>,
             <? }else{ ?>
             <? OUT($data['login']) ?>, <? OUT(norm_date($data['date'])) ?>,
             <? } ?>
             <b>
             "<? OUT($data['title']) ?>"
             </b>
             </td><tr><td class=tbl>
             <? OUT($data['descr']) ?>
             </td>
             </table>
             </td>
             <tr><td> 
             <input name=ids[] type=checkbox value="<? OUT($data['id']) ?>">выбрать
             </td></tr>
             </table> <p> 
             <?
             }          
           }        
         
         OUT("Страница: ".$navig);
         ?>        
         <div align=center><input type=submit value="Удалить!"></div>
         </form> <br>
         <a href="<? OUT("?p=$p&act=$act") ?>">Назад</a>
         
         <?
          
         }
       elseif($a=="add")
         {
         ?>
           <center><big><b>Добавление объявлений</b></big></center><br><br>
         <?                  
         $form=true;
         if(isset($sure) && $sure=="true")
           {
           global $FLTR;
           $title=$FLTR->DirectProcessString($inp_ann[0],1);
           $text=$FLTR->DirectProcessText($inp_ann[1],1,1);
           $error="";
           if(strlen($title)<2)$error.="<br><b>Ошибка:</b> Заголовок объявления не может быть короче 2х символов!<br>";           
           if(strlen($text)<5)$error.="<br><b>Ошибка:</b> Текст объявления не может быть короче 5 символов!<br>";
           if(!$error)
             {$ann=array();
             $ann["login"]=$CURRENT_USER["id"];
             $ann["date"]=time();
             $ann["title"]=$title;
             $ann["descr"]=$text;                                       
             $ANN->AddAnn($ann);
             $form=false;
             echo("<br>Добавлено!");}
           else echo($error);
           }
           else
           {
           if(!isset($title))$title="";
           if(!isset($text))$text="";           
           }
         
         if($form)
           {
           ?>             
           <form action="<? OUT("?p=$p&act=$act&a=$a&sure=true") ?>" method=post>
           <table align=center>
           <td> 
           Заголовок объявления
           </td><td>
           <input class=input name=inp_ann[] style="width:100%;" value="<? OUT($title) ?>"></td><tr>
           <td width=30%> 
           Текст объявления
           </td><td>
           <textarea class=input name=inp_ann[] rows=8 style="width:100%;"><? OUT($text) ?></textarea></td>         
           </table>
           <table align=center><td><input type=submit class=button value="Добавить" ></td></table>
           </form>
           <?
           }
           ?>
           <br>
           <a href="<? OUT("?p=$p&act=$act") ?>">Назад</a>  
           <?
         
         }
       }
       else
       {
       ?>
       <center><big><b>Объявления. Страница администратора</b></big></center><br><br>       
       <div align=center>
       <a href="<? OUT("?p=$p&act=$act&a=delete") ?>">Удаление объявлений</a><br>
       <a href="<? OUT("?p=$p&act=$act&a=add") ?>">Добавление объявлений</a><br>
       <br><br>
       <a href="<? OUT("?p=$p") ?>">Назад</a></div>
       <?
       }
     }
    else
      include(SK_DIR."/annoucements_admin.php");
  }
elseif($_MODULE)        
if($_NOTBAR)
  if(!file_exists(SK_DIR."/annoucements.php"))
    {
    global $MDL;
     if(isset($act) && $act=="admin"){$MDL->LoadAdminPage($p);return;}    
     include "config.php";
    
    global $PDIV;
    global $DIRS,$p;

    $ANN = new CAnnoucements($DIRS["anns_list"]);
    $ANN->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);
    $alist  = $ANN->GetAnnsList();
    
    global $DIRS,$p;
    if(!isset($page) || $page<1)$page=1;

    ?>
    <center><big><b>Объявления</b></big></center>
    <?php
    $ancnt=$ANN->GetAnnsCount();

    $pgcnt=$PDIV->GetPagesCount($alist);
    $navig="";
    for($k=0;$k<$pgcnt;$k++)
     {
     if($k+1!=$page) 
      $navig.="<a href=\"?p=$p&page=".($k+1)."\">";
      $navig.=($k+1);
     if($k+1!=$page) 
      $navig.="</a>";
     if($k<$pgcnt-1)$navig.=", ";      
     }  
     ?>   
     Всего объявлений: <? OUT($ancnt) ?>, Страниц: <? OUT($pgcnt) ?>,<br>
     Страница: <? OUT($navig) ?>

     <?php
     $list=$PDIV->GetPage($alist,$page);
     for($i=0;$i<count($list);++$i)
     { 
     $data=$ANN->GetAnnData($list[$i]);
     ?>
     <table width=100%>
     <td class=tbl1 width=100%>
     <table class=tbl2 width=100%><td class=tbl1 width=100%>
     <? if(is_user_exists($data['login'])){ ?>
     <a href="?p=users&act=userinfo&id=<? $ud=get_user_data($data['login']); OUT($ud["id"]) ?>"><? OUT($ud["nick"]) ?></a>, <? OUT(norm_date($data['date'])) ?>,
     <? }else{ ?>
     <? OUT($data['login']) ?>, <? OUT(norm_date($data['date'])) ?>,
     <? } ?><br>
     <b>
     "<? OUT($data['title']) ?>"
     </b>
     </td><tr><td class=tbl>
     <? OUT($data['descr']) ?>
     </td>
     </table>
     </td>
     </table> <p> 
     <?php
     }
     ?>
     Всего объявлений: <? OUT($ancnt) ?>, Страниц: <? OUT($pgcnt) ?>,<br>
     Страница: <? OUT($navig) ?>  <br><br>
     <? 
     if($CURRENT_USER["level"]>=5)
      {
      ?>
      <div align=center>
      <a href="?p=<? OUT($p) ?>&act=admin">Администрирование</a>
      </div>      
      <?
      }
    }
  else
    include(SK_DIR."/annoucements.php");
else
  if(!file_exists(SK_DIR."/annoucementsbar.php"))
    {  
    $ANN = new CAnnoucements($DIRS["anns_list"]);
    $ANN->SetSeparators($GV["sep1"],$GV["sep2"],$GV["sep3"]);
    $alist  = $ANN->GetAnnsList();
      if(!count($alist))echo("<div align=center>нет объявлений</div>");
	for($i=count($alist)-1;$i>=0 && $i>=count($alist)-3;--$i){
   	         $data=$ANN->GetAnnData($alist[$i]);
                ?>
                <a style="font-size:9px" href="?p=users&act=userinfo&id=<? $ud=get_user_data($data['login']); OUT($ud["id"]) ?>"><? OUT($ud["nick"]) ?></a>:<? OUT($data['title']) ?><br>                
                <font color=gray style="font-size:8.5px">/<? OUT(norm_date($data['date'])) ?></font><br>
                <? }
                ?>
                <b><div align=center><a style="font-size:8.5px" href=?p=annoucements>подробнее</a></div>    
              <?    
    }
  else
    include(SK_DIR."/annoucementsbar.php");
}
?>



