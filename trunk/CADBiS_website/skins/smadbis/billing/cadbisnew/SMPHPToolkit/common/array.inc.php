<?php

/**
 * Array object
 */
class __array{
	protected $_content;
	/**
	 * Creates new array
	 * @param array $arr
	 */
	public function __construct($arr){
		$this->_content = $arr;
	}
	/**
	 * Returns array itself
	 * @return array
	 */
	public function toArray(){
		return $this->_content;
	}
	/**
	 * Applies closure to each array element
	 * @param __closure $closure
	 */
	public function select($closure){
		$keys = $closure->__args();
		$key = $keys[0];
		foreach($this->_content as &$el){
			$closure->__ref($key, $el);
			$closure->invoke($el);
		}
	}
};
/**
 * Creates new array object
 * @return __array
 */
function arr(){return new __array(func_get_args());}
