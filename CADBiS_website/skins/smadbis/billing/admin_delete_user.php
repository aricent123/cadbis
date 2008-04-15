<?
if($BILLEVEL<3) return;

?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
 <div align=center><b>Удалить пользователей</b></div>
<?


$form=1;
if(isset($mod)  && $mod=="delete") 
 {
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);   
           if(isset($sure) && $sure=="true")
             {
              for($i=0;$i<count($ids);++$i)
               {
               $data=$BILL->GetUserData($ids[$i]);
               $BILL->DeleteUser($ids[$i]);
               }             
             }
             else
             {
             $form=0;
              ?>
              <br><br><font color=red size=6px><b>ВНИМАНИЕ:</b></font> Вы собираетесь удалить пользователей. Вы уверены, что хотите это сделать?<br>
              Удаление может повлечь необратимые последствия, пользователи потеряют идентификаторы и не смогут входить на сайт!<br>
              Если вы действительно хотите удалить следующих пользователей, нажмите "Удалить". В противном случае - "Назад".
              <form action="<? OUT("?p=$p&act=$act&action=$action&mod=delete&page=$page") ?>&sure=true" method=post>            
              <?
              for($i=0;$i<count($ids);++$i)
               {
               $data=$BILL->GetUserData($ids[$i]);
               $adata=$BILL->GetUserTotalAcctsData($ids[$i]);
               ?>
               <input type=hidden name=ids[] value="<? OUT($data["uid"]) ?>">
                  <table width=100% align=center class=tbl2>
                    <tr><td width=30%>
                      <table width=100% align=center><tr><td align=center width=100% align=center>
                      <a href="<? OUT("?p=users&act=userinfo&id=".$data["uid"]) ?>">
                      <b><? OUT($data["nick"]) ?></b>
                      </td></tr><tr><td align=center>
                      <? OUT($data["rang"]) ?>
                      </td></tr>
                      <tr><td align=center height=100% valign=top>
                      <? OUT(make_raiting_str($data["raiting"])) ?>
                      </td></tr>                    
                      </table>
                    <td width=70%>
                      <table  width=100%>
                        <tr><td width=50%>
                        Login:
                        </td><td width=50%><? OUT($data["user"]) ?></td></tr>                      
                        <tr><td width=50%>
                        E-mail:
                        </td><td width=50%><? OUT(make_email_str($data["email"])) ?></td></tr>
                        <tr><td width=50%>
                        ФИО:
                        </td><td width=50%><? OUT($data["fio"]) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($adata["traffic"])." Mb") ?></td></tr>
                        <tr><td width=50%>                    
                        Время:
                        </td><td width=50%><? OUT(gethours($adata["time"]).":".getmins($adata["time"]).":".getsecs($adata["time"])) ?></td></tr>                        
                      </table>
                    </td></tr>
                   </table>
               <?
               }
              ?><br><br>
                <div align=center><input type=submit class=button value="Удалить!"></div>              
              </form>
               <div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&page=$page") ?>">назад</a></div> 
              <?                   
             } 
 
 }


if($form)
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
 <? }
 if(!isset($page) || ($page<1 && $page!="all"))$page=1;
 
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 $ulist=$BILL->GetUsersList();
 $pgcnt=$PDIV->GetPagesCount($ulist);
 if($page!="all")$list=$PDIV->GetPage($ulist,$page);else $list=$ulist;
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=$i+1)$pagestext.="<a href=\"?p=$p&act=$act&action=$action&show=$show&page=".($i+1)."\">".($i+1)."</a>";
           else $pagestext.="".($i+1)."";
           $pagestext.=", ";
           }
           if($page!="all")$pagestext.="<a href=\"?p=$p&act=$act&action=$action&show=$show&page=all\">все</a>";
           else $pagestext.="все";

  if(isset($show) && $show=="tbl")
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
  } 
  ?>
 <form action="<? OUT("?p=$p&act=$act&action=$action&mod=delete&page=$page") ?>" method=post>
 <?
     $USR=new CUsers($DIRS["users_data"],$DIRS["users_list"],$DIRS["users_private"],$DIRS["users_groups"],$DIRS["users_online"]);
     $USR->SetSeparators($GV["sep1"],$GV["sep2"]);   
 if(count($list))
 {
 OUT("Страница: ".$pagestext);
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
      if($gd["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$data["uid"]){ ?><input type=checkbox name=ids[] value="<? OUT($data["uid"]) ?>">выбрать<br>
      <a href="<? OUT("?p=$p&act=$act&action=$action&mod=delete&ids[]=".$data["uid"]) ?>">удалить</a> <? } ?>                    
    </td>  
    </tr> 
    <?     
    }
    else
     {
     ?>
                  <div align=center><b><? OUT($data["uid"]) ?>: <? OUT($data["fio"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <tr><td width=25%>
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
                    </td><td width=75%>
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
                  <table width=100%><td width=50% class=tbl1 align=left>
                   <? $gd=$USR->GetGroupData($data["group"]);
                   if($gd["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$data["uid"]){ ?><input type=checkbox name=ids[] value="<? OUT($data["uid"]) ?>">выбрать</td> <? } ?>
                  <td align=right class=tbl1 width=50%>
                    <? if($gd["level"]<$CURRENT_USER["level"] || $CURRENT_USER["id"]==$data["uid"]){ ?><a href="<? OUT("?p=$p&act=$act&action=$action&mod=delete&ids[]=".$data["uid"]) ?>">удалить</a> <? } ?>                  
                  </td></table><br>    
     <?
     }
 }   
 if(isset($show) && $show=="tbl")
  {echo("</table>");}
  ?>
  Страница: <? OUT($pagestext) ?>
  <br>
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
 ?>  
  
   <div align=center><input type=submit class=button value="Удалить"></div>
   
   <br>
   <? } else {OUT("<div align=center><b>нет ни одного пользователя!</b></div>");}?>
   <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
   <?
 
 }

?>