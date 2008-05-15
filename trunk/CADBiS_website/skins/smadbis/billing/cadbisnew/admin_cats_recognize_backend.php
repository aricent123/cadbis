<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__).'/CADBiS/recognize.php');

if(!isset($_GET['myself']) || empty($_GET['myself']))
	die(Recognizer::recognizeByUrlCheck($_GET['url']));
else
{
	$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
	$cats = $BILL->GetUrlCategories();	
	foreach($cats as $cat)
		$cat['keywords'] = $BILL->GetUrlCategoryKeywords($cat['cid']);
	$uswords = $BILL->GetUrlCategoriesUnsenseWords();
	die(Recognizer::recognizeByMyself($_GET['url'], $cats));
}
