<?
if($BILLEVEL<3)return;

 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 $BILL->KillInactiveUsers();
 if(!isset($sort))$sort="start_time";
 $list=$BILL->GetOnlineUsersData($sort);
 
 
 if(!isset($action))$action="";
 
  if($action=="message")
   {
   if($sure=="true")
     {
     $klmessage=str_replace("'","",$klmessage);
     $klmessage=$FLTR->DirectProcessString($klmessage);
     $prg=$GV["send_program"]." ".$klip." '".$klmessage."'";
     //die($prg);
     //$res=shell_exec("nohup $prg> /dev/null 2>&1 &");
     $res=shell_exec("$prg");
     //echo($prg.":<br>".$res."<br>");
     //exec($GV["kill_program"]." ".$user." ".$server." ".$ip." ".$port);   
     //$BILL->KillUser($port);
     //$list=$BILL->GetOnlineUsersData();
     setpage("?p=$p&act=$act");
     }
     else
     {
     ?>
     <div align=center><b>Напишите сообщение следующим пользователям:</b></div>
     <table width=100% align=center class=tbl2><td> 
     <form action="<? OUT("?p=$p&act=$act&action=message&klip=$klip&sure=true") ?>" method=post>    
     <?
     for($i=0;$i<count($list);++$i)
       {
       $uid=$BILL->GetUidByLogin($list[$i]["user"]);
        if(is_user_exists($uid))$data=$BILL->GetUserData($uid);
        else{$data=array();$data["nick"]="не зарегистрирован";}
        
        if($list[$i]["port"]==$klport)
        {   
        $ud=get_user_data($data["uid"]);
        if(($data["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$data["id"]))
        {
        ?>
                <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      </a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>                    
                      </table>
                    </td><td width=70%>
                      <table  width=100% class=tbl1>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($list[$i]["user"]) ?></td></tr>                      
                        <tr><td width=50%>
                        VIP:
                        </td><td width=50%><? OUT($list[$i]["ip"]) ?></td></tr>
                        <tr><td width=50%>
                        IP:
                        </td><td width=50%><? OUT($list[$i]["call_from"]) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($list[$i]["out_bytes"])." Mb (".bytes2kb($list[$i]["out_bytes"])." Kb)") ?></td></tr>
                        <tr><td width=50%>                    
                        Время:
                        </td><td width=50%><? OUT(gethours($list[$i]["time_on"]).":".getmins($list[$i]["time_on"]).":".getsecs($list[$i]["time_on"])) ?></td></tr>                        
                        <tr><td width=50%>                    
                        Начало сессии:
                        </td><td width=50%><? OUT(norm_date(strtotime($list[$i]["start_time"]))) ?></td></tr>
                        <tr><td width=50%>
                        Последнее изменение:
                        </td><td width=50%><? OUT(norm_date($list[$i]["last_change"])) ?></td></tr>

                      </table>
                    </td></tr>
                  </table>                
        <?
        }}}
       ?>Сообщение:<br>
       <input type=text style="width:100%" class=inputbox name=klmessage>
       </td>
       </table>
       <div align=center><input type=submit class=button value="Отправить!"></div>
       </form>
       <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>       
       <?
     return;
     }
   }
 
 if($action=="kill")
   {
   if($sure=="true")
     {
     
     $prg=$GV["kill_program"]." ".$kluser." ".$klserver." ".$klip." ".$klport;
     $res=shell_exec("$prg");
     $BILL->KillUser($klport);

     $klmessage="youarekilled";
     $prg=$GV["send_program"]." ".$klip." '".$klmessage."'";
     $res=shell_exec("$prg");  

     $BILL->AddEventKillUser($kluser);

     setpage("?p=$p&act=$act");
     }
     else
     {
     ?>
     <div align=center><b>Вы уверены, что хотите сбросить следующих пользователей?</b></div>
     <table width=100% align=center class=tbl2><td> 
     <form action="<? OUT("?p=$p&act=$act&action=kill&klip=$klip&kluser=$kluser&klserver=$klserver&klport=$klport&sure=true") ?>" method=post>    
     <?
     for($i=0;$i<count($list);++$i)
       {
       $uid=$BILL->GetUidByLogin($list[$i]["user"]);
        if(is_user_exists($uid))$data=$BILL->GetUserData($uid);
        else{$data=array();$data["nick"]="не зарегистрирован";}
        
        if($list[$i]["port"]==$klport)
        {   
        $ud=get_user_data($data["uid"]);
        if(($data["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$data["id"]))
        {
        ?>
                <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      </a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>                    
                      </table>
                    </td><td width=70%>
                      <table  width=100% class=tbl1>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($list[$i]["user"]) ?></td></tr>                      
                        <tr><td width=50%>
                        VIP:
                        </td><td width=50%><? OUT($list[$i]["ip"]) ?></td></tr>
                        <tr><td width=50%>
                        IP:
                        </td><td width=50%><? OUT($list[$i]["call_from"]) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($list[$i]["out_bytes"])." Mb (".bytes2kb($list[$i]["out_bytes"])." Kb)") ?></td></tr>
                        <tr><td width=50%>                    
                        Время:
                        </td><td width=50%><? OUT(gethours($list[$i]["time_on"]).":".getmins($list[$i]["time_on"]).":".getsecs($list[$i]["time_on"])) ?></td></tr>                        
                        <tr><td width=50%>                    
                        Начало сессии:
                        </td><td width=50%><? OUT(norm_date(strtotime($list[$i]["start_time"]))) ?></td></tr>
                        <tr><td width=50%>
                        Последнее изменение:
                        </td><td width=50%><? OUT(norm_date($list[$i]["last_change"])) ?></td></tr>

                      </table>
                    </td></tr>
                  </table>                
        <?
        }}}
       ?>
       </table>
       <div align=center><input type=submit class=button value="Отключить!"></div>
       </form>
       <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>       
       <?
     return;
     }
   }
   
    if(!isset($view) || $view!="table")
    {
    ?>
    <a href="<? OUT("?p=$p&act=$act&view=table") ?>">отобразить в виде таблицы</a>    
    <?
    }    
 ?>
  <table width=100% align=center class=tbl2><td>
 <?
    if(isset($view) && $view=="table")
    {
    ?>
    <a href="<? OUT("?p=$p&act=$act") ?>">подробное отображение</a><br><br>
    <table width=100%><tr>
    <td class=tbl1>№</td>     
    <td class=tbl1>Nick</td>
    <td class=tbl1>ФИО</td>
    <td class=tbl1><a href="<? OUT("?p=$p&act=$act&view=$view&sort=ip") ?>">VIP</a></td>
    <td class=tbl1><a href="<? OUT("?p=$p&act=$act&view=$view&sort=call_from") ?>">IP</a></td>
    <td class=tbl1><a href="<? OUT("?p=$p&act=$act&view=$view&sort=out_bytes") ?>">Траффик</a></td>
    <td class=tbl1><a href="<? OUT("?p=$p&act=$act&view=$view&sort=time_on") ?>">Время</a></td>
    <td class=tbl1><a href="<? OUT("?p=$p&act=$act&view=$view&sort=start_time") ?>">Начало сессии</a></td>
    <td class=tbl1>Управление</td>
    </tr>        
    <?   
    }  
  for($i=0;$i<count($list);++$i)
  {
  $uid=$BILL->GetUidByLogin($list[$i]["user"]);
    if(is_user_exists($uid))$data=$BILL->GetUserData($uid);
    else{$data=array();$data["nick"]="не зарегистрирован";}

    if(isset($view) && $view=="table")
    {
    ?>
    <tr>
    <td class=tbl1><? OUT($i+1) ?></td>   
    <td class=tbl1>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b></a>        
    <td class=tbl1><b><a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>"><? OUT($data["fio"]) ?></a></b></td>
    <td class=tbl1><? OUT($list[$i]["ip"]) ?></td>
    <td class=tbl1><? OUT($list[$i]["call_from"]) ?></td>
    <td class=tbl1><? OUT(bytes2mb($list[$i]["out_bytes"])." Mb (".bytes2kb($list[$i]["out_bytes"])." Kb)") ?></td>
    <td class=tbl1><? OUT(gethours($list[$i]["time_on"]).":".getmins($list[$i]["time_on"]).":".getsecs($list[$i]["time_on"])) ?></td>
    <td class=tbl1><? OUT($list[$i]["start_time"]) ?></td>
    <td class=tbl1>
        <a href="<? OUT("?p=$p&act=$act&action=message&klip=".$list[$i]["call_from"]."&klport=".$list[$i]["port"]) ?>">написать сообщение</a>
        <? $ud=get_user_data($data["uid"]); if($ud["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$ud["uid"]){ ?><a href="<? OUT("?p=$p&act=$act&action=kill&klip=".$list[$i]["call_from"]."&kluser=".$list[$i]["user"]."&klserver=".$list[$i]["server"]."&klport=".$list[$i]["port"]) ?>">отключить</a> <? } ?>
        <? $ud=get_user_data($data["uid"]); if($ud["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$ud["uid"]){ ?><a href="<? OUT("?p=$p&act=log_url&action=online&unique_id=".$list[$i]["unique_id"]) ?>">посещённые сайты</a> <? } ?>   
    </td>
    </tr>        
    <?   
    }  
    else
    {
  ?>
                  <table width=100% align=center class=tbl2>
                    <tr><td width=100% colspan=2>
                    <div align=center><b><? OUT($data["fio"]) ?></b></div>                   
                    </td></tr>                  
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$data["uid"]) && is_file($DIRS["users_avatars"]."/".$data["uid"])) 
                        OUT("<img border=0 src=\"".$DIRS["users_avatars"]."/".$data["uid"]."\">");
                      ?></a>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>                    
                      </table>
                    </td><td width=70%>
                      <table  width=100% class=tbl1>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($list[$i]["user"]) ?></td></tr>                      
                        <tr><td width=50%>
                        VIP:
                        </td><td width=50%><? OUT($list[$i]["ip"]) ?></td></tr>
                        <tr><td width=50%>
                        IP:
                        </td><td width=50%><? OUT($list[$i]["call_from"]) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($list[$i]["out_bytes"])." Mb (".bytes2kb($list[$i]["out_bytes"])." Kb)") ?></td></tr>
                        <tr><td width=50%>                    
                        Время:
                        </td><td width=50%><? OUT(gethours($list[$i]["time_on"]).":".getmins($list[$i]["time_on"]).":".getsecs($list[$i]["time_on"])) ?></td></tr>                        
                        <tr><td width=50%>                    
                        Начало сессии:
                        </td><td width=50%><? OUT(norm_date(strtotime($list[$i]["start_time"]))) ?></td></tr>
                        <tr><td width=50%>
                        Последнее изменение:
                        </td><td width=50%><? OUT(norm_date($list[$i]["last_change"])) ?></td></tr>

                      </table>
                    </td></tr>
                  </table>                
                  <table width=50% height=100%>
                  <td align=center class=tbl1 width=30%>
                    <a href="<? OUT("?p=$p&act=$act&action=message&klip=".$list[$i]["call_from"]."&klport=".$list[$i]["port"]) ?>">написать сообщение</a></td>                   
                  <td align=center class=tbl1 width=30%>
                    <? $ud=get_user_data($data["uid"]); if($ud["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$ud["uid"]){ ?><a href="<? OUT("?p=$p&act=$act&action=kill&klip=".$list[$i]["call_from"]."&kluser=".$list[$i]["user"]."&klserver=".$list[$i]["server"]."&klport=".$list[$i]["port"]) ?>">отключить</a> <? } ?></td>                  
                  <td align=center class=tbl1 width=30%>
                    <? $ud=get_user_data($data["uid"]); if($ud["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$ud["uid"]){ ?><a href="<? OUT("?p=$p&act=log_url&action=online&unique_id=".$list[$i]["unique_id"]) ?>">посещённые сайты</a> <? } ?>                  
                  </td></table><br>    
  
    <?     
    }
  } 
    if(isset($view) && $view=="table")
    {
    ?>
    </table><br>
    <a href="<? OUT("?p=$p&act=$act") ?>">подробное отображение</a>
    <?
    }
      
  
  if(!count($list))OUT("<div align=center>никого нет</div>");
?></td></table>
 <?
    if(!isset($view) || $view!="table")
    {
    ?>
    <a href="<? OUT("?p=$p&act=$act&view=table") ?>">отобразить в виде таблицы</a>    
    <?
    } 
 
 ?>
 <div align=center><a href="<? OUT("?p=$p") ?>">назад</a></div>