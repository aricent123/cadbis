<?php

class Recognizer{
	const MINIMAL_CWORD_COEF = 2;
	const MINIMAL_KWLEN = 6;
	const META_KWD_COEF = 20;
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
	
	protected static function killPunctuation($content)
	{
		$punctuation = array(
						'&nbsp;','&lt;','&gt;','&laquo;','&raquo;','&middot;',
						'.',';',':','[',']','-','(',')','_','/',
						'\\','^','{','}','>','<','%','$','#','@','?',
						'—','\'',',','!','=','+','©','"','«','»','&',
						'…',
						);
		return str_ireplace($punctuation,' ',$content);
	}

	
	protected static function toLowerStringCyr($content)
	{
		$arrayU = array('А','Б','В','Г','Д','Е','Ё','Ж',
						'З','И','К','Л','М','Н','О','П',
						'Р','С','Т','У','Ф','Х','Ц','Ч',
						'Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я');
		$arrayL = array('а','б','в','г','д','е','ё','ж',
						'з','и','к','л','м','н','о','п',
						'р','с','т','у','ф','х','ц','ч',
						'ш','щ','ъ','ы','ь','э','ю','я');
		return str_replace($arrayU,$arrayL,$content);
	}
	
	protected static function toLowerStringLat($content)
	{
		$arrayU = array('A','B','C','D','E','F','G','H',
						'I','J','K','L','M','N','O','P',
						'Q','R','S','T','U','V','W','X',
						'Y','Z');
		$arrayL = array('a','b','c','d','e','f','g','h',
						'i','j','k','l','m','n','o','p',
						'q','r','s','t','u','v','w','x',
						'y','z');		
		return str_replace($arrayU,$arrayL,$content);
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
				$charset = str_ireplace(array(';',' '),'',substr($matches[2],strpos($matches[2],'charset=')+8));
		return $charset;
	}
	
	protected static function sortCatCoef($a,$b)
	{
    	return ($a['coef'] == $b['coef'])?0:($a['coef'] < $b['coef']) ? 1 : -1;		
	}
	protected static function sortWordCount($a,$b)
	{
    	return ($a['wcount'] == $b['wcount'])?0:($a['wcount'] < $b['wcount']) ? 1 : -1;		
	}	
	
