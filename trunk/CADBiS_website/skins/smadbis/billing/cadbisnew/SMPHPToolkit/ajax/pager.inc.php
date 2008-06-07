<?php
require_once(dirname(__FILE__).'/common.inc.php');
require_once(dirname(__FILE__).'/var.inc.php');
require_once(dirname(__FILE__).'/buffer.inc.php');
require_once(dirname(__FILE__).'/../grid/pager.inc.php');

class ajax_grid_pager extends grid_pager {
	public $pager_img_first				= 'img/paging_first.gif';
	public $pager_img_last				= 'img/paging_last.gif' ;
	public $pager_img_prev				= 'img/paging_prev.gif';
	public $pager_img_next				= 'img/paging_next.gif';
	public $pager_img_first_disabled	= 'img/paging_first_disabled.gif';
	public $pager_img_last_disabled		= 'img/paging_last_disabled.gif';
	public $pager_img_prev_disabled		= 'img/paging_prev_disabled.gif';
	public $pager_img_next_disabled		= 'img/paging_next_disabled.gif';

	protected $grid = null;
	protected $pagenum, $buffer;

	protected $_default_template = '
						if($page==1)
						{
							$paging_first = "<img src=\"".$this->pager_img_first_disabled."\" border=\"0\">";
							$paging_prev = "<img src=\"".$this->pager_img_prev_disabled."\" border=\"0\">";
						}
						else
						{
							$link = "".$this->buffer->client_id().".set_var(\'".$this->pagenum->client_id()."\',".(1).");".$this->buffer->client_id().".update()";
							$paging_first = "<a href=\"javascript:".$link."\"><img src=\"".$this->pager_img_first."\" border=\"0\"></a>";
							$link = "".$this->buffer->client_id().".set_var(\'".$this->pagenum->client_id()."\',".($page-1).");".$this->buffer->client_id().".update()";
							$paging_prev = "<a href=\"javascript:".$link."\"><img src=\"".$this->pager_img_prev."\" border=\"0\"></a>";
						}
						if($page==$pcount)
						{
							$paging_last = "<img src=\"".$this->pager_img_last_disabled."\" border=\"0\">";
							$paging_next = "<img src=\"".$this->pager_img_next_disabled."\" border=\"0\">";
						}
						else
						{
							$link = "".$this->buffer->client_id().".set_var(\'".$this->pagenum->client_id()."\',".($page+1).");".$this->buffer->client_id().".update()";
							$paging_next = "<a href=\"javascript:".$link."\"><img src=\"".$this->pager_img_next."\" border=\"0\"></a>";
							$link = "".$this->buffer->client_id().".set_var(\'".$this->pagenum->client_id()."\',".($pcount).");".$this->buffer->client_id().".update()";
							$paging_last = "<a href=\"javascript:".$link."\"><img src=\"".$this->pager_img_last."\" border=\"0\"></a>";
						}

						$link = "".$this->buffer->client_id().".set_var(\'".$this->pagenum->client_id()."\',document.getElementById(\'".$place.$this->client_id()."_pnum\').value);".$this->buffer->client_id().".update()";
						?> 
							<?=$paging_first?>&nbsp;&nbsp;
							<?=$paging_prev?>&nbsp;&nbsp;
								<input type="text" id="<?=$place.$this->client_id()?>_pnum" class="grid_pagenum" value="<?=$page?>"/> 
									of <?=$pcount?> 
								<input type="button" value="Go" onclick="<?=$link?>"/>&nbsp;&nbsp;
							<?=$paging_next?>&nbsp;&nbsp;
							<?=$paging_last?>&nbsp;&nbsp;
						';
	
	//--------------------------------------------------
	/**
	 * Sets the ajax buffer for this ajax grid pager
	 * @param ajax_buffer $buffer
	 * @param int $pagenum
	 */
	public function reset_buffer(&$buffer,&$pagenum)
	{
		$this->buffer = &$buffer;
		$this->pagenum = &$pagenum;
		$this->pager->set_curpage($this->pagenum->get_value());
		$this->pager->recalc();
	}
	//--------------------------------------------------
	/**
	 * Creates new ajax grid pager
	 * @param string $id
	 * @param int $total
	 * @param int $pagesize
	 * @param string $template
	 */
	public function __construct($id, $total, $pagesize = 10, $template = null)
	{
		parent::__construct($id,$total,$pagesize,$template);
	}


	//--------------------------------------------------
	/**
	 * name: render
	 * @param $place place for pager
	 */
	public function render($place = 'top')
	{
		return parent::render($place);
	}
};