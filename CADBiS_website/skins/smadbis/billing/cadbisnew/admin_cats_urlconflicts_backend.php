<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
CADBiSNew::instance()->script_src('js/ajax/buffer.js');
CADBiSNew::instance()->link_href('skins/smadbis/css/grid.css');
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
$cats = $BILL->GetUrlCategories();


$grid
