<?php

////////////////////////////////////////////////////
/**
 * Grid Data Source
 */
class grid_data_source{
	protected $header;
	protected $values;
	/**
	 * Returns array of grid header items
	 * @return grid_header_item_array
	 */
	public function get_header()
	{
		return $this->header;
	}
	/**
	 * Sets grid header
	 *
	 * @param grid_header_item_array $value
	 */
	public function set_header($value)
	{
	  $this->header = $value;
	}
	/**
	 * Returns array of values
	 * @return array
	 */
	public function get_values()
	{
		return $this->values;
	}
	/**
	 * Returns rows count
	 * @return int 
	 */
	public function get_rows_count()
	{
		return count($this->values);
	}	
	/**
	 * Sets values array
	 *
	 * @param array $value
	 */
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
	/**
	 * returns sizeof values(count)
	 * @return int
	 */
	public function size()
	{
		if(!is_null($this->values))
			return sizeof($this->values);
	}
};
