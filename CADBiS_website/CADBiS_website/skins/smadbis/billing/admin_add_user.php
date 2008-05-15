<?
if($BILLEVEL<3) return;

$form=1;
if(isset($mod)  && $mod=="add") 
 {
	
 if($gender!=0)$gender=1;
  $error="";
  $perror="";
  for($i=0;$i<count($vars);++$i)
   {
   if($i!=15 && $i!=16)
   $vars[$i]=$FLTR->DirectProcessString($vars[$i],1);    
   //$vars[$i]=addslashes($vars[$i]);
   }

   $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 if($vars[1]!=$vars[2])$perror.="<br><b>Ошибка!</b> Введённые пароли не совпадают!!!<br>";
  if(isset($mode) && $mode=="edit")
   $user=$BILL->GetUserData($uid);
  else
   {
   $user=array();
   $user['gender']     = $gender;
   $user['add_uid']    = $CURRENT_USER["id"];
   $user['address']    = "";
   $user['icq']        = "";
   $user['rang']       = "";
   $user['city']       = "";
   $user['country']    = "";
   $user['raiting']    = 0;
   $user['signature']  = "";
   $user['info']       = "";
   $user['expired']    = "0000-00-00";
   $user['password']   = $vars[1];  
   }
 $user['user']       = $vars[0];
 //$user['password']   = $vars[1];
 $user['gid']        = $vars[3];
 $user['max_total_traffic'] 	= addslashes($vars[4]);
 $user['max_month_traffic'] 	= addslashes($vars[5]);
 $user['max_week_traffic'] 		= addslashes($vars[6]);
 $user['max_day_traffic'] 		= addslashes($vars[7]);
 $user['simultaneouse_use'] 	= addslashes($vars[8]);
 
 $user['fio']        = addslashes($vars[9]);
 $user['email']      = addslashes($vars[10]);
 $user['phone']      = addslashes($vars[11]);
 /*
  *  Array
(
    [0] =&gt; login
    [1] =&gt; passwd
    [2] =&gt; passwd2
    [3] =&gt; 6
    [4] =&gt; totallim
    [5] =&gt; monthlim
    [6] =&gt; weeklim
    [7] =&gt; daylim
    [8] =&gt; fio
    [9] =&gt; email
    [10] =&gt; telephone
    [11] =&gt; prim
    [12] =&gt; admins
)
  */
if(isset($mode) && $mode=="edit")
 {
 if($vars[18]!=0 && $vars[18]!="0")
  $vars[18]=1;
 $user['nick']       = addslashes($vars[12]);
 $user['rang']       = addslashes($vars[13]);
 $user['country']    = addslashes($vars[14]);
 $user['city']       = addslashes($vars[15]);    
 $user['address']    = addslashes($vars[16]);
 $user['url']        = addslashes($vars[17]);
 $user['icq']        = addslashes($vars[18]);
 $user['blocked']    = (int)$vars[19];
 $user['signature']  = addslashes($FLTR->DirectProcessText($vars[20],1,1));
 $user['info']      = addslashes($FLTR->DirectProcessText($vars[21],1,1));
 $user['prim']      = $vars[22];    
 $user['group']      = $vars[23];    
 $user['gender']     = $gender;
 }
 else
 {
 $user['prim']       = addslashes($vars[12]);
 $user['nick']       = addslashes($vars[0]);
 $user['group']      = addslashes($vars[13]);
 }  
  global $DIRS;
 
 if(!$user['user'])$error.="<br><b>Ошибка!</b> Пустой логин недопустим!!!<br>";
 if(!$vars[1])$perror.="<br><b>Ошибка!</b> Пустой пароль недопустим!!!<br>";
 
 if(!is_numeric($user['max_total_traffic']) ||
 !is_numeric($user['max_month_traffic']) ||
 !is_numeric($user['max_week_traffic']) ||
 !is_numeric($user['max_day_traffic']))
 	 $perror.="<br><b>Ошибка!</b> Лимиты трафика должны быть целыми числами!!!<br>";
 
 
 if(isset($avdelete) && $avdelete=="true")
   {
   if(file_exists($DIRS['users_avatars']."/".$uid))unlink($DIRS['users_avatars']."/".$uid);
   }
 if(isset($avatar) && $avatar["tmp_name"]!="")
    {
     global $GV_USERS;
       $max_av_width=$GV_USERS["max_av_width"];
       $max_av_height=$GV_USERS["max_av_height"];
       $max_av_size=$GV_USERS["max_av_size"];;
       $upl_tmpname=$avatar["tmp_name"];
       list($width, $height, $type, $attr) = getimagesize($upl_tmpname);
       if ($type != IMAGETYPE_GIF && $type != IMAGETYPE_JPEG)
         $error.="<br><b>Ошибка:</b> Картинка должна быть корректным GIF или JPEG изображением! <br>";
       if(!$error && (filesize($upl_tmpname)<$max_av_size*1024)&& (($width<=$max_av_width)&&($height<=$max_av_height)))
      	{                   	
        if(file_exists($DIRS['users_avatars']."/".$uid))
        	unlink($DIRS['users_avatars']."/".$uid);
	if(!copy($upl_tmpname,$DIRS['users_avatars']."/".$uid))
	  $error="<br><b>Ошибка:</b> Не удалось скопировать файл аватары.<br>";
        }
        elseif(!$error) $error.="<br><b>Ошибка:</b> Размер картинки не должен превышать ".$max_av_width."x".$max_av_height." пкс, а объём $max_av_size Kb<br>";
    }
 
 
 if(!$perror)$user['password']=$vars[1];
 if(!is_group_allowed($user['group'],$user['uid']))$error.="<br><b>Ошибка!</b> Данная группа администрирования не существует или недоступна!<br>";
 if(!is_gid_allowed($user['gid'],$BILLEVEL))$error.="<br><b>Ошибка!</b> Данный тариф вам недоступен!<br>";
 if(!$user['fio'])$error.="<br><b>Ошибка!</b> Поле ФИО обязательно для заполнения!<br>";
  
if((!isset($mode) || $mode!="edit") && $perror)$error.=$perror;

 if(!$error)
  {  
   if(!isset($mode) || $mode!="edit")
    { 
    	

   $res=$BILL->AddUser($user);
    if(!$res){
     ?>
     <b>ОК!</b> Пользователь '<? OUT($user['user']) ?>' успешно добавлен!<br> Вот его данные:<br>
     <table width=100%>
     <tr><td>Логин:<td></td><td><? OUT($user['user']) ?></td></tr>
     <tr><td>Пароль:<td></td><td>***</td></tr>
     <tr><td>ФИО:<td></td><td><? OUT($user['fio']) ?></td></tr>
     <tr><td>E-mail:<td></td><td><? OUT($user['email']) ?></td></tr>
     <tr><td>Телефон:<td></td><td><? OUT($user['phone']) ?></td></tr>
     <tr><td>Примечание:<td></td><td><? OUT($user['prim']) ?></td></tr>
     <tr><td>Пол:<td></td><td><? OUT(make_gender_str($user['gender'])) ?></td></tr>
     <tr><td>Дата истечения срока действия:<td></td><td>никогда</td></tr>    
     </table> 
     <br>
     Добавил администратор <? OUT($CURRENT_USER["nick"]) ?>.
    <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>      
      <?
       $form=0;}
     else {$form=true;echo($res);}
     }
     elseif(isset($mode) && $mode=="edit")
     {
     $res=$BILL->UpdateUser($uid,$user);
     if(!$res){
     ?>
     <b>ОК!</b> Пользователь '<? OUT($user['user']) ?>' успешно изменён!<br> Вот его данные:<br>
     <table width=100%>
     <tr><td>Логин:<td></td><td><? OUT($user['user']) ?></td></tr>
     <tr><td>Пароль:<td></td><td><? OUT(($perror)?"пароль не изменён":"***") ?></td></tr>
     <tr><td>ФИО:<td></td><td><? OUT($user['fio']) ?></td></tr>
     <tr><td>E-mail:<td></td><td><? OUT($user['email']) ?></td></tr>
     <tr><td>Телефон:<td></td><td><? OUT($user['phone']) ?></td></tr>
     <tr><td>Адрес:<td></td><td><? OUT($user['address']) ?></td></tr>
     <tr><td>Страна:<td></td><td><? OUT($user['country']) ?></td></tr>
     <tr><td>Город:<td></td><td><? OUT($user['city']) ?></td></tr>
     <tr><td>URL:<td></td><td><? OUT($user['url']) ?></td></tr>
     <tr><td>ICQ:<td></td><td><? OUT($user['icq']) ?></td></tr></table>
     <table  width=100%><tr><td>Подпись:<td></td></tr><tr><td><? OUT($user['signature']) ?></td></tr></table> 
     <table  width=100%><tr><td>Доп. инфо:<td></td></tr><tr><td><? OUT($user['info']) ?></td></tr></table>
     <table  width=100%>
     <tr><td>Примечание:<td></td><td><? OUT($user['prim']) ?></td></tr>
     <tr><td>Пол:<td></td><td><? OUT(make_gender_str($user['gender'])) ?></td></tr>
     <tr><td>Дата истечения срока действия:<td></td><td>никогда</td></tr>
     <? if(isset($avdelete) && $avdelete=="true") {?>
     <tr><td>Аватар:<td></td><td>удалён</td></tr><? } ?>    

     <? if(isset($avatar) && $avatar["tmp_name"]!="") {?>
     <tr><td>Аватар:<td></td><td><img src="<? OUT($DIRS["users_avatars"]."/".$uid) ?>" border=0></td></tr><? } ?>    
     </table> 
     <br>
     Изменил администратор <? OUT($CURRENT_USER["nick"]) ?>.
    <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>   
      <?
       $form=0;}
     else {$form=true;echo($res);}       
     }
    }    
   else
   {
   echo($error);
   $form=1;
   }  
 }

if($form)
 {
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 if(!isset($mod) && isset($mode) && $mode=="edit" && isset($uid))
  {
  $vars=array();
  $user=$BILL->GetUserData($uid);
  $k=0;
  $vars[$k++]=$user['user'];
  $k+=2;
  //$vars[1]=$user['password'];
  //$vars[2]=$user['password'];
  $vars[$k++]=$user['gid'];
  $vars[$k++]=$user['max_total_traffic'];
  $vars[$k++]=$user['max_month_traffic'];
  $vars[$k++]=$user['max_week_traffic'];
  $vars[$k++]=$user['max_day_traffic'];
  $vars[$k++]=$user['simultaneouse_use'];  
  $vars[$k++]=$user['fio'];
  $vars[$k++]=$user['email'];
  $vars[$k++]=$user['phone'];
  $vars[$k++]=$user['nick'];
  $gender=$user['gender'];
  $vars[$k++]=$user['rang'];
  $vars[$k++]=$user['country'];
  $vars[$k++]=$user['city'];
  $vars[$k++]=$user['address'];
  $vars[$k++]=$user['url'];
  $vars[$k++]=$user['icq'];
  $vars[$k++]=$user['blocked'];
  $vars[$k++]=$FLTR->ReverseProcessText($user['signature']);
  $vars[$k++]=$FLTR->ReverseProcessText($user['info']);
  $vars[$k++]=$user['prim'];   
  $vars[$k++]=$user['group'];
  $gr=$user['group'];
  $tr=$user['gid'];
  if($user['blocked']==0){$actc="checked";$blockedc="";}else{$blockedc="checked";$actc="";}
  $blockedsel="<input type=radio value=0 name=vars[] $actc>да</input><input type=radio name=vars[] value=1 $blockedc>нет</input>";
  }
  else
  {$gr=$vars[12];$tr=$vars[3];}
  
 if(!isset($vars))$vars=array();
 if(!isset($gender))$gender=1;
 $tarlist=$BILL->GetTarifs();
 //$tarlist=array(array("id"=>"0","title"=>"Без доступа в интернет"),array("id"=>"1","title"=>"Пользовательский"));
 $tarselect="<select name=vars[] style=\"width:100%;\" class=inputbox><option value=\"0\" style=\"background-color:#DD9999\">Без доступа в интернет</option>";
 for($i=0;$i<count($tarlist);++$i)
  {
  if($tr==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
  $tarselect.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
  }
 $tarselect.="</select>";
 
 global $MDL;
 $MDL->Load("users");
 $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
 $USR->SetSeparators($GV["sep1"],$GV["sep2"]);
 $groupssel="<select name=vars[] style=\"width:100%;\" class=inputbox>";
 $glist=$USR->GetGroups();
  for($i=0;$i<count($glist);++$i)
   {
    if($glist[$i]["level"]<=$CURRENT_USER["level"])
     {
     if($gr==$glist[$i]["id"])$sel=" selected";else $sel="";
     $groupssel.="<option value=\"".$glist[$i]["id"]."\"$sel>".$glist[$i]["name"]."</option>";
     }   
   }
  $groupssel.="</select>";
 
  if($gender==0){$womanch="checked";$manch="";}else{$manch="checked";$womanch="";}
  $gendersel="<input type=radio value=0 name=gender $womanch>женский</input><input type=radio name=gender value=1 $manch>мужской</input>";
 
 $k=0;
 
 if(!isset($mode) || $mode!="edit")
  OUT("<div align=center><b>Добавление нового пользователя:</b></div>");
 else OUT(" <div align=center><b>Редактирование пользователя:</b></div>");
 ?>

 <form action="<? OUT("?p=$p&act=$act&action=$action&mod=add&mode=$mode&uid=$uid") ?>" method=post enctype="multipart/form-data" >
  <table width=100% class=tbl1>
  <tr>
    <td width=40%>Логин*</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Пароль*</td><td width=60%><input type=password class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Подтверждение*</td><td width=60%><input type=password class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Тариф*</td><td width=60%><? OUT($tarselect) ?></td>
  </tr>
  <tr>
    <td width=40%>Общий лимит трафика</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? ++$k; OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Месячный лимит трафика</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>  
  <tr>
    <td width=40%>Недельный лимит трафика</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>  
  <tr>
    <td width=40%>Дневной лимит трафика</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Подключений под 1 логином</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>  
    
  <tr>
    <td width=40%>ФИО*</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>E-mail</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Телефон</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <? if(isset($mode) && $mode=="edit")
  { 
  ?>
		  <tr>
		    <td width=40%>Ник</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>  
		  <tr>
		    <td width=40%>Ранг</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>        
		  <tr>
		    <td width=40%>Страна</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>    
		  <tr>
		    <td width=40%>Город</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>    
		  <tr>
		    <td width=40%>Адрес</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>  
		  <tr>
		    <td width=40%>URL</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>    
		  <tr>
		    <td width=40%>ICQ</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
		  </tr>    
		  <tr>
		    <td width=40%>Активен</td><td width=60%><? OUT($blockedsel); $k++;?></td>
		  </tr> </table>
		  <table class=tbl1 width=100%> 
		  <tr>
		    <td width=40%>Подпись:</td></tr>
		  <tr>
		    <td width=40%><Textarea name=vars[] style="width:100%" rows=4><? OUT($vars[$k++]) ?></textarea></td>
		  </tr>
		  <tr>
		    <td width=40%>Дополнительно:</td></tr>   
		  <tr>
		    <td width=40%><Textarea name=vars[] style="width:100%" rows=4><? OUT($vars[$k++]) ?></textarea></td>
		  </tr> 
		  </table>
		  <table width=100% class=tbl1>      
  <? } ?>
  <tr>
    <td width=40%>Примечание</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Пол</td><td width=60%><? OUT($gendersel) ?></td>
  </tr>
  <tr>
    <td width=40%>Группа администрирования сайта*</td><td width=60%><? OUT($groupssel) ?></td>
  </tr>
  <? if(isset($mode) && $mode=="edit")
  { 
  ?>  
  <tr>
    <td width=40%>Аватар:</td><td width=60% align=center>
          <? if(file_exists($DIRS["users_avatars"]."/".$user['uid'])){ ?>
           <img src="<? OUT($DIRS["users_avatars"]."/".$user['uid']) ?>" border=0><br>
           <input type=checkbox name=avdelete value="true">Удалить аватар <? } ?> <br>
           <input style="width:100%" name=avatar type=file class="button" accept="image/gif">
           
                                  
    </td>
  </tr>  
   <? 
	}
  ?>
  </table> <br>
  <div align=center><small>* - Поля отмеченные звёздочкой обязательны для заполнения</small></div><br>
  <div align=center><input type=submit class=button value="Сохранить"></div> 
 </form><br>
 <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
 
 <? 
 }

?>