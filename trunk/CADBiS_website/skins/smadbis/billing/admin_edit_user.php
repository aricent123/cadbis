<?
if($BILLEVEL<3) return;
$form=1;

if(!isset($search) && !isset($page) && !isset($show))
  {
  $search="user";
  $see[]="user";
  $see[]="fio";
  }
?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
<br>
<?
if(isset($search) && $search=="user"){
?>
<br><div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&search=no") ?>">Отобразить список пользователей</a></div><br>
 <div align=center><b>Поиск пользователей</b></div>
<br>
<? }else{  ?>                                                                            
<br><div align=center><a href="<? OUT("?p=$p&action=$action&act=$act") ?>">Поиск пользователей</a></div>
 <div align=center><b>Список пользователей</b></div>
<br>
<? } 
if(isset($do) && $do=="find")
  {
  if((!isset($see) || !count($see)))
    {OUT("<div align=center>Необходимо указать просматриваемые поля!</div>");$search="user";}
  if(!isset($searchstr) || !$searchstr)
    {OUT("<div align=center>Необходимо указать строку поиска!</div>");$search="user";}      
  }


if(isset($search) && ($search=="user" || $search=="list"))
{
if($search!="list")for($i=0;$i<9;++$i)$seeu[$i]="";
for($i=0;$i<count($see);++$i)
 switch($see[$i])
   {
   case"user":$seeu[0]=" checked";break;
   case"fio":$seeu[1]=" checked";break;
   case"email":$seeu[2]=" checked";break;
   case"country":$seeu[3]=" checked";break;
   case"city":$seeu[4]=" checked";break;
   case"address":$seeu[5]=" checked";break;            
   case"signature":$seeu[6]=" checked";break;
   case"info":$seeu[7]=" checked";break;
   case"prim":$seeu[8]=" checked";break;
   case"nick":$seeu[9]=" checked";break;
   };
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  $tarlist=$BILL->GetTarifs();
  $tarsel="<select name=gid style=\"width:100%\"  class=inputbox><option value=\"all\">--любой--</option>";
  for($i=0;$i<count($tarlist);++$i)
   {
   if($gid==$tarlist[$i]["gid"])$sel=" selected";else $sel="";
   $tarsel.="<option value=\"".$tarlist[$i]["gid"]."\"$sel>".$tarlist[$i]["packet"]."</option>\r\n";
   }
  $tarsel.="</select>";    

 ?>
 <form action="<? OUT("?p=$p&act=$act&action=$action&search=list&do=find") ?>" method=post>
 <table width=70% align=center class=tbl1>
   <tr><td>
   Только тариф:<? OUT($tarsel) ?>   
   Строка поиска:<input type=text name=searchstr class=inputbox value="<? OUT($searchstr) ?>"style="width:100%"></td></tr>
   <tr><td>Проверять:<br>
     <input type=checkbox name=see[] value="user"<? OUT($seeu[0]) ?>>Логин
     <input type=checkbox name=see[] value="fio"<? OUT($seeu[1]) ?>>ФИО
     <input type=checkbox name=see[] value="email"<? OUT($seeu[2]) ?>>E-mail
     <input type=checkbox name=see[] value="country"<? OUT($seeu[3]) ?>>Страна     
     <input type=checkbox name=see[] value="city"<? OUT($seeu[4]) ?>>Город
     <input type=checkbox name=see[] value="address"<? OUT($seeu[5]) ?>>Адрес     
     <input type=checkbox name=see[] value="signature"<? OUT($seeu[6]) ?>>Подпись 
     <input type=checkbox name=see[] value="info"<? OUT($seeu[7]) ?>>Доп. инфо
     <input type=checkbox name=see[] value="prim"<? OUT($seeu[8]) ?>>Примечание       
     <input type=checkbox name=see[] value="nick"<? OUT($seeu[9]) ?>>Ник
   </td></tr>
 </table>
 <div align=center><input type=submit class=button value="Найти"></div>
 <?
}

