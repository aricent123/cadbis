<?php
require_once(dirname(__FILE__).'/../control.inc.php');


/**
 * Ajax value
 */
class ajax_var extends smphp_control{
	protected $value;

	public function get_value()
	{
		return $this->value;
	}

	public function set_value($value)
	{
		$this->value = $value;
	}

	public function render()
	{
		return '<input type="hidden" id="'.$this->client_id().ajax_common::AJAX_VAR_HIDDEN_PF.'" value="'.$this->value.'"/>';
	}

	public function client_id()
	{
		return $this->UID(ajax_common::AJAX_VAR_CLIENT_PF);
	}

	public function __construct($id, $value)
	{
		parent::__construct($id);
		$this->value = $value;
	}
};
