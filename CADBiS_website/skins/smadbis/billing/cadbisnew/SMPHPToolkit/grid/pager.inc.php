<?php
require_once(dirname(__FILE__)."/../pager.inc.php");
require_once(dirname(__FILE__)."/../control.inc.php");

class grid_pager extends smphp_control{

	protected $pager = null;
	protected $template;
	protected $cl_next = "pager_next";
	protected $cl_first = "pager_first";
	protected $cl_last = "pager_last";
	protected $cl_prev = "page_prev";

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
							$paging_first = "<a href=\"$base_url?$param_page=1&params=$params\"><img src=\"".$this->pager_img_first."\" border=\"0\"></a>";
							$paging_prev = "<a href=\"$base_url?$param_page=".($page-1)."&params=$params\"><img src=\"".$this->pager_img_prev."\" border=\"0\"></a>";
						}
						if($page==$pcount)
						{
							$paging_last = "<img src=\"".$this->pager_img_last_disabled."\" border=\"0\">";
							$paging_next = "<img src=\"".$this->pager_img_next_disabled."\" border=\"0\">";
						}
						else
						{
							$paging_next = "<a href=\"$base_url?$param_page=".($page+1)."&params=$params\"><img src=\"".$this->pager_img_next."\" border=\"0\"></a>";
							$paging_last = "<a href=\"$base_url?$param_page=$pcount&params=$params\" border=\"0\"><img src=\"".$this->pager_img_last."\" border=\"0\"></a>";
						}

						echo "	$paging_first&nbsp;&nbsp;
								$paging_prev&nbsp;&nbsp;
								$page of $pcount&nbsp;&nbsp;
								$paging_next&nbsp;&nbsp;
								$paging_last&nbsp;&nbsp;";
						';


	public function get_pcount()
	{
		return $this->pager->get_pages_count();
	}
	public function get_template()
	{
		return $this->template;
	}

	public function set_template($value)
	{
		$this->template = $value;
	}
	public function set_curpage($value)
	{
		$this->pager->set_curpage($value);
		$this->pager->recalc();
	}
	public function set_total($value)
	{
		$this->pager->set_total($value);
	}	
	public function set_pagesize($value)
	{
		$this->pager->set_pagesize($value);
	}
	public function get_pagesize()
	{
		return $this->pager->get_pagesize();
	}
	public function get_curpage()
	{
		return $this->pager->get_curpage();
	}


	//--------------------------------------------------
	/**
	 * name: render
	 * params:
	 */
	public function render($place)
	{
		$out = "";
		$this->pager->recalc();
		$base_url = $this->base_url;
		$param_page = $this->UID('page');
		$page = $this->pager->get_curpage();
		$params = $this->url_params($this->UID('page'));
		$pcount = $this->pager->get_pages_count();
		$from = $this->pager->get_curfirst();
		$to = $this->pager->get_curlast();
		$total = $this->pager->get_total();
		ob_start();
		eval($this->template);
		$result = ob_get_contents();
		$out .= $result;
		ob_end_clean();
		return $out;
	}

	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $page,$count
	 */
	public function __construct($id, $total, $pagesize = 10, $template = null)
	{
		parent::__construct($id);
		$this->pager = new pager($total, $pagesize);
		$page = (isset($_REQUEST[$this->UID('page')]))?$_REQUEST[$this->UID('page')]:1;
		$this->pager->set_curpage($page);
		$this->pager->recalc();
		if(is_null($template))
			$this->template = $this->_default_template;
		else
			$this->template = $template;
	}
};