if($search=="list")
{
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
 $list=$BILL->FindUsers(strtolower($searchstr),$see,$gid);
 
 
 
 if(count($list))
 {
  ?>
  <table width=100% class=tbl2>
  <tr>
  <td class=tbl1>№</td> 
  <td class=tbl1>Логин</td>
  <td class=tbl1>ФИО</td> 
  <td class=tbl1>Трафик</td>
  <td class=tbl1>Время</td>
  <td class=tbl1>Действия</td>  
  </tr>
  <?

     $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
     $USR->SetSeparators($GV["sep1"],$GV["sep2"]);   
 for($i=0;$i<count($list);++$i)
   {
   $data=$BILL->GetUserData($list[$i]);
   $adata=$BILL->GetUserTotalAcctsData($list[$i]);   
    ?>
    <tr>
    <td class=tbl1><? OUT($i+1) ?></td> 
    <td class=tbl1><a href="?p=users&act=userinfo&id=<? OUT($data["uid"]) ?>"><? OUT($data["user"]) ?></a></td>
    <td class=tbl1><? OUT($data["fio"]) ?></td> 
    <td class=tbl1><? OUT(bytes2mb($adata["traffic"])) ?> Mb</td>
    <td class=tbl1><? OUT(gethours($adata["time"]).":".getmins($adata["time"]).":".getsecs($adata["time"])) ?></td>
    <td class=tbl1>
      <? $gd=$USR->GetGroupData($data["group"]);
       if($CURRENT_USER["level"]>$gd["level"] || $CURRENT_USER["id"]==$data["uid"]){ ?>
      <a href="<? OUT("?p=$p&act=$act&action=delete&mod=delete&ids[]=".$data["uid"]) ?>">удалить</a><br>
      <a href="<? OUT("?p=$p&act=$act&action=block&uid=".$data["uid"]) ?>">
      <? if($BILL->IsUserActivated($data["uid"])){ ?>блокировать<? }else{ ?><font color=green>разблокировать<? }?></font></a><br>
      <a href="?p=smadbis&act=users&action=add&mode=edit&uid=<? OUT($data["uid"]) ?>">редактировать</a><br>
      <? } ?> 
      <a href="?p=smadbis&act=stats&action=sessions&user=<? OUT($data["user"]) ?>">статистика</a><br>       
    </td>  
    </tr> 
    <?     
   }
   
  echo("</table>");
   ?>
   <? } else {OUT("<br><br><div align=center><b>ничего не найдено!</b></div>");}
}
elseif($search!="user")
{
 if(isset($show) && $show=="tbl")
  {
  ?>
 <div align=left><a href="<? OUT("?p=$p&act=$act&action=$action&page=$page") ?>">Отобразить с подробными данными</a></div>
 <? } 
  else
  {
  ?>
 <div align=left><a href="<? OUT("?p=$p&act=$act&action=$action&show=tbl&page=$page") ?>">Отобразить в виде таблицы</a></div>
 <? } ?>
<?
 if($form)
 {
 if(!isset($page) || ($page<1 && $page!="all"))$page=1;
 if(!isset($sort))$sort="";
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
  $ulist=$BILL->GetUsersList($sort); 

   if($sort=="traffic" || $sort=="time")
     {
     for($i=0;$i<count($ulist);++$i)
       {
       $data=$BILL->GetUserTotalAcctsData($ulist[$i]);
       $tmplist[$i]["traffic"]= $data["traffic"];
       $tmplist[$i]["uid"]= $ulist[$i];
       $tmplist[$i]["time"]= $data["time"];       
       }
     if($sort=="traffic")usort($tmplist,"accts_compare_traffic_desc");
     elseif($sort=="time")usort($tmplist,"accts_compare_time_desc");
     $tmplist=array_values($tmplist);
     for($i=0;$i<count($tmplist);++$i)
       $ulist[$i]=$tmplist[$i]["uid"];       
     }
 $pgcnt=$PDIV->GetPagesCount($ulist);
 if($page!="all")
   $list=$PDIV->GetPage($ulist,$page);
 else 
   $list=$ulist;
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=$i+1)$pagestext.="<a href=\"?p=$p&act=$act&action=$action&show=$show&page=".($i+1)."\">".($i+1)."</a>";
           else $pagestext.="".($i+1)."";
           $pagestext.=", ";
           }
           if($page!="all")$pagestext.="<a href=\"?p=$p&act=$act&action=$action&show=$show&page=all\">все</a>";
           else  $pagestext.="все";

 ?>
 <?
 if(count($list))
 {
 OUT("Страница: ".$pagestext);
 if(isset($show) && $show=="tbl")
  {
  ?>
  <table width=100% class=tbl2>
  <tr>
  <td class=tbl1>№</td> 
  <td class=tbl1><a href="<? OUT("?p=$p&act=$act&action=$action&show=$show&page=$page&sort=login") ?>">Логин</a></td>
  <td class=tbl1><a href="<? OUT("?p=$p&act=$act&action=$action&show=$show&page=$page&sort=fio") ?>">ФИО</a></td> 
  <td class=tbl1><a href="<? OUT("?p=$p&act=$act&action=$action&show=$show&page=$page&sort=traffic") ?>">Трафик</a></td>
  <td class=tbl1><a href="<? OUT("?p=$p&act=$act&action=$action&show=$show&page=$page&sort=time") ?>">Время</a></td>
  <td class=tbl1>Действия</td>  
  </tr>
  <?
  }
     $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
     $USR->SetSeparators($GV["sep1"],$GV["sep2"]);   
 for($i=0;$i<count($list);++$i)
   {
   $data=$BILL->GetUserData($list[$i]);
   $adata=$BILL->GetUserTotalAcctsData($list[$i]);   
   if(isset($show) && $show=="tbl")
    {
    ?>
    <tr>
    <td class=tbl1><? OUT($i+1) ?></td> 
    <td class=tbl1><a href="?p=users&act=userinfo&id=<? OUT($data["uid"]) ?>"><? OUT($data["user"]) ?></a></td>
    <td class=tbl1><? OUT($data["fio"]) ?></td> 
    <td class=tbl1><? OUT(bytes2mb($adata["traffic"])) ?> Mb</td>
    <td class=tbl1><? OUT(gethours($adata["time"]).":".getmins($adata["time"]).":".getsecs($adata["time"])) ?></td>
    <td class=tbl1>
      <? $gd=$USR->GetGroupData($data["group"]);
       if($CURRENT_USER["level"]>$gd["level"] || $CURRENT_USER["id"]==$data["uid"]){ ?>
      <a href="<? OUT("?p=$p&act=$act&action=delete&mod=delete&ids[]=".$data["uid"]) ?>">удалить</a><br>
      <a href="<? OUT("?p=$p&act=$act&action=block&uid=".$data["uid"]) ?>">
      <? if($BILL->IsUserActivated($data["uid"])){ ?>блокировать<? }else{ ?><font color=green>разблокировать<? }?></font></a><br>
      <a href="?p=smadbis&act=users&action=add&mode=edit&uid=<? OUT($data["uid"]) ?>">редактировать</a><br>
      <? } ?> 
      <a href="?p=smadbis&act=stats&action=sessions&user=<? OUT($data["user"]) ?>">статистика</a><br>       
    </td>  
    </tr> 
    <?     
    }
    else
     {
     ?>
                  <div align=center><b><? OUT($data["uid"]) ?>: <? OUT($data["fio"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b><br>
                      <? if(file_exists($DIRS["users_avatars"]."/".$data["uid"])) 
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
                      <table  width=100%>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($data["user"]) ?></td></tr>                      
                        <tr><td width=50%>
                        E-mail:
                        </td><td width=50%><? OUT(make_email_str($data["email"])) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($adata["traffic"])." Mb") ?></td></tr>
                        <tr><td width=50%>                    
                        Время:
                        </td><td width=50%><? OUT(gethours($adata["time"]).":".getmins($adata["time"]).":".getsecs($adata["time"])) ?></td></tr>                        
                      </table>
                    </td></tr>
                  </table>                
                  <table width=100%><td width=35% class=tbl1 align=left>
                  <td align=center class=tbl1 width=32%>
                    <a href="<? OUT("?p=$p&act=stats&action=sessions&user=".$data["user"]) ?>">статистика</a>  
                  </td> <td align=center class=tbl1 width=32%>
                    <? $ud=get_user_data($data["uid"]);if($ud["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$ud["id"]){ ?><a href="<? OUT("?p=$p&act=$act&action=add&mode=edit&uid=".$data["uid"]) ?>">редактировать</a> <? } ?>                  
                  </td></table><br>    
     <?
     }
   }
   
 if(isset($show) && $show=="tbl")
  {echo("</table>");}
   ?>
   Страница: <? OUT($pagestext) ?>
   <? } else {OUT("<br><br><div align=center><b>нет ни одного пользователя!</b></div>");}?>
<?
 if(isset($show) && $show=="tbl")
  {
  ?>
 <div align=left><a href="<? OUT("?p=$p&act=$act&action=$action&page=$page") ?>">Отобразить с подробными данными</a></div>
 <? } 
  else
  {
  ?>
 <div align=left><a href="<? OUT("?p=$p&act=$act&action=$action&show=tbl&page=$page") ?>">Отобразить в виде таблицы</a></div>
 <? } 
 }
}

if(isset($search) && $search=="user"){
?>
<br><div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&search=no") ?>">Отобразить список пользователей</a></div>
<? }else{  ?>
<br><div align=center><a href="<? OUT("?p=$p&act=$act&action=$action") ?>">Поиск пользователей</a></div>
<? } ?>
<br><div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>