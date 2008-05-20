<?php

/**
 * Closure class
 */
class __closure{
	protected $_code = '';	
	protected $_env = array();
	protected $_args = array();
	protected $_refs = array();
	
	public function __args()
	{return $this->_args;}
	
	public function __ref($key,&$obj)
	{
		$this->_refs[$key] = &$obj;
	}
	
	public function __construct($code, $env, $args)
	{
		$this->_code = $code.';';
		$this->_env = $env;
		$this->_args = $args;	
	}
	
	public function invoke()
	{
		$_argv = func_get_args();
		foreach($this->_refs as $__key => &$__ref)
			$$__key =& $__ref;

	    foreach($this->_args as $__num => $__key)
			if(!isset($$__key))
	      		$$__key =& $_argv[$__num];

	    foreach(array_keys($this->_env) as $__key){
			if(!isset($$__key))
	      		$$__key =& $this->_env[$__key];		
		}
	    return eval($this->_code);
	}
};

/**
 * Closure class
 */
class closure{
	protected static $_COUNTER = 0;
	protected static $_PREFIX = '__closure__';
	protected $_id = '';
	protected $_func = '';
	protected $_code = '';
	protected $_escaped = '';
	protected $_grabber = '';
	protected $_env = '';
	protected $_args = '';
	protected function generate_id(){
		return self::$_PREFIX.(self::$_COUNTER++);
	}
	/**
	 * Returns closure initialization
	 * @param string $code
	 * @return string
	 */
	protected function makeGrabber($code)
	{
		$zot = $seen = array();
		$args = '';
		foreach($this->_args as &$arg){
			$arg = str_replace('$','',$arg);
			$args .= (empty($args)?'':',')."'".$arg."'";
		}		
		if(preg_match_all('/\$(\w+)/', $code, $m))
		foreach($m[1] as $var) 
			if(!isset($seen[$var]) || !$seen[$var]++){
				if(!in_array($var,$this->_args)){
		    		$zot[] = "'$var' => &\$$var";		    	
				}
			}	
		$this->_grabber = join(', ', $zot);
		$this->_code = $code;
		$this->_escaped = preg_replace("/['\\\\]/", "\\$&", $code);		
		$this->_grabber = "new __closure('{$this->_escaped}', array($this->_grabber), array($args))";
		return $this->_grabber;
	}	
	/**
	 * Construct
	 */
	public function __construct()
	{
		$this->_args = func_get_args();
		$this->_id = $this->generate_id();
		$GLOBALS[$this->_id] = &$this;		
	}
	/**
	 * Returns created closure id
	 * @return string
	 */
	public function __toString(){
		return $this->_id;
	}
	/**
	 * @param string $code
	 * @return string
	 */
	public function __get($code)
	{
		return 'return '.$this->makeGrabber($code).';';
	}
	/**
	 * @param string $code
	 * @return string
	 */
	public function invoke($code)
	{
		$grabber = $this->makeGrabber($code);
		return 'return closure('.$grabber.');';
	}
	
}

/**
 * Runs closure
 * @param __closure $closure
 */
function closure($cl)
{$cl->invoke();}