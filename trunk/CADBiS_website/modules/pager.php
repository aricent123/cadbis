<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for get pager                                    //
//    MAKER: SMStudio                                                   //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Pager";
$MDL_DESCR="For pager";
$MDL_UNIQUEID="pager";
$MDL_MAKER="SMStudio";



if(!$_GETINFO)
{

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//*********************** class for menu ******************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CPager"))
{class CPager
{
var $list_file;
var $data_dir;
  
  function CPager($data_dir, $list_file)
    {
    $this->data_dir=$data_dir;
    $this->list_file=$list_file;
    }

  function GetPagesList()
    {               
    include $this->list_file;
    return $PGR;
    }

  //-----------------------------------------------------------------------
    
  function AddPage($title,$text)
    {
    include $this->list_file;
    $new_id=time();
    $fp=fopen($this->data_dir."/".$new_id,"w+");
    if(!$fp)return false;    
    fwrite($fp,$text);
    fclose($fp);
    $fp=fopen($this->list_file,"w+");
    $txt="<?php\r\n";
    for($i=0;$i<count($PGR);++$i)
      $txt.="\$PGR[$i][\"title\"]=\"".$PGR[$i]["title"]."\";\r\n"."\$PGR[$i][\"id\"]=\"".$PGR[$i]["id"]."\";\r\n";
    $i=count($PGR);
    $txt.="\$PGR[$i][\"title\"]=\"".$title."\";\r\n"."\$PGR[$i][\"id\"]=\"".$new_id."\";\r\n";
    $txt.="?>";
    fwrite($fp,$txt);
    fclose($fp);
    } 

  //-----------------------------------------------------------------------    
  
   function PageExists($id)
    {
    return (file_exists($this->data_dir."/".$id));
    }
    
  //-----------------------------------------------------------------------    
  
   function GetPageData($id)
    {
    include $this->list_file;
    $k=0;    
    //finding i of id:
    for($i=0;$i<count($PGR);++$i)if($PGR[$i]["id"]==$id){$k=$i;break;}
    if($k<0)return NULL; //page with id doesn't exist;
    return $PGR[$k];
    }
    
  //-----------------------------------------------------------------------    
  
   function SetPageData($id,$data)
    {
    include $this->list_file;
    $k=0;    
    //finding i of id:
    for($i=0;$i<count($PGR);++$i)if($PGR[$i]["id"]==$id){$k=$i;break;}
    if($k<0)return NULL; //page with id doesn't exist;
    $PGR[$k]["title"]=$data["title"];
    $this->SavePages($PGR);    
    }

  //-----------------------------------------------------------------------        
    
   function SavePages($PGR)
    {
    $txt="<?php\r\n";
    for($i=0;$i<count($PGR);++$i)
      $txt.="\$PGR[$i][\"title\"]=\"".$PGR[$i]["title"]."\";\r\n"."\$PGR[$i][\"id\"]=\"".$PGR[$i]["id"]."\";\r\n";
    $txt.="?>";
    $fp=fopen($this->list_file,"w+");
    fwrite($fp,$txt);
    fclose($fp);    
    }    

  //-----------------------------------------------------------------------        
    
   function CopyPage($id)
    {
    $pdata=$this->GetPageData($id);
    include $this->list_file;
    $new_id=time();
    $fp=fopen($this->data_dir."/".$new_id,"w+");
    if(!$fp)return false;    
    fwrite($fp,$text);
    fclose($fp);
    $fp=fopen($this->list_file,"w+");
    $txt="<?php\r\n";
    for($i=0;$i<count($PGR);++$i)
      $txt.="\$PGR[$i][\"title\"]=\"".$PGR[$i]["title"]."\";\r\n"."\$PGR[$i][\"id\"]=\"".$PGR[$i]["id"]."\";\r\n";
    $i=count($PGR);
    $title="����� '".$pdata["title"]."'";
    $txt.="\$PGR[$i][\"title\"]=\"".$title."\";\r\n"."\$PGR[$i][\"id\"]=\"".$new_id."\";\r\n";
    $txt.="?>";
    fwrite($fp,$txt);
    fclose($fp);
    copy($this->data_dir."/".$id,$this->data_dir."/".$new_id);
    }       
  //-----------------------------------------------------------------------    
    
   function AddPageToMenu($id)
    {
    global $DIRS,$PGR;
    include($DIRS["menu_list"]);
    include($this->list_file);
    
    //begin generate record text 
    $txt="<?php\r\n";
    //save exists menu items
    for($i=0;$i<count($MENU);++$i)
      $txt.="\$MENU[$i][\"link\"]=\"".$MENU[$i]["link"]."\";\r\n".
      "\$MENU[$i][\"title\"]=\"".$MENU[$i]["title"]."\";\r\n".
      "\$MENU[$i][\"ulevel\"]=\"".$MENU[$i]["ulevel"]."\";\r\n".
      "\$MENU[$i][\"group\"]=\"".$MENU[$i]["group"]."\";\r\n".
      "\$MENU[$i][\"parent\"]=\"".$MENU[$i]["parent"]."\";\r\n";         
     
    $k=-1;
    
    //finding i of id:
    for($i=0;$i<count($PGR);++$i)if($PGR[$i]["id"]==$id){$k=$i;break;}
    if($k<0)return NULL; //page with id doesn't exist;

    //add new item  
    $i=count($MENU);    
    $txt.="\$MENU[$i][\"link\"]=\"?p=pager&id=$id\";\r\n".
    "\$MENU[$i][\"title\"]=\"".$PGR[$k]["title"]."\";\r\n".
    "\$MENU[$i][\"ulevel\"]=\"0\";\r\n".
    "\$MENU[$i][\"group\"]=\"0\";\r\n".
    "\$MENU[$i][\"parent\"]=\"0\";\r\n";
    $txt.="?>";     

    //ok, opening file
    $fp=fopen($DIRS["menu_list"],"w+");    
    fwrite($fp,$txt);
    fclose($fp);         
    }
 
   //-----------------------------------------------------------------------
    
    function DeletePage($id)
    {
    global $DIRS;
    include $this->list_file;
    $fp=fopen($this->list_file,"w+");
    $txt="<?php\r\n";
    $k=0;
    for($i=0;$i<count($PGR);++$i)
      {
      if($PGR[$i]["id"]!=$id)
        {$txt.="\$PGR[$k][\"title\"]=\"".$PGR[$i]["title"]."\";\r\n"."\$PGR[$k][\"id\"]=\"".$PGR[$i]["id"]."\";\r\n";
        $k++;}
      }
    unlink($this->data_dir."/".$id);
    fwrite($fp,$txt);
    fclose($fp);    
    }
    
  //-----------------------------------------------------------------------    
    
    function SetPageText($id,$text)
    {
    include $this->list_file;
    $fp=fopen($this->data_dir."/".$id,"w+");
    fwrite($fp,$text);
    fclose($fp);
    }
};}
/////////////////////////////////////////////////////////////////////////


/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////


global $id;

  if($_INSTALL)
  {
  //��������� ������
  }
  elseif($_UNINSTALL)
  {
  //�������� ������
  }                 
  elseif($_CONFIGURE)
  {
  //��������� ������
  } 
  elseif($_MENU)
  {
  //���� ��������������
  }  
  elseif($_ADMIN && _isroot())
  {
  if(!file_exists(SK_DIR."/pager_admin.php"))
    {
    // skin doesn't support pager, let's draw all by ourself
    // {
    
    //we'll need for our own extract $GLOBALS
    include "config.php";
    
    ?>
    <script language="javascript">
    function ConfirmUnsaved()
    {
    return confirm('�� ������� ��� ������ ����������� ��������? ������������ ������ ����� ��������!');
    }
    </script>
    <?
    
    if(!isset($a))$a="";
    $PGR= new CPager($DIRS["pager_data"],$DIRS["pager_list"]);
    $list=$PGR->GetPagesList();
    if(!isset($editor))$editor="html";    
    if(isset($furl))$furl=str_replace(">","&",$furl);
    switch($a)
     {
     case "add":
        if(isset($mod) && $mod=="add")
         {
            if(isset($editor) && $editor=="html")
            {
            $text=$FLTR->DirectProcessHTML($text);            
            }
            else
            {     
            $text=$FLTR->DirectProcessText($text,$nb,$kt);
            }   
         $PGR->AddPage($title,$text);
         OUT("����� ��������");          
         }
         else
         {
         ?>
         <form action="<? OUT("?p=$p&act=$act&id=$id&editor=$editor&a=$a") ?>&mod=add" method=post id="EditForm" enctype="multipart/form-data">
         ���������:<br>
         <input type=text name=title style="width:100%" value="��� ��������"><br><br>
          <?               
            if(isset($editor) && $editor=="html")
             {           
			$oFCKeditor = new FCKeditor('text') ;
			$oFCKeditor->BasePath	= "js/fckeditor/" ;
			$oFCKeditor->Value		= "����� ��������";
			$oFCKeditor->Height = 500;
			$oFCKeditor->Create() ;		             	
            ?>
            
            <br>
            ��������: | <b>HTML</b> | <a href="<? OUT("?p=$p&a=$a&pgrec=$pgrec&editor=text&act=$act&type=$type&id=$id") ?>" onclick="return ConfirmUnsaved()">�������</a>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
            <div align=center><input type="submit" class="button" value="���������"></div></form>
            <?                          
             }
             else
             {
            ?>                     
         �����:<br>
         <textarea name=text style="width:100%" rows=30><div align=center><b>��� ��������</b></div><? OUT("\r\n") ?></textarea><br>
            ��������: | <a href="<? OUT("?p=$p&act=$act&a=$a&pgrec=$pgrec&editor=html&type=$type&id=$id") ?>" onclick="return ConfirmUnsaved()">HTML</a> | <b>�������</b>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div>          
         <input type=checkbox name=nb unchecked>���������� ������� �� ����� ������ � &lt;br&gt;? <br>  
         <input type=checkbox name=kt unchecked>��������� HTML-���� ?        
         <div align=center><input type=submit value="���������" class=button></div>  
         </form>
         <?
             }
         }   
     
       break;
     case "delete": 
        if(isset($mod) && $mod=="del")
         {
         $PGR->DeletePage($pgrec);
         OUT("����� �������");          
         }
         else
         {
         ?>
         <div align=center>
         <br><a href="<? OUT("?p=$p&act=$act&a=$a&id=$id&pgrec=$pgrec") ?>&mod=del">� ������, ��� ���� ������� "<? OUT($pgrec) ?>"</a><br><br>
         </div>
         <?
         }
              
       break;
     case "edit":
        if(isset($mod) && $mod=="save")
         {
        $title=$FLTR->DirectProcessString($title);         
            if(isset($editor) && $editor=="html")
            {         
            $text=$FLTR->DirectProcessHTML($text);         
            $PGR->SetPageText($pgrec,$text);
            $data=array();
            $data["title"]=$title;
            }
            else
            {
            $text=$FLTR->DirectProcessText($text,$nb,$kt);         
            $PGR->SetPageText($pgrec,$text);
            $data=array();
            $data["title"]=$title;
            }
         $PGR->SetPageData($pgrec,$data);
         OUT("����� ���������");          
         }
         else
         {
         $data=$PGR->GetPageData($pgrec);
         ?>
         
         <form action="<? OUT("?p=$p&act=$act&id=$id&editor=$editor&a=$a&pgrec=$pgrec") ?>&mod=save" method=post id="EditForm" enctype="multipart/form-data">
         ���������:<br>
         <input type=text name=title style="width:100%" value="<? OUT($data["title"]) ?>"><br><br>
         <?               
            if(isset($editor) && $editor=="html")
             {             
            ?>
            <table width=100% height="500px"><tr><td>          
            <?
            $text=str_replace("\r\n","",get_file($DIRS["pager_data"]."/".$pgrec));
            $text=str_replace("\r","",$text);
            $text=str_replace("\n","",$text);
            
			$oFCKeditor = new FCKeditor('text') ;
			$oFCKeditor->BasePath	= "js/fckeditor/" ;
			$oFCKeditor->Value		= $text;
			$oFCKeditor->Height = 500;
			$oFCKeditor->Create() ;			                       
            ?>
            </td></tr></table>          
            <br>
            ��������: | <b>HTML</b> | <a href="<? OUT("?p=$p&a=$a&pgrec=$pgrec&act=$act&type=$type&id=$id&editor=txt") ?>" onclick="return ConfirmUnsaved()">�������</a>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
            <div align=center><input type="submit" class="button" value="���������"></div>           </FORM>  
            <?                          
             }
             else
             {
            ?>            
            �����:<br>
            <textarea class=inputbox name=text style="width:100%" rows=30><? OUT(get_file($DIRS["pager_data"]."/".$pgrec)); ?></textarea><br>
            ��������: | <a href="<? OUT("?p=$p&act=$act&a=$a&pgrec=$pgrec&editor=html&type=$type&id=$id") ?>" onclick="return ConfirmUnsaved()">HTML</a> | <b>�������</b>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
            <input class=inputbox type=checkbox name=nb unchecked>���������� ������� �� ����� ������ � &lt;br&gt;? <br>  
            <input class=inputbox type=checkbox name=kt unchecked>��������� HTML-���� ?         
            <div align=center><input type=submit value="���������" class=button></div>  
            </form> 
            <?
             }
         }
     
       break;       
     case "tomenu":
        if(isset($mod) && $mod=="save")
         {    
         $PGR->AddPageToMenu($pgrec);
         OUT("����� ��������");          
         }
         else
         {
         $data=$PGR->GetPageData($pgrec);
         ?>
         <div align=center>
         <br><a href="<? OUT("?p=$p&act=$act&a=$a&id=$id&pgrec=$pgrec") ?>&mod=save">� ������, ��� ���� �������� � ���� "<? OUT($pgrec) ?>"</a><br><br>
         </div>
         <?         
         }     

       break;        
     case "copy":
         OUT("����� �����������");          
        $PGR->CopyPage($pgrec);
       break;         
     case "save": 
       for($i=0;$i<count($tits);++$i)
        {
        $tits[$i]=$FLTR->DirectProcessString($tits[$i]);
        $list[$i]["title"]=$tits[$i];        
        }
        $PGR->SavePages($list);
       break;       
     default:
     //$furl=str_replace("&",">",getfullurl());
     ?>
     <form action="<? OUT("?p=$p&act=$act&id=$id") ?>&a=save" method=post>
      <table width=100%  class=tbl1>
      <tr><td>��������</td><td>���������</td><td>��������</td>
     <?
       for($i=0;$i<count($list);++$i)
         {
         ?>
         <tr>
           <td width=30% class=tbl1 align=center><b><? OUT($list[$i]["id"]) ?></b><br>
           (?p=pager&id=<? OUT($list[$i]["id"]) ?>)</td>
           <td width=50% class=tbl1><input class=inputbox  type=text style="width:100%" name=tits[] value="<? OUT($list[$i]["title"]) ?>"></td>
           <td width=20% class=tbl1 align=center>  
             <a href="<? OUT("?p=$p&act=$act&id=$id") ?>&a=delete&pgrec=<? OUT($list[$i]["id"]) ?>">�������</a><br>
             <a href="<? OUT("?p=$p&act=$act&id=$id") ?>&a=edit&pgrec=<? OUT($list[$i]["id"]) ?>">��������</a><br>
             <a href="<? OUT("?p=$p&act=$act&id=$id") ?>&a=tomenu&pgrec=<? OUT($list[$i]["id"]) ?>">� ����</a><br>
             <a href="<? OUT("?p=$p&act=$act&id=$id") ?>&a=copy&pgrec=<? OUT($list[$i]["id"]) ?>">������� �����</a><br>
             <a href="?p=pager&id=<? OUT($list[$i]["id"]) ?>">��������</a>
            </td>
         </tr><tr><td colspan=3></td></tr>
         <?
         }
     ?>
     </table>
     <div align=center><input class=button type=submit value="���������"></div></form><br>
     <div align=center>
       <a href="<? OUT("?p=$p&act=$act&id=$id&furl=$furl") ?>&a=add">�������� ��������</a>
     </div>       
     <?
     $furl=getfullurl();
     };
      ?>
      
     <div align=center> <a href="<? OUT("?p=$p&act=$act&id=$id") ?>">�����</a></div>
      <?
    
    
    // }
    }
  else
    include(SK_DIR."/pager_admin.php");  
  }
  else
  {
if($_MODULE)
  if($id && file_exists($DIRS["pager_data"]."/".$id))
  {
  	OUT(get_file($DIRS["pager_data"]."/".$id));
    if(check_auth() && _isroot())
    {
    ?>
    <div align=center>
    <a href="?p=user_page&act=root&id=pager&a=edit&pgrec=<? OUT($id) ?>">�������������</a>
    </div>
    <?
    }   
  }
  else {global $page; $page="404";$this->LoadModule("error",false);}
  }
}