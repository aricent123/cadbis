<?php


function test($a)
{
	$res = '';
	if(is_array($a)){
		foreach($a as $k=>$v)
		{
			if(is_array($v))
				$res .= test($v);
			else
				$res .= "<$k>$v</$k>";
		}
	}
	return $res;
}
die(test(array('key'=>array('h'=>'value'),'key2'=>'v2')));
?>