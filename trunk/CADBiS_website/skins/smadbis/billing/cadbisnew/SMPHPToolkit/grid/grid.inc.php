<?php
require_once(dirname(__FILE__)."/../common.inc.php");
require_once(dirname(__FILE__)."/datasource.inc.php");
require_once(dirname(__FILE__)."/../control.inc.php");

////////////////////////////////////////////////////
/**
 * Grid Formatter
 */
class grid_formatter extends basic_formatter
{
	public function format($data, $type, $number = 0, $columns = null)
	{
		return parent::format($data, $type);
	}
};

////////////////////////////////////////////////////
/**
 * Grid Data Item
 */
class grid_header_item{
	protected $name;
	protected $title;
	protected $type;
	protected $sortable;
	protected $formatter;
	protected $visible;
	protected $filterable;

	public function get_visible()
	{
		return $this->visible;
	}

	public function set_visible($value)
	{
		$this->visible = $value;
	}

	public function get_formatter()
	{
		return $this->formatter;
	}

	public function set_formatter($value)
	{
		$this->formatter = $value;
	}

	public function is_sortable()
	{
		return $this->sortable;
	}
	
	public function is_filterable()
	{
		return $this->filterable;
	}
	
	public function set_filterable($value)
	{
	  $this->filterable = $value;
	}	
	
	public function set_sortable($value)
	{
	  $this->sortable = $value;
	}

	public function get_name()
	{
		return $this->name;
	}

	public function set_name($value)
	{
	  $this->name = $value;
	}

	public function get_title()
	{
		return $this->title;
	}

	public function set_title($value)
	{
	  $this->title = $value;
	}

	public function get_type()
	{
		return $this->type;
	}

	public function set_type($value)
	{
	  $this->type = $value;
	}
	//--------------------------------------------------
	/**
	 * 
	 * @param string $name
	 * @param string $title
	 * @param int $type
	 * @param bool $sortable
	 * @param grid_formatter $formatter
	 * @param bool $visible
	 */
	public function __construct($name, $title, $type = type::STRING, $sortable = false, $formatter = null, $visible = true, $filterable = false)
	{
		$this->title = $title;
		$this->name = $name;
		$this->type = $type;
		$this->sortable = $sortable;
		$this->visible = $visible;
		$this->filterable = $filterable;
		if($formatter != null)
			$this->formatter = $formatter;
		else
			$this->formatter = new grid_formatter();
	}
}

////////////////////////////////////////////////////
/**
 * Grid Header Item Array
 */
class grid_header_item_array{
	protected $items;
	/**
	 * Returns array of items
	 *
	 * @return array of grid_header_item
	 */
	public function get_items()
	{
		return $this->items;
	}

	public function set_items($value)
	{
	  $this->items = $value;
	}

	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $items
	 */
	public function __construct()
	{
		$args = func_get_args();
		foreach($args as $item)
		{
			$this->add($item);
		}
	}

	//--------------------------------------------------
	/**
	 * name: add
	 * params: $item
	 */
	public function add($item)
	{
		$this->items[$item->get_name()] = $item;
	}

	//--------------------------------------------------
	/**
	 * name: add
	 * params: $item
	 */
	public function add_items()
	{
		$args = func_get_args();
		foreach($args as $item)
		{
			$this->add($item);
		}
	}
	//--------------------------------------------------
	/**
	 * name: get_types
	 * params:
	 */
	public function get_types()
	{
		$types = array();
		foreach($this->items as $name => $item)
			$types[] = $item->get_type();
		return $types;
	}
	//--------------------------------------------------
	/**
	 * name: get_types
	 * params:
	 */
	public function is_visible($name)
	{
		return $this->items[$name]->get_visible();
	}

	//--------------------------------------------------
	/**
	 * name: get_names
	 * params:
	 */
	public function get_names()
	{
		return array_keys($this->items);
	}
	//--------------------------------------------------
	public function get($name)
	{
		return $this->items[$name];
	}
};


////////////////////////////////////////////////////
/**
 * Grid
 */
class grid extends smphp_control {
	public $cssclass = "grid";
	public $cellclass = "grid_cell";
	public $headercellclass = "grid_header_cell";
	public $no_data_message = 'no data';
	
	/**
	 * @var grid_data_source
	 */
	protected $datasource = null;
	protected $cellspacing = 0;
	protected $cellpadding = 0;
	protected $cache_enabled = true;
	protected $cache;
	protected $header_template;
	public $img_sort_asc		= 'img/sort_asc.gif';
	public $img_sort_desc		= 'img/sort_desc.gif';

	
	protected $_current_sorting = sorting::DEFAULT_SORT;
	protected $_sort_dir = sorting::SORT_DIR_DEFAULT;

	protected $_default_header_template = 'if($sortable)
											{
												echo spchr::tab2."<a href=\"$base_url?$sortby_param=$sortname&$direction_param=$direction&".$params."\">".$title."</a>".spchr::endl;
												if($direction == sorting::SORT_DIR_ASC)
													echo "<img src=\"".$this->img_sort_desc."\"/>";
												else
													echo "<img src=\"".$this->img_sort_asc."\"/>";
											}
											else
												echo spchr::tab2.$title.spchr::endl;';


