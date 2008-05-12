<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}

$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

if(isset($_POST['btnSave'])){
	foreach($_POST['deniedcats'] as $gid => $dencats)
	{
		$BILL->SetUrlCategoriesDenied($gid,array_keys($dencats));
	}
}
$packets = $BILL->GetTarifs();
$cats = $BILL->GetUrlCategories();
