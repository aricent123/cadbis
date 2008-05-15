<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

if(isset($_POST['btnSave'])){
	$BILL->UpdateUrlCategoriesUnsenseWords(explode("\r\n",$_POST['uswords']));
}
$uswords = implode("\r\n",$BILL->GetUrlCategoriesUnsenseWords());