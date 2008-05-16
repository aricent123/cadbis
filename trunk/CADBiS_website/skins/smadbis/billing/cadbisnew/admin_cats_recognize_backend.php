<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__).'/CADBiS/recognize.php');


if(isset($_GET['urlcheck']) || !empty($_GET['urlcheck']))
	die(Recognizer::recognizeByUrlCheck($_GET['url']));
else
{
	$result = "";	
	$url="www.yandex.ru";
	if(isset($_GET['set']) || isset($_POST['btnAttach']))
	{
		// TODO: trying to set cid & find conflicts
	}
	if(isset($_POST['btnResolveConflicts']))
	{
		// TODO: applying conflicts resolves		
	}	
	if(isset($_POST['btnSubmit']) || isset($_GET['manualcheck'])){
		$url = $_REQUEST['url'];
		$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
		$cats = $BILL->GetUrlCategories();
		$cat_by_cid = array();$i=0;	
		foreach($cats as &$cat)
		{
			$cat_by_cid[$cat['cid']] = $i++;
			$cat['keywords'] = $BILL->GetUrlCategoryKeywords($cat['cid']);
		}
		$uswords = $BILL->GetUrlCategoriesUnsenseWords();
		$result = Recognizer::recognizeByMyself($url, $cats, $uswords, false);
	}
	
	if(isset($result) && isset($set))
	{
		$conflict_cats = array();
		foreach($result['cwords'] as $cword=>$wcount) 
		{
			if($wcount<Recognizer::MINIMAL_CWORD_COEF)
				continue;
			$c_cid = $BILL->GetUrlCategoryKeyword($cword);
			if($c_cid>0 && $c_cid != $setcid)
			{
				if(isset($conflict_cats[$c_cid]['cwords']))
					$conflict_cats[$c_cid] = array('cwords'=>array($cword));
				else
					$conflict_cats[$c_cid]['cwords'][] = $cword;				
			}
			else
			{
				$BILL->UrlCategoryAttachKeyword($cid, $cword);
			}
		}
	}
	
}
