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
   $user[gender]     = $gender;
   $user[add_uid]    = $CURRENT_USER["id"];
   $user[address]    = "";
   $user[icq]        = "";
   $user[rang]       = "";
   $user[city]       = "";
   $user[country]    = "";
   $user[raiting]    = 0;
   $user[signature]  = "";
   $user[info]       = "";
   $user[expired]    = "0000-00-00";
   $user[password]   = $vars[1];  
   }
 $user[user]       = $vars[0];
 //$user[password]   = $vars[1];
 $user[gid]        = $vars[3];
 $user[fio]        = addslashes($vars[4]);
 $user[email]      = addslashes($vars[5]);
 $user[phone]      = addslashes($vars[6]);
 
if(isset($mode) && $mode=="edit")
 {
 if($vars[13]!=0 && $vars[13]!="0")$vars[13]=1;
 $user[nick]       = addslashes($vars[7]);
 $user[rang]       = addslashes($vars[8]);
 $user[address]    = addslashes($vars[11]);
 $user[country]    = addslashes($vars[9]);
 $user[city]       = addslashes($vars[10]);    
 $user[url]        = addslashes($vars[12]);
 $user[icq]        = addslashes($vars[13]);
 $user[blocked]    = (int)$vars[14];
 $user[signature]  = addslashes($FLTR->DirectProcessText($vars[15],1,1));
 $user[info]      = addslashes($FLTR->DirectProcessText($vars[16],1,1));
 $user[prim]      = $vars[17];    
 $user[group]      = $vars[18];    
 $user[gender]     = $gender;
 }
 else
 {
 $user[prim]       = addslashes($vars[7]);
 $user[nick]       = addslashes($vars[0]);
 $user[group]      = addslashes($vars[8]);
 }
 global $DIRS;
 
 if(!$user[user])$error.="<br><b>Ошибка!</b> Пустой логин недопустим!!!<br>";
 if(!$vars[1])$perror.="<br><b>Ошибка!</b> Пустой пароль недопустим!!!<br>";
 
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
        if(file_exists($DIRS['users_avatars']."/".$uid))unlink($DIRS['users_avatars']."/".$uid);
	if(!copy($upl_tmpname,$DIRS['users_avatars']."/".$uid))
	  $error="<br><b>Ошибка:</b> Не удалось скопировать файл аватары.<br>";
        }
        elseif(!$error) $error.="<br><b>Ошибка:</b> Размер картинки не должен превышать ".$max_av_width."x".$max_av_height." пкс, а объём $max_av_size Kb<br>";
    }
 
 
 if(!$perror)$user[password]=$vars[1];
 if(!is_group_allowed($user[group],$user[uid]))$error.="<br><b>Ошибка!</b> Данная группа администрирования не существует или недоступна!<br>";
 if(!is_gid_allowed($user[gid],$BILLEVEL))$error.="<br><b>Ошибка!</b> Данный тариф вам недоступен!<br>";
 
  
