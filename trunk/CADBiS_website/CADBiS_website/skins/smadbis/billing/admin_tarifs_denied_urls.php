<?
if($BILLEVEL<4) return;


 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 
 if(isset($mod) && $mod=="users")
  {
  $tdata=$BILL->GetTarifData($gid); 
  $list=$BILL->GetUsersOfTarif($gid);  
   ?>
  <div align=center><a href="<? OUT("?p=$p&act=$act&action=$action") ?>">назад</a></div>
  <div align=center><b>Список пользователей тарифа '<? OUT($tdata['packet']) ?>'</b></div>
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
  for($i=0;$i<count($list);++$i)
    {
    $adata=$BILL->GetUserTotalAcctsData($list[$i]["uid"]);
    ?>
    <tr>
    <td class=tbl1><? OUT($i+1) ?></td> 
    <td class=tbl1><a href="?p=users&act=userinfo&id=<? OUT($list[$i]["uid"]) ?>"><? OUT($list[$i]["user"]) ?></a></td>
    <td class=tbl1><? OUT($list[$i]["fio"]) ?></td> 
    <td class=tbl1><? OUT(bytes2mb($adata["traffic"])) ?> Mb</td>
    <td class=tbl1><? OUT(gethours($adata["time"]).":".getmins($adata["time"]).":".getsecs($adata["time"])) ?></td>
    <td class=tbl1>
      <a href="?p=smadbis&act=users&action=add&mode=edit&uid=<? OUT($list[$i]["uid"]) ?>">редактировать</a><br> 
      <a href="?p=smadbis&act=stats&action=sessions&user=<? OUT($list[$i]["user"]) ?>">статистика</a>       
    </td>  
    </tr>    
    <?
    }
   ?>
   </table>
  <div align=center><a href="<? OUT("?p=$p&act=$act&action=$action") ?>">назад</a></div>
  <?  
  return;
  }




?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
 <div align=center><b>Список тарифов</b></div>
<?          
 if(!isset($page) || $page<1)$page=1;
 

 $ulist=$BILL->GetTarifs();
 $pgcnt=$PDIV->GetPagesCount($ulist);
 $list=$PDIV->GetPage($ulist,$page);
         for($i=0;$i<$pgcnt;++$i)
           {
           if($page!=$i+1)$pagestext.="<a href=\"?p=$p&act=$act&action=$action&page=".($i+1)."\">".($i+1)."</a>";
           else $pagestext.="".($i+1)."";
           if($i<$pgcnt-1)$pagestext.=", ";
           }

 ?>
 <?
 if(count($list))
 {
 OUT("Страница: ".$pagestext);
 for($i=0;$i<count($list);++$i)
   {
   $data=$list[$i];  
   $tdata=$BILL->GetTarifTotalAccts($data["gid"]);     
   if(!isset($tdata["time"]) || !$tdata["time"])$tdata["time"]=0;
   $ucount=$BILL->GetCountUsersOfTarif($data["gid"]);
   ?>
                  <div align=center><b><? OUT($data["gid"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <td width=100%>
                      <table  width=100%>
                        <tr><td width=50%>
                        Название тарифа:
                        </td><td width=50%><? OUT($data["packet"]) ?></td></tr>                      
                        <tr><td width=50%>
                        Количество пользователей:
                        </td><td width=50%><? OUT($ucount) ?></td></tr>
                        <tr><td width=50%>
                        Траффик:
                        </td><td width=50%><? OUT(bytes2mb($tdata["traffic"])." Mb") ?></td></tr>
                        <tr><td width=50%>
                        Время:
                        </td><td width=50%><? OUT(gethours($tdata["time"]).":".getmins($tdata["time"]).":".getsecs($tdata["time"])) ?></td></tr>
                        </table>
                    </td>
                  </table>                
                  <table width=100%>
                  <td width=32% class=tbl1 align=center>
                  <a href="<? OUT("?p=$p&act=$act&action=edit&mod=users&gid=".$data["gid"]) ?>">список пользователей</a> (<? OUT($ucount) ?>)</td>
                  <td align=center class=tbl1 width=32%>
                  <a href="<? OUT("?p=$p&act=stats&action=tarifs&tarif=".$data["gid"]) ?>">статистика</a></td>
                  <td align=center class=tbl1 width=32%>
                    <? if($data["level"]<=$BILLEVEL){?><a href="<? OUT("?p=$p&act=$act&action=add&mode=edit&gid=".$data["gid"]) ?>">редактировать</a> <? } ?>                  
                  </td></table><br>    
   <?
   }

   ?>
   Страница: <? OUT($pagestext) ?>
  
   <br>
   <? } else {OUT("<br><br><div align=center><b>нет ни одного тарифа!</b></div><br><br>");}?>
   <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">назад</a></div>
   <?
?>