<?php
require_once(dirname(__FILE__).'/common.inc.php');
require_once(dirname(__FILE__).'/var.inc.php');
require_once(dirname(__FILE__).'/buffer.inc.php');
require_once(dirname(__FILE__).'/pager.inc.php');
require_once(dirname(__FILE__).'/../grid/grid.inc.php');
require_once(dirname(__FILE__).'/../grid/pager.inc.php');


class ajax_grid extends grid{
	protected $page_size;
	/**
	 * Ajax buffer for rendering
	 * @var ajax_buffer
	 */
	protected $buffer;
	/**
	 * Current page number
	 * @var ajax_var
	 */
	protected $pagenum;
	/**
	 * Current ajax pager
	 * @var grid_pager
	 */
	protected $pager;
	/**
	 * Sorting field
	 * @var ajax_var
	 */
	protected $sorting;
	/**
	 * Filtering field value
	 * @var ajax_var
	 */
	protected $filtering;
	/**
	 * Filter field
	 * @var ajax_var
	 */
	protected $filterfield;
	/**
	 * Sorting direction
	 * valid values: sorting::ASC,sorting::DESC, sorting::DEFAULT
	 * @var ajax_var
	 */
	protected $sortdir;
	/**
	 * Indicates that grid uses external ajax buffer
	 * @var bool
	 */
	protected $_isExternalBuffer = false;
	/**
	 * Render the pager at the top of the grid?
	 * @var bool
	 */
	protected $_renderPagerTop = false;
	/**
	 * Render the filter at the top of the grid?
	 * @var bool
	 */
	protected $_renderFilterTop = true;
	/**
	 * Render the filter at the bottom of the grid?
	 * @var bool
	 */
	protected $_renderFilterBottom = false;		
	/**
	 * Grid header template
	 * @var string
	 */
	protected $header_template = '
			if($sortable)
			{
				if($current_sorting == $sortname)
					$link = "javascript:".$buffer.".set_var(\'".$sortdir_name."\',\'".($direction)."\');".$buffer.".update();";
				else
					$link = "javascript:".$buffer.".set_var(\'".$sorting_name."\',\'".($sortname)."\');".$buffer.".update();";
				echo spchr::tab2."<a href=\"$link\">".$title."</a>".spchr::endl;
				if($direction == sorting::SORT_DIR_ASC)
					echo "<img src=\"".$this->img_sort_desc."\"/>";
				else
					echo "<img src=\"".$this->img_sort_asc."\"/>";
			}
			else
				echo spchr::tab2.$title.spchr::endl;';
	//--------------------------------------------------
	/**
	 * name: __construct
	 * params:
	 * @param string $id
	 * @param grid_data_source $datasource
	 * @param int $page_size
	 * @param ajax_buffer $ajax_buffer
	 * @param grid_pager $pager
	 */
	public function __construct($id, &$datasource = null, $ajax_buffer = null, $pager = null, $page_size = 10)
	{
		$this->id = $id;
		$this->datasource = $datasource;
		$this->page_size = $page_size;
		if($ajax_buffer == null)
		$this->buffer = new ajax_buffer($this->UID('buffer'));
		else
		{
			$this->_isExternalBuffer = true;
			$this->buffer = $ajax_buffer;
		}
		$this->sorting = new ajax_var($this->UID('sorting'),sorting::DEFAULT_SORT);
		$this->pagenum = new ajax_var($this->UID('pagenum'),1);
		$this->sortdir = new ajax_var($this->UID('sortdir'),sorting::SORT_DIR_DEFAULT);
		$this->filtering = new ajax_var($this->UID('filtering'),'');
		$this->filterfield = new ajax_var($this->UID('filterfield'),'');
		$this->buffer->register_vars($this->filterfield,$this->sorting,$this->pagenum,$this->sortdir,$this->filtering);
		if($pager != null)
		$this->attach_pager($pager);
	}
	//--------------------------------------------------
	/**
	 * returns the current ajax buffer client id
	 * @return string
	 */
	public function buffer_client_id()
	{
		return $this->buffer->client_id();
	}
	//--------------------------------------------------
	/**
	 * returns the internal ajax_buffer
	 * @return ajax_buffer
	 */
	public function get_buffer()
	{
		return $this->buffer;
	}
	//--------------------------------------------------
	/**
	 * sets the internal ajax_buffer
	 * @param ajax_buffer $buffer
	 */
	public function set_buffer(&$buffer)
	{
		$this->buffer = $buffer;
		$this->_isExternalBuffer = true;
		$this->buffer->register_var($this->sorting);
		$this->buffer->register_var($this->pagenum);
		$this->buffer->register_var($this->sortdir);
	}
	//--------------------------------------------------
	/**
	 * Attaches the grid pager to this grid
	 * @param grid_pager $pager
	 */
	public function attach_pager(&$pager)
	{
		$this->pager = &$pager;
		$this->pager->reset_buffer($this->buffer, $this->pagenum);
	}
	//--------------------------------------------------
	/**
	 * Returns sorting field
	 * @return string
	 */
	public function get_sorting()
	{
		return $this->sorting->get_value();
	}
	//--------------------------------------------------
	/**
	 * Returns filtering field value
	 * @return string
	 */
	public function get_filtering()
	{
		return $this->filtering->get_value();
	}
	/**
	 * Returns filtering field
	 * @return string
	 */
	public function get_filterfield()
	{
		return $this->filterfield->get_value();
	}	
	//--------------------------------------------------
	/**
	 * Returns current sort direction
	 * @return unknown
	 */
	public function get_sort_direction()
	{
		return $this->sortdir->get_value();
	}
	//--------------------------------------------------
	/**
	 * If true then pager will be rendered also at the top of the grid
	 * @param bool $set
	 */
	public function render_pager_top($set)
	{
		$this->_renderPagerTop = $set;
	}
	/**
	 * If true then filter will be rendered also at the top of the grid
	 * @param bool $set
	 */
	public function render_filter_top($set)
	{
		$this->_renderFilterTop = $set;
	}
	/**
	 * If true then filter will be rendered also at the bottom of the grid
	 * @param bool $set
	 */
	public function render_filter_bottom($set)
	{
		$this->_renderFilterBottom = $set;
	}		
	//--------------------------------------------------
	/**
	 * Sets the show progress variable (true/false) for internal ajax buffer
	 * If set to true, the window showing ajax progress will be shown
	 * on each ajax request
	 * @param bool $value
	 */
	public function show_progress($value)
	{
		$this->buffer->show_progress($value);
	}
	//--------------------------------------------------
	/**
	 * Renders filters
	 * @param place for the filter (top/bottom)
	 */
	protected function render_filters($place = 'top')
	{
		$header_items = array_values($this->datasource->get_header()->get_items());
		$items = array();
		foreach($header_items as $item)
		if($item->is_filterable())
		$items[] = $item;
		if(!empty($items)){
			$apply_link = $this->buffer->client_id().
				".set_var('".$this->filterfield->client_id().
				"',document.getElementById('".$place.$this->UID('filter_field')."').value);".
				$this->buffer->client_id().
				".set_var('".$this->filtering->client_id().
				"',document.getElementById('".$place.$this->UID('filter_value')."').value);".
				$this->buffer->client_id().".update();";
			?>
			Filter:
			<input type="text" class="grid_filter_value" id="<?=$place.$this->UID('filter_value') ?>" value="<?=$this->get_filtering() ?>"/>
			<select id="<?=$place.$this->UID('filter_field') ?>" class="grid_filter_field">
			<? foreach($items as $item){?>
				<option value="<?=$item->get_name() ?>"<?=($this->get_filterfield()==$item->get_name())?' selected':''?>"><?=$item->get_title() ?></option>
				<?} ?>
			</select>
			<input type="button" value="Apply" onclick="<?=$apply_link?>" />
		<?
		}
	}
	
	//--------------------------------------------------
	/**
	 * Renders the ajax grid
	 * @param ajax_grid_pager $pager
	 */
	public function render($pager = null)
	{
		$sortdir_name = $this->sortdir->client_id();
		if(!$this->_isExternalBuffer)
		$this->buffer->start();
		if($this->_renderFilterTop)
			echo $this->render_filters('top');
		if(!is_null($this->pager) && $this->_renderPagerTop)
		echo $this->pager->render();
		echo parent::render(array(
								'buffer'=>$this->buffer->client_id(),
								'sortdir_name'=>$this->sortdir->client_id(),
								'sorting_name'=>$this->sorting->client_id()
							));
		if($this->_renderFilterBottom)
			echo $this->render_filters('bottom');
		if(!is_null($this->pager))
			echo $this->pager->render();
		if(!$this->_isExternalBuffer)
			$this->buffer->end();
	}
};