	//--------------------------------------------------
	public function __construct($id, $datasource = null, $header_template = null){
		parent::__construct($id);
		$this->datasource = $datasource;
		$this->cache = new cacher();
		if($header_template != null)
			$this->header_template = $header_template;
		else
			$this->header_template = $this->_default_header_template;
			
		if(isset($_REQUEST[$this->get_sortby_param()]))
			$this->_current_sorting = $_REQUEST[$this->get_sortby_param()];
		else
			$this->_current_sorting = sorting::DEFAULT_SORT;
		if(isset($_REQUEST[$this->get_sort_direction_param()]))
			$this->_sort_dir = $_REQUEST[$this->get_sort_direction_param()];
		else
			$this->_sort_dir = sorting::SORT_DIR_DEFAULT;		
	}
	//--------------------------------------------------
	public function get_header_template()
	{
		return $this->header_template;
	}
	//--------------------------------------------------
	public function set_header_template($value)
	{
		$this->header_template = $value;
	}
	//--------------------------------------------------
	public function get_cache_enabled()
	{
		return $this->cache_enabled;
	}
	//--------------------------------------------------
	public function set_cache_enabled($value)
	{
		$this->cache_enabled = $value;
	}
	//--------------------------------------------------
	public function get_cellspacing()
	{
		return $this->cellspacing;
	}
	//--------------------------------------------------
	public function set_cellspacing($value)
	{
	  $this->cellspacing = $value;
	}
	//--------------------------------------------------
	public function get_cellpadding()
	{
		return $this->cellpadding;
	}
	//--------------------------------------------------
	public function set_cellpadding($value)
	{
	  $this->cellpadding = $value;
	}
	//--------------------------------------------------
	public function get_cellclass()
	{
		return $this->cellclass;
	}
	//--------------------------------------------------
	public function set_cellclass($value)
	{
	  $this->cellclass = $value;
	}
	//--------------------------------------------------
	public function get_headercellclass()
	{
		return $this->headercellclass;
	}
	//--------------------------------------------------
	public function set_headercellclass($value)
	{
	  $this->headercellclass = $value;
	}
	//--------------------------------------------------
	public function get_datasource()
	{
		return $this->datasource;
	}
	//--------------------------------------------------
	public function set_datasource($value)
	{
	  $this->datasource = $value;
	}
	//--------------------------------------------------
	public function get_cssclass()
	{
		return $this->cssclass;
	}
	//--------------------------------------------------
	public function set_cssclass($value)
	{
	  $this->cssclass = $value;
	}

	//--------------------------------------------------
	public function get_sortby_param()
	{
		return $this->UID('sort_by');
	}
	//--------------------------------------------------
	public function get_sort_direction_param()
	{
		return $this->UID('direction');
	}
	//--------------------------------------------------
	public function set_current_sorting($name)
	{
		$this->_current_sorting = $name;
	}
	//--------------------------------------------------
	public function set_sort_direction($dir)
	{
		$this->_sort_dir = $dir;
	}	
	//--------------------------------------------------
	public function get_current_sorting()
	{
		return $this->_current_sorting;
	}
	//--------------------------------------------------
	public function get_sort_direction()
	{
		return $this->_sort_dir;
	}
	/**
	 * name: render
	 * params:
	 */
	public function render($additional_values = null)
	{
		$out = "";
		$out .= "<table cellspacing=\"".$this->cellspacing."\" cellpadding=\"".$this->cellpadding."\" class=\"".$this->cssclass."\" id=\"".$this->client_id()."\">".spchr::endl;
		
		
		if($this->datasource !== null)
		{
			$types = $this->datasource->get_header()->get_types();
			$names = $this->datasource->get_header()->get_names();
			$header_items = array_values($this->datasource->get_header()->get_items());
			$out .= "<tr>".spchr::endl;
			$header_class = $this->headercellclass;
			$base_url = $this->base_url;
			$sortby_param = $this->get_sortby_param();
			$current_sorting = $this->get_current_sorting();
			$current_direction = $this->get_sort_direction();
			$direction_param = $this->get_sort_direction_param();
			if(!is_null($additional_values))
				extract($additional_values);
	
			foreach($header_items as $hitem)
			{
				if(!$hitem->get_visible())
					continue;
				$sortable = $hitem->is_sortable();
				$sortname = $hitem->get_name();
				$title = $hitem->get_title();
				$direction = ($current_sorting==$hitem->get_name() && $current_direction!=sorting::SORT_DIR_DESC)?sorting::SORT_DIR_DESC:sorting::SORT_DIR_ASC;
				$params = $this->url_params($sortby_param,$direction_param);
				ob_start();
				eval($this->header_template);
				$result = ob_get_contents();
				$out .= spchr::tab."<td class=\"{$header_class}\">".spchr::endl;
				$out .= spchr::tab2.$result.spchr::endl;
				$out .= spchr::tab."</td>".spchr::endl;
				ob_end_clean();
			}
			$out .= "</tr>".spchr::endl;
			$values =$this->datasource->get_values();
			if(count($values))
				foreach($values as $num=>$columns)
				{
					$col_id = 0;
					$out .= "<tr>".spchr::endl;
					foreach($columns as $data)
					{
						if(!$this->datasource->get_header()->is_visible($names[$col_id]))
						{$col_id++;continue;}
						$out .= spchr::tab."<td class=\"".$this->cellclass."\">".spchr::endl;
						$out .= spchr::tab2.$header_items[$col_id]->get_formatter()->format($data,$types[$col_id],$col_id++,$columns).spchr::endl;
						$out .= spchr::tab."</td>".spchr::endl;
					}
					$out .= "</tr>".spchr::endl;
				}
			else
				$out .= '<tr><td colspan="'.sizeof($header_items).'">'.$this->no_data_message.'</td></tr>';
		}
		else
			$out.='<tr><td>'.$this->no_data_message.'</td></tr>';
		$out .= "</table>".spchr::endl;
		return $out;
	}
};
