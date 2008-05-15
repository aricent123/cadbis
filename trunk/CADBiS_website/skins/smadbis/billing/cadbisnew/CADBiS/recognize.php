<?php

class Recognizer{
	protected static $_proxies = null;
	protected static $_current_proxy = 0;
	protected static $_contenttype = '';
	protected static function page_post($url,$user_agent,$params,$proxy="")
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
	
	protected static function page_get($url,$user_agent,$params,$proxy="")
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url.'?'.$params);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(!empty($proxy))
			curl_setopt($ch,CURLOPT_PROXY,$proxy);
		curl_setopt($ch, CURLOPT_USERAGENT, $user_agent);
		$result=curl_exec($ch);
		
		self::$_contenttype = curl_getinfo($ch,CURLINFO_CONTENT_TYPE);
		curl_close($ch);	
		return $result;
	}	
	
	public static function recognizeByUrlCheck($url,$proxy="")
	{
		if(self::$_proxies == null)
			self::$_proxies = file(dirname(__FILE__)."/proxies.txt");
		if(self::$_current_proxy>count(self::$_proxies))
			return $unrecognized;
		$recognurl="http://filterdb.iss.net/urlcheck/url-report-dboem.asp";
		$user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$params="fullurl=".$url;
		$unrecognized = 'Неопознанная';
		$content = self::page_post($recognurl,$user_agent,$params,$proxy);	
		if(strstr($content,'Not yet categorized or does not fit into any category')) 
			return '';
		if(strstr($content,'Error: user time restriction'))
			return self::recognize($url, self::$_proxies[++self::$_current_proxy]);
		if(preg_match("/<\/b> is classified as<\/h2><ul><li>([^<.]+)<\/li><\/ul>/",$content,$matches));
			return $matches[1];
		return self::recognize($url, self::$_proxies[++self::$_current_proxy]);
	}
	
	protected static function getKeywords($content)
	{
		$metaKeywords = '';
		if(preg_match_all("/<meta name=[\"|\']keywords[\"|\'] content=[\"|\'](.*)[\"|\'][>| \/>]/i",$content,$matches))
		{
			$metaKeywords = $matches[1];
		}
		return $metaKeywords;
	}
	
	protected static function getDescription($content)
	{
		$metaDesc = '';
		if(preg_match_all("/<meta name=[\"|\']description[\"|\'] content=[\"|\'](.*)[\"|\'][>| \/>]/i",$content,$matches))
		{
			$metaDesc = $matches[1];
		}
		return $metaDesc;
	}
	protected static function getTitle($content)
	{	
		$title = '';
		if(preg_match("/<title>(.*)<\/title>/i",$content,$matches))
			$title = $matches[1];
		return $title;		
		
	}

	protected static function killNewLines($content)
	{
		return str_ireplace(array("\r","\n"),' ',$content);
	}	
	protected static function killDoubleSpaces($content)
	{
		while(strstr($content,'  '))
			$content = str_ireplace('  ',' ',$content);
		return $content;
	}
	
	protected static function getCharset()
	{
		$charset = 'UTF-8';
		if(strstr(self::$_contenttype,'charset='))
			$charset = substr(self::$_contenttype,strpos(self::$_contenttype,'charset=')+8);
//		switch (strtolower($charset))
//		{
//			case 'windows-1251':
//				return 'cp1251';
//		}
		return $charset;
	}
	
	public static function recognizeByMyself($url,$cats,$uswords)
	{
		$user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$unrecognized = 'Неопознанная';
		$content = self::page_get($url,$user_agent,'',$proxy);
		echo('<b>Got content:</b><br/>');
		echo('<textarea cols="100" rows="10">'.$content.'</textarea>');
		echo('<hr/>');
		$charset = self::getCharset();
		if($charset != 'UTF-8')
			$content = iconv($charset,'UTF-8',$content);
		echo('<b>Changed charset content (from '.$charset.' to UTF-8):</b><br/>');
		echo('<textarea cols="100" rows="10">'.$content.'</textarea>');
		echo('<hr/>');
		
		$metaKeywds = explode(',',implode('',self::getKeywords($content)));
		foreach($metaKeywds as &$kwd)
			$kwd = ltrim($kwd);
		$metaDesc = implode('',self::getDescription($content));		
		$title = self::getTitle($content);
		$content = preg_replace("/<script[^>.]*>.*<\/script>/ims", " ",$content);
		$content = preg_replace("/<style[^>.]*>.*<\/style>/ims", " ",$content);
		$content = preg_replace("/<[^>]*>/ims", " ",$content);
		$content = self::killNewLines($content);
		$content = self::killDoubleSpaces($content);
		$content = str_replace($uswords,'',$content);

		echo('<b>Keywords:</b><br/>');
		var_dump($metaKeywds);
		echo('<hr/>');
		echo('<b>Desc:</b><br/>');
		echo($metaDesc);
		echo('<hr/>');
		echo('<b>Title:</b><br/>');
		echo($title);
		echo('<hr/>');
		echo('<b>Content:</b><br/>');
		echo('<textarea cols="100" rows="10">'.$content.'</textarea>');
		die;
	}	
}