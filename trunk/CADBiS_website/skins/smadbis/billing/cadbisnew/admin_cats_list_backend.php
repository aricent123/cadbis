<?
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
CADBiSNew::instance()->script_src('js/ajax/buffer.js');
CADBiSNew::instance()->script_src('js/ajax/manager.js');

CADBiSNew::instance()->link_href('skins/smadbis/css/grid.css');
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);


if(isset($_REQUEST['renderkwdsfor'])){
	$cid = $_REQUEST['renderkwdsfor'];
	die(implode(", ",$BILL->GetUrlCategoryKeywords($cid)));
}

$ajaxbuf_cats = new ajax_buffer("update_buffer_cats");
$emanager = new ajax_entities_manager('entities_manager', $ajaxbuf_cats);

class cats_formatter extends grid_formatter {
	protected $_field = '';
	/**
	 * Entities manager
	 * @var ajax_entities_manager
	 */
	protected $_entities_manager = null;
	/**
	 * @param string $field
	 * @param ajax_entities_manager $entities_manager
	 */
	public function __construct($field,$entities_manager){
		$this->_field = $field;
		$this->_entities_manager = $entities_manager;
	}
	public function format($data, $type, $number = 0, $columns = null)
	{
		switch($this->_field){
			case 'actions':
				return '<a href="javascript:deleteCat('.$data['cid'].');">Удалить</a>,
						<a href="javascript:editCat('.$data['cid'].',\''.
						$data['title'].'\');">Изменить</a>';
			default:
				return parent::format($data,$type);	
		}
		
	}	
};

$cats_ds = new grid_data_source(new grid_header_item_array(
					new grid_header_item('cid','Id',type::STRING, true),
					new grid_header_item('title','Категория',type::STRING, true),
					new grid_header_item('actions','Действия',null, false, new cats_formatter('actions',$emanager))
				));

$cats_grid = new ajax_grid('cats_grid',$cats_ds,$ajaxbuf_cats);
$cats_grid_pager = new ajax_grid_pager('cats_grid_pager',$BILL->GetRowsCount('url_categories'),10);
$cats_grid->attach_pager($cats_grid_pager);

/**
 * Check if we need to make some actions
 */
if($emanager->isAnyAction() && $ajaxbuf_cats->is_post_back())
{
	switch($emanager->getAction())
	{
		case $emanager->action->ADD:
			$item = json_decode($emanager->getItem());
			$BILL->AddUrlCategory(array('title'=>$item->title));
		break;
		case $emanager->action->UPD:
			$item = json_decode($emanager->getItem());
			$item->keywords = explode(',',$item->keywords);
			for($i=0;$i<count($item->keywords);++$i)
			{
				$item->keywords[$i] = rtrim(ltrim($item->keywords[$i]));
				if(strlen($item->keywords[$i])<2 || empty($item->keywords[$i])){
					array_splice($item->keywords,$i,1);
					$i=0;
				}
			}
			$BILL->UpdateUrlCategoryKeywords($item->cid,$item->keywords);
			$BILL->UpdateUrlCategory($item->cid,array('title'=>$item->title));
			
		break;		
		case $emanager->action->DEL:
			$item = json_decode($emanager->getItem());
			if($item->cid>0)
				$BILL->DeleteUrlCategory($item->cid);
		break;
	}
	$emanager->eraseAction();
}

/**
 * Retrieve categories from the database
 */
$cats = $BILL->GetUrlCategories($cats_grid_pager->get_curpage(),10,$cats_grid->get_current_sorting(), $cats_grid->get_sort_direction());
foreach($cats as $cat)
{
	$cat['keywords'] = implode(", ",$BILL->GetUrlCategoryKeywords($cat['cid']));
	$cats_ds->add_row(array(
			$cat['cid'],
			$cat['title'],
			$cat,
			));	
}




