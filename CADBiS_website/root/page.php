<?php

include "config.php";
global $MDL,$GV,$DIRS;


if(!isset($a))$a="";
  
switch($id)
 {
 case "modules":

  if($a=="uninstall")
   {
    $MDL->UninstallModule($modl);
    //setpage("?act=root&id=modules");
   }
  elseif($a=="install")
   {
    $MDL->InstallModule($modl);;    
   }
  elseif($a=="configure")
   {
    $MDL->ConfigureModule($modl);;    
   }   
   else
   {
   //показываем форму изменения переменных
   ?>
    <div align=center><b>Управление модулями</b></div><br><br>
    <div align=center>Установленные модули:</div>
    <table width=100% border=0px style="border-width:1px;border-style:solid;border-color:black;">
    <td>Модуль:</td><td>Название:</td><td>Описание:</td><td>Создатель:</td><td>Управление:</td>
   <?php 
     $mlist=$MDL->GetInstalledModulesList();
     for($i=0;$i<count($mlist);++$i)
       {
       $info=$MDL->GetModuleInfo($mlist[$i])
        ?>                               
         <tr>
          <td><a href="?p=<? OUT($mlist[$i]) ?>"><? OUT($info["id"]) ?></a></td>
          <td><? OUT($info["title"]) ?></td>
          <td><? OUT($info["descr"]) ?></td>
          <td><? OUT($info["maker"]) ?></td>
          <td class=tbl1 align=center>
            <?
            if(!$MDL->IsModuleInstalled($mlist[$i])){ ?>
            <a href="?act=root&id=modules&a=install&modl=<? OUT($mlist[$i]) ?>">установить</a><br>
            <? }
            else{ ?>             
            <a href="?act=root&id=modules&a=uninstall&modl=<? OUT($mlist[$i]) ?>">удалить</a><br>
            <a href="?act=root&id=modules&a=configure&modl=<? OUT($mlist[$i]) ?>">настроить</a><br>
            <br><font style="font-size:9px">
            <?
            $MDL->LoadMenu($mlist[$i]);
            ?>         
            </font>
            </td>
            <? } ?>                           
         </tr>        
        <?
       }
    ?>
    </table>
    <div align=center>Неустановленные модули:</div>
    <table width=100% border=0px style="border-width:1px;border-style:solid;border-color:black;">
    <td>Модуль:</td><td>Название:</td><td>Описание:</td><td>Создатель:</td><td>Управление:</td>
    <?
     $mlist=$MDL->GetNotInstalledModulesList(); 
    for($i=0;$i<count($mlist);++$i)
       {
       $info=$MDL->GetModuleInfo($mlist[$i])
        ?>                               
         <tr>
          <td><? OUT($info["id"]) ?></td>
          <td><? OUT($info["title"]) ?></td>
          <td><? OUT($info["descr"]) ?></td>
          <td><? OUT($info["maker"]) ?></td>
          <td class=tbl1 align=center>
            <?
            if(!$MDL->IsModuleInstalled($mlist[$i])){ ?>
            <a href="?act=root&id=modules&a=install&modl=<? OUT($mlist[$i]) ?>">установить</a><br>
            <? }
            else{ ?>             
            <a href="?act=root&id=modules&a=uninstall&modl=<? OUT($mlist[$i]) ?>">удалить</a><br>
            <a href="?act=root&id=modules&a=configure&modl=<? OUT($mlist[$i]) ?>">настроить</a>       
            <br><font style="font-size:9px">
            <?
            $MDL->LoadMenu($mlist[$i]);
            ?>         
            </font>           
            </td>            
            <? } ?>                           
         </tr>        
        <?
       }
    ?>
    </table>
    <?
   
   }
 break;

 case "vars":

  if($a=="save")
   {
   $GV["site_title"]=          htmlspecialchars($vars[0]);
   $GV["site_descr"]=          htmlspecialchars($vars[1]);      
   $GV["site_owner"]=          htmlspecialchars($vars[2]);
   $GV["site_owner_email"]=    htmlspecialchars($vars[3]);
   $GV["site_kywds"]=          htmlspecialchars($vars[4]);
   $GV["skins_dir"]=           $vars[5];
   $GV["modules_dir"]=         $vars[6];
   $GV["modules_conf_dir"]=    $vars[7];
   $GV["data_dir"]=            $vars[8];   
   $GV["downloads_dir"]=       $vars[9];       
   $GV["skin"]=                $vars[10];
   $GV["module_ext"]=          $vars[11]; 
   $GV["default_page"]=        str_replace("?p=","",$vars[12]); 
   $GV["sep1"]=                $vars[13];
   $GV["sep2"]=                $vars[14];
   $GV["sep3"]=                $vars[15];
   
   $res="<?php
// CONSTANTS
\$GV[\"skins_dir\"]=           \"".$GV["skins_dir"]."\";
\$GV[\"modules_dir\"]=         \"".$GV["modules_dir"]."\";
\$GV[\"modules_conf_dir\"]=    \"".$GV["modules_conf_dir"]."\";
\$GV[\"data_dir\"]=             \"".$GV["data_dir"]."\";
\$GV[\"downloads_dir\"]=       \"".$GV["downloads_dir"]."\";
\$GV[\"module_ext\"]=          \"".$GV["module_ext"]."\";
\$GV[\"skin\"]=                \"".$GV["skin"]."\";
\$GV[\"default_page\"]=        \"".$GV["default_page"]."\";
\$GV[\"skin_dir\"]=\$GV[\"skins_dir\"].\"/\".\$GV[\"skin\"];

// SEPARATORS
\$GV[\"sep1\"]=                 \"".$GV["sep1"]."\";
\$GV[\"sep2\"]=                 \"".$GV["sep2"]."\";
\$GV[\"sep3\"]=                 \"".$GV["sep3"]."\";

//TITLES & DESCRS                        
\$GV[\"site_title\"]=           \"".$GV["site_title"]."\";
\$GV[\"site_owner\"]=           \"".$GV["site_owner"]."\";
\$GV[\"site_owner_email\"]=     \"".$GV["site_owner_email"]."\";
\$GV[\"site_descr\"]=           \"".$GV["site_descr"]."\";
\$GV[\"site_kywds\"]=           \"".$GV["site_kywds"]."\";
?>";
    $fp=fopen("conf/globals.php","w+");
    if(!$fp){$ERR->Error("error while opening globals file!!!");exit;}
    fwrite($fp,$res);
    fclose($fp);
    OUT("saved!");
    setpage("?act=root&id=vars");

   }
   else
   {
   $skinslist=array();
   $dirct=opendir($GV["skins_dir"]);
   while($file=readdir($dirct))
	{
	if(($file!=".")&&($file!="..")&& is_dir($GV["skins_dir"]."/".$file))
		{
		$skinslist[]=$file;
		}
	}
   $skinsinfo=array(array());
   $skinsselect="<select class=inputbox name=vars[] style=\"width:100%\">";
   $pageselect="<select class=inputbox name=vars[] style=\"width:100%\">";   
   for($i=0;$i<count($skinslist);++$i)
    {
    include $GV["skins_dir"]."/".$skinslist[$i]."/skin_info.php";
    $skinsinfo[$i]["title"]=$SKIN_TITLE;
    $skinsinfo[$i]["descr"]=$SKIN_DESCR;
    $skinsinfo[$i]["id"]=$SKIN_UNIQUEID;
    $skinsinfo[$i]["maker"]=$SKIN_MAKER;
    if($skinslist[$i]==$GV["skin"])$sel=" selected";else $sel="";
    $skinsselect.="<option value=\"".$skinslist[$i]."\"$sel>$SKIN_TITLE ($SKIN_VER)</option>\r\n";                
    }
    $skinsselect.="</select>";
   include $DIRS["menu_list"];
   for($i=0;$i<count($MENU);++$i)
    {     
    if("?p=".$GV["default_page"]==$MENU[$i]["link"])$sel=" selected"; else $sel="";
    $pageselect.="<option value=\"".$MENU[$i]["link"]."\"$sel>".$MENU[$i]["title"]."</option>";
    }
    $pageselect.="</select>";    
    
//   $MENU[0]["link"]="pager&id=about";
//$MENU[0]["title"]="О компании"
   //показываем форму изменения переменных
   ?>
    <div align=center><b>Редактор основных переменных</b></div>
    <form action="<? OUT("?act=$act&id=$id") ?>" method=post>
    <input type=hidden name=act value=root>
    <input type=hidden name=a value=save>
    <input type=hidden name=id value=vars>    
    <div align=center>Основные переменные:</div>
    <table width=100% style="border-width:1px;border-style:solid;border-color:black;">
    <tr><td width=30%>Название сайта</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["site_title"]) ?>" style="width:100%"></td></tr>
    <tr><td>Описание сайта</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["site_descr"]) ?>" style="width:100%"></td></tr>
    <tr><td>Владелец сайта</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["site_owner"]) ?>" style="width:100%"></td></tr>
    <tr><td>E-mail владельца сайта</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["site_owner_email"]) ?>" style="width:100%"></td></tr>
    <tr><td>Ключевые слова</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["site_kywds"]) ?>" style="width:100%"></td></tr>
    </table>
    <div align=center>Директории:</div>
    <table width=100% style="border-width:1px;border-style:solid;border-color:black;">
    <tr><td width=30%>Директория обложек:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["skins_dir"]) ?>" style="width:100%"></td></tr>
    <tr><td>Директория модулей:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["modules_dir"]) ?>" style="width:100%"></td></tr>
    <tr><td>Директория файлов конфигурации модулей:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["modules_conf_dir"]) ?>" style="width:100%"></td></tr>
    <tr><td>Директория данных:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["data_dir"]) ?>" style="width:100%"></td></tr>   
    <tr><td>Директория downloads:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["downloads_dir"]) ?>" style="width:100%"></td></tr>       
    </table>
    <div align=center>Прочее:</div>
    <table width=100% style="border-width:1px;border-style:solid;border-color:black;">
    <tr><td width=30%>Обложка</td><td><? OUT($skinsselect) ?></td></tr>
    <tr><td>Расширение файлов модулей:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["module_ext"]) ?>" style="width:100%"></td></tr>
    <tr><td>Страница открываемая первой:</td><td><? OUT($pageselect) ?></td></tr> 
    </table>
    <div align=center>Separators:</div>
    <table width=100% style="border-width:1px;border-style:solid;border-color:black;">
    <tr><td width=30%>Separator lev1</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["sep1"]) ?>" style="width:100%"></td></tr>
    <tr><td>Separator lev2</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["sep2"]) ?>" style="width:100%"></td></tr>
    <tr><td>Separator lev3</td><td><input class=inputbox type=text name=vars[] value="<? OUT($GV["sep3"]) ?>" style="width:100%"></td></tr>
    </table>
    <div align=center><input class=button type=submit value="Сохранить"></div>
    </form>
   <?php 
   }
 break;
 case "pass":
 include "conf/passwd.php";
  if($a=="save")
   {
   //Пишем файл passwd.php  
    $err="";   
    if($vars[1]!=$vars[2])$err.="<br>Введёные пароли не совпадают!";
    if(!$vars[0] || strlen($vars[0])<3)$err.="<br>Слишком короткий логин!";
    if(strlen($vars[0])<3)$err.="<br>Слишком короткий пароль!";
    
    if($err){OUT("<b>Возникла проблема:</b>".$err);
    OUT("<br><a href=?act=root&id=pass>назад</a>");return;};
    
    $res="<?php 
    \$_root_login=\"".$vars[0]."\";
    \$_root_passwd=\"".md5($vars[1])."\";
    ?>";     
    $fp=fopen("conf/passwd.php","w+");
    if(!$fp){$ERR->Error("error while opening master passwd file!!!");exit;}
    fwrite($fp,$res);
    fclose($fp);
    OUT("saved!");
    setpage("?act=root&id=pass");
   
   }
   else
   {
   //показываем форму изменения пароля
   ?>
    <div align=center><b>Пароль root</b></div>
    <form action="<? OUT("?act=$act&id=$id") ?>" method=post>
    <input type=hidden name=act value=root>
    <input type=hidden name=a value=save>
    <input type=hidden name=id value=pass>    
    <div align=center>Введите пароль и подтверждение:</div>
    <table width=100% style="border-width:1px;border-style:solid;border-color:black;">
    <tr><td width=50%>Логин:</td><td><input class=inputbox type=text name=vars[] value="<? OUT($_root_login) ?>" style="width:100%"></td>
    <tr><td width=50%>Пароль:</td><td><input class=inputbox type=password name=vars[] value="" style="width:100%"></td>    
    <tr><td width=50%>Подтверждение:</td><td><input class=inputbox type=password name=vars[] value="" style="width:100%"></td>
    </table> 
    <div align=center><input class=button type=submit value="Сохранить"></div>    
    </form>  
    
   <?php 
   }
 break;
 case "menu":
 if($MDL->IsModuleExists("menu"))
  {


  if(isset($a) && $a=="save")
   {
   //Пишем файл data/menu/list.php
   if(!file_exists($DIRS["menu_list"])){$ERR->Error("menu list file not exists!!!");exit;}
   $res="<?php\r\n";
   for($i=0;$i<count($num);++$i)
    {
    //$num[$i]=htmlspecialchars($num[$i]);
    $title[$i]=htmlspecialchars($title[$i]);
    //$link[$i]=htmlspecialchars($link[$i]);
    if($umod[$i]!="")$href="?p=".$umod[$i];else $href=$link[$i];
    if($num[$i]>0){
    $res.="\$MENU[".($num[$i]-1)."][\"link\"]=\"".$href."\";\r\n";
    $res.="\$MENU[".($num[$i]-1)."][\"title\"]=\"".$title[$i]."\";\r\n";
    $res.="\$MENU[".($num[$i]-1)."][\"ulevel\"]=\"".$ulevel[$i]."\";\r\n";
    $res.="\$MENU[".($num[$i]-1)."][\"group\"]=\"".$group[$i]."\";\r\n";
    $res.="\$MENU[".($num[$i]-1)."][\"parent\"]=\"".$parent[$i]."\";\r\n";}    
    }
    $res.="?>";
    
    OUT($res);
    $fp=fopen($DIRS["menu_list"],"w+");
    if(!$fp){$ERR->Error("error while opening menu list file!!!");exit;}
    fwrite($fp,$res);
    fclose($fp);
    OUT("saved!");
    setpage("?act=root&id=menu");
   }
  else
   {
   //показываем форму изменения элементов меню
   ?>
    <div align=center><b>Редактор меню</b></div>
    
    <form action="<? OUT("?act=$act&id=$id") ?>" method=post>
    <input type=hidden name=act value=root>
    <input type=hidden name=a value=save>
   <?php 
   $MDL->LoadAdminPage('menu');                                      
   $mdlist=$MDL->GetInstalledModules();
   include($DIRS["menu_list"]);
    for($i=0;$i<count($MENU);++$i)
     {
     $parentselect="<select class=inputbox name=parent[]  style=\"width:100%\">
     <option value=0>---</option>";
     for($j=0;$j<count($MENU);++$j)
       {
       $sel=($MENU[$i]["parent"]==$j+1)?" selected":"";
       $parentselect.="<option value=\"".($j+1)."\"$sel>".($MENU[$j]["title"])."</option>\r\n";
       }
      $parentselect.="</select>\r\n"; 
     $ulevselect="<select class=inputbox name=ulevel[]  style=\"width:100%\">";
     for($j=0;$j<$CURRENT_USER["level"];++$j)
       {
       $sel=($MENU[$i]["ulevel"]==$j)?" selected":"";
       $ulevselect.="<option value=\"$j\"$sel>$j</option>\r\n";
       }
      $ulevselect.="</select>\r\n"; 
      ?>
      <table width=100% style="border-width:1px;border-style:solid;border-color:black;"><td width=70%>
      <table width=100%>
      <tr><td width=50%>Порядковый номер</td><td><input class=inputbox style="width:100%" type=text value="<? OUT($i+1) ?>" name=num[]></td></tr>
      <tr><td width=50%>Заголовок</td><td><input class=inputbox style="width:100%" type=text value="<? OUT($MENU[$i]["title"]) ?>" name=title[]></td></tr>    
      <tr><td width=50%>Ссылка</td><td><input class=inputbox style="width:100%" type=text value="<? OUT($MENU[$i]["link"]) ?>" name=link[]></td></tr>
      <tr><td width=50%>Права на видимость</td><td><? OUT($ulevselect) ?></td></tr>
      <tr><td width=50%>Группа</td><td><input class=inputbox style="width:100%" type=text value="<? OUT($MENU[$i]["group"]) ?>" name=group[]></td></tr>
      <tr><td width=50%>Родитель</td><td><? OUT($parentselect) ?></td></tr>            
      </table>
      </td><td>
      Использовать модуль:<br>
      <?
        $mselect="<select class=inputbox name=umod[]  style=\"width:100%\"><option value=''></option>";
        for($j=0;$j<count($mdlist);++$j)
         {
         if("?p=".$mdlist[$j]["page"]==$MENU[$i]["link"])$sel=" selected"; else $sel="";
         $mselect.="<option value=\"".$mdlist[$j]["page"]."\"$sel>".$mdlist[$j]["page"]."</option>";
         }
        $mselect.="</select>";
      ?>
      
      <? OUT($mselect) ?>
      </td>
      </table>
      <?
     }
    if(!isset($pc))$pc=0;
   for($i=0;$i<$pc;++$i)
     {
     $ulevselect="<select class=inputbox name=ulevel[]  style=\"width:100%\">";
     for($j=0;$j<$CURRENT_USER["level"];++$j)
       {
       $sel=($MENU[$i]["ulevel"]==$j)?" selected":"";
       $ulevselect.="<option value=\"$j\"$sel>$j</option>\r\n";
       }
      $ulevselect.="</select>\r\n"; 
     $parentselect="<select class=inputbox name=parent[]  style=\"width:100%\">
     <option value=0>---</option>";
     for($j=0;$j<count($MENU);++$j)
       {
       $sel=($MENU[$i]["parent"]==$j+1)?" selected":"";
       $parentselect.="<option value=\"".($j+1)."\"$sel>".($MENU[$j]["title"])."</option>\r\n";
       }
      $parentselect.="</select>\r\n";  
      ?>
      <table width=100% style="border-width:1px;border-style:solid;border-color:black;" class=tbl1><td width=70%>
      <table width=100%>
      <tr><td width=50%>Порядковый номер</td><td><input style="width:100%"  class=inputbox type=text value="<? OUT((count($MENU)+$i+1)) ?>" name=num[]></td></tr>
      <tr><td width=50%>Заголовок</td><td><input style="width:100%"  class=inputbox type=text value="" name=title[]></td></tr>
      <tr><td width=50%>Ссылка</td><td><input style="width:100%" class=inputbox type=text value="" name=link[]></td></tr>
      <tr><td width=50%>Права на видимость</td><td><? OUT($ulevselect) ?></td></tr>
      <tr><td width=50%>Группа</td><td><input style="width:100%" class=inputbox style="width:100%" type=text value="0" name=group[]></td></tr>
      <tr><td width=50%>Родитель</td><td><? OUT($parentselect) ?></td></tr>         

      </table>
      </td><td>
      Использовать модуль:<br>
      <?
        $mselect="<select class=inputbox style=\"width:100%\"  name=umod[]><option value='' selected></option>";
        for($j=0;$j<count($mdlist);++$j)
         {
         $mselect.="<option value=\"".$mdlist[$j]["page"]."\">".$mdlist[$j]["page"]."</option>";
         }
        $mselect.="</select>";
      ?>      
      <? OUT($mselect) ?>
      </td>
      </table>
      <?
     }
      $pc++;
    ?>    

      <div align=center><a href="?act=root&id=menu&pc=<? OUT($pc) ?>">Добавить пункт</a></div>
      <div align=center><input class=button type=submit value="Сохранить"></div>
      </form>
    <?
   }
  }
  else
  {
  echo("У ВАС ОТСУТСТВУЕТ НЕОБХОДИМЫЙ МОДУЛЬ!");
  }
 break;
 case "users":
   if($MDL->IsModuleExists("users"))
    {
    //показываем форму
    ?>
     <div align=center><b>Пользователи</b></div>
    <?php
    $MDL->LoadAdminPage('users'); 
    }
    else
     echo("У ВАС ОТСУТСТВУЕТ НЕОБХОДИМЫЙ МОДУЛЬ!"); 
 break; 
 case "pager":
   if($MDL->IsModuleExists("pager"))
    {
    //показываем форму
    ?>
     <div align=center><b>Произвольные страницы</b></div>
    <?php
    $MDL->LoadAdminPage('pager'); 
    }
    else
     echo("У ВАС ОТСУТСТВУЕТ НЕОБХОДИМЫЙ МОДУЛЬ!"); 
 break;
 case "logout":
 ?>   

 <?php
 _logoutroot();
 ?>

 <?php
 break;
 default:
 ?>
 <B>404 No such root page (UNDER CONSTRUCTION?)</b>
 <?php
 };


?>