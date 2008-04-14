<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete guestbook posts             //
//    MAKER: ShurupINC                                                  //
//    specially for SMS CMS                                             //
//----------------------------------------------------------------------//

$MDL_TITLE="Guestbook";
$MDL_DESCR="For GB";
$MDL_UNIQUEID="guestbook";
$MDL_MAKER="ShurupINC";

if(!$_GETINFO)
{
//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************* class for projects ****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("GuestBook"))
{
class GuestBook{
   var $data_dir;
   var $pre_dir;
   var $answ_separator;
   var $premoderation;
   var $answ_item_sep;
   var $item_separator;
   var $curr_page;
   var $max_mess_on_page;
   
//-----------------------------------------------------------------------------
   function GuestBook($dd,$pd,$premoderation){
      $this->data_dir = $dd;
      $this->pre_dir = $pd;
      $this->max_mess_on_page = 10;
      $this->premoderation=$premoderation;
   }
   
//-----------------------------------------------------------------------------
   function set_max_messages($mm){
      $this->max_mess_on_page = $mm;
   }
   
//-----------------------------------------------------------------------------
   function set_curr_page($pp){
      $this->curr_page = $pp;
   }
   
//-----------------------------------------------------------------------------
   function mess_count(){
      
      // чтение списка файлов
      $files = read_dir($this->data_dir);
      return count($files);
      
   }
   
//-----------------------------------------------------------------------------
   function get_notapplied_messages(){
   
      // чтение списка файлов
      $files = read_dir($this->pre_dir);
      if(count($files) == 0)
         return NULL;
         
      // сортировка         
      rsort($files);
      
      // список всех сообщений
      $result = NULL;
      
      // цикл считывания сообшщений из файлов
      for($file_idx = 0; $file_idx < count($files); ++$file_idx){
         $file = get_file($this->pre_dir."/".$files[ $file_idx ]);         
         $item_res = explode($this->answ_separator,$file);
         $item_res = explode($this->item_separator,$item_res[0]);
         $result[$file_idx]["id"]     = $files[ $file_idx ];
         $result[$file_idx]["name"]   = $item_res[0];
         $result[$file_idx]["email"]  = $item_res[1];
         $result[$file_idx]["url"]    = $item_res[2];
         $result[$file_idx]["time"]   = $item_res[3];
         $result[$file_idx]["ip"]     = $item_res[4];
         $result[$file_idx]["text"]   = $item_res[5];
      }
      
      return $result;
   }

//-----------------------------------------------------------------------------
   function get_all_messages(){
   
      // чтение списка файлов
      $files = read_dir($this->data_dir);
      if(count($files) == 0)
         return NULL;
         
      // сортировка         
      rsort($files);
      
      // список всех сообщений
      $result = NULL;
      
      // цикл считывания сообшщений из файлов
      for($file_idx = 0; $file_idx < count($files); ++$file_idx){
         $file = get_file($this->data_dir."/".$files[ $file_idx ]);         
         $item_res = explode($this->answ_separator,$file);
         $item_res = explode($this->item_separator,$item_res[0]);
         $result[$file_idx]["id"]     = $files[ $file_idx ];
         $result[$file_idx]["name"]   = $item_res[0];
         $result[$file_idx]["email"]  = $item_res[1];
         $result[$file_idx]["url"]    = $item_res[2];
         $result[$file_idx]["time"]   = $item_res[3];
         $result[$file_idx]["ip"]     = $item_res[4];
         $result[$file_idx]["text"]   = $item_res[5];
      }
      
      return $result;
   }
   
//-----------------------------------------------------------------------------
   function get_message($id){
      if(!file_exists($this->data_dir."/".$id)) 
         return NULL;
      
      // чтение списка файлов
      $file = get_file($this->data_dir."/".$id);

      $result = array();
      
      
      $item_res = explode($this->answ_separator,$file);
      $item_res = explode($this->item_separator,$item_res[0]);
      
      $result["id"]     = $id;
      $result["name"]   = $item_res[0];
      $result["email"]  = $item_res[1];
      $result["url"]    = $item_res[2];
      $result["time"]   = $item_res[3];
      $result["ip"]     = $item_res[4];
      $result["text"]   = $item_res[5];
      
      return $result;
   }
//-----------------------------------------------------------------------------
   function get_notapplied_message($id){
      if(!file_exists($this->pre_dir."/".$id)) 
         return NULL;

      
      // чтение списка файлов
      $file = get_file($this->pre_dir."/".$id);

      $result = array();
      
      
      $item_res = explode($this->answ_separator,$file);
      $item_res = explode($this->item_separator,$item_res[0]);
      
      $result["id"]     = $id;
      $result["name"]   = $item_res[0];
      $result["email"]  = $item_res[1];
      $result["url"]    = $item_res[2];
      $result["time"]   = $item_res[3];
      $result["ip"]     = $item_res[4];
      $result["text"]   = $item_res[5];
      
      return $result;
   }
   
//-----------------------------------------------------------------------------
   function apply_message($id){
          if(!file_exists($this->pre_dir."/".$id)) 
         return;
         rename($this->pre_dir."/".$id,$this->data_dir."/".$id);      
   }
//-----------------------------------------------------------------------------
   function delete_notapplied_message($id){
          if(!file_exists($this->pre_dir."/".$id)) 
         return;
         unlink($this->pre_dir."/".$id);      
   }   
//-----------------------------------------------------------------------------
   function add_message($name, $email, $url, $time, $ip, $text){
         
      $result .= $name.$this->item_separator.
                 $email.$this->item_separator.
                 $url.$this->item_separator.
                 $time.$this->item_separator.
                 $ip.$this->item_separator.
                 $text;      
      
      $file_name = get_serial().".txt";
      // записываем в файл
      
      
      $DIR=($this->premoderation)?$this->pre_dir:$this->data_dir;
      $fp=fopen($DIR."/".$file_name,"w+");
      if(!$fp)
            return false;     
      if(!fwrite($fp,$result))
            return false;
      fclose($fp);
      return true;
   }

//-----------------------------------------------------------------------------
   function answer_mess($id, $name, $email, $url, $time, $ip, $text){
      if(!file_exists($this->data_dir."/".$id))
         return false;
         
      $file = get_file($this->data_dir."/".$id);
      
      $file .= $this->answ_separator.
               $name.$this->answ_item_sep.
               $email.$this->answ_item_sep.
               $url.$this->answ_item_sep.
               $time.$this->answ_item_sep.
               $ip.$this->answ_item_sep.
               $text;
               
      $fp=fopen($this->data_dir."/".$id,"w+");
      if(!$fp)
            return false;     
      if(!fwrite($fp,$file))
            return false;
      fclose($fp);
                 
      return true;
   }   

//-----------------------------------------------------------------------------

   function get_answer_mess($id){
      if(!file_exists($this->data_dir."/".$id))
         return NULL;
         
      $file = get_file($this->data_dir."/".$id);
      
      // все ответы
      $result = NULL;
      
      // часть файла, где хранятся только ответы, при этом 1-й итем не считать...
      // разбиты на сообщения целиком
      $answers = explode($this->answ_separator,$file);
      for($answ_idx=1; $answ_idx < count($answers); ++$answ_idx){
          $items = explode($this->answ_item_sep,$answers[ $answ_idx ]);
          $result[$answ_idx-1]["name"]  = $items[0];
          $result[$answ_idx-1]["email"] = $items[1];
          $result[$answ_idx-1]["url"]   = $items[2];
          $result[$answ_idx-1]["time"]  = $items[3];
          $result[$answ_idx-1]["ip"]    = $items[4];
          $result[$answ_idx-1]["text"]  = $items[5];
      }
      
      return $result;               
   }   

//-----------------------------------------------------------------------------

   function get_pages_count(){
      return Ceil($this->mess_count() / $this->max_mess_on_page);
   }


//-----------------------------------------------------------------------------
   function get_messages_onpage($page){
      $messages = $this->get_all_messages();
      $pages_count = Ceil(count($messages) / $this->max_mess_on_page);
      if($page > $pages_count)
         return NULL;
      
      $fst_mess = $this->max_mess_on_page * ($page - 1);
      $lst_mess = $this->max_mess_on_page * $page;
      $k=0;
      $result = Array();
      for($m_idx=$fst_mess; $m_idx<count($messages) && $m_idx<$lst_mess; ++$m_idx){
         $result[$k] = $messages[$m_idx];
         $k++;
      }
      return $result;
   }
   
//-----------------------------------------------------------------------------
   function delete_mess($id){
      if(!file_exists($this->data_dir."/".$id))
         return false;
      
      return unlink($this->data_dir."/".$id);      
   }

//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------
//-----------------------------------------------------------------------------


};
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
//================================================
// МОДУЛЬ ДЛЯ АДМИНИСТРАТОРА
//================================================
  elseif($_ADMIN && check_auth() && $CURRENT_USER["level"]>=5)
  {
    if(!file_exists(SK_DIR."/guestbook_admin.php"))
     {   
     global $side;
     include "config.php";
     $guest = new GuestBook($DIRS["guestbook_files"],$DIRS["guestbook_predir"],$GV["guestbook_premoderation"]);
     $guest->answ_item_sep  = $GV["sep3"];
     $guest->answ_separator = $GV["sep1"];
     $guest->item_separator = $GV["sep2"];  
     
           
 if((isset($side) && $side==0) || !isset($side)){ 
 
            if(isset($actm) && $actm=="messagesact")
             {
             if(!isset($messageaction))$messageaction="";
             if($messageaction=="delete") 
               {
                for($i=0;$i<count($ids);++$i)
                 $guest->delete_notapplied_message($ids[$i]);
                }
                elseif($messageaction=="apply")
                {
                for($i=0;$i<count($ids);++$i)
                 $guest->apply_message($ids[$i]);
                } 
             }
     ?>
     <div align=center><b>Управление утверждёнными сообщениями:</b></div>
     <?

      if(!isset($p_idx))$p_idx = 1;
   
       $pagestext="";
         for($i=0;$i<$guest->get_pages_count();++$i)
             {
	      if($i+1!=$p_idx)$pagestext.="<a href='?p=$p&a=$a&p_idx=".($i+1)."'>";
              $pagestext.= "<b>".($i+1)."</b>";
             if($i+1!=$guest)
               $pagestext.=("</a>");
	      if($i != $guest->get_pages_count()-1)
       	      $pagestext.= " | ";
             }   

         
         if($act == "del"){
            
            $act = "view";
            $guest->delete_mess($id);
            
         }
         if($act == "answ"){
         
         if($page == "add"){
          $name=$FLTR->DirectProcessString($name);
          $email=$FLTR->DirectProcessString($email);
          $url=$FLTR->DirectProcessString($url);
         $text=$FLTR->DirectProcessText($text);
          if(check_auth())
          {
          $name=$CURRENT_USER["id"];
          $email=$CURRENT_USER["email"];
          $url=$CURRENT_USER["url"];             
          } 
     
         $error="";
         if(strlen($name)<2)$error.="<br><b>Ошибка:</b> имя не может быть короче 2-х символов!<br>";     
         if(strlen($text)<4)$error.="<br><b>Ошибка:</b> текст не может быть короче 4-х символов!<br>";
         
        if(!$error)
         {
         $guest->answer_mess($id,$name,$email,$url,time(),"",$text); 
         $act="view";
         $name=$FLTR->DirectProcessString($name);
         $email=$FLTR->DirectProcessString($email);
         $url=$FLTR->DirectProcessString($url);
         $text="";            
         }
          else
          {
          echo($error);
          }         
          
         }         
        elseif(check_auth())
        {
        $name=$CURRENT_USER["nick"];
        $email=$CURRENT_USER["email"];
        $url=$CURRENT_USER["url"]; 
        }
            $mess = $guest->get_message($id);  ?>
            <table width=50% align=center bordercolor=black border=1 style="border: 0px">
             <tr>
             <td align=center width=50%>
             <?php echo $mess["name"]; ?>
             </td>
             <td align=center width=50%>
             <?php echo $mess["email"]; ?>
              </td>
              </tr>
              <tr>
              <td align=center width=50%>
              <?php echo $mess["url"]; ?> 
               </td>
                <td align=center width=50%>
                <?php echo $mess["time"]; ?> 
              </td>
              </tr>
              <tr>
              <td colspan=2 width=100%>
              <?php echo $mess["text"]; ?> 
              </td>
              </tr>
              </table>
                  <table align=center style="border: 0px">
                  <form name="comment_form" action="<?php echo "?p=$p&a=$a&page=add&act=answ&id=$id&p_idx=$p_idx" ?>" method="post">
                        Имя: <br>
                        <input type="text" name="name" style="width:100%" value="<? OUT($name) ?>" class=inputbox <? if(check_auth())OUT("readonly") ?>><br>
                        E-mail: <br>
                        <input type="text" name="email" style="width:100%" value="<? OUT($email) ?>" class=inputbox <? if(check_auth())OUT("readonly") ?>><br>
                        Url: <br>
                        <input type="text" name="url" style="width:100%" value="<? OUT($url) ?>" class=inputbox <? if(check_auth())OUT("readonly") ?>><br>
                        Комментарий: <br>
                        <textarea rows=5 name="text" style="width:100%" rows=9 class=inputbox><? OUT($text) ?></textarea><br>
                        <br>
                        <input type="submit" class=button value="Отправить">
                        <input type="reset" class=button value="Очистить">
                  </form>
                  </table><br>
                  
                  <div align=center><a href="<?php echo "?p=$p&a=$a&p_idx=$p_idx" ?>">назад</a></div>
            <?php
            
                                 
         }

         
         if(!isset($act) || $act == "view"){
         
         $messages = $guest->get_messages_onpage($p_idx);
         echo "<CENTER>Страница: ".$pagestext."</CENTER>";         
         for($m_idx=0; $m_idx<count($messages); ++$m_idx){
            $mess = $messages[$m_idx];
            $answers = $guest->get_answer_mess($mess["id"]);
            ?>
                  <table width=50% align=center class=tbl2>
                               <tr>
                                    <td align=center width=50% class=tbl1>
                                          <?php $ud=get_user_data($mess["name"]); if(is_user_exists($mess["name"])){ OUT("<a href=?p=users&act=userinfo&id=".$mess["name"].">".$ud["nick"]."</a>");}else{OUT($mess["name"]);} ?>, <?php if($ans[$answ_idx]["email"])echo make_email_str($mess["email"]); ?> <?php if($mess["url"])echo make_url_str($mess["url"]); ?> 
                                    </td>
                                    <td align=center width=50% class=tbl1>
                                          <?php echo norm_date($mess["time"]); ?> 
                                    </td>
                                    </tr>
                                    <tr>
                                    <td colspan=2 width=100% class=tbl1>
                                          <?php echo $mess["text"]; ?> 
                                    </td>
                                    </tr> 
                         
                  <tr>
                  <td colspan=2 width=100%>
                        <?php echo "<center><a href='?p=$p&a=$a&page=$page&act=del&id=".$mess["id"]."'>Удалить</a></center>"; ?>
                        <?php echo "<center><a href='?p=$p&a=$a&page=$page&act=answ&id=".$mess["id"]."'>Ответить</a> (ответов: ".count($answers).")</center>"; ?> 
                  </td>
                  </tr> 
                  </table><br><Br>        
            <?php
            }
            if(!count($messages))OUT("<div align=center><b>НЕТ СООБЩЕНИЙ</b></div>");
         echo "<CENTER>Страница: ".$pagestext."</CENTER>";            
         ?>
          <div align=center><a href="<?php echo "?p=$p&p_idx=$p_idx" ?>">назад</a></div>
         <?

         }     
           }
           
           if(((isset($side) && $side==1)|| !isset($side))&&($GV["guestbook_premoderation"]))                  
           {

             
           ?>
           <div align=center><b>Неутверждённые сообщения:</b></div>
           <form action="<? OUT("?p=$p&a=$a&actm=messagesact") ?>" method=post>
           <?
           $messages = $guest->get_notapplied_messages();
           for($i=0; $i<count($messages); ++$i)
             {
             $mess=$messages[$i];
              ?>
                <table width=50% align=center class=tbl2>
                               <tr>
                                    <td align=center width=50% class=tbl1>
                                          <?php $ud=get_user_data($mess["name"]); OUT("<a href=?p=users&act=userinfo&id=".$mess["name"].">".$ud["nick"]."</a>"); ?>, <?php if($ans[$answ_idx]["email"])echo make_email_str($mess["email"]); ?> <?php if($mess["url"])echo make_url_str($mess["url"]); ?> 
                                    </td>
                                    <td align=center width=50% class=tbl1>
                                          <?php echo norm_date($mess["time"]); ?> 
                                    </td>
                                    </tr>
                                    <tr>
                                    <td colspan=2 width=100% class=tbl1>
                                          <?php echo $mess["text"]; ?> 
                                    </td>
                                    </tr> 
                         
                  <tr>
                  <td colspan=2 width=100%>
                  <input type=checkbox name=ids[] value="<? OUT($mess["id"]) ?>">выбрать
                  </td>                                                         
                  </tr> 
                  </table><br>        
              <?
             }
            if(!count($messages))OUT("<div align=center><b>НЕТ СООБЩЕНИЙ</b></div>");             
             ?>
             <div align=center><input type=radio name=messageaction value="apply" checked>Утвердить
             <input type=radio name=messageaction value="delete">Удалить</div>
             <div align=center><input type=submit сlass="button" value="Применить!"></div>
             </form>
             <?
           
           }
     }
    else
      include(SK_DIR."/guestbook_admin.php");  
  }
//================================================
// МОДУЛЬ ДЛЯ ВСЕХ
//================================================
 elseif($_MODULE) 
if($_NOTBAR)
  if(!file_exists(SK_DIR."/guestbook.php"))
   {
   
   if(isset($a)&& $a=="admin"){$MDL->LoadAdminPage("$p");return;}

   include "config.php";
   global $side;

   $guest = new GuestBook($DIRS["guestbook_files"],$DIRS["guestbook_predir"],$GV["guestbook_premoderation"]);
   $guest->answ_item_sep  = $GV["sep3"];
   $guest->answ_separator = $GV["sep1"];
   $guest->item_separator = $GV["sep2"];

   if(!isset($p_idx)){
   $p_idx = 1;
   }
   


   $guest->set_curr_page($p_idx);

   
   if(isset($add) && $add==1 && (!isset($side) || $side==0)){
   $add=0;
   $time = norm_date(time());
   
       $name1=$FLTR->DirectProcessString($name);
       $email=$FLTR->DirectProcessString($email);
       $url=$FLTR->DirectProcessString($url);
       $text=$FLTR->DirectProcessText($text);
       if(check_auth())
       {
       $name1=$CURRENT_USER["id"];
       $email=$CURRENT_USER["email"];
       $url=$CURRENT_USER["url"];             
       } 
     
   $error="";
   if(strlen($name1)<$GV["guestbook_minnamelen"])$error.="<br><b>Ошибка:</b> имя не может быть короче ".$GV["guestbook_minnamelen"]." символов!<br>";     
   if(strlen($text)<$GV["guestbook_mintextlen"])$error.="<br><b>Ошибка:</b> текст не может быть короче ".$GV["guestbook_mintextlen"]." символов!<br>";
   if(strlen($text)>$GV["guestbook_maxtextlen"])$error.="<br><b>Ошибка:</b> текст не может быть длиннее ".$GV["guestbook_maxtextlen"]." символов!<br>";
         
   if(!$error)
      {
       $guest->add_message($name1,$email,$url,time(),"",$text);
       $name=$FLTR->DirectProcessString($name);
       $email=$FLTR->DirectProcessString($email);
       $url=$FLTR->DirectProcessString($url);
       $text="";            
      }
      else
      {
      echo($error);
      }
      
   $p_idx = $guest->curr_page;
   }
   elseif(check_auth())
    {
       $name=$CURRENT_USER["nick"];
       $email=$CURRENT_USER["email"];
       $url=$CURRENT_USER["url"];
 
    }

       $name=$FLTR->ReverseProcessString($name);
       $email=$FLTR->ReverseProcessString($email);
       $url=$FLTR->ReverseProcessString($url);
       $text=$FLTR->ReverseProcessText($text);
       if((isset($side) && $side==0) || !isset($side)){              
      ?>
         <div align=centeR><b>Оставить отзыв:</b></div>
         <table align=center width=70% class=tbl1>
         <form name="comment_form" action="<? OUT("?p=$p&page=$page") ?>" method="post">
            <tr><td width=100%>Имя*: <br>
            <input type="text" name="name" class=inputbox style="width:100%" value="<? OUT($name) ?>"<? if(check_auth())OUT("readonly") ?>><br>
            </tr></td>
            <tr><td width=100%>E-mail: <br>
            <input type="text" name="email" class=inputbox style="width:100%" value="<? OUT($email) ?>"<? if(check_auth())OUT("readonly") ?>><br>
            </tr></td>
            <tr><td width=100%>Url: <br>
            <input type="text" name="url" class=inputbox style="width:100%" value="<? OUT($url) ?>"<? if(check_auth())OUT("readonly") ?>><br>
            </tr></td>
            <tr><td width=100%>Комментарий* (не длиннее <? OUT($GV["guestbook_maxtextlen"]) ?> символов): <br>
            <textarea rows=5 name="text" rows=9 class=inputbox style="width:100%"><? OUT($text) ?></textarea><br><br>
            * - Обязательные для заполнения поля            
            </tr></td></table>
            <br>
            <input type="hidden" name="add" value="1">
            <div align=center><input type="submit" value="Отправить" class=button></div>
         </form>   
         <? if($GV["guestbook_premoderation"]){ ?>
         <div align=justify><b>Внимание:</b> Гостевая книга модерируется. Ваш отзыв будет доступен только после утверждения администратором!</div>
         <? } ?>
         
         <?   }
   

       $pagestext="";
         for($i=0;$i<$guest->get_pages_count();++$i)
             {
	      if($i+1!=$p_idx)$pagestext.="<a href='?p=$p&page=$page&p_idx=".($i+1)."'>";
              $pagestext.= "<b>".($i+1)."</b>";
             if($i+1!=$guest)
               $pagestext.=("</a>");
	      if($i != $guest->get_pages_count()-1)
       	      $pagestext.= " | ";
             }   


       if((isset($side) && $side==1) || !isset($side)){ 
    if(check_auth() && $CURRENT_USER["level"]>=5)
    {
    ?>
    <div align=center>
    <a href="?p=<? OUT($p) ?>&a=admin">Администрирование</a>
    </div>
    <?
    }                  



         echo "<br><br><CENTER>Страница: ".$pagestext."</CENTER>"; 

   
         $messages = $guest->get_messages_onpage($p_idx);
         for($m_idx=0; $m_idx<count($messages); ++$m_idx)
            {
            $mess = $messages[$m_idx];
            $answers = $guest->get_answer_mess($mess["id"]);
            ?>
                  <table width=70% align=center style="border: 0px" class=tbl2>
                  <tr>
                  <td align=left width=50% colspan=2 class=tbl2>
                       <?php $ud=get_user_data($mess["name"]); 
                       if(is_user_exists($mess["name"])){
                       OUT("<a href=?p=users&act=userinfo&id=".$mess["name"].">".$ud["nick"]."</a>");}
                       else OUT("".$ud["nick"]);  ?>, <?php if($mess["email"]) echo make_email_str($mess["email"]); ?>
                   <?php if($mess["url"])echo make_url_str($mess["url"]).","; ?>  
                   <?php echo norm_date($mess["time"]); ?> 
                  </td>
                  </tr>
                  <tr>
                  <td colspan=2 width=100% class=tbl1>
                        <?php echo $mess["text"]; ?> 
                        <?php
                           if(count($answers)>0)
                           {
                           ?>
                           </tr></td>
                           <tr>
                           <td colspan=2 width=90% ><B>Ответы:</B>
                               <table width=90% align=center> 
                                  
                                 <?php for($answ_idx=0; $answ_idx<count($answers); ++$answ_idx){ 
                                    $ans = $guest->get_answer_mess($mess["id"]); ?>
                                 
                                    <tr>
                                    <td align=center width=50% class=tbl1>
                                          <?php $ud=get_user_data($ans[$answ_idx]["name"]); OUT("<a href=?p=users&act=userinfo&id=".$ans[$answ_idx]["name"].">".$ud["nick"]."</a>"); ?><?php if($ans[$answ_idx]["email"])echo ", ".make_email_str($ans[$answ_idx]["email"]); ?> <?php if($ans[$answ_idx]["url"])echo ", ".make_url_str($ans[$answ_idx]["url"]); ?> 
                                          <?php echo ", ".norm_date($ans[$answ_idx]["time"]); ?> 
                                    </td>
                                    </tr>
                                    <tr>
                                    <td colspan=2 width=100% class=tbl1>
                                          <?php echo $ans[$answ_idx]["text"]; ?> 
                                    </td>
                                    </tr>
                                  <?php } ?>
                              
                              </table>
                            </td></tr>   
                       <?  } ?>

                  </td>
                  </tr> 
                  </table><br><br> 
        <?php  }
        if(!count($messages))OUT("<div align=center>нет сообщений</div>");  
        
         echo "<CENTER>Страница: ".$pagestext."</CENTER>";
    if(check_auth() && $CURRENT_USER["level"]>=5)
    {
    ?>
    <div align=center>
    <a href="?p=<? OUT($p) ?>&a=admin">Администрирование</a>
    </div>
    <? }
    }                  
   }
  else
    include(SK_DIR."/guestbook.php");
else
  if(!file_exists(SK_DIR."/guestbookbar.php"))
    $ERR->Warning("Skin '".$GV["skin"]."' doesn't support this module!");
  else
    include(SK_DIR."/guestbookbar.php");
    
}?>



