<?

 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 $list=$BILL->GetTarifs();

 if(count($list))
 {
 for($i=0;$i<count($list);++$i)
   {
   $data=$list[$i];
   
  $total_time_limit=($data[total_time_limit])?(gethours($data[total_time_limit]).":".getmins($data[total_time_limit]).":".getsecs($data[total_time_limit])):"������������";  
  $month_time_limit=($data[month_time_limit])?(gethours($data[month_time_limit]).":".getmins($data[month_time_limit]).":".getsecs($data[month_time_limit])):"������������";
  $week_time_limit=($data[week_time_limit])?(gethours($data[week_time_limit]).":".getmins($data[week_time_limit]).":".getsecs($data[week_time_limit])):"������������";
  $day_time_limit=($data[day_time_limit])?(gethours($data[day_time_limit]).":".getmins($data[day_time_limit]).":".getsecs($data[day_time_limit])):"������������";
  $session_timeout=($data[session_timeout])?(gethours($data[session_timeout]).":".getmins($data[session_timeout]).":".getsecs($data[session_timeout])):"������������";
  $total_traffic_limit=($data[total_traffic_limit])?bytes2mb($data[total_traffic_limit])." ��":"������������";
  $month_traffic_limit=($data[month_traffic_limit])?bytes2mb($data[month_traffic_limit])." ��":"������������";
  $week_traffic_limit=($data[week_traffic_limit])?bytes2mb($data[week_traffic_limit])." ��":"������������";
  $day_traffic_limit=($data[day_traffic_limit])?bytes2mb($data[day_traffic_limit])." ��":"������������";
  $times_d=NULL;
  $times_hf=NULL;
  $times_ht=NULL;
  $times_mf=NULL;
  $times_mt=NULL;      
  makelogintimearrays($data[login_time],&$times_d,&$times_hf,&$times_ht,&$times_mf,&$times_mt); 
  $times_d_template_s=array("Mo","Tu","We","Th","Fr","Sa","Wk","Al");
  $times_d_template_t=array("�����������","�������","�����","�������","�������","�������","������� ���","��� ��� ������");  
  if(isset($times_d) && count($times_d))
   {
   $timeslist="";
   for($j=0;$j<count($times_d);++$j)
    {
    $dsel="<select name=times_d[] style=\"width:100%;\" class=tbl1>";
    for($k=0;$k<count($times_d_template_s);++$k)
      if($times_d[$j]==$times_d_template_s[$k])$timeslist.=$times_d_template_t[$k];
      $timeslist.="(� ".$times_hf[$j].":".$times_mf[$j]." �� ".$times_ht[$j].":".$times_mt[$j].")<br>";
    }
   }
   else  $timeslist="� ����� �����";

  ?>
                  <div align=center><b><? OUT($data["gid"]) ?></b></div>
                  <table width=100% align=center class=tbl2>
                    <td width=100%>
                    <div align=center><b><? OUT($data["packet"]) ?></b></div>                      
                    <div align=right><font style="font-size:9px">�������������:<? OUT(count($BILL->GetUsersOfTarif($data["gid"])))?></font></div>
                    <tr><td width=100%>
                    <? OUT($data["prim"]) ?>
                    <div align=center><b>�����������:</b></div>
                    <table width=70% class=tbl1 align=center>
                    <tr><td width=50%>
                     ����������� ����� �������:
                    </td><td width=50%>
                    <? OUT($timeslist) ?></td></tr>
                    <tr><td width=50%>
                     ����� ����� �������:
                    </td><td width=50%>
                    <? OUT($total_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     �������� ����� �������:
                    </td><td width=50%>
                    <? OUT($month_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     ��������� ����� �������:
                    </td><td width=50%>
                    <? OUT($week_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     ������� ����� �������:
                    </td><td width=50%>
                    <? OUT($day_time_limit) ?></td></tr>
                    <tr><td width=50%>
                     ����� ������� �� ���� ������:
                    </td><td width=50%>
                    <? OUT($session_timeout) ?></td></tr>                                                            
                    <tr><td width=50%>
                     ����� ����������� �� ��������:
                    </td><td width=50%>
                    <? OUT($total_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     �������� ����������� ��������:
                    </td><td width=50%>
                    <? OUT($month_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     ��������� ����������� ��������:
                    </td><td width=50%>
                    <? OUT($week_traffic_limit) ?></td></tr>
                    <tr><td width=50%>
                     ����������� �������� � ����:
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
   <? } else {OUT("<br><br><div align=center><b>��� �� ������ ������!</b></div><br><br>");}?>