	public static function recognizeByMyself($url,$cats,$uswords,$debug=false)
	{
		$result = '';
		$user_agent="Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)";
		$unrecognized = 'Неопознанная';
		$content = self::page_get($url,$user_agent,'',$proxy);
		
		// ==== debug ====> //
		if($debug){
		echo '
		<style type="text/css">
			.codeblock{
				width:800px; 
				border:1px dashed black;
				height: 200px; 
				overflow: auto;
			}
		</style>';
		echo '<b>Got content:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //
				
		$charset = self::getCharset($content);
		if($charset != 'UTF-8')
			$content = iconv($charset,'UTF-8',$content);

		// ==== debug ====> //
		if($debug){	
		echo '<b>Content with changed charset (from '.$charset.' to UTF-8) and in lowercase:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //		
		
		$metaKeywds = explode(',',implode('',self::getKeywords($content)));
		foreach($metaKeywds as &$kwd)
			$kwd = ltrim($kwd);
		$metaDesc = implode('',self::getDescription($content));		
		$title = self::getTitle($content);
		
		
		$content = preg_replace("/<style[^>]*>.*<\/style>/Uims", " ",$content);
		
		// ==== debug ====> //
		if($debug){	
		echo '<b>Content after style tags replacement:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //			
		
		$content = preg_replace("/<script[^>]*>.*<\/script>/Uims", " ",$content);	

		// ==== debug ====> //
		if($debug){	
		echo '<b>Content after script tags replacement:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //				
			
		
		$content = preg_replace("/<[^>]*>/ims", " ",$content);				
		
		// ==== debug ====> //
		if($debug){	
		echo '<b>Content after other tags replacement:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //			
		
		
		foreach($uswords as $usword)
		{
			$regexp = utils::escape_regexp($usword);
			$content = preg_replace("/\h".$regexp."\h/m"," ",$content);
		}
		
		$content = preg_replace("/[0-9]/"," ",$content);
		$content = self::killPunctuation($content);
		$content = self::killNewLines($content);
		$content = self::killDoubleSpaces($content);		
		// ==== debug ====> //
		if($debug){	
		echo '<b>Content after killing numbers, spaces and punctuation:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //				
		
		$content = self::toLowerStringCyr($content);
		$content = self::toLowerStringLat($content);

		// ==== debug ====> //
		if($debug){	
		echo '<b>Content after lowerstring:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';}
		// <==== debug ==== //					
		
		
		$cwords = explode(' ',$content);
		array_walk($cwords,'ltrim');
		array_walk($cwords,'rtrim');
		$content_words = array();
		foreach($cwords as $cword)
		{
			if(in_array($cword, $uswords) || strlen($cword)<self::MINIMAL_KWLEN)
				continue;
			if(isset($content_words[$cword]))
				$content_words[$cword]++;
			else
				$content_words[$cword] = 1;
		}
	
		$cword_ord =array();
		foreach($content_words as $cword => $wcount)
			$cword_ord[]=array('cword'=>$cword,'wcount'=>$wcount);
		usort($cword_ord,'Recognizer::sortWordCount');
			
		// ==== debug ====> //
		if($debug){
		echo '<b>Keywords:</b><br/>';
		echo '<pre class="codeblock">'.utils::buffered_dump($metaKeywds).'</pre>';
		echo '<hr/>';
		echo '<b>Desc:</b><br/>';
		echo $metaDesc;
		echo '<hr/>';
		echo '<b>Title:</b><br/>';
		echo $title;
		echo '<hr/>';
		echo '<b>Content:</b><br/>';
		echo '<pre class="codeblock">'.htmlspecialchars($content).'</pre>';
		echo '<hr/>';
		echo '<b>Content words:</b><br/>';
		echo '<pre class="codeblock">'.utils::buffered_dump($content_words).'</pre>';
		echo '<hr/>';
		}				
		// <==== debug ==== //
		
		
		$cats_coefs = array();
		foreach($cats as $cat)
		{
			$cats_coefs[$cat['cid']]['coef'] = 0;
			foreach($cat['keywords'] as $keyword)
			{
				$added = 0;
				if(isset($content_words[$keyword]))
				{
					// ==== debug ====> //
					if($debug){
						echo ' +'.$cat['title'].' / '.$keyword.'('.$content_words[$keyword].')<br/>';
					}
					// <==== debug ==== //
					$added = $content_words[$keyword];
					$cats_coefs[$cat['cid']]['coef']+=$content_words[$keyword];
				}
				if(in_array($keyword,$metaKeywds))
				{
					$added = self::META_KWD_COEF;
					$cats_coefs[$cat['cid']]['coef']+= self::META_KWD_COEF;
				}
				if($added>0)
				{
					if(isset($cats_coefs[$cat['cid']]['keywords'][$keyword]))
						$cats_coefs[$cat['cid']]['keywords'][$keyword]+=$added;
					else
						$cats_coefs[$cat['cid']]['keywords'][$keyword]=$added;
				}				
			}
			
	
			
		}
		$i=0;
		$cat_by_cid = array();
		foreach($cats as &$cat)
			$cat_by_cid[$cat['cid']] = $i++;	
		$i=0;
		$coef_by_ord = array();
		foreach($cats_coefs as $cid=>$ccoef)
			$coef_by_ord[]=array('cid'=>$cid,'coef'=>$ccoef['coef'],'keywords'=>$ccoef['keywords']);
		usort($coef_by_ord,'Recognizer::sortCatCoef');	
			
		// ==== debug ====> //
		if($debug){	
		echo '<b>Categories coefs:</b><br/>';
		echo '<pre class="codeblock">';
		foreach($cats_coefs as $cid => $ccoef)
			echo $cats[$cat_by_cid[$cid]]['title'].'('.$cid.')='.$ccoef['coef']."\r\n";		
		echo '</pre>';
		echo '<hr/>';}					
		// <==== debug ==== //
				
		if($debug)
			exit;
		return array('coefs'=>$cats_coefs,'cwords'=>$content_words,'cwordord'=>$cword_ord,'ordcoefs'=>$coef_by_ord);
	}	
}