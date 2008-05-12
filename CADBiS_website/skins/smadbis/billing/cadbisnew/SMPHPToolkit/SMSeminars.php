<?php
require_once(dirname(__FILE__)."/common.inc.php");
require_once(dirname(__FILE__)."/storage.inc.php");
require_once(dirname(__FILE__)."/grid/pager.inc.php");
require_once(dirname(__FILE__)."/grid/grid.inc.php");

class seminar extends file_storage_object 
{ 
	protected $title;
	protected $desc;
	protected $time;
	
	public function get_time()
	{
		return $this->time;
	}
	
	public function set_time($value)
	{
		$this->time = $value;
	}
	
	public function set_title($value)
	{
	  $this->title = $value;
	}		
	public function get_title()
	{
		return $this->title;
	}
	public function set_desc($value)
	{
	  $this->desc = $value;
	}	
	public function get_desc()
	{
		return $this->desc;
	}
	
	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $name,$desc
	 */
	public function __construct($title,$desc,$time = null)
	{
		$this->title = $title;
		$this->desc = $desc;
		$this->time = (is_null($time))?time():$time;
	}
};

class seminars extends file_storage 
{
	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: 
	 */
	public function __construct($dirs)
	{
		global $GV;
		parent::__construct($dirs,'seminar',array('title','time'));
	} 
};



class sem_formatter extends grid_formatter 
{
	public function format($data, $type)
	{
		switch($type){
		case type::DATE_TIME:
			return date('d/m/Y', $data);
		case type::LINK:
			return '<a href="http://tralala">'.$data.'</a>';
		}
	}
};