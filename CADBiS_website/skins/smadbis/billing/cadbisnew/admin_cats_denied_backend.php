<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}

$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);

$gid = 0;
$packet = null;
if(isset($_POST['btnSave'])){
	foreach($_POST['deniedcats'] as $gid => $dencats)
	{
		$BILL->SetUrlCategoriesDenied($gid,array_keys($dencats));
	}
	$gid = $_POST['hdnGid']; 
}
if(isset($_POST['btnFilter']))
	$gid = $_POST['selPacket'];

if($gid>0)
	$packet = $BILL->GetTarifData($gid);	
$packets = $BILL->GetTarifs();
$cats = $BILL->GetUrlCategories();