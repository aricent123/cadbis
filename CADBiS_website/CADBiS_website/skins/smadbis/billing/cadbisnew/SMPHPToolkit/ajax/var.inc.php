<?php
require_once(dirname(__FILE__).'/../control.inc.php');


/**
 * Ajax value
 */
class ajax_var extends smphp_control{
	/**
	 * @var string
	 */
	protected $value;
	/**
	 * Returns the value of the variable
	 *
	 * @return string
	 */
	public function get_value()
	{
		return $this->value;
	}
	/**
	 * Sets the value for the variable
	 *
	 * @param string $value
	 */
	public function set_value($value)
	{
		$this->value = $value;
	}
	/**
	 * Renders this variable on the client code
	 * @return string
	 */
	public function render()
	{
		return '<input type="hidden" id="'.$this->client_id().ajax_common::AJAX_VAR_HIDDEN_PF.'" value="'.$this->value.'"/>';
	}
	/**
	 * Returns client id
	 * @return string
	 */
	public function client_id()
	{
		return $this->UID(ajax_common::AJAX_VAR_CLIENT_PF);
	}
	/**
	 * Creates new ajax_var object
	 *
	 * @param string $id
	 * @param string $value
	 */
	public function __construct($id, $value)
	{
		parent::__construct($id);
		$this->value = $value;
	}
};
