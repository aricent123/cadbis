<?php

////////////////////////////////////////////////////
/**
 * Grid Data Source
 */
class grid_data_source{
	protected $header;
	protected $values;
	public function get_header()
	{
		return $this->header;
	}
	
	public function set_header($value)
	{
	  $this->header = $value;
	}
	
	public function get_values()
	{
		return $this->values;
	}
	
	public function get_rows_count()
	{
		return count($this->values);
	}	
	
	public function set_values($value)
	{
	  $this->values = $value;
	}
	
	//--------------------------------------------------
	/**
	 * name: add_column
	 * params: $header_item
	 */
	public function add_column($header_item)
	{
		if(!is_null($this->header))
			$this->header->add($header_item);
	}
	//--------------------------------------------------
	/**
	 * name: __construct
	 * params: $header
	 */
	public function __construct($header_items = null)
	{
		$this->header = $header_items;
	}
	//--------------------------------------------------
	/**
	 * name: add_row
	 * params: $columns
	 */
	public function add_row($columns)
	{
		$this->values[] = $columns;		
	}
	//--------------------------------------------------	
	public function size()
	{
		if(!is_null($this->values))
			return sizeof($this->values);
	}
};
