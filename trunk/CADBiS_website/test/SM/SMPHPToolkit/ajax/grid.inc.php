<?php
require_once(dirname(__FILE__).'/common.inc.php');
require_once(dirname(__FILE__).'/var.inc.php');
require_once(dirname(__FILE__).'/buffer.inc.php');
require_once(dirname(__FILE__).'/pager.inc.php');
require_once(dirname(__FILE__).'/../grid/grid.inc.php');
require_once(dirname(__FILE__).'/../grid/pager.inc.php');


class ajax_grid extends grid{
	protected $page_size;
	protected $buffer,$pagenum,$pager,$sorting,$sortdir;


	protected $header_template = 'if($sortable)
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
	 */
	public function __construct($datasource = null, $page_size = 10)
	{
		$this->datasource = $datasource;
		$this->page_size = $page_size;
		$this->buffer = new ajax_buffer($this->UID('buffer'));
		$this->sorting = new ajax_var($this->UID('sorting'),sorting::DEFAULT_SORT);
		$this->pagenum = new ajax_var($this->UID('pagenum'),1);
		$this->sortdir = new ajax_var($this->UID('sortdir'),sorting::SORT_DIR_DEFAULT);
		$this->buffer->register_var($this->sorting);
		$this->buffer->register_var($this->pagenum);
		$this->buffer->register_var($this->sortdir);
	}
	//--------------------------------------------------
	public function buffer_client_id()
	{
		return $this->buffer->client_id();
	}
	//--------------------------------------------------
	public function get_buffer()
	{
		return $this->buffer;
	}
	//--------------------------------------------------
	public function attach_pager(&$pager)
	{
		$this->pager = &$pager;
		$this->pager->reset_buffer($this->buffer, $this->pagenum);
	}
	//--------------------------------------------------
	public function get_current_sorting()
	{
		return $this->sorting->get_value();
	}
	//--------------------------------------------------
	public function get_sort_direction()
	{
		return $this->sortdir->get_value();
	}
	//--------------------------------------------------
	public function show_progress($value)
	{
		$this->buffer->show_progress($value);
	}
	//--------------------------------------------------
	public function render($pager = null)
	{
		$sortdir_name = $this->sortdir->client_id();
		$this->buffer->start();
		echo parent::render(
						array(
							'buffer'=>$this->buffer->client_id(),
							'sortdir_name'=>$this->sortdir->client_id(),
							'sorting_name'=>$this->sorting->client_id()
							));
		if(!is_null($this->pager))
			echo $this->pager->render();
		$this->buffer->end();
	}
};