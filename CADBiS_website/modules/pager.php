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
    
   function AddPageToMenu($id)
    {
    global $DIRS,$PGR;
    include($DIRS["menu_list"]);
    include($this->list_file);
    
    //begin generate record text 
    $txt="<?php\r\n";
    //save exists menu items
    for($i=0;$i<count($MENU);++$i)
      $txt.="\$MENU[$i][\"link\"]=\"".$MENU[$i]["link"]."\";\r\n"."\$MENU[$i][\"title\"]=\"".$MENU[$i]["title"]."\";\r\n";
     
    $k=-1;
    
    //finding i of id:
    for($i=0;$i<count($PGR);++$i)if($PGR[$i]["id"]==$id){$k=$i;break;}
    if($k<0)return NULL; //page with id doesn't exist;

    //add new item  
    $i=count($MENU);    
    $txt.="\$MENU[$i][\"link\"]=\"?p=pager&id=$id\";\r\n"."\$MENU[$i][\"title\"]=\"".$PGR[$k]["title"]."\";\r\n";
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
    
    if(!isset($a))$a="";
    $PGR= new CPager($DIRS["pager_data"],$DIRS["pager_list"]);
    $list=$PGR->GetPagesList();
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
            ?>
            <table width=100%><tr><td>
            <input type="hidden" name="text">
            </FORM>
            </TD></TR>
            <TR><TD height="1" bgcolor="#dddddd"></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd"><div id="tools"></div></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd">
            �����
            <div id="fonts">        
            <select id="fface" onchange="SetFace()">
            <option value="Arial">Arial
            <option value="Courier New">Courier New
            <option value="Tahoma">Tahoma
            <option value="Times New Roman">Times New Roman
            <option value="Verdana" selected>Verdana
            </select>
            ������
            <select id="fsize" style="width:40" onchange="SetSize()">
            <option value="1">1
            <option value="2" selected>2
            <option value="3">3
            <option value="4">4
            <option value="5">5
            <option value="6">6
            <option value="7">7
            </select>
            </div>
            </td></tr><tr><td>
            <IFRAME id="EditFrame" width="100%" height=400px frameborder="0" style="border-width:1px; border-color:#000000; border-style: solid;" contenteditable="true"></IFRAME>         
            <script>
               var Content; 
                Content="<div align=center><b>��� ��������</b></div><br>";
            </script>
            <SCRIPT src="js/editor.js"></SCRIPT>
            </td></tr></table>          
            <br>
            ��������: | <b>HTML</b> | <a href="<? OUT("?p=$p&a=$a&pgrec=$pgrec&act=$act&type=$type&id=$id") ?>">�������</a>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
            <div align=center><input type="button" class="button" value="���������" onclick="Save()"></div>
            <?                          
             }
             else
             {
            ?>                     
         �����:<br>
         <textarea name=text style="width:100%" rows=30><div align=center><b>��� ��������</b></div><? OUT("\r\n") ?></textarea><br>
            ��������: | <a href="<? OUT("?p=$p&act=$act&a=$a&pgrec=$pgrec&editor=html&type=$type&id=$id") ?>">HTML</a> | <b>�������</b>
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
            <table width=100%><tr><td>
            <input type="hidden" name="text">
            </FORM>
            </TD></TR>
            <TR><TD height="1" bgcolor="#dddddd"></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd"><div id="tools"></div></TD></TR>
            <TR><TD height="25" bgcolor="#dddddd">
            �����
            <div id="fonts">        
            <select id="fface" onchange="SetFace()">
            <option value="Arial">Arial
            <option value="Courier New">Courier New
            <option value="Tahoma">Tahoma
            <option value="Times New Roman">Times New Roman
            <option value="Verdana" selected>Verdana
            </select>
            ������
            <select id="fsize" style="width:40" onchange="SetSize()">
            <option value="1">1
            <option value="2" selected>2
            <option value="3">3
            <option value="4">4
            <option value="5">5
            <option value="6">6
            <option value="7">7
            </select>
            </div>
            </td></tr><tr><td>
            <IFRAME id="EditFrame" width="100%" height=400px frameborder="0" style="border-width:1px; border-color:#000000; border-style: solid;" contenteditable="true"></IFRAME>         
            <script>
               var Content; 
                Content="<? OUT(str_replace("\r\n","",addslashes(get_file($DIRS["pager_data"]."/".$pgrec)))) ?>";
            </script>
            <SCRIPT src="js/editor.js"></SCRIPT>
            </td></tr></table>          
            <br>
            ��������: | <b>HTML</b> | <a href="<? OUT("?p=$p&a=$a&pgrec=$pgrec&act=$act&type=$type&id=$id") ?>">�������</a>
            <br>(<small><b>��������!</b> ��� ������� �� ��� ������ �������� ��� ������������ ������!</small>)</div> 
            <div align=center><input type="button" class="button" value="���������" onclick="Save()"></div>
            <?                          
             }
             else
             {
            ?>            
            �����:<br>
            <textarea class=inputbox name=text style="width:100%" rows=30><? include($DIRS["pager_data"]."/".$pgrec); ?></textarea><br>
            ��������: | <a href="<? OUT("?p=$p&act=$act&a=$a&pgrec=$pgrec&editor=html&type=$type&id=$id") ?>">HTML</a> | <b>�������</b>
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
     case "save": 
       for($i=0;$i<count($tits);++$i)
        {
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
    include $DIRS["pager_data"]."/".$id;
  else {global $page; $page="404";$this->LoadModule("error",false);}
  }
}