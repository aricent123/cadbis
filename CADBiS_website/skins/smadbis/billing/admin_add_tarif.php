<?
if($BILLEVEL<4) return;

$form=1;

if(isset($mod)  && $mod=="add" && !isset($addtime) && !isset($deltimes)) 
 {
 $error="";
 $logintime="";
 for($i=0;$i<count($times_d);++$i)
  {
  $end=($i<count($times_d)-1)?",":"";
  $logintime.=$times_d[$i].addlzeroes($times_hf[$i]).addlzeroes($times_mf[$i])."-".addlzeroes($times_ht[$i]).addlzeroes($times_mt[$i]).$end;
  }

 //if($vars[1]!=$vars[2])$error.="<br><b>Ошибка!</b> Введённые пароли не совпадают!!!<br>";
 $data=array();
 $data['packet']                 = addslashes($vars[0]);
 $data['blocked']                = "".(!$activated);
 $data['total_time_limit']       = timeinsec($time_vals[0],$time_vals[1],$time_vals[2]);
 $data['month_time_limit']       = timeinsec($time_vals[3],$time_vals[4],$time_vals[5]);
 $data['week_time_limit']        = timeinsec($time_vals[6],$time_vals[7],$time_vals[8]);
 $data['day_time_limit']         = timeinsec($time_vals[9],$time_vals[10],$time_vals[11]);
 $data['total_traffic_limit']    = mb2bytes($vars[1]);
 $data['month_traffic_limit']    = mb2bytes($vars[2]);
 $data['week_traffic_limit']     = mb2bytes($vars[3]);
 $data['day_traffic_limit']      = mb2bytes($vars[4]);
 $data['login_time']             = $logintime;
 $data['simultaneous_use']       = (int)$vars[5];
 $data['port_limit']             = (int)$vars[6];
 $data['session_timeout']        = timeinsec($time_vals[12],$time_vals[13],$time_vals[14]);
 $data['idle_timeout']           = (int)$vars[7];
 $data['level']                  = (int)$vars[8];
 $data['prim']                  =  addslashes($FLTR->DirectProcessText($vars[9],1,1));
 $data['rang']					= (int)$vars[10];
 $data['exceed_times']			= (int)$vars[11];
 if($data['blocked']!=1)$data['blocked']="0";
    
 if(!$data['packet'])$error.="<br><b>Ошибка!</b> Необходимо ввести название тарифа!!!<br>";
 if($data['level']>$BILLEVEL)$error.="<br><b>Ошибка!</b> Вы не можете добавлять тарифы выше своего уровня!<br>";

 if(!$error)
  {
   $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
  
    if(!isset($mode) || $mode!="edit")
     {
     $res=$BILL->AddTarif($data);
     if(!$res)
       {
       ?>
       <b>ОК!</b> Тариф '<? OUT($data['packet']) ?>' успешно добавлен!<br>
       Добавил администратор <? OUT($CURRENT_USER["nick"]) ?>.
       <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>      
       <?
       $form=0;
       }
       else {$form=true;echo($res);}
    } 
    elseif(isset($mode) && $mode=="edit" && isset($gid))
    {
     $res=$BILL->UpdateTarif($gid,$data);
     if(!$res)
       {
       ?>
       <b>ОК!</b> Тариф '<? OUT($data['packet']) ?>' успешно изменён!<br>
       Изменил администратор <? OUT($CURRENT_USER["nick"]) ?>.
       <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>      
       <?
       $form=0;
       }
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
 
 if(!isset($mod) && isset($mode) && $mode=="edit" && isset($gid))
  {
  $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  $data=$BILL->GetTarifData($gid);      
  $vars[0]=$data['packet'];
  $activated=!$data['blocked'];
  $time_vals[0]=gethours($data['total_time_limit']);
  $time_vals[1]=getmins($data['total_time_limit']);
  $time_vals[2]=getsecs($data['total_time_limit']);  
  $time_vals[3]=gethours($data['month_time_limit']);
  $time_vals[4]=getmins($data['month_time_limit']);
  $time_vals[5]=getsecs($data['month_time_limit']);
  $time_vals[6]=gethours($data['week_time_limit']);
  $time_vals[7]=getmins($data['week_time_limit']);
  $time_vals[8]=getsecs($data['week_time_limit']);
  $time_vals[9]=gethours($data['day_time_limit']);
  $time_vals[10]=getmins($data['day_time_limit']);
  $time_vals[11]=getsecs($data['day_time_limit']);  
  $time_vals[12]=gethours($data['session_timeout']);
  $time_vals[13]=getmins($data['session_timeout']);
  $time_vals[14]=getsecs($data['session_timeout']);    
  $vars[1]=bytes2mb($data['total_traffic_limit']);
  $vars[2]=bytes2mb($data['month_traffic_limit']);
  $vars[3]=bytes2mb($data['week_traffic_limit']);
  $vars[4]=bytes2mb($data['day_traffic_limit']);
  $vars[5]=(int)$data['simultaneous_use'];
  $vars[6]=(int)$data['port_limit'];
  $vars[7]=(int)$data['idle_timeout'];
  $vars[8]=(int)$data['level'];
  $vars[9]=$FLTR->ReverseProcessText($data['prim']);
  $vars[10]=$data['rang'];
  $vars[11]=$data['exceed_times'];
  makelogintimearrays($data['login_time'],&$times_d,&$times_hf,&$times_ht,&$times_mf,&$times_mt);  
  $data['session_timeout']        = timeinsec($time_vals[12],$time_vals[13],$time_vals[14]);

  }
 
 
 if(!isset($vars))
   {
   $vars=array();
   for($i=1;$i<8;++$i)$vars[$i]=0;
   $vars[7]="300";
   for($i=0;$i<15;++$i)$time_vals[$i]="00";
   }
 
 $levelsel="<select name=vars[] style=\"width:100%;\" class=inputbox>";
 for($i=1;$i<=$BILLEVEL;++$i)
  {
  $sel=($i==$BILLEVEL)?" selected":"";
  $levelsel.="<option value=\"$i\"$sel>".$i."</option>\r\n";
  }
  $levelsel.="</select>";
  
     
 //составляем timeslist
 if(isset($addtime))
  {
  $times_d[]="Mo";
  $times_hf[]="00";
  $times_ht[]="23";
  $times_mf[]="00";
  $times_mt[]="59";   
  }
  elseif(isset($deltimes))
  {
  for($i=0;$i<count($times_del);++$i)
   {
   unset($times_d[$times_del[$i]]);$times_d=array_values($times_d);
   unset($times_hf[$times_del[$i]]);$times_hf=array_values($times_hf);
   unset($times_ht[$times_del[$i]]);$times_ht=array_values($times_ht);
   unset($times_mf[$times_del[$i]]);$times_mf=array_values($times_mf); 
   unset($times_mt[$times_del[$i]]);$times_mt=array_values($times_mt);
   }
  }
 
 
  $times_d_template_s=array("Mo","Tu","We","Th","Fr","Sa","Wk","Al");
  $times_d_template_t=array("Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Рабочие дни","Все дни недели");  
 if(isset($times_d) && count($times_d))
  {
  $timeslist.="<table width=100% height=100% class=tbl1>";
  for($i=0;$i<count($times_d);++$i)
    {
    $dsel="<select name=times_d[] style=\"width:100%;\" class=tbl1>";
    for($k=0;$k<count($times_d_template_s);++$k)
      {
      $sel=($times_d[$i]==$times_d_template_s[$k])?" selected":"";
      $dsel.="<option value=\"".$times_d_template_s[$k]."\"$sel>".$times_d_template_t[$k]."</option>";
      }
    $dsel.="</select>";      
    $timeslist.="<tr>
      <td width=10%>".($i+1).") день недели: </td><td width=30%>$dsel</td>
      <td width=10%> время с: </td><td width=20%>
       <input type=text class=inputbox style=\"width:40%\" maxlength=2 name=times_hf[] value=\"".$times_hf[$i]."\">:
       <input type=text class=inputbox style=\"width:40%\" maxlength=2 name=times_mf[] value=\"".$times_mf[$i]."\">
      </td>
      <td width=10%> время до:</td><td width=20%> 
       <input type=text class=inputbox style=\"width:40%\" maxlength=2 name=times_ht[] value=\"".$times_ht[$i]."\">:
       <input type=text class=inputbox style=\"width:40%\" maxlength=2 name=times_mt[] value=\"".$times_mt[$i]."\">
      </td>
      <td><input type=checkbox name=times_del[] value=\"$i\"></td>
      </tr>
      ";
    }
    $timeslist.="</table>";
  } 
  else
  {
  $timeslist.="<div align=center>время не ограничено!</center>";   
  }
  
  

  if(!isset($activated))$activated=1; 
  if($activated==1){$yesch="checked";$nosch="";}else{$noch="checked";$yesch="";}
  $actsel="<input type=radio value=\"1\" name=activated $yesch>да</input><input type=radio name=activated value=\"0\" $noch>нет</input>";
 
 $k=0;$l=0;
 if(!isset($mode) || $mode!="edit")
  OUT("<div align=center><b>Добавление нового тарифа:</b></div>");
 else OUT(" <div align=center><b>Редактирование тарифа:</b></div>");
 ?>
 <form action="<? OUT("?p=$p&act=$act&action=$action&mod=add&mode=$mode&gid=$gid") ?>" method=post>
  <table width=100% class=tbl1>
  <tr>
    <td width=50%>Название</td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Разрешить данный тариф?</td><td width=50%><? OUT($actsel) ?></td>
  </tr>
  <tr>
    <td width=50%> Общий лимит времени(0 - неогр)</td><td width=50%>
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>"> (ЧЧ:ММ:СС)    
    </td>
  </tr>
  <tr>
    <td width=50%> Месячный лимит на время(0 - неогр)</td><td width=50%>
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>"> (ЧЧ:ММ:СС)  
    </td>
  </tr>
  <tr>
    <td width=50%> Недельный лимит на время(0 - неогр)</td><td width=50%>
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>"> (ЧЧ:ММ:СС) 
    </td>
  </tr>
  <tr>
    <td width=50%> Дневной лимит на время(0 - неогр)</td><td width=50%>
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>"> (ЧЧ:ММ:СС) 
    </td>
  </tr>
  <tr>
    <td width=50%>Общий лимит на траффик (мб)(0 - неогр)</td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Месячный лимит на траффик (мб)(0 - неогр)</td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Недельный лимит на траффик (мб)(0 - неогр)</td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>           
  <tr>
    <td width=50%>Дневной лимит на траффик (мб)(0 - неогр)</td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  </table>
    <div align=center class=tbl1>Разрешенное время подключения:</div>
    <table width=100%><tr><td><? OUT($timeslist) ?></td></tr>
    <tr><td>
      <table width=100%><td width=50% align=left><input type=submit name=addtime value="Добавить время" class=button></td>
     <?  if(isset($times_d) && count($times_d)){ ?> <td width=50% align=right><input type=submit name=deltimes value="Удалить выбранные" class=button></td> <? } ?>      
      </table></td></tr>
    </table>
     
     
  <table width=100% class=tbl1>
  <tr>
    <td width=50%>Одновременно подключений на тарифе(0 - неогр) </td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Подключений под одним логином(0 - неогр) </td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>            
    <td width=50%> Максимальное время сессии(0 - неогр) </td>
     <td width=50%>
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>">:
     <input type=text class=inputbox style="width:10%" maxlength=2 name=time_vals[] value="<? OUT($time_vals[$l++]) ?>"> (ЧЧ:ММ:СС) 
     </td>
  </tr>  
  <tr>
    <td width=50%>Максимальное время простоя(с)(0 - неогр) </td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Уровень администраторов, которые могут назначать этот тариф </td><td width=50%><? OUT($levelsel) ?></td>
  </tr>
  <tr>
    <td width=50%>Описание тарифа </td><td width=50%><textarea name=vars[] class=inputbox style="width:100%" rows=5><? ++$k;OUT($vars[$k++]) ?></textarea></td>
  </tr>  
  <tr>
    <td width=50%>Ранг данного тарифа </td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>
  <tr>
    <td width=50%>Максимальное дневное превышение трафика (раз) </td><td width=50%><input type=text class=inputbox style="width:100%" name=vars[] value="<? OUT($vars[$k++]) ?>"></td>
  </tr>   
     
  </table>
  <div align=center><input type=submit name=submform class=button value="Сохранить"></div> 
 </form><br>
 <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
 
 <? 
 }

?>