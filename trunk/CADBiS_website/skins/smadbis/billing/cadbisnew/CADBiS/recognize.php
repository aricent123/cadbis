<?php

class Recognizer{
	protected static $_proxies = null;
	protected static $_current_proxy = 0;
	protected static function page_get($url,$user_agent,$params,$proxy="")
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1); // set POST method  
		if(!empty($proxy))
			curl_setopt($ch,CURLOPT_PROXY,$proxy);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$params);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		$result=curl_exec($ch);
		curl_close($ch);
		return $result;
	}
	
	public static function recognize($url,$proxy="")
	{
		if(self::$_proxies == null)
			self::$_proxies = file(dirname(__FILE__)."/proxies.txt");
		if(self::$_current_proxy>count(self::$_proxies))
			return $unrecognized;
		$recognurl="http://filterdb.iss.net/urlcheck/url-report-dboem.asp";
		$user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$params="fullurl=".$url;
		$unrecognized = 'Неопознанная';
		$content = self::page_get($recognurl,$user_agent,$params,$proxy);	
		if(strstr($content,'Not yet categorized or does not fit into any category')) 
			return '';
		if(strstr($content,'Error: user time restriction'))
			return self::recognize($url, self::$_proxies[++self::$_current_proxy]);
		if(preg_match("/<\/b> is classified as<\/h2><ul><li>([^<.]+)<\/li><\/ul>/",$content,$matches));
			return $matches[1];
		return self::recognize($url, self::$_proxies[++self::$_current_proxy]);
	}
}