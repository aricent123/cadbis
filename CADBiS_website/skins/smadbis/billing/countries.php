<?                          
if($BILLEVEL<5)return;

 $BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]); 
 
 
 $countries = $BILL->GetAvailableCountries();
 
  if($_POST['country'] || $_GET['country'])
 $country = ($_POST['country'])?$_POST['country']:$_GET['country'];
 ?>
 <b>Выберите страну:</b>
 <form action="<?=("?p=$p&act=$act")?>" method="post">
 <select name="country">
   <? 
   foreach($countries as $ctry){
     $sel = (isset($country) && $country==$ctry['ctry'])?"selected":"";
     ?><option value="<?=$ctry['ctry']?>" <?=$sel?>><?=$ctry['country']."(".$ctry['ctry'].")"?></option><?
     }
  ?>
  </select>
  <input name="op" type=submit value="Показать диапазоны">
  </form>
  
<?
 if($_POST['country'] || $_GET['country'])
 {
 $country = ($_POST['country'])?$_POST['country']:$_GET['country'];
 
 if($_GET['action'] == "save")
   {
    // ...                       
    foreach($_POST['sips'] as &$sip)
      $sip = ip2value($sip);
    foreach($_POST['eips'] as &$eip)
      $eip = ip2value($eip);
    foreach($_POST['assigned'] as &$ass)
      $ass = strtotime($ass);
      
     $BILL->SaveDiapasons($_POST['ids'],$_POST['sips'],$_POST['eips'],$_POST['sources'],$_POST['assigned']);
     die("<script>document.location.href='?p=$p&act=$act&country={$country}';</script>");
   }
   elseif($_GET['action'] == "adddiap")
   {
   // ...
      $diap = array(
              'sip' => ip2value($_POST['addsip']),
              'eip' => ip2value($_POST['addeip']),
              'source' => $_POST['addsource'],
              'ctry' => $_POST['addctry'],
              'cntry' => $_POST['addcntry'],
              'country' => $_POST['addcountry']
              );
     $BILL->AddDiapason($diap);
     die("<script>document.location.href='?p=$p&act=$act&country={$country}';</script>");     
   }        
   elseif($_GET['action'] == "delete")
   {
   // ...
     $BILL->DeleteDiapason($_GET['id']);
     die("<script>document.location.href='?p=$p&act=$act&country={$country}';</script>");     
   }   
 
 ?>
 <form action="<?=("?p=$p&act=$act&country={$country}&action=save")?>" method="post">
  <table width=100% class="tbl1">
   <tr>
    <td>ID</td>
    <td>От</td>
    <td>До</td>
    <td>Источник</td>
    <td>Присвоено</td>
    <td>Админ</td>    
   </tr>
 
 <?
    $dp = $BILL->GetDiapasons($country);
    foreach($dp as $diap)
     {
      ?>
         <tr>
          <td><?=$diap['id'] ?><input type=hidden name="ids[]" value="<?=$diap['id']?>"></td>
          <td><input type=text name="sips[]" value="<?=value2ip($diap['sip'])?>"></td>
          <td><input type=text name="eips[]" value="<?=value2ip($diap['eip'])?>"></td>
          <td><input type=text name="sources[]" value="<?=$diap['source']?>"></td>
          <td><input type=text name="assigned[]" value="<?=date('d/m/Y',$diap['assigned'])?>"></td>
          <td><a href="<?=("?p=$p&act=$act&country={$country}&action=delete")?>&id=<?=$diap['id'] ?>">Delete</a></td>
         </tr>
      <?
     }
     ?>
     </table>
 <input type=submit value="Save">
 </form>
  <b>Добавить диапазон:</b>
 <form action="<?=("?p=$p&act=$act&country={$country}&action=adddiap")?>" method="post">
   <table>
   <tr><td>От:</td><td><input type="text" name="addsip" value="<?=value2ip($dp[0]['sip'])?>"></td></tr>
   <tr><td>До:</td><td><input type="text" name="addeip" value="<?=value2ip($dp[0]['eip'])?>"></td></tr>
   <tr><td>Source:</td><td><input type="text" name="source" value="<?=$dp[0]['source']?>"></td></tr>
   <tr><td>Country:</td><td><input type="text" name="addcountry" value="<?=$dp[0]['country']?>"></td></tr>
   <tr><td>Cntry:</td><td><input type="text" name="addcntry" value="<?=$dp[0]['cntry']?>"></td></tr>
   <tr><td>Ctry:</td><td><input type="text" name="addctry" value="<?=$dp[0]['ctry']?>"></td></tr>
   </table>
   <input type="submit" value="Добавить">
 </form>
<?     
 }
 ?><br/>
  <a href="?p=smadbis">НАЗАД</a>
 <?
