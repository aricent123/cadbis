<?php
require_once(dirname(__FILE__)."/../control.inc.php");
require_once(dirname(__FILE__)."/common.inc.php");

class ajax_buffer_method{	
	const APPEND_AFTER = 'append_after';
	const APPEND_BEFORE =  'append_before';
	const REPLACE =  'replace';
};

/**
 * Ajax buffer
 */
class ajax_buffer extends smphp_control{
	protected $content;
	protected $is_post_back;
	protected $vars;
	protected $request;
	protected $postback_url;
	protected $client_id = null;
	protected static $_post_back_ok = false;
	protected $show_progress = false;
	protected $method = ajax_buffer_method::REPLACE;
	
	//--------------------------------------------------
	public function set_method($value)
	{
		$this->method = $value;
	}
	//--------------------------------------------------
	public function show_progress($value)
	{
		$this->show_progress = ($value != false);
	}
	//--------------------------------------------------
	public function is_post_back()
	{
		return $this->is_post_back;
	}
	//--------------------------------------------------
	public function client_id()
	{
		if(!$this->client_id)
			$this->client_id = $this->UID(ajax_common::AJAX_BUFFER_PF);
		return $this->client_id;
	}
	//--------------------------------------------------
	protected function client_side_init()
	{
	return '
	<script language="javascript" type="text/javascript">
		var '.$this->client_id().' = null;
		function '.$this->client_id().'_init()
		{
			'.$this->client_id().' = new ajax_buffer(\''.$this->client_id().'\',\''.$this->postback_url.'\',\''.ajax_common::AJAX_VAR_HIDDEN_PF.'\');'.spchr::endl.'
			'.$this->client_id().'.show_progress('.(($this->show_progress)?'true':'false').');'.'
			'.$this->client_id().'.set_method(\''.($this->method).'\');'.'
			'.$this->client_side_vars().'
		}
	</script>'.spchr::endl;
	}
	//--------------------------------------------------
	protected function client_side_register()
	{
	return '
	<script language="javascript" type="text/javascript">
		Event.observe(window,\'load\','.$this->client_id().'_init);
	</script>'.spchr::endl;
	}
	//--------------------------------------------------
	/**
	 * Render variables list
	 *
	 * @return string
	 */
	protected function render_vars()
	{
		$result = "";
		if(!is_null($this->vars))
			foreach($this->vars as $name => $var)
			{
				$result .= spchr::endl.$var->render().spchr::endl;
			}
		return $result;
	}
	//--------------------------------------------------
	/**
	 *
	 * @return string generated client-side vars list
	 */
	protected function client_side_vars()
	{
		$result = "";
		if(!is_null($this->vars))
			foreach($this->vars as $name => $var)
			{
				$result .= $this->client_id().'.set_var(\''.$var->client_id().'\',\''.$var->get_value().'\');'.spchr::endl;
			}
		return $result;
	}
	//--------------------------------------------------
	/**
	 * Begin of the buffer area
	 *
	 */
	public function start()
	{
		echo '<div id="'.$this->client_id().'">';
		ob_start();
		echo $this->render_vars();
	}
	//--------------------------------------------------
	/**
	 * End of the buffer area
	 *
	 */
	public function end()
	{
		$this->content .= ob_get_contents();
		ob_end_flush();
		if($this->is_post_back)
		{
			while (@ob_end_clean());
			self::$_post_back_ok = true;
			die($this->content);
		}
		echo '</div>';
	}
	//--------------------------------------------------
	/**
	 * Registers an ajax variable in the buffer
	 *
	 * @param ajax_var $variable
	 */
	public function register_var(&$variable)
	{
		if(!isset($this->vars[$variable->get_id()]))
			$this->vars[$variable->get_id()]= &$variable;
		$client_id = $variable->client_id();
		if($this->is_post_back && isset($_REQUEST[$client_id]))
		{
			$variable->set_value($_REQUEST[$client_id]);
		}
	}
	//--------------------------------------------------
	/**
	 * Set postback url for buffer
	 *
	 * @param string $url
	 */	
	public function set_postback_url($url)
	{
		$this->postback_url = $url;
	}
	//--------------------------------------------------
	/**
	 * Returns postback url for buffer
	 *
	 * @return string
	 */	
	public function get_postback_url()
	{
		return $this->postback_url;
	}
	
	//--------------------------------------------------
	/**
	 * Registers array of variables in the buffer
	 *
	 * @param array $variables
	 */
	public function register_vars(&$variables)
	{
		foreach($variables as &$variable)
			$this->register_var($variable);
	}
	//--------------------------------------------------
	public function __construct($id)
	{
		parent::__construct($id);
		$rid = $this->client_id();
		$this->is_post_back = isset($_REQUEST[$rid]);
		$this->content = '';
		ob_start();
	}
	//--------------------------------------------------
	public function __destruct()
	{
		$content = ob_get_contents();
		if(!$this->is_post_back && !self::$_post_back_ok)
		{
			if(stristr($content,tags::head_e))
				$content = str_ireplace(tags::head_e,$this->client_side_init().tags::head_e,$content);
			elseif(stristr($content,tags::body))
				$content = str_ireplace(tags::body,tags::body.$this->client_side_init(),$content);
			else
				$content .= $this->client_side_init();

			if(stristr($content,tags::body))
				$content = str_ireplace(tags::body,tags::body.$this->client_side_register(),$content);
			elseif(stristr($content,tags::head_e))
				$content = str_ireplace(tags::head_e,tags::head_e.$this->client_side_register(),$content);
			else
				$content .= $this->client_side_register();
		}
		try{
			@ob_end_clean();
		}catch(Exception $e)
		{/* ignore close errors */}
		echo($content);
	}
};

