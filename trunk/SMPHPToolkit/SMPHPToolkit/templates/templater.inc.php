<?php
require_once(dirname(__FILE__)."/../common.inc.php");
require_once(dirname(__FILE__)."/../control.inc.php");

/**
 * Template manager
 */
class templater extends smphp_control {
	/**
	 * @var array $_variables
	 */
	protected $_variables = array();
	/**
	 * Template path
	 * @var string
	 */	
	protected $_template_path = "";
	/**
	 * Cretes new templater
	 * @param array $variables
	 * @param string $variables
	 */
	public function __construct($template_path = null,$variables = null){
		if($variables != null)
			$this->set_variables($variables);		
		if($template_path!=null)
			$this->set_template($template_path);
	}
	/**
	 * Sets the template path 
	 * @param string $template_path
	 */
	public function set_template($template_path)
	{
		$this->_template_path = $template_path;
	}	
	/**
	 * Sets the variables for the template (assoc array)
	 * @param array $variables
	 */
	public function set_variables($variables)
	{
		$this->_variables = $variables;
	}
	/**
	 * Renders template with applied variables (rendered result)
	 * @param array $variables
	 * @return string
	 */	
	public function render($variables = null)
	{
		if($variables != null)
			$this->set_variables($variables);
		ob_start();
		extract($this->_variables);
		require($this->_template_path);				
		$result = ob_get_contents();
		@ob_end_clean();
		return $result;
	}
};