<?php
class pager{
	protected $pagesize;
	protected $total;
	protected $curpage = 1;		
	protected $curfirst = 0;
	protected $curlast = 0;
	
	public function get_curpage()
	{
		return $this->curpage;
	}
	//--------------------------------------------------	
	public function set_curpage($value)
	{		
		$this->curpage = $value;
		if($this->curpage <= 0)
			$this->curpage = 1;
		if($this->curpage > $this->get_pages_count() && $this->get_pages_count()>0)
			$this->curpage = $this->get_pages_count();			
	}
	//--------------------------------------------------	
	public function get_curlast()
	{
		return $this->curlast;
	}
	//--------------------------------------------------	
	public function set_curlast($value)
	{
		$this->curlast = $value;
	}
	//--------------------------------------------------	
	public function get_curfirst()
	{
		return $this->curfirst;
	}
	//--------------------------------------------------	
	public function set_curfirst($value)
	{
		$this->curfirst = $value;
	}
	//--------------------------------------------------
	public function get_pagesize()
	{
		return $this->pagesize;
	}
	//--------------------------------------------------	
	public function set_pagesize($value)
	{
		$this->pagesize = $value;
	}
	//--------------------------------------------------	
	public function get_total()
	{
		return $this->total;
	}
	//--------------------------------------------------	
	public function set_total($value)
	{
		$this->total = $value;
	}
	//--------------------------------------------------	
	public function recalc()
	{
		$this->curfirst = ($this->curpage-1)*$this->pagesize + 1;
		if($this->curfirst > $this->total)
			$this->curfirst = $this->total;
		$this->curlast = $this->curfirst + $this->pagesize - 1;
		if($this->curlast >= $this->total)
			$this->curlast = $this->total;		
	}
	
	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $page,$count
	 */
	public function __construct($total, $pagesize = 10)
	{
		$this->total = $total;
		$this->pagesize = $pagesize;
		$this->recalc();			
	}
	
	//--------------------------------------------------
	/**
	 * name: get_pages_count
	 * params: 
	 */
	public function get_pages_count()
	{
		if($this->pagesize>0 && $this->total>0)
			return ceil($this->total / $this->pagesize);
		else
			return 1;
	}
	//--------------------------------------------------
	/**
	 * name: get_page
	 * params: $page
	 */
	public function get_page($data,$page = null)
	{
		if(!is_null($page))
		{	
			$this->curpage = $page;
			$this->recalc();
			return array_slice($data,$this->curfirst - 1,$this->curlast - $this->curfirst + 1);
		}
		return $data;		
	}
};
