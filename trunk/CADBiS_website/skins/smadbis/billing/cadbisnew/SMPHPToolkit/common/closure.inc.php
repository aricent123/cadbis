<?

class closure{
	protected static $_COUNTER = 0;
	protected static $_PREFIX = '_closure_';
	protected $_args;
	
	protected function generate_id()
	{
		return self::$_PREFIX.(self::$_COUNTER++);
	}
	
	public function __construct()
	{		
		foreach(func_get_args() as $arg)
			die($$arg);//$this->_args[] = $$arg;
		$_GLOBALS[$this->generate_id()] = $$this;
	}
	
    function __get($func_name) 
    {
		$prop_value = $this->_func;
        return true;
    }
	
	public function __toString()
	{
		return $$this;
	}
};
$arr = array(1,2,3);
die(var_dump(new closure($arr)));
//$var = ${(new closure($arr))}->${'return $arr*10;'};
