<?

 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 $list=$BILL->GetTarifs();

 if(count($list))
 {
 for($i=0;$i<count($list);++$i)
   {
   $data=$list[$i];
   
  $total_time_limit=($data[total_time_limit])?(gethours($data[total_time_limit]).":".getmins($data[total_time_limit]).":".getsecs($data[total_time_limit])):"неограничено";  
  $month_time_limit=($data[month_time_limit])?(gethours($data[month_time_limit]).":".getmins($data[month_time_limit]).":".getsecs($data[month_time_limit])):"неограничено";
  $week_time_limit=($data[week_time_limit])?(gethours($data[week_time_limit]).":".getmins($data[week_time_limit]).":".getsecs($data[week_time_limit])):"неограничено";
  $day_time_limit=($data[day_time_limit])?(gethours($data[day_time_limit]).":".getmins($data[day_time_limit]).":".getsecs($data[day_time_limit])):"неограничено";
  $session_timeout=($data[session_timeout])?(gethours($data[session_timeout]).":".getmins($data[session_timeout]).":".getsecs($data[session_timeout])):"неограничено";
  $total_traffic_limit=($data[total_traffic_limit])?bytes2mb($data[total_traffic_limit])." Мб":"неограничено";
  $month_traffic_limit=($data[month_traffic_limit])?bytes2mb($data[month_traffic_limit])." Мб":"неограничено";
  $week_traffic_limit=($data[week_traffic_limit])?bytes2mb($data[week_traffic_limit])." Мб":"неограничено";
  $day_traffic_limit=($data[day_traffic_limit])?bytes2mb($data[day_traffic_limit])." Мб":"неограничено";
  $times_d=NULL;
  $times_hf=NULL;
  $times_ht=NULL;
  $times_mf=NULL;
  $times_mt=NULL;      
  makelogintimearrays($data[login_time],&$times_d,&$times_hf,&$times_ht,&$times_mf,&$times_mt); 
  $times_d_template_s=array("Mo","Tu","We","Th","Fr","Sa","Wk","Al");
  $times_d_template_t=array("Понедельник","Вторник","Среда","Четверг","Пятница","Суббота","Рабочие дни","Все дни недели");  
  if(isset($times_d) && count($times_d))
   {
   $timeslist="";
   for($j=0;$j<count($times_d);++$j)
    {
    $dsel="<select name=times_d[] style=\"width:100%;\" class=tbl1>";
    for($k=0;$k<count($times_d_template_s);++$k)
      if($times_d[$j]==$times_d_template_s[$k])$timeslist.=$times_d_template_t[$k];
      $timeslist.="(с ".$times_hf[$j].":".$times_mf[$j]." до ".$times_ht[$j].":".$times_mt[$j].")<br>";
    }
   }
   else  $timeslist="в любое время";

  ?>
                  <div align=center><b><? OUT($data["gid"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <td width=100%>
                    <div align=center><b><? OUT($data["packet"]) ?></b></div>                      
                    <div align=right><font style="font-size:9px">Пользователей:<? OUT(count($BILL->GetUsersOfTarif($data["gid"])))?></font></div>
                    <tr><td width=100%>
                    <? OUT($data["prim"]) ?>
                    <div align=center><b>Ограничения:</b></div>
                    <table width=70% class=tbl1 align=center>
                    <tr><td width=50%>
                     Разрешённое время доступа:
                    </td><td width=50%>
                    <? OUT($timeslist) ?></td></tr>
                    <tr><td width=50%>
                     Общий лимит времени:
                    </td><td width=50%>
                    <? OUT($total_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     Месячный лимит времени:
                    </td><td width=50%>
                    <? OUT($month_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     Недельный лимит времени:
                    </td><td width=50%>
                    <? OUT($week_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     Дневной лимит времени:
                    </td><td width=50%>
                    <? OUT($day_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     Лимит времени на одну сессию:
                    </td><td width=50%>
                    <? OUT($session_timeout) ?></td></tr>                                                            
                    <tr><td width=50%>
                     Общее ограничение по траффику:
                    </td><td width=50%>
                    <? OUT($total_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     Месячное ограничение траффика:
                    </td><td width=50%>
                    <? OUT($month_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     Недельное ограничение траффика:
                    </td><td width=50%>
                    <? OUT($week_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     Ограничение траффика в день:
                    </td><td width=50%>
                    <? OUT($day_traffic_limit) ?></td></tr>                                                            
                    </table>  
<<<<<<< .mine
                    </td>
=======
                    </td></tr>
>>>>>>> .r53
                  </table>                
  
   <?
   }

   ?>
   <br>
   <? } else {OUT("<br><br><div align=center><b>нет ни одного тарифа!</b></div><br><br>");}?>