<?php
require_once(dirname(__FILE__).'/common.inc.php');
require_once(dirname(__FILE__).'/var.inc.php');
require_once(dirname(__FILE__).'/buffer.inc.php');

class ajax_entities_manager_action{
	const DEL = 0;
	const ADD = 1;
	const UPD = 2;
	const NOACT = 3;
	public $DEL = self::DEL;
	public $ADD = self::ADD;
	public $UPD = self::UPD;
	public $NOACT = self::NOACT;
};

class ajax_entities_manager extends smphp_control {
	/**
	 * @var ajax_buffer
	 */
	protected $_ajax_buf = null;	
	/**
	 * @var ajax_var
	 */
	protected $actionvar = null;
	/**
	 * @var ajax_var
	 */
	protected $itemvar = null;
	/**
	 * @var ajax_var
	 */
	protected $confirmvar = null;
	/**
	 * @var ajax_entities_manager_action
	 */	
	public $action;
	/**
	 * 
	 * @param string $id
	 * @param ajax_buffer $ajax_buf
	 */
	public function __construct($id, &$ajax_buf)
	{
		parent::__construct($id);
		$this->action = new ajax_entities_manager_action();
		$this->_ajax_buf = $ajax_buf;
		$this->actionvar = new ajax_var($this->UID('actionvar'),ajax_entities_manager_action::NOACT);
		$this->itemvar = new ajax_var($this->UID('itemvar'),'');
		$this->confirmvar = new ajax_var($this->UID('confirmvar'),false);
		$this->_ajax_buf->register_var($this->actionvar);
		$this->_ajax_buf->register_var($this->itemvar);
		$this->_ajax_buf->register_var($this->confirmvar);
	}
	
	/**
	 * Renders client side javascript
	 * @return string
	 */
	public function render_client_side()
	{
		return '
		<script type="text/javascript">
			var '.$this->client_id().' = null;
			function '.$this->client_id().'_init(){
			'.$this->client_id().' = new EntitiesManager('.$this->_ajax_buf->client_id().',
				\''.$this->actionvar->client_id().'\',
				\''.$this->confirmvar->client_id().'\',
				\''.$this->itemvar->client_id().'\');
			}
			Event.observe(window,\'load\','.$this->client_id().'_init);
		</script>
		';
	}
	
	/**
	 * Returns true if any action is occured
	 * @return bool
	 */
	public function isAnyAction()
	{
		return $this->actionvar->get_value() != ajax_entities_manager_action::NOACT;
	}
	/**
	 * Returns action
	 * @return int
	 */
	public function getAction()
	{
		return $this->actionvar->get_value();
	}
	/**
	 * Returns action
	 * @return int
	 */
	public function eraseAction()
	{
		return $this->actionvar->set_value($this->action->NOACT);
	}	
	/**
	 * Returns item
	 * @return string
	 */
	public function getItem()
	{
		return stripslashes($this->itemvar->get_value());
	}
	/**
	 * Returns confirmed
	 * @return bool
	 */
	public function isConfirmed()
	{
		return (bool)$this->confirmvar->get_value();
	}				
};
