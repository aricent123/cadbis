<?php
//----------------------------------------------------------------------//
//    TITLE: PHP Class for read/edit/delete/comment news                //
//    MAKER: Shurup                                                  //
//    specially for SMS CMS (SM & Shurup Content Management System)     //
//----------------------------------------------------------------------//

$MDL_TITLE="News";
$MDL_DESCR="For news";
$MDL_UNIQUEID="news";
$MDL_MAKER="ShurupINC";



if(!$_GETINFO)
{

/////////////////////////////////////////////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////

//.............................................................//
//..........................CLASSES............................//
//.............................................................//

//********************** class for news   *****************************//
/////////////////////////////////////////////////////////////////////////
if(!class_exists("CNews"))
{class CNews{
  var $data_dir;	            //data_dir
  var $files_dir;	            //data_dir
  var $page_max_news;	      //max news on one page
  var $current_news_page;	//current idx of page with news
  var $curr_file;		      //current file with news
  
  // separators
  var $comment_part_sep;      // отделяет часть комментариев от информативной части
  var $news_info_sep;         // разделяет информацию о новости
  var $comment_sep;           // разделяет комментарии между собой
  var $comment_info_sep;      // разделяет информацию о комментарии внутри комментария

  // constructor 
  function CNews($data_dir,$files_dir){
	$this->data_dir = $data_dir;
	$this->files_dir = $files_dir;
      $this->current_news_page = 1;
	$this->curr_file = "";
  }

//-----------------------------------------------
  // set/get news count on one page  

  function set_max_news($max_news){
	$this->page_max_news = $max_news;
  }

  function get_max_news(){
	return $this->page_max_news;
  }
  
//-----------------------------------------------

  function get_news_count(){
	return count(read_dir($this->data_dir));
  }

//-----------------------------------------------

  function get_pages_count(){  
	$news_count = count(read_dir($this->data_dir));
	return Ceil($news_count / $this->page_max_news);
  }

//-----------------------------------------------

  function get_news_text($news_id){ 
      $info = $this->get_news_info($news_id);
      if($info == NULL)
         return NULL;
      
      $file = $info["file"];
      if(!file_exists($this->files_dir."/".$file))
         return NULL;
      
      $text = get_file($this->files_dir."/".$file);
      return $text;
  }
  
//-----------------------------------------------

  function get_news_list(){
	$news_list = array(array());
	$news_files = read_dir($this->data_dir);
	rsort($news_files);
        $temp = array();
        for($i=0;$i<count($news_files);++$i){
		$file = get_file($this->data_dir."/".$news_files[$i]);
		$temp = explode($this->comment_part_sep,$file);
		$temp = explode($this->news_info_sep,$temp[0]);
		$news_list[$i]["id"] = $news_files[$i];		
		$news_list[$i]["date"] =  $temp[0];
		$news_list[$i]["head"] = $temp[1];
		$news_list[$i]["file"] = $temp[2];
		$news_list[$i]["text"] = $temp[3];
		$news_list[$i]["name"] = $temp[4];
		$news_list[$i]["comc"] = $temp[5];
	}
	return $news_list;
  }
  
  function is_exist($news_id){
      return file_exists($this->data_dir."/".$news_id);      
  }

//-----------------------------------------------

  function get_news_on_page($page){
	// устраняем ошибку при неправлильном вводе номера страницы
	$news_files = read_dir($this->data_dir);
	if(!count($news_files))return NULL;
	$pages_count = count($news_files);
	if($page > $pages_count)
		$page = $pages_count;

	$news_list = NULL;
	rsort($news_files);
        $temp = array();
	$first_news_on_page = ($page-1) * $this->page_max_news;
        $k=0;
        for($i=$first_news_on_page; $i<count($news_files) && $i<$page*$this->page_max_news; $i++){
		$file = get_file($this->data_dir."/".$news_files[$i]);
		$temp = explode($this->comment_part_sep,$file);
		$temp = explode($this->news_info_sep,$temp[0]);
		$news_list[$i]["id"] = $news_files[$i];		
		$news_list[$k]["date"] = $temp[0];
		$news_list[$k]["head"] = $temp[1];
		$news_list[$k]["file"] = $temp[2];
		$news_list[$k]["text"] = $temp[3];
		$news_list[$k]["name"] = $temp[4];
		$news_list[$k]["comc"] = $temp[5];
		++$k;
	}
	return $news_list;
   } 
   
//-----------------------------------------------
 
   function get_news_info($news_id){
      if(!file_exists($this->data_dir."/".$news_id))
            return NULL;
            
	$file = get_file($this->data_dir."/".$news_id);
	if(count($file) == 0)
	     return NULL;
	     
	$temp = explode($this->comment_part_sep,$file);
	$temp = explode($this->news_info_sep,$temp[0]);
	$news_info = array();
        $news_info["id"] =$file;	
	$news_info["date"] = $temp[0];
	$news_info["head"] = $temp[1];
	$news_info["file"] = $temp[2];
	$news_info["text"] = $temp[3];
	$news_info["name"] = $temp[4];
	$news_info["comc"] = $temp[5];
	
	return $news_info;
   }
   
//-----------------------------------------------
   
   function add_news($id, $news_info){
      if(count($news_info) != 6) 
            return false;
            
      $result = time().$this->news_info_sep.
                $news_info["head"].$this->news_info_sep.
                $news_info["file"].$this->news_info_sep.
                $news_info["text"].$this->news_info_sep.
                $news_info["name"].$this->news_info_sep.
                "0".$this->comment_part_sep;
                              
      // получаем уникальный идентификатор файла новостей
      // полученный в процессе добавления новости
      $news_id = $id;
      
      // записываем в файл параметры новости
      $fp=fopen($this->data_dir."/".$news_id,"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$result))
            return false;      
      fclose($fp);
      
      // записываем текст новости
      $fp=fopen($this->files_dir."/".$news_info["file"],"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$news_info["fulltext"]))
            return false;      
      fclose($fp);
      
      return true;      
   }

//-----------------------------------------------
   
   function del_news($news_id){
      if(!file_exists($this->data_dir."/".$news_id))
            return false;
      $info = $this->get_news_info($news_id);
      $file = $info["file"];
      $res = unlink($this->data_dir."/".$news_id);

      if(!res || !file_exists($this->files_dir."/".$file))
            return false;
            
      return unlink($this->files_dir."/".$file);
      
   }

 //-----------------------------------------------
   
   function edit_news($news_id, $news_info){
   


      if(!file_exists($this->data_dir."/".$news_id))
            return false;
   
     // записываем инфу о новости
      $result = $news_info["date"].$this->news_info_sep.
                $news_info["head"].$this->news_info_sep.
                $news_info["file"].$this->news_info_sep.
                $news_info["text"].$this->news_info_sep.
                $news_info["name"].$this->news_info_sep.
                count($comments).$this->comment_part_sep;
      
	// записываем блок комментариев	
      for($comm_idx=0; $comm_idx<count($comments); ++$comm_idx){
            $result .= $comments[$comm_idx]["id"].$this->comment_info_sep.
                       $comments[$comm_idx]["author"].$this->comment_info_sep. 
                       $comments[$comm_idx]["email"].$this->comment_info_sep. 
                       $comments[$comm_idx]["url"].$this->comment_info_sep. 
                       $comments[$comm_idx]["time"].$this->comment_info_sep. 
                       $comments[$comm_idx]["ip"].$this->comment_info_sep. 
                       $comments[$comm_idx]["text"];
            if($comm_idx != count($comments)-1)
                  $result .= $this->comment_sep;
      }       
                                    
      // записываем в файл
      $fp=fopen($this->data_dir."/".$news_id,"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$result))
            return false;      
      fclose($fp);

      // записываем текст новости
      $fp=fopen($this->files_dir."/".$news_info["file"],"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$news_info["fulltext"]))
            return false;      
      fclose($fp);

      
      return true;      
   }


//-----------------------------------------------
// комментарии
//-----------------------------------------------
   
   function get_comments($news_id){
   
      if(!file_exists($this->data_dir."/".$news_id)){
            trace("file '".$news_id."' not found!");
            return NULL;
      }
            
	$file = get_file($this->data_dir."/".$news_id);
	if(count($file) == 0){
	     trace("file '".$news_id."' is empty!");
	     return NULL;
	}
	     
	$comments_part = explode($this->comment_part_sep,$file);
	if(count($comments_part) < 2){ // типа нету нифига комментариев
	     trace("no Comment Separator ([*3*]) found in file '".$news_id."'!");
           return NULL; 
      }
           
	$comments_all = explode($this->comment_sep,$comments_part[1]);
	if($comments_all[0] == ""){
	     trace("no comments was found in comment part (after CommSeparator)' in file ".$news_id."'");
	     return NULL;
	}
	
	     
	$comments_devided = array(array());
	$k=0;
	for($comm_idx=0; $comm_idx<count($comments_all); ++$comm_idx){
            $temp = explode($this->comment_info_sep,$comments_all[$comm_idx]);
            if(count($temp) != 7)
                  continue;
            $comments_devided[$k]["id"]     = $temp[0];
            $comments_devided[$k]["author"] = $temp[1];
            $comments_devided[$k]["email"]  = $temp[2];
            $comments_devided[$k]["url"]    = $temp[3];
            $comments_devided[$k]["time"]   = $temp[4];
            $comments_devided[$k]["ip"]     = $temp[5];
            $comments_devided[$k]["text"]   = $temp[6];
            ++$k;
      }
	
	return $comments_devided; 
   }

//-----------------------------------------------

  function add_comment($news_id, $new_comment){
      // проверка на наличие файла
      if(!file_exists($this->data_dir."/".$news_id)){
            return false;
      }
      
      // получить информацию о файле
      $info = $this->get_news_info($news_id);
      
      // получиаем все комментарии к этой новости
      $comments = $this->get_comments($news_id);
            
      // добавляем новый комментарий
      $comm_count = count($comments);
      $comments[$comm_count]["id"]     = $new_comment["id"];
      $comments[$comm_count]["author"] = $new_comment["author"];
      $comments[$comm_count]["email"]  = $new_comment["email"];
      $comments[$comm_count]["url"]    = $new_comment["url"];
      $comments[$comm_count]["time"]   = $new_comment["time"];
      $comments[$comm_count]["ip"]     = $new_comment["ip"];
      $comments[$comm_count]["text"]   = $new_comment["text"];
      
      
      // увеличиваем кол-во комметнариев
      $info["comc"] = count($comments);      
      
      // создаём результирующую строчку
      $result = "";
      // записываем информацию о "новости"
	$result .= $info["date"].$this->news_info_sep.
	           $info["head"].$this->news_info_sep.
	           $info["file"].$this->news_info_sep.
	           $info["text"].$this->news_info_sep.
	           $info["name"].$this->news_info_sep.
	           $info["comc"].$this->comment_part_sep;
	
	// записываем блок комментариев	
      for($comm_idx=0; $comm_idx<count($comments); ++$comm_idx){
            $result .= $comments[$comm_idx]["id"].$this->comment_info_sep.
                       $comments[$comm_idx]["author"].$this->comment_info_sep. 
                       $comments[$comm_idx]["email"].$this->comment_info_sep. 
                       $comments[$comm_idx]["url"].$this->comment_info_sep. 
                       $comments[$comm_idx]["time"].$this->comment_info_sep. 
                       $comments[$comm_idx]["ip"].$this->comment_info_sep. 
                       $comments[$comm_idx]["text"];
            if($comm_idx != count($comments)-1)
                  $result .= $this->comment_sep;
      }
      
      // записываем в файл
      $fp=fopen($this->data_dir."/".$news_id,"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$result))
            return false;      
      fclose($fp);
      return true;
  }

//-----------------------------------------------

  function del_comment($news_id, $comm_id){
      // проверка на наличие файла
      if(!file_exists($news_id))
            return false;
      
      // получить информацию о файле
      $info = $this->get_news_info($news_id);
      // получиаем все комментарии к этой новости
      $comments = $this->get_comments($news_id);
                
      // создаём результирующую строчку
      $result = "";
      // записываем информацию о "новости"
	$result .= $info["date"].$this->news_info_sep.
	           $info["head"].$this->news_info_sep.
	           $info["file"].$this->news_info_sep.
	           $info["text"].$this->news_info_sep.
	           $info["name"].$this->news_info_sep.
	           ($info["comc"]-1).$this->comment_part_sep;
	
	// записываем блок комментариев	
      for($comm_idx=0; $comm_idx<count($comments); ++$comm_idx){
            if($comments[$comm_idx]["id"] == $comm_id)
                  continue;
                  
            $result .= $comments[$comm_idx]["id"].$this->comment_info_sep.
                       $comments[$comm_idx]["author"].$this->comment_info_sep. 
                       $comments[$comm_idx]["email"].$this->comment_info_sep. 
                       $comments[$comm_idx]["url"].$this->comment_info_sep. 
                       $comments[$comm_idx]["time"].$this->comment_info_sep. 
                       $comments[$comm_idx]["ip"].$this->comment_info_sep. 
                       $comments[$comm_idx]["text"];
            if($comm_idx != count($comments)-1)
                  $result .= $this->comment_sep;
      }
      
      // записываем в файл
      $fp=fopen($this->data_dir."/".$id,"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$result))
            return false;      
      fclose($fp);    
      return true;
  }

//-----------------------------------------------

  function edit_comment($news_id, $comm_id, $comment_info){
      if(!file_exists($this->data_dir."/".$news_id))
            return false;
         
      // получиаем все комментарии к этой новости
      $comments = $this->get_comments($news_id);
      if($comments == NULL)
            return false;   
           

      // получить информацию о файле
      $info = $this->get_news_info($news_id);
                
      // создаём результирующую строчку
      $result = "";
      // записываем информацию о "новости"
	$result .= $info["date"].$this->news_info_sep.
	           $info["head"].$this->news_info_sep.
	           $info["file"].$this->news_info_sep.
	           $info["text"].$this->news_info_sep.
	           $info["name"].$this->news_info_sep.
	           $info["comc"].$this->comment_part_sep;
	
	// записываем блок комментариев	
      for($comm_idx=0; $comm_idx<count($comments); ++$comm_idx){
            if($comments[$comm_idx]["id"] == $comm_id){
                  $result .= $comment_info[$comm_idx]["id"].$this->comment_info_sep.
                             $comment_info[$comm_idx]["author"].$this->comment_info_sep. 
                             $comment_info[$comm_idx]["email"].$this->comment_info_sep. 
                             $comment_info[$comm_idx]["url"].$this->comment_info_sep. 
                             $comment_info[$comm_idx]["time"].$this->comment_info_sep. 
                             $comment_info[$comm_idx]["ip"].$this->comment_info_sep. 
                             $comment_info[$comm_idx]["text"];
                  if($comm_idx != count($comments)-1)
                        $result .= $this->comment_sep;
                  continue;        
            }
                  
            $result .= $comments[$comm_idx]["id"].$this->comment_info_sep.
                       $comments[$comm_idx]["author"].$this->comment_info_sep. 
                       $comments[$comm_idx]["email"].$this->comment_info_sep. 
                       $comments[$comm_idx]["url"].$this->comment_info_sep. 
                       $comments[$comm_idx]["time"].$this->comment_info_sep. 
                       $comments[$comm_idx]["ip"].$this->comment_info_sep. 
                       $comments[$comm_idx]["text"];
                       
            if($comm_idx != count($comments)-1)
                  $result .= $this->comment_sep;
      }
      
      // записываем в файл
      $fp=fopen($this->data_dir."/".$id,"w+");
      if(!$fp)
            return false;
      if(!fwrite($fp,$result))
            return false;      
      fclose($fp);    
      return true;

            
  }

 function GetNewsPost($id)
  {
  if(file_exists($this->files_dir."/".$id))
  OUT(get_file($this->files_dir."/".$id));
   else die("no such file: ".$this->files_dir."/".$id);
  }
  
 function GetNewsList()
 {
 return read_dir($this->data_dir);
 }


};}





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
 include "config.php";
    $news = new CNews($DIRS["news_list"],$DIRS["news_files"]);
    $news->comment_part_sep = "[*3*]";
    $news->news_info_sep    = "[*1*]";
    $news->comment_sep      = "[*2*]";
    $news->comment_info_sep = "[*4*]";    
    $news_files = $news->GetNewsList();

    if(!isset($action) || $action == "list"){
        ?>   
          <table align=center width=100% class=tbl1>
          <tr>
             <td align=center>№</td>
             <td align=center>Название</td>
             <td align=center>Автор</td>
             <td align=center>Время</td>
             <td align=center>Управление</td>
          </tr>
        <?php
        $cnt=$news->get_news_count();
        for($n_idx=$cnt-1; $n_idx>=0; --$n_idx){
           $news_info = $news->get_news_info($news_files[$n_idx]);
           // добавление списка новостей 
           ?>
              <tr>
                <td align=center><?php echo $n_idx ?></td>
                <td align=center><?php OUT("<a href=?p=$p&view=post&id=".$news_files[$n_idx].">"); echo $news_info["head"] ?></a></td>
                <td align=center><? $ud=get_user_data($news_info["name"]); OUT("<a href=?p=users&act=userinfo&id=".$news_info["name"].">".$ud["nick"]."</a>"); ?></td>
                <td align=center><?php echo norm_date($news_info["date"]) ?></td>
                <td align=center><?php 
                    echo "<a href='?p=$p&page=$page&action=edit&act=$act&id=".$news_files[$n_idx]."'>Редактировать</a><br>";
                    echo "<a href='?p=$p&page=$page&action=delete&act=$act&id=".$news_files[$n_idx]."'>Удалить</a><br>";
                ?></td>
              </tr>
           <?php 
       }
       ?> </table> <?php
         echo "<center><b><font size='+1'><a href='?p=$p&page=$page&action=add&act=$act'>Добавить новость</a></font></b></center>";
                echo "<center><b><a href='?p=$p'>Назад</a></b></center>";         
    }
    else{
       switch($action){
          //-------------------------//
          //----------ADD------------//
          //-------------------------//
          case "add":
		if(!isset($editor))$editor="html";
                  $id = get_serial();
                  // $a = 1 - значит "добавлнеие", а не "редавктирование"
                  ?>  
                  <form action="<? echo("?p=$p&page=$page&action=save&id=$id&editor=$editor&a=1&act=$act") ?>" method=post id="EditForm" enctype="multipart/form-data">
                     <table width=100%>
                        <tr><td class=tbl1 width=50%>Название:</td><td class=tbl1 width=50%><input style="width:100%" type=text name='head' value=''><br><small>Пример: Новость №1</small></td></tr>
                        <tr><td class=tbl1 width=50%>Автор:</td><td class=tbl1 width=50%><? OUT($CURRENT_USER["nick"]) ?></td></tr>
                        <tr><td class=tbl1 width=50%>Время:</td><td class=tbl1 width=50%><input style="width:100%" type=text readonly name='date' value="<? echo norm_date(time()) ?>"></td></tr>
                        <tr><td class=tbl1 width=50%>Краткое описание:</td><td class=tbl1 width=50%><input style="width:100%" type=text name='small_text' value=''></td></tr>
                     </table>
                     <table width=100%>
                        <tr><td class=tbl1 width=100%>Текст новости:</td></tr>
			<?               
            if(isset($editor) && $editor=="html")
             {
            ?>
            <tr><td>
            <?php 
            $oFCKeditor = new FCKeditor('text') ;
			$oFCKeditor->BasePath	= "js/fckeditor/" ;
			$oFCKeditor->Value		= $fulltext;
			$oFCKeditor->Height = 500;
			$oFCKeditor->Create() ;		
            ?>
            </td></tr>               
			</table>          
            <br>
            Редактор: | <b>HTML</b> | <a href="<? OUT("?p=$p&page=$page&action=$action&id=$id&a=1&act=$act&editor=txt") ?>">Обычный</a>
            <br>(<small><b>Внимание!</b> При нажатии на эти ссылки теряются все несохранённые данные!</small>)</div> 
            <div align=center><input type="submit" class="button" value="Сохранить"></div>
            <?                          
             }
             else
             {
            ?>        
			<tr><td class=tbl1 width=100%>    
            Текст:<br>
            <textarea class=inputbox name=text style="width:100%" rows=30></textarea><br>
            </td></tr></table>                  
            Редактор: | <a href="<? OUT("?p=$p&page=$page&action=$action&id=$id&a=1&act=$act&editor=html") ?>">HTML</a> | <b>Обычный</b>
            <br>(<small><b>Внимание!</b> При нажатии на эти ссылки теряются все несохранённые данные!</small>)</div> 
            <input class=inputbox type=checkbox name=nb unchecked>Переводить переход на новую строку в &lt;br&gt;? <br>  
            <input class=inputbox type=checkbox name=kt unchecked>Отключить HTML-теги ?<br>
            <input class=inputbox type=checkbox name=ml unchecked>Переводить в href URL'ы ?          
            <div align=center><input type=submit value="Сохранить" class=button></div>  
            
            </form> 
            <?
             }
                  echo "<center><b><a href='?p=$p&page=$page&act=$act'>Назад</a></b></center>";
          break;
          //-------------------------//
          //----------EDIT-----------//
          //-------------------------//
          case "edit":
          global $MDL;
                  $news_info = $news->get_news_info($id);
                  $fulltext = $news->get_news_text($id);
                  $author=$news_info["name"];
                  if(!isset($editor))$editor="html";

            if($MDL->IsModuleExists("users"))
             {            
             $MDL->Load("users");
             $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
             $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
             
             if($USR->GetUserLevel($author)<$CURRENT_USER["level"] || $author==$CURRENT_USER["id"])
               {
               $authorselect="<select name=art style=\"width:100%\">";
               $udata=$USR->GetUsers();
               for($j=0;$j<count($udata);++$j)
                 {
                 if($udata[$j]["id"]==$author)$sel=" selected"; else $sel=""; 
                 if($USR->GetUserLevel($udata[$j]["id"])<$CURRENT_USER["level"] || $udata[$j]["id"]==$CURRENT_USER["id"]) 
                   $authorselect.="<option value=\"".$udata[$j]["id"]."\"$sel>".$udata[$j]["nick"]."</option>";
                 }         
               $authorselect.="</select>";
               }
               else
               {
               $udata=$USR->GetUserData($author);               
               $authorselect=$udata["nick"];
               }
             }
             else {$authorselect="root";}      
                  ?>  
                  <form action="<? echo("?p=$p&page=$page&action=save&editor=$editor&id=$id&act=$act") ?>" method=post id="EditForm" enctype="multipart/form-data">
                     <table width=100%>
                        <tr><td class=tbl1 width=50%>Название:</td><td class=tbl1 width=50%><input style="width:100%" type=text name='head' value="<? echo $news_info["head"] ?>"><br><small>Пример: Новость №1</small></td></tr>
                        <tr><td class=tbl1 width=50%>Автор:</td><td class=tbl1 width=50%><? OUT($authorselect) ?></td></tr>
                        <tr><td class=tbl1 width=50%>Время:</td><td class=tbl1 width=50%><input style="width:100%" type=text readonly name='date' value="<? echo norm_date(time()) ?>"></td></tr>
                        <tr><td class=tbl1 width=50%>Краткое описание:</td><td class=tbl1 width=50%><input style="width:100%" type=text name='small_text' value="<? echo $news_info["text"] ?>"></td></tr>
                     </table>
                     <table width=100%>
                     
                        <tr><td class=tbl1 width=100%>Текст новости:</td></tr>
			<?               
            if(isset($editor) && $editor=="html")
             {
            	//$fulltext=$FLTR->ReverseProcessHTML($fulltext);
            ?>                        
            <tr><td>
            <?php 
            $oFCKeditor = new FCKeditor('text') ;
			$oFCKeditor->BasePath	= "js/fckeditor/" ;
			$oFCKeditor->Value		= $fulltext;
			$oFCKeditor->Height = 500;
			$oFCKeditor->Create() ;		
            ?>
            </td></tr>   
            </table>          
            <br>
            Редактор: | <b>HTML</b> | <a href="<? OUT("?p=$p&page=$page&action=$action&id=$id&a=1&act=$act&editor=txt") ?>">Обычный</a>
            <br>(<small><b>Внимание!</b> При нажатии на эти ссылки теряются все несохранённые данные!</small>)</div> 
            <div align=center><input type="submit" class="button" value="Сохранить"></div>
            <?                          
             }
             else
             {
            ?>        
			<tr><td class=tbl1 width=100%>    
            Текст:<br>
            <textarea class=inputbox name=text style="width:100%" rows=30><? OUT($fulltext) ?></textarea><br>
            </td></tr></table>                  
            Редактор: | <a href="<? OUT("?p=$p&page=$page&action=$action&id=$id&a=1&act=$act&editor=html") ?>">HTML</a> | <b>Обычный</b>
            <br>(<small><b>Внимание!</b> При нажатии на эти ссылки теряются все несохранённые данные!</small>)</div> 
            <input class=inputbox type=checkbox name=nb unchecked>Переводить переход на новую строку в &lt;br&gt;? <br>  
            <input class=inputbox type=checkbox name=kt unchecked>Отключить HTML-теги ?<br>
            <input class=inputbox type=checkbox name=ml unchecked>Переводить в href URL'ы ?         
            <div align=center><input type=submit value="Сохранить" class=button></div>  
            
            </form> 
            <?
             }        
                  echo "<center><b><a href='?p=$p&page=$page&act=$act'>Назад</a></b></center>";            

          break;
          //-------------------------//
          //---------DELETE----------//
          //-------------------------//
          case "delete":
             $action="list";
             $del_res = $news->del_news($id);
             if($del_res)
                echo "<center><b><font size='+2'>Новость удалена</font></b></center>";
             else
                echo "<center><b><font size='+2'>Новость не удалена</font></b></center>";
             
             echo "<br><center><b><a href='?p=$p&page=$page&action=$action&act=$act'>Назад</a></b></center>";
          break;
          //-------------------------//
          //---------SAVE----------//
          //-------------------------//
          case "save":
             $action = "list";
             if($a == 1){
                  $a=0;
                  $news_info = array();

                  $news_info["head"] = $FLTR->CutString($FLTR->ExtractSmallText($FLTR->DirectProcessString($head),125),"title");
                  $news_info["date"] = $FLTR->DirectProcessString($date);
                  $news_info["name"] = $CURRENT_USER["id"];
                  if(!$small_text)
                  {
                  	$small_text = $FLTR->ExtractSmallText(strip_tags($text))."...";
                  }                  
                  $news_info["text"] = $FLTR->DirectProcessString($small_text,0);
                  $news_info["file"] = $id."html";
                  if(isset($editor) && $editor=="txt") 
                    $news_info["fulltext"] = $FLTR->DirectProcessText($text,$nb,$kt,$ml);
                  else
                    $news_info["fulltext"] = $FLTR->DirectProcessHTML($text);
                                  
                  $add_res = $news->add_news($id,$news_info);
                  if($add_res)
                        echo "<center><b>Новость добавлена</b></center>";
                  else
                        echo "<center><b>При добавлении новости произошла ошибка</b></center>";
                  echo "<center><b><a href='?p=$p&page=$page&action=$action&act=$act'>Назад</a></b></center>";
             }else{
                  $news_info = $news->get_news_info($id);
                  $news_info["head"] = $FLTR->DirectProcessString($head);
                  //$news_info["date"] = $date;
                  $news_info["name"] = $FLTR->DirectProcessString($art);
                  if(!$small_text)
                  {
                  	$small_text = $FLTR->ExtractSmallText(strip_tags($text))."...";
                  }                      
                  $news_info["text"] = $FLTR->DirectProcessString($small_text,0);  
                  if(isset($editor) && $editor=="txt") 
                    $news_info["fulltext"] = $FLTR->DirectProcessText($text,$nb,$kt,$ml);
                  else
                    $news_info["fulltext"] = $FLTR->DirectProcessHTML($text);

                  $news->edit_news($id,$news_info);
                  echo "<center><b><a href='?p=$p&page=$page&id=$id&action=$action&act=$act'>Исправления приняты</a></b></center>";
            }             
          break;
       }
    }
}
elseif($_MODULE)
if($_NOTBAR)
  if(!file_exists(SK_DIR."/news.php"))
    {
    include "config.php";
    global $MDL,$DIRS;
    if(isset($act) && $act=="admin"){$MDL->LoadAdminPage("news");return;}    
    if(!isset($page) || $page == "list")
      {

 	if(!isset($page))$page=1;

      $news = new CNews($DIRS["news_list"],$DIRS["news_files"]);
      $news->set_max_news(2);
      $news->comment_part_sep = "[*3*]";
      $news->news_info_sep    = "[*1*]";
      $news->comment_sep      = "[*2*]";
      $news->comment_info_sep = "[*4*]";

      global $add;
 
      $form=true;
      if(!isset($text))$text="";
      if(!isset($name))$name="";
      if(!isset($email))$email="";
      if(!isset($url))$url="";

	if($GV["news_comments"])
      {
      $error="";
      if(isset($add) && $add==1){
       $error="";
       $form=false;
       $add=0;
       $new_comment = array();
       $new_comment["id"]=1;
       $new_comment["author"]=(check_auth)?$CURRENT_USER["id"]:$FLTR->DirectProcessString($name);
       $new_comment["email"]=(check_auth)?$CURRENT_USER["email"]:$FLTR->DirectProcessString($email);
       $new_comment["url"]=(check_auth)?$CURRENT_USER["url"]:$FLTR->DirectProcessString($url);
       $new_comment["time"]=time();
       $new_comment["ip"]=get_ip_address();
       $new_comment["text"]=$FLTR->DirectProcessText($text);
       
       
       if($GV["news_captcha"])
       {
       if($_POST['img_code']!=$_SESSION['IMG'])
       		$error.="<br><b>Ошибка:</b> Код картинки не совпадает с введённым!<br>";
       }
       
       
       if(!$text)$error.="<br><b>Ошибка:</b> Необходимо ввести текст!<br>";
       if(!$name)$error.="<br><b>Ошибка:</b> Необходимо ввести ваш ник!<br>";

       if(!$error)$news->add_comment($id,$new_comment);
       } 
      }

      if(!isset($view) || $view=="list"){

   global $DIRS;
       $news = new CNews($DIRS["news_list"],$DIRS["news_files"]);
      $news->comment_part_sep = "[*3*]";
      $news->news_info_sep    = "[*1*]";
      $news->comment_sep      = "[*2*]";
      $news->comment_info_sep = "[*4*]";  
  $news->set_max_news(5);
  ?><br>Страница: <?php
  for($i=0;$i<$news->get_pages_count();++$i){
	if($i+1!=$page)echo "<a href='?p=$p&page=".($i+1)."'>";
        echo($i+1);
        if($i+1!=$page)echo("</a>");
	if($i != $news->get_pages_count()-1)
		echo " | ";
  }  
	$news_list = $news->get_news_on_page($page);  
	for($i=0;$i<count($news_list);$i++){
                $ud=get_user_data($news_list[$i]["name"]);   
                ?><br>
                <table width=100%><tr><td>
                <table width=100%><td class=tdnewsheader><a href="?p=users&act=userinfo&id=<? OUT ($news_list[$i]["name"]) ?>"><? OUT($ud["nick"]) ?></a>, <b><? OUT(norm_date($news_list[$i]["date"])) ?></b>, <? OUT($news_list[$i]["head"]) ?></td><td></td></table>
                </td></tr><tr><td class=tdnewspost>
                <? OUT($news_list[$i]["text"]) ?>
                <br>
                <small>[<a href="?p=news&view=post&id=<? OUT($news_list[$i]["id"]) ?>">читать целиком</a>] <?php if($GV["news_comments"]){?>Комментариев: (<? OUT($news_list[$i]["comc"]) ?>)<?php }?></small>
                </td>
                </table> <?php }
    if(!count($news_list))OUT("<div align=center>no news</div>");


   ?><br>Страница: <?php
   for($i=0;$i<$news->get_pages_count();++$i){
	if($i+1!=$page)echo "<a href='?p=$p&page=".($i+1)."'>";
        echo($i+1);
        if($i+1!=$page)echo("</a>");
	if($i != $news->get_pages_count()-1)
		echo " | ";
  }          
          
       }else{
          $news_info = $news->get_news_info($id);
          ?>
          <table width=85% align=center><td> <?
          OUT($news->GetNewsPost($news_info["file"]));
          ?></td></table><?
	if($GV["news_comments"]){
          echo "<br><br><div align=center><b><u>Комментарии:</u></b><br><br>";

          $comments = $news->get_comments($id);
          if($comments!=NULL){
                for($comm_idx=0; $comm_idx<count($comments); ++$comm_idx){
                      $comment = $comments[$comm_idx];
                      ?>
                      <table align=center width=100% class=tbl1>
                       <tr><td>
                      <?php 
                      if(is_user_exists($comment["author"]))
                        {
                        $ud=get_user_data($comment["author"]);
                        ?>                                                                                      
                        Автор: <a href="?p=users&act=userinfo&id=<? OUT($comment["author"]) ?>"><? OUT($ud["nick"]) ?></a>
                        <?
                        }
                      else{
                      	$ud = get_user_data($comment["author"]);
                      	if($ud["id"] == "!GUEST!")
                      		echo "Автор: ".$ud["nick"];
                      	else
                        	echo "Автор: ".$comment["author"]; 
                      }
                        ?>
                      </td><td>
                      <?php echo "Дата: ".norm_date($comment["time"]) ?>
                      </td></tr>
                      <tr><td>
                      <?php echo "E-mail: ".make_email_str($comment["email"]) ?>
                      </td><td>
                       <?php echo "URL: ".make_url_str($comment["url"]) ?>
                       </td></tr>
                       <tr>
                       <td colspan=2 class=tbl1> <?php echo $comment["text"]?> </td>
                       </tr>
                       </table>
                       <br>
                       <?
                      }
                }
                else
                {
                OUT("<div align=center><b>НЕТ КОММЕНТАРИЕВ</b></div>");
                }

         if($error)OUT($error);      
         ?>
         <table align=center style="border: 0px" width=50%><td>
         <form name="comment_form" 
            <?php
               if(!$page)$page='list';
                  echo "action='?p=$p&view=post&id=$id'";
               ?>
            method="post">      
            <?php 
            if($GV["news_captcha"])
            {
            ?>
            Код картинки:<input type="text" name="img_text"/><img src="captcha.php"><br>              	
            <?php 
            }                       
             ?>            
            Имя: <br>
            <input type="text" name="name" class=inputbox style="width:50%;" <? if(check_auth())OUT("value=\"".$CURRENT_USER["nick"]."\" readonly");else OUT($name); ?>><br>
            E-mail: <br>
            <input type="text" name="email" class=inputbox style="width:50%;" <? if(check_auth())OUT("value=\"".$CURRENT_USER["email"]."\" readonly");else OUT($email); ?>><br>
            Url: <br>
            <input type="text" name="url" class=inputbox style="width:50%;" <? if(check_auth())OUT("value=\"".$CURRENT_USER["url"]."\" readonly");else OUT($url); ?>><br>
            Комментарий: <br>
            <textarea rows=5 class=inputbox style="width:90%;" name="text"><? OUT($text) ?></textarea><br>
            <br>
            <input type="hidden" name="add" value=1>
            <input type="submit" class=button value="Отправить">
         </form>
        </td></table>   
        <?php 
        
        			} // eof if(news_comments)
        ?>
        
       <br><div align=center><a href="<? OUT("?p=$p") ?>">Назад</a></div>        
       <?      
       }
      } 
      if(check_auth() && $CURRENT_USER["level"]>=5)
      {
      ?>
      <div align=center>
      <a href="?p=<? OUT($p) ?>&act=admin">Администрирование</a>
      </div>
      <?
       }         
       ?>

       <?
    }
  else
    include(SK_DIR."/news.php");
else
  if(!file_exists(SK_DIR."/newsbar.php"))
   {      
   global $DIRS;
     $news = new CNews($DIRS["news_list"],$DIRS["news_files"]);
      $news->comment_part_sep = "[*3*]";
      $news->news_info_sep    = "[*1*]";
      $news->comment_sep      = "[*2*]";
      $news->comment_info_sep = "[*4*]";  
      $news->set_max_news(5); 
      $news_list = $news->get_news_on_page(1);
      if(!count($news_list))echo("<div align=center>нет новостей</div>");
	for($i=0;$i<count($news_list);$i++){
               $ud=get_user_data($news_list[$i]["name"]);  	 
                ?>
                <div align=center><a tyle="font-size:8.5px;" href="?p=users&act=userinfo&id=<? OUT ($news_list[$i]["name"]) ?>"><? OUT($ud["nick"]) ?></a>: "<a style="font-size:9px;" href="?p=news&view=post&id=<? OUT($news_list[$i]["id"]) ?>"><b><? OUT($news_list[$i]["head"]) ?></b></a>"</div>
                <? OUT($news_list[$i]["text"]) ?><br>(<a style="font-size:9px;" href="?p=news&view=post&id=<? OUT($news_list[$i]["id"]) ?>">читать целиком</a>)<br>                
                <font color=gray style="font-size:8.5px">/<? OUT(norm_date($news_list[$i]["date"])) ?></font><br>
                <? }
                          
   }
  else
    include(SK_DIR."/newsbar.php");
}
?>