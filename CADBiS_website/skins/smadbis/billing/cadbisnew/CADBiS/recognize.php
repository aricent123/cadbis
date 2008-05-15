<?php

class Recognizer{
	const MINIMAL_STRLEN = 4;
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
		if(!empty($params))
			$url .='?'.$params;
		curl_setopt($ch, CURLOPT_URL, $url);
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
		if(preg_match_all("/<meta (name|http-equiv)=[\"|\']keywords[\"|\'] content=[\"|\'](.*)[\"|\'][>| \/>]/i",$content,$matches))
		{
			$metaKeywords = $matches[2];
		}
		return $metaKeywords;
	}
	
	protected static function getDescription($content)
	{
		$metaDesc = '';
		if(preg_match_all("/<meta (name|http-equiv)=[\"|\']description[\"|\'] content=[\"|\'](.*)[\"|\'][>| \/>]/i",$content,$matches))
		{
			$metaDesc = $matches[2];
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
		return str_ireplace(array("\r","\n","\t"),' ',$content);
	}	
	protected static function killDoubleSpaces($content)
	{
		while(strstr($content,'  '))
			$content = str_ireplace('  ',' ',$content);
		return $content;
	}
	
	protected static function getCharset($content)
	{
		$charset = 'UTF-8';
		if(strstr(self::$_contenttype,'charset='))
			$charset = substr(self::$_contenttype,strpos(self::$_contenttype,'charset=')+8);
		if(preg_match("/<meta (name|http-equiv)=[\"|\']content-type[\"|\'] content=[\"|\'](.*)[\"|\'][>| \/>]/i",$content,$matches))
			if(strstr($matches[2],'charset='))
				$charset = str_replace(array(';',' '),'',substr($matches[2],strpos($matches[2],'charset=')+8));
		return $charset;
	}
	
	public static function recognizeByMyself($url,$cats,$uswords)
	{
		$result = '';
		$user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$unrecognized = 'Неопознанная';
		$content = self::page_get($url,$user_agent,'',$proxy);
		
		// ==== debug ====> //
		$result.='<b>Got content:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.$content.'</textarea>';
		$result.='<hr/>';
		// <==== debug ==== //
		
		$content = strtolower($content);
		$charset = self::getCharset($content);
		if($charset != 'UTF-8')
			$content = iconv($charset,'UTF-8',$content);

		// ==== debug ====> //			
		$result.='<b>Content with changed charset (from '.$charset.' to UTF-8) and in lowercase:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.$content.'</textarea>';
		$result.='<hr/>';
		// <==== debug ==== //		
		
		$metaKeywds = explode(',',implode('',self::getKeywords($content)));
		foreach($metaKeywds as &$kwd)
			$kwd = ltrim($kwd);
		$metaDesc = implode('',self::getDescription($content));		
		$title = self::getTitle($content);
		$content = preg_replace("/<script[^>.]*>.*<\/script>/ims", " ",$content);
		$content = preg_replace("/<style[^>.]*>.*<\/style>/ims", " ",$content);
		$content = preg_replace("/<[^>]*>/ims", " ",$content);
		$content = preg_replace("/\d+/ims","",$content);
		$content = str_ireplace($uswords,'',$content);		
		$content = self::killNewLines($content);
		$content = self::killDoubleSpaces($content);
		
		$cwords = explode(' ',$content);
		$content_words = array();
		foreach($cwords as $cword)
		{
			if(in_array($cword, $uswords) || strlen($cword)<self::MINIMAL_STRLEN)
				continue;
			if(isset($content_words[$cword]))
				$content_words[$cword]++;
			else
				$content_words[$cword] = 1;
		}
		
		$count_words = array();
		foreach($content_words as $cword => $count)
			$count_words[$count][] = $cword;
		
		
		// ==== debug ====> //
		$result.='<b>Keywords:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.utils::buffered_dump($metaKeywds).'</textarea>';
		$result.='<hr/>';
		$result.='<b>Desc:</b><br/>';
		$result.=$metaDesc;
		$result.='<hr/>';
		$result.='<b>Title:</b><br/>';
		$result.=$title;
		$result.='<hr/>';
		$result.='<b>Content:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.$content.'</textarea>';
		$result.='<hr/>';
		$result.='<b>Content words:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.utils::buffered_dump($content_words).'</textarea>';
		$result.='<hr/>';
		$result.='<b>Content words counts:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.utils::buffered_dump($count_words).'</textarea>';
		$result.='<hr/>';					
		// <==== debug ==== //
		
		
		$cats_coefs = array();
		foreach($cats as $cat)
		{
			foreach($cat['keywords'] as $keyword)
			{
				if(isset($cats_coefs[$cat['cid']]))
					$cats_coefs[$cat['cid']]++;
				else
					$cats_coefs[$cat['cid']] = 0;
			}
		}
		
		// ==== debug ====> //		
		$result.='<b>Categories coefs:</b><br/>';
		$result.='<textarea cols="70" rows="10">'.utils::buffered_dump($cats_coefs).'</textarea>';
		$result.='<hr/>';					
		// <==== debug ==== //		
		
		return $result;
	}	
}