if((!isset($mode) || $mode!="edit") && $perror)$error.=$perror;

 if(!$error)
  {  
   if(!isset($mode) || $mode!="edit")
    { 
   $res=$BILL->AddUser($user);
    if(!$res){
     ?>
     <b>ОК!</b> Пользователь '<? OUT($user[user]) ?>' успешно добавлен!<br> Вот его данные:<br>
     <table width=100%>
     <tr><td>Логин:<td></td><td><? OUT($user[user]) ?></td></tr>
     <tr><td>Пароль:<td></td><td>***</td></tr>
     <tr><td>ФИО:<td></td><td><? OUT($user[fio]) ?></td></tr>
     <tr><td>E-mail:<td></td><td><? OUT($user[email]) ?></td></tr>
     <tr><td>Телефон:<td></td><td><? OUT($user[phone]) ?></td></tr>
     <tr><td>Примечание:<td></td><td><? OUT($user[prim]) ?></td></tr>
     <tr><td>Пол:<td></td><td><? OUT(make_gender_str($user[gender])) ?></td></tr>
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
     <b>ОК!</b> Пользователь '<? OUT($user[user]) ?>' успешно изменён!<br> Вот его данные:<br>
     <table width=100%>
     <tr><td>Логин:<td></td><td><? OUT($user[user]) ?></td></tr>
     <tr><td>Пароль:<td></td><td><? OUT(($perror)?"пароль не изменён":"***") ?></td></tr>
     <tr><td>ФИО:<td></td><td><? OUT($user[fio]) ?></td></tr>
     <tr><td>E-mail:<td></td><td><? OUT($user[email]) ?></td></tr>
     <tr><td>Телефон:<td></td><td><? OUT($user[phone]) ?></td></tr>
     <tr><td>Адрес:<td></td><td><? OUT($user[address]) ?></td></tr>
     <tr><td>Страна:<td></td><td><? OUT($user[country]) ?></td></tr>
     <tr><td>Город:<td></td><td><? OUT($user[city]) ?></td></tr>
     <tr><td>URL:<td></td><td><? OUT($user[url]) ?></td></tr>
     <tr><td>ICQ:<td></td><td><? OUT($user[icq]) ?></td></tr></table>
     <table  width=100%><tr><td>Подпись:<td></td></tr><tr><td><? OUT($user[signature]) ?></td></tr></table> 
     <table  width=100%><tr><td>Доп. инфо:<td></td></tr><tr><td><? OUT($user[info]) ?></td></tr></table>
     <table  width=100%>
     <tr><td>Примечание:<td></td><td><? OUT($user[prim]) ?></td></tr>
     <tr><td>Пол:<td></td><td><? OUT(make_gender_str($user[gender])) ?></td></tr>
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
  $vars[0]=$user[user];
  //$vars[1]=$user[password];
  //$vars[2]=$user[password];
  $vars[3]=$user[gid];
  $vars[4]=$user[fio];
  $vars[5]=$user[email];
  $vars[6]=$user[phone];
  $vars[7]=$user[nick];
  $gender=$user[gender];
  $vars[8]=$user[rang];
  $vars[11]=$user[address];
  $vars[9]=$user[country];
  $vars[10]=$user[city];
  $vars[12]=$user[url];
  $vars[13]=$user[icq];
  $vars[14]=$user[blocked];
  $vars[15]=$FLTR->ReverseProcessText($user[signature]);
  $vars[16]=$FLTR->ReverseProcessText($user[info]);
  $vars[17]=$user[prim];   
  $vars[18]=$user[group];
  $gr=$vars[18];
  $tr=$user[gid];
  if($vars[14]==0){$actc="checked";$blockedc="";}else{$blockedc="checked";$actc="";}
  $blockedsel="<input type=radio value=0 name=vars[] $actc>да</input><input type=radio name=vars[] value=1 $blockedc>нет</input>";
  }
  else
  {$gr=$vars[8];$tr=$vars[3];}
  
  
 
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
    <td width=40%>ФИО</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? ++$k; OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>E-mail</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=40%>Телефон</td><td width=60%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <? if(isset($mode) && $mode=="edit"){ ?>
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
  <tr>
    <td width=40%>Аватар:</td><td width=60% align=center>
          <? if(file_exists($DIRS["users_avatars"]."/".$user['uid'])){ ?>
           <img src="<? OUT($DIRS["users_avatars"]."/".$user['uid']) ?>" border=0><br>
           <input type=checkbox name=avdelete value="true">Удалить аватар <? } ?> <br>
           <input style="width:100%" name=avatar type=file class="button" accept="image/gif">
           
                                  
    </td>
  </tr>  

  </table> <br>
  <div align=center><small>* - Поля отмеченные звёздочкой обязательны для заполнения</small></div><br>
  <div align=center><input type=submit class=button value="Сохранить"></div> 
 </form><br>
 <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
 
 <? 
 }

?>