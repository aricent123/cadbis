<?php

require_once(dirname(__FILE__)."/pager.inc.php");
////////////////////////////////////////////////////
/**
 * Storage Config
 */
class file_storage_config
{
	const IVAL_PF = '_value';
	public $paths;
	public $lists_paths;
	public $count;
	public $last_id;

	public function __construct($paths = null)
	{
		if(!is_null($paths))
		{
			$this->paths['base'] = isset($paths['base'])?$paths['base']:null;
			$this->paths['storage'] = isset($paths['storage'])?$paths['storage']:null;
			$this->paths['lists'] = isset($paths['lists'])?$paths['lists']:null;
			$this->paths['file_conf'] = isset($paths['file_conf'])?$paths['file_conf']:null;
		}
	}
};



////////////////////////////////////////////////////
/**
 * Storage Object
 */
class file_storage_object
{
	protected $id;

    public function get_id()
    {
    	return $this->id;
    }

    public function set_id($value)
    {
    	$this->id = $value;
    }

    public function generate_id()
    {
    	return 'f'.utils::get_serial();
    }
};

////////////////////////////////////////////////////
/**
 * Storage Filter
 */
class file_storage_filter
{
	const COND_GREATER_EQUAL = 0x01;
	const COND_GREATER = 0x02;
	const COND_EQUAL = 0x03;
	const COND_LESSER_EQUAL = 0x04;
	const COND_LESSER = 0x05;
	const COND_LIKE = 0x08;

	protected $cond, $value;
	
	public function set_cond($value)
	{
		$this->cond = $value;
	}
	public function get_cond()
	{
		return $this->cond;
	}
	public function set_value($value)
	{
		$this->value = $value;
	}
	public function get_value()
	{
		return $this->value;
	}
	
	public function __toString()
	{
		return $this->cond.':'.$this->value;
	}
	
	public function __construct($cond, $value)
	{
		$this->cond = $cond;
		$this->value = $value;	
	}

}

////////////////////////////////////////////////////
/**
 * Storage
 */
class file_storage{
	protected static $sort_field;
	const DEFAULT_LIST_FNAME = 'default';

	protected $data = null;
	protected $list = null;
	protected $count = 0;
	protected $conf = null;
	protected $filtered_data = null;
	protected $sortables = null;
	protected $values_data = null;
	protected $cache_enabled = true;

	protected $separator1 = '{+*1*+}';
	protected $separator2 = '{*+2+*}';


	public function get_separator2()
	{
		return $this->separator2;
	}

	public function set_separator2($value)
	{
		$this->separator2 = $value;
	}
	public function get_separator1()
	{
		return $this->separator1;
	}

	public function set_separator1($value)
	{
		$this->separator1 = $value;
	}
	public function get_cache_enabled()
	{
		return $this->cache_enabled;
	}

	public function set_cache_enabled($value)
	{
		$this->cache_enabled = $value;
	}

	public function get_filter()
	{
		return $this->filter;
	}

	public function set_filter($value)
	{
		$this->filter = $value;
	}
    //--------------------------------------------------
	public function set_sortables($sortables)
	{
		$this->sortables = $sortables;
	}
    //--------------------------------------------------
    public function get_conf()
    {
    	if(file_exists($this->conf->paths['file_conf']))
			$this->conf = unserialize(file_get_contents($this->conf->paths['file_conf']));
		return $this->conf;
    }

    //--------------------------------------------------
    public function get_count()
    {
		if(!$this->cache_enabled || !$this->count || !$this->conf)
		{
			$this->get_conf();
			$this->count = $this->conf->count;
		}
		return $this->count;
    }
    //--------------------------------------------------
	protected function hash_filters($filters)
	{
		if(is_array($filters))
			return implode('|',$filters);
		else
			return ''.$filters;		
	}	
    //--------------------------------------------------
    public function get_count_bycond($field, $filters)
    {	    	
    	$cond = $this->hash_filters($filters);
    	if(!$this->cache_enabled || !$this->filtered_data[$field][$cond])
			$this->get_data_bycond($field, $filters);
		return sizeof($this->filtered_data[$field][$cond]);
    }

    //--------------------------------------------------
    public function __construct($paths = null, $bo_class = "file_storage_object", $sortables = null, $cache_enabled = true)
    {
    	$this->cache_enabled = $cache_enabled;
    	$this->conf = new file_storage_config($paths);
    	if(is_null($sortables))
    	{
    	$refl = new ReflectionClass($bo_class);
    	$props = $refl->getProperties();
		foreach ($props as $property)
			{
    			$this->sortables[] = $property->getName();
			}
    	}
    	else
    		$this->sortables = $sortables;
    	$this->get_conf();
    	$this->conf->lists_paths['default'] = self::DEFAULT_LIST_FNAME;
    }
    //--------------------------------------------------

    protected static function sort_u($a, $b)
    {
        $al = strtolower($a->{"get_".self::$sort_field}());
        $bl = strtolower($b->{"get_".self::$sort_field}());
        if ($al == $bl) {
            return 0;
        }
        return ($al > $bl) ? +1 : -1;
    }

    //--------------------------------------------------
    /**
     * name: sort_by_field
     * params:
     */
    protected function sort_by_field($data, $field_name)
    {
    	self::$sort_field = $field_name;
    	usort($data, array(__CLASS__, "sort_u"));
    	return $data;
    }
    //--------------------------------------------------    
	public function reset_all_data()
	{
		$this->data = null;
		$this->list = null;
		$this->filtered_data = null;
		$this->values_data = null;		
	}    
    //--------------------------------------------------
    public function update_stat()
    {
    	$dir = $this->conf->paths['storage'];
	    $files = utils::read_dir($dir);
	    $this->conf->count = count($files);
	    if(count($files))
	    {
		    rsort($files);
	 		$fp = fopen($this->conf->paths['lists']."/".$this->conf->lists_paths['default'],"w+");
			fwrite($fp,implode(spchr::endl,$files));
			fclose($fp);
			$this->conf->last_id = $files[count($files)-1];
			$this->data = null;
			$this->list = null;
    		$fp = fopen($this->conf->paths['file_conf'], "w+");
			fwrite($fp, serialize($this->conf));
			fclose($fp);
			$this->get_data();
			if(count($this->data))
				foreach($this->sortables as $field)
				{
					$this->conf->lists_paths[$field] = md5($field);
					$items = array();
					$items_values = array();
					$sorted = $this->sort_by_field($this->data, $field);
					foreach($sorted as $item)
					{
						$items[] = $item->get_id();
						$items_values[] = $item->{"get_$field"}().$this->separator1.$item->get_id();
					}
		 			$fp = fopen($this->conf->paths['lists']."/".$this->conf->lists_paths[$field],"w+");
					fwrite($fp,implode(spchr::endl,$items));
					fclose($fp);
		 			$fp = fopen($this->conf->paths['lists']."/".$this->conf->lists_paths[$field].file_storage_config::IVAL_PF,"w+");
					fwrite($fp,implode($this->separator2,$items_values));
					fclose($fp);

				}
	    }
    	$fp = fopen($this->conf->paths['file_conf'], "w+");
		fwrite($fp, serialize($this->conf));
		fclose($fp);
		$this->reset_all_data();
    }

	//--------------------------------------------------
	/**
	 * name: add_seminar
	 * params: $seminar
	 */
	public function add($object)
	{
		$this->get_list();
		$id = $object->generate_id();
		$object->set_id($id);
		$fp = fopen($this->conf->paths['storage']."/".$id, "w+");
		if(!$fp)
			throw new Exception("Failed to open file: '".$this->conf->paths['storage']."/".$id."'");
		fwrite($fp, serialize($object));
		fclose($fp);
		$this->update_stat();
	}


	//--------------------------------------------------
	/**
	 * name: add_seminar
	 * params: $seminar
	 */
	public function update($object)
	{
		if(file_exists($this->conf->paths['storage']."/".$object->get_id()))
			$fp = fopen($this->conf->paths['storage']."/".$object->get_id(), "w");
		if(!$fp)
			throw new Exception("Failed to open file: '".$this->conf->paths['storage']."/".$object->get_id()."'");
		fwrite($fp, serialize($object));
		fclose($fp);
		$this->update_stat();
	}

	//--------------------------------------------------
	/**
	 * name: get_list
	 * params:
	 */
	public function get_list($page = null, $count = 10, $sortby = sorting::DEFAULT_SORT, $direction = sorting::SORT_DIR_DEFAULT)
	{
		if(!$this->cache_enabled || !$this->list)
			if(is_file($path = $this->conf->paths['lists']."/".$this->conf->lists_paths[$sortby]) && file_exists($path))
				$this->list = array_map("rtrim",file($path));
			else return ($this->list = null);
		if($direction == sorting::SORT_DIR_DESC)
			$this->list = array_reverse($this->list);
		$pager = new pager(count($this->list),$count);
		return $pager->get_page($this->list, $page);
	}

	//--------------------------------------------------
	/**
	 * name: check_cond
	 * params:
	 */	
	protected function check_cond(file_storage_filter $filter, $obval)
	{
		$cond = $filter->get_cond();
		$value = $filter->get_value();
		switch($cond)
		{
			case file_storage_filter::COND_EQUAL:
      			return ($obval==$value);
			case file_storage_filter::COND_LESSER_EQUAL:
      			return ($obval<=$value);
			case file_storage_filter::COND_LESSER:
      			return ($obval<$value);
			case file_storage_filter::COND_GREATER_EQUAL:
	      		return ($obval>=$value);
			case file_storage_filter::COND_GREATER:
      			return ($obval>$value);
			case file_storage_filter::COND_LIKE:
      			return (strstr($obval,$value));      			
		}
		return false;
	}

	//--------------------------------------------------
	/**
	 * name: check_cond
	 * params:
	 */	
	protected function need_break(file_storage_filter $filter, $obval, $sortdir)
	{
		$cond = $filter->get_cond();
		$value = $filter->get_value();
		switch($cond)
		{
			case file_storage_filter::COND_EQUAL:
      			return (($sortdir == sorting::SORT_DIR_ASC && $obval>$value) || ($sortdir == sorting::SORT_DIR_DESC && $obval<$value));
			case file_storage_filter::COND_LESSER_EQUAL:
      			return (($sortdir == sorting::SORT_DIR_ASC && $obval>$value) || ($sortdir == sorting::SORT_DIR_DESC && $obval<$value));
			case file_storage_filter::COND_LESSER:
      			return (($sortdir == sorting::SORT_DIR_ASC && $obval>=$value) || ($sortdir == sorting::SORT_DIR_DESC && $obval<=$value));
			case file_storage_filter::COND_GREATER_EQUAL:
	      		return false;
			case file_storage_filter::COND_GREATER:
      			return false;
			case file_storage_filter::COND_LIKE:
      			return false;   
		}	
	}	
	//--------------------------------------------------
	/**
	 * name: get_list
	 * params:
	 */
	public function get_data_bycond($field, $filters, $sortby = sorting::DEFAULT_SORT, $sortdir = sorting::SORT_DIR_DEFAULT, $page = null, $count = 10)
	{
		$cond = $this->hash_filters($filters);		
		if(!$this->cache_enabled || !$this->filtered_data[$field][$cond])
		{
			$dir = $this->conf->paths['storage'];
			if(is_file($path = $this->conf->paths['lists']."/".$this->conf->lists_paths[$field].file_storage_config::IVAL_PF) && file_exists($path))
				$data = explode($this->separator2,file_get_contents($path));
			else return ($this->filtered_data[$field][$cond] = null);
			if($sortdir == sorting::SORT_DIR_DESC)
				rsort($data);					
			foreach($data as $tmpdata)
			{
		       	$tmp = explode($this->separator1,rtrim($tmpdata));
		       	$obval = $tmp[0];
		       	$obid = $tmp[1];
		       	$add = false;	
		       	if(is_array($filters))
		       	{
					foreach($filters as $filter)
				    {
					    if($filter instanceof file_storage_filter)
				    	{
				    		$add = $add || $this->check_cond($filter, $obval);
				    	}
					}
					
		       	}
				elseif($filters instanceof file_storage_filter)
				{
			   		$add = $add || $this->check_cond($filters, $obval);
			   		if($this->need_break($filters,$obval,$sortdir))
			   			break;
				}
		       	if($add)
		       	{
		       		$file = file_get_contents($dir."/".rtrim($obid));
		       		$this->filtered_data[$field][$cond][] = unserialize($file);
		       	}
			}
		}
		$pager = new pager(count($this->filtered_data[$field][$cond]),$count);
		return $pager->get_page($this->filtered_data[$field][$cond], $page);
	}


	//--------------------------------------------------
	/**
	 * name: get_all
	 * params:
	 */
	public function get_data($page = null, $count = 10, $sortby = sorting::DEFAULT_SORT, $direction = sorting::SORT_DIR_DEFAULT)
	{
		if(!$this->cache_enabled || !$this->data)
		{
		  $dir = $this->conf->paths['storage'];
		  $files = $this->get_list($page, $count,$sortby,$direction);
	      if($files && count($files) == 0)
	       {
	      	$this->update_stat();
	      	return NULL;
	       }
	      for($file_idx = 0; $file_idx < count($files); ++$file_idx)
	      {
	         $file = file_get_contents($dir."/".rtrim($files[$file_idx]));
	         $this->data[] = unserialize($file);
	      }
		}
		return $this->data;
	}


	//--------------------------------------------------
	/**
	 * name: get_item
	 * params: $id
	 */
	public function item_exists($id)
	{
	  $path = $this->conf->paths['storage']."/".$id;
      if($this->conf->count && file_exists($path))
         return true;
       return false;
	}

	//--------------------------------------------------
	/**
	 * name: get_values
	 * params: $field
	 */
	public function get_values($field)
	{
		if(!$this->cache_enabled || !$this->values_data[$field])
		{		
			$this->values_data[$field] = array();
			if(is_file($path = $this->conf->paths['lists']."/".$this->conf->lists_paths[$field].file_storage_config::IVAL_PF) && file_exists($path))
			{
				$data = explode($this->separator2,file_get_contents($path));
				foreach($data as $tmpdata)
				{
			       	$tmp = explode($this->separator1,rtrim($tmpdata));
			       	$this->values_data[$field][] = $tmp[0];
				}
			}
			else
			{
				$this->data = null;
				$this->get_data();
				foreach($data as $obj)
				{
			       	$this->values_data[$field][] = $obj->{'get_'.$field}();
				}
				$this->data = null;				
			}
		}
		return $this->values_data[$field];
			
	}
		
	//--------------------------------------------------
	/**
	 * name: get_item
	 * params: $id
	 */
	public function get_item($id)
	{
	  $path = $this->conf->paths['storage']."/".$id;
      if(!$this->item_exists($id))
         return NULL;
      return unserialize(file_get_contents($path));
	}

	//--------------------------------------------------
	/**
	 * name: get_item
	 * params: $id
	 */
	public function delete_item($id)
	{
	  $path = $this->conf->paths['storage']."/".$id;
      if(!$this->item_exists($id))
         return NULL;
      unlink($path);
      $this->update_stat();
	}

	//--------------------------------------------------
	/**
	 * name: clear
	 * params:
	 */
	public function clear_data()
	{
    	$dir = $this->conf->paths['storage'];
        // чтение списка файлов
	    $files = utils::read_dir($dir);
	    foreach($files as $file)
	    {
	    	unlink($dir."/".rtrim($file));
	    }
	    foreach($this->conf->lists_paths as $sort => $path)
	    {
	    	unlink($this->conf->paths['lists']."/".$path);
	    }
	    unlink($this->conf->paths['file_conf']);
	    $this->update_stat();
	}
};
