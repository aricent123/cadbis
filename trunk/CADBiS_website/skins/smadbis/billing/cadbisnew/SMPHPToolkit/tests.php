<?php
require_once(dirname(__FILE__)."/common.inc.php");

// C# code:
//var ary = { 1, 2, 3 };
// var x = 2;
// ary.Select(elem => elem * x;);

// PHP code:
$arr = arr(1,2,3);
$x = 2;
$arr->select(eval(${new closure('$el')}->{'$el*=$x'}));



class __Base__{
	private function name_space($ns_parts)
	{
		$nsp = '';
		foreach($ns_parts as $part)
			$nsp .= ((empty($nsp))?'':'::').$part;
		return $nsp;
	}
	public function __construct(){
		$ns_parts = explode('__',get_class($this));
		echo $this->name_space($ns_parts);		
	}
};

class A extends __Base__{
};

class A__B__C extends __Base__{
	
};


$A = new A();
$AA = new A__B__C();