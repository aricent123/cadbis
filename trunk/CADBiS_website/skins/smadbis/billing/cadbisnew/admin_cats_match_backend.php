<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
CADBiSNew::instance()->script_src('js/ajax/buffer.js');
CADBiSNew::instance()->script_src('js/ajax/manager.js');

CADBiSNew::instance()->link_href('skins/smadbis/css/grid.css');
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
$cats = $BILL->GetUrlCategories();
global $cats_cid;
$cats_cid_title = array();
foreach($cats as $cat)
	$cats_cid[$cat['cid']] = $cat;

$ajaxbuf_url_cats = new ajax_buffer("update_buffer_cats");
$emanager = new ajax_entities_manager('entities_manager', $ajaxbuf_url_cats);
$ajaxbuf_url_matched_cats = new ajax_buffer("update_buffer_cats_matched");
//$emanager_matched = new ajax_entities_manager('entities_manager_matched', $ajaxbuf_url_matched_cats);

class cat_urls_formatter extends grid_formatter {
	protected $_field = '';
	/**
	 * Entities manager
	 * @var ajax_entities_manager
	 */
	protected $_cats_cid_title = null;
	protected $_manager = '{}';
	/**
	 * @param string $field
	 * @param ajax_entities_manager $entities_manager
	 */
	public function __construct($field,$cats_cid,$manager){
		$this->_field = $field;
		$this->_cats_cid = $cats_cid;
		$this->_manager = $manager;
	}
	public function format($data, $type, $number = 0, $columns = null)
	{
		switch($this->_field){
			case 'actions':
				return '<a href="javascript:deleteURL('.$this->_manager.','.$data['u2cid'].');">Удалить</a>,
						<a href="javascript:recognizeCat('.$this->_manager.',\''.$data['url'].'\');">Распознать</a>,
						<a href="javascript:editURL('.$this->_manager.','.$data['u2cid'].',\''.
						$data['url'].'\',\''.
						$data['cid'].'\');">Изменить</a>';
			case 'category':
				return (!empty($this->_cats_cid[$data]['title_ru']))?$this->_cats_cid[$data]['title_ru']:$this->_cats_cid[$data]['title'];
			default:
				return parent::format($data,$type);	
		}
		
	}	
};
/**
 *
 * @return grid_data_source
 */
function create_ds_header($manager)
{
	global $cats_cid;
	return new grid_data_source(new grid_header_item_array(
					new grid_header_item('u2cid','Id',type::STRING, true),
					new grid_header_item('url','URL',type::LINK_NEWWIN, true, null, true, true),
					new grid_header_item('cid','Категория',type::STRING, true, new cat_urls_formatter('category',$cats_cid,$manager)),
					new grid_header_item('actions','Действия',null, false, new cat_urls_formatter('actions',$cats_cid,$manager))
				));
}



$url_cats_unmatched_ds = create_ds_header($emanager->client_id());
$url_cats_unmatched_grid = new ajax_grid('url_cats_grid_unmatched',$url_cats_unmatched_ds,$ajaxbuf_url_cats);
$url_cats_unmatched_grid_pager = new ajax_grid_pager('url_cats_grid_pager_unmatched',
								$BILL->GetCategoriesUrlUnMatchedCount(
									$url_cats_unmatched_grid->get_filterfield(),
									$url_cats_unmatched_grid->get_filtering()),10);
$url_cats_unmatched_grid->attach_pager($url_cats_unmatched_grid_pager);

$url_cats_matched_ds = create_ds_header($emanager->client_id());
$url_cats_matched_grid = new ajax_grid('url_cats_grid_matched',$url_cats_matched_ds,$ajaxbuf_url_matched_cats);
$url_cats_matched_grid_pager = new ajax_grid_pager('url_cats_grid_pager_matched',
								$BILL->GetCategoriesUrlMatchedCount(
									$url_cats_matched_grid->get_filterfield(),
									$url_cats_matched_grid->get_filtering()),10);									
$url_cats_matched_grid->attach_pager($url_cats_matched_grid_pager);


/**
 * Check if we need to make some actions
 */
if($emanager->isAnyAction())
{
	//special actions
	if($emanager->getAction()=='changeCatByName')
	{
		$item = json_decode($emanager->getItem());
		$BILL->UpdateUrlCategoryMatchByName($item->url,$item->name);
	}
	elseif($emanager->getAction()=='recognizeAll')
	{
		$url_cats = $BILL->GetUrlCategoriesMatch(
			$url_cats_unmatched_grid_pager->get_curpage(),10,			
			$url_cats_unmatched_grid->get_sorting(), 
			$url_cats_unmatched_grid->get_sort_direction(),
			array(0),array());
		require_once(dirname(__FILE__).'/CADBiS/recognize.php');
		foreach($url_cats as $url)
		{			
			$catname = Recognizer::recognize($url['url']);
			if(!empty($catname))
				$BILL->UpdateUrlCategoryMatchByName($url['url'],$catname);
		}
	}
	switch($emanager->getAction())
	{
		case $emanager->action->UPD:
			$item = json_decode($emanager->getItem());
			$BILL->UpdateUrlCategoryMatch($item->u2cid,$item->url,$item->cid);
		break;
		case $emanager->action->DEL:
			$item = json_decode($emanager->getItem());
			$BILL->DeleteUrlCategoryMatch($item->u2cid);
		break;
		case $emanager->action->ADD:
			$item = json_decode($emanager->getItem());
			$BILL->AddUrlCategoryMatch($item->url,$item->cid);
		break;
	}
	$emanager->eraseAction();
}

/**
 * Retrieve urls matchescategories from the database
 */
$url_cats = $BILL->GetUrlCategoriesMatch(
			$url_cats_unmatched_grid_pager->get_curpage(),10,			
			$url_cats_unmatched_grid->get_sorting(), 
			$url_cats_unmatched_grid->get_sort_direction(),
			array(0),array(), 
			$url_cats_unmatched_grid->get_filterfield(),
			$url_cats_unmatched_grid->get_filtering());
foreach($url_cats as $cat)
{
	$url_cats_unmatched_ds->add_row(array(
			$cat['u2cid'],
			$cat['url'],
			$cat['cid'],
			$cat,
			));	
}

/**
 * Retrieve categories from the database
 */
$url_cats = $BILL->GetUrlCategoriesMatch(
			$url_cats_matched_grid_pager->get_curpage(),10,			
			$url_cats_matched_grid->get_sorting(), 
			$url_cats_matched_grid->get_sort_direction(),
			array(),array(0), 
			$url_cats_matched_grid->get_filterfield(),
			$url_cats_matched_grid->get_filtering());
foreach($url_cats as $cat)
{
	$url_cats_matched_ds->add_row(array(
			$cat['u2cid'],
			$cat['url'],
			$cat['cid'],
			$cat,
			));	
}


