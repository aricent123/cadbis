<?
if($BILLEVEL<4) return;

?>
<div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>
 <div align=center><b>������� ������</b></div>
<?


$form=1;
if(isset($mod)  && $mod=="delete") 
 {
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);   
           if(isset($sure) && $sure=="true")
             {
              for($i=0;$i<count($ids);++$i)
               {
               $BILL->DeleteTarif($ids[$i]);
               }             
             }
             else
             {
             $form=0;
              ?>
              <br><br><font color=red size=6px><b>��������:</b></font> �� ����������� ������� ������. �� �������, ��� ������ ��� �������?<br>
              �������� ����� ������� ����������� �����������, ������������, ��������� � ���� ������� ����� ��� �� ������� �� ��������� 
              ����� ���� ������.
              <form action="<? OUT("?p=$p&act=$act&action=$action&mod=delete&page=$page") ?>&sure=true" method=post>            
              <?
              for($i=0;$i<count($ids);++$i)
               {
               $data=$BILL->GetTarifData($ids[$i]);
               $tdata=$BILL->GetTarifTotalAccts($data["gid"]);             
               ?>                   
               <input type=hidden name=ids[] value="<? OUT($ids[$i]) ?>">
                  <div align=center><b><? OUT($ids[$i]) ?></b></div>               
<<<<<<< .mine
                  <table width=100% align=center class=tbl2>
                   <tr>
                    <td width=100%>
=======
                  <table width=100% align=center class=tbl2>
                    <tr><td width=100%>
>>>>>>> .r53
                      <table  width=100%>
                        <tr><td width=50%>
                        �������� ������:
                        </td><td width=50%><? OUT($data["packet"]) ?></td></tr>                      
                        <tr><td width=50%>
                        �������:
                        </td><td width=50%><? OUT(bytes2mb($tdata["traffic"])." Mb") ?></td></tr>
                        <tr><td width=50%>
                        �����:
                        </td><td width=50%><? OUT(gethours($tdata["time"]).":".getmins($tdata["time"]).":".getsecs($tdata["time"])) ?></td></tr>
                        </table>
                    </td></tr>
                  </table>   
               <?
               }
              ?><br><br>
                <div align=center><input type=submit class=button value="�������!"></div>              
              </form>
               <div align=center><a href="<? OUT("?p=$p&act=$act&action=$action&page=$page") ?>">�����</a></div> 
              <?                   
             } 
 
 }

if($form)
 {
 if(!isset($page) || $page<1)$page=1;
 
 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
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
 <form action="<? OUT("?p=$p&act=$act&action=$action&mod=delete&page=$page") ?>" method=post>
 <?
 if(count($list))
 {
 OUT("��������: ".$pagestext);
 for($i=0;$i<count($list);++$i)
   {
   $data=$list[$i];
   $tdata=$BILL->GetTarifTotalAccts($data["gid"]);
   ?>
                  <div align=center><b><? OUT($data["gid"]) ?></b></div>
<<<<<<< .mine
                  <table width=100% align=center class=tbl2>
                  <tr>
                    <td width=100%>
=======
                  <table width=100% align=center class=tbl2>
                    <tr><td width=100%>
>>>>>>> .r53
                      <table  width=100%>
                        <tr><td width=50%>
                        �������� ������:
                        </td><td width=50%><? OUT($data["packet"]) ?></td></tr>                      
                        <tr><td width=50%>
                        ���������� �������������:
                        </td><td width=50%><? OUT(count($BILL->GetUsersOfTarif($data["gid"])))?></td></tr>
                        <tr><td width=50%>
                        �������:
                        </td><td width=50%><? OUT(bytes2mb($tdata["traffic"])." Mb") ?></td></tr>
                        <tr><td width=50%>
                        �����:
                        </td><td width=50%><? OUT(gethours($tdata["time"]).":".getmins($tdata["time"]).":".getsecs($tdata["time"])) ?></td></tr>
                        </table>
                    </td></tr>
                  </table>                
                  <table width=100%><td width=50% class=tbl1 align=left>
                   <? if($data["level"]<=$BILLEVEL){ ?><input type=checkbox name=ids[] value="<? OUT($data["gid"]) ?>">�������</td> <? } ?>
                  <td align=right class=tbl1 width=50%>
                    <? if($data["level"]<=$BILLEVEL){?><a href="<? OUT("?p=$p&act=$act&action=$action&mod=delete&ids[]=".$data["gid"]) ?>">�������</a> <? } ?>                  
                  </td></table><br>    
   <?
   }
   ?>
   ��������: <? OUT($pagestext) ?>
   <div align=center><input type=submit class=button value="�������"></div>
   </form>
  
   <br>
   <? } else {OUT("<div align=center><b>��� �� ������ ������!</b></div>");}?>
   <div align=center><a href="<? OUT("?p=$p&act=$act") ?>">�����</a></div>
   <?
 
 }

?>