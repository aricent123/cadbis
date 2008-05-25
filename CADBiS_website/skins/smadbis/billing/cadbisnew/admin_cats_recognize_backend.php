<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__).'/CADBiS/recognize.php');


if(isset($_GET['urlcheck']) || !empty($_GET['urlcheck']))
	die(Recognizer::recognizeByUrlCheck($_GET['url']));
else
{
	$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
	$result = "";	
	$url="www.yandex.ru";
	if(isset($_REQUEST['url']))
		$url = $_REQUEST['url'];
	$current_cid = $BILL->GetUrlCategory($url);
	$cats = $BILL->GetUrlCategories();
	$kwds_weights = $BILL->GetKeywordsWeights();		 
	$cat_by_cid = array();$i=0;	
	foreach($cats as &$cat)
	{
		$cat_by_cid[$cat['cid']] = $i++;
		$cat['keywords'] = $BILL->GetUrlCategoryKeywords($cat['cid']);		
	}	
	
	// Setting url category
	if(isset($_GET['set']) || isset($_POST['btnAttach']))
	{
		if($setcid > 0 && !empty($url))
			$BILL->AddUrlCategoryMatch($url,$setcid);		
	}
	// Applying conflicts resolves
	if(isset($_POST['btnResolveConflicts']))
	{
		$actionfor = $_POST['actionfor'];
		foreach($actionfor as $word => $action)
		{		
			switch($action)
			{
				case 'delete':
					$BILL->DeleteUrlCategoryKeyword($word);
					$BILL->ResolveUrlCategoryConflict($word);
					break;
				case 'replace':
					$BILL->ReplaceUrlCategoryKeyword($word, $setcid);
					$BILL->ResolveUrlCategoryConflict($word);
					break;
				case 'unsense':
					$BILL->DeleteUrlCategoryKeyword($word);
					$BILL->AddUrlCategoryUnsenseword($word);
					$BILL->ResolveUrlCategoryConflict($word);
					break;
			}
		}
	}	
	// Recognize content	
	if(isset($_POST['btnSubmit']) || isset($_GET['manualcheck'])){
		$uswords = $BILL->GetUrlCategoriesUnsenseWords();
		$result = Recognizer::recognizeByMyself($url, $cats, $uswords, $kwds_weights, isset($_REQUEST['debug']));
	}
	// Other (finding conflicts etc)
	if(isset($result) && isset($set))
	{
		$conflict_cats = array();
		foreach($result['cwords'] as $cword=>$wcount) 
		{
			if($wcount<Recognizer::MINIMAL_CWORD_COEF)
				continue;
			$c_cid = $BILL->GetUrlCategoryKeyword($cword);
			if($c_cid>0)
			{
				if($c_cid != $setcid)
				{
					$conflict_cats[$c_cid][$cword]= $wcount;
					$BILL->AddUrlCategoryConflict($cword,$setcid,$c_cid,$url);
				}
			}
			else
			{
				//echo('Adding '.$cword.' to '.$cats[$cat_by_cid[$setcid]]['title'].'<br/>');
				$BILL->UrlCategoryAttachKeyword($setcid, $cword);
			}
		}
	}
	
}
