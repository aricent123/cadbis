<?
require_once(dirname(__FILE__)."/common.inc.php");
/*	CSTruter PHP Web Control Class version 1.0
	Author: Christoff Truter

	Date Created: 3 November 2006
	Last Update: 12 November 2006

	e-Mail: christoff@cstruter.com
	Website: www.cstruter.com
	Copyright 2006 CSTruter				*/


abstract class smphp_control
{
	const CLIENT_ID_PRF = 'client_';
	protected $base_url;
	protected $id;	// If we need to place more than one control on a page, this will be the unique ID
	protected static $ids;

	public function get_id()
	{
		return $this->id;
	}

	public function set_id($id)
	{
		if(!is_array(self::$ids))
			self::$ids = array();
		if(is_null($id))
			throw new Exception('The required parameter is not present: id');
		if(in_array($id,self::$ids))
			throw new Exception('Only one control with the id \''.$id.'\' can be on the page');
		self::$ids[] = $id;
		$this->id = $id;
	}

	public function get_base_url()
	{
		return $this->base_url;
	}

	public function set_base_url($value)
	{
		$this->base_url = $value;
	}

	// Persist values sent via URL
	protected function url_params()
	{

		$excludes = func_get_args();
		for($i=0;$i<count($excludes);++$i)
		{
			if(is_array($excludes[$i]))
			{
				$excludes = array_merge($excludes,$excludes[$i]);
				unset($excludes[$i]);
			}
			if(is_null($excludes[$i]))
			{
				unset($excludes[$i]);
			}
		}

		$returnValue = "";

		foreach ($_GET as $key => $value)
		{
			if (!(in_array($key, $excludes))) $returnValue.="&$key=$value";
		}
		return $returnValue;
	}

	protected function UID($Name)
	{
		return $this->id.$Name;
	}
	//--------------------------------------------------

	public function client_id()
	{
		return self::CLIENT_ID_PRF.$this->id;
	}

	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $
	 */
	public function __construct($id)
	{
		$this->set_id($id);
	}

}
