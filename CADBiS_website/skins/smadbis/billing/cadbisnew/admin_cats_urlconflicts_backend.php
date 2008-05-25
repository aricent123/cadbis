<?
define('COUNT_ON_PAGE',30);
if(!check_auth() || $CURRENT_USER['level']<7){	
	die("Access denied!");
}
require_once(dirname(__FILE__)."/SMPHPToolkit/SMAjax.php");
CADBiSNew::instance()->script_src('js/ajax/buffer.js');
CADBiSNew::instance()->link_href('skins/smadbis/css/grid.css');
$BILL=new CBilling($GV["dbhost"],$GV["dbname"],$GV["dblogin"],$GV["dbpassword"]);
$cats = $BILL->GetUrlCategoriesAssoc();


// any action on selected keywords
if(isset($_POST['selected_kwds'])){
	$selkwds = $_POST['selected_kwds'];
	foreach($selkwds as $word=>$on){
		if(isset($_POST['btnLeave'])){
			$BILL->ResolveUrlCategoryConflict($word);
		}elseif(isset($_POST['btnDelete'])){
			$BILL->DeleteUrlCategoryKeyword($word);
			$BILL->ResolveUrlCategoryConflict($word);
		}elseif(isset($_POST['btnReplace'])){
			$keyword = $BILL->GetUrlCategoryConflictKeyword($word);
			$BILL->ReplaceUrlCategoryKeyword($word, $keyword['forcid']);
			$BILL->ResolveUrlCategoryConflict($word);
		}elseif(isset($_POST['btnUnsense'])){			
			$BILL->AddUrlCategoryUnsenseword($word);
			$BILL->DeleteUrlCategoryKeyword($word);
			$BILL->ResolveUrlCategoryConflict($word);
		}
	}
}

class conflicts_act_formatter extends grid_formatter {
	public function format($data, $type, $number = 0, $columns = null)
	{return '<input type="checkbox" name="selected_kwds['.$data['keyword'].'][]" />';}	
};
class conflicts_cat_formatter extends grid_formatter {
	protected $_cats = null;
	public function __construct($cats){$this->_cats = $cats;}
	public function format($data, $type, $number = 0, $columns = null)
	{return (!empty($this->_cats[$data]['title_ru'])?$this->_cats[$data]['title_ru']:$this->_cats[$data]['title']);}	
};


$ajaxbuffer = new ajax_buffer("update_buffer");
$datasource = new grid_data_source(new grid_header_item_array(
					new grid_header_item('id','',null, false, new conflicts_act_formatter()),
					new grid_header_item('url','URL',type::LINK_NEWWIN, true),
					new grid_header_item('keyword','Слово',type::STRING, true),					
					new grid_header_item('forcid','Категория',type::STRING, true, new conflicts_cat_formatter($cats)),
					new grid_header_item('incid','Конфликт с',type::STRING, true, new conflicts_cat_formatter($cats)),
					new grid_header_item('date','Дата',type::STRING, true)					
				));
$grid = new ajax_grid('grid',$datasource,$ajaxbuffer);
$grid->no_data_message = 'Нет записей';
$grid->render_pager_top(true);
$grid_pager = new ajax_grid_pager('grid_pager',$BILL->GetRowsCount('url_categories_conflicts'),COUNT_ON_PAGE);
$grid->attach_pager($grid_pager);


$conflicts = $BILL->GetUrlCategoriesConflicts(
					$grid_pager->get_curpage(),
					$grid_pager->get_pagesize(),			
					$grid->get_current_sorting(), 
					$grid->get_sort_direction());

foreach($conflicts as $conflict){
	$datasource->add_row(array(
		$conflict,
		$conflict['url'],
		$conflict['keyword'],
		$conflict['forcid'],
		$conflict['incid'],
		$conflict['date'])
	);
}