package cadbis.proxy.httpparser;

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;
import java.nio.charset.CharsetDecoder;
import java.util.regex.MatchResult;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import org.htmlparser.Node;
import org.htmlparser.NodeFilter;
import org.htmlparser.Parser;
import org.htmlparser.filters.HasAttributeFilter;
import org.htmlparser.filters.TagNameFilter;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.utils.StringUtils;

public class ContentAnalyzer {
	protected static final Logger logger = LoggerFactory.getLogger("ContentAnalyzer");
//	

//	
//	protected static function getDescription($content)
//	{
//		$metaDesc = '';
//		if(preg_match_all("/<meta[^>^\/]*(name|http-equiv)=[\"|\'][^>^\/]*description[\"|\'][^>^\/]*content=[\"|\'](.*)[\"|\'][^>^\/]*[>| \/>]/Uims",$content,$matches))
//		{
//			$metaDesc = $matches[2];
//		}
//		return $metaDesc;
//	}
//	protected static function getTitle($content)
//	{	
//		$title = '';
//		if(preg_match("/<title>(.*)<\/title>/i",$content,$matches))
//			$title = $matches[1];
//		return $title;		
//		
//	}
//	
//	protected static function killPunctuation($content)
//	{
//		$punctuation = array(
//						'&nbsp;','&lt;','&gt;','&laquo;','&raquo;','&middot;',
//						'.',';',':','[',']','-','(',')','_','/',
//						'\\','^','{','}','>','<','%','$','#','@','?',
//						'—','\'',',','!','=','+','©','"','«','»','&',
//						'…',
//						);
//		return str_ireplace($punctuation,' ',$content);
//	}
//
//	
//	protected static function toLowerStringCyr($content)
//	{
//		$arrayU = array('А','Б','В','Г','Д','Е','Ё','Ж',
//						'З','И','К','Л','М','Н','О','П',
//						'Р','С','Т','У','Ф','Х','Ц','Ч',
//						'Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я');
//		$arrayL = array('а','б','в','г','д','е','ё','ж',
//						'з','и','к','л','м','н','о','п',
//						'р','с','т','у','ф','х','ц','ч',
//						'ш','щ','ъ','ы','ь','э','ю','я');
//		return str_replace($arrayU,$arrayL,$content);
//	}
//	
//	protected static function toLowerStringLat($content)
//	{
//		$arrayU = array('A','B','C','D','E','F','G','H',
//						'I','J','K','L','M','N','O','P',
//						'Q','R','S','T','U','V','W','X',
//						'Y','Z');
//		$arrayL = array('a','b','c','d','e','f','g','h',
//						'i','j','k','l','m','n','o','p',
//						'q','r','s','t','u','v','w','x',
//						'y','z');		
//		return str_replace($arrayU,$arrayL,$content);
//	}	
//	
//	protected static function killNewLines($content)
//	{
//		return str_ireplace(array("\r","\n","\t"),' ',$content);
//	}	
//	protected static function killDoubleSpaces($content)
//	{
//		while(strstr($content,'  '))
//			$content = str_ireplace('  ',' ',$content);
//		return $content;
//	}
//	
//	protected static function getCharset($content)
//	{
//		$charset = 'UTF-8';
//		if(strstr(self::$_contenttype,'charset='))
//			$charset = substr(self::$_contenttype,strpos(self::$_contenttype,'charset=')+8);
//		if(preg_match("/<meta[^>^\/]*(name|http-equiv)=[\"|\'][^>^\/]*content-type[\"|\'][^>^\/]*content=[\"|\'](.*)[\"|\'][^>^\/]*[>|\/>]/Uims",$content,$matches))
//		{
//			if(strstr($matches[2],'charset='))
//				$charset = str_ireplace(array(';',' '),'',substr($matches[2],strpos($matches[2],'charset=')+8));
//		}
//		return $charset;
//	}
	
	protected static String getKeywords(String content)
	{
		String metaKeywords = "";
		Pattern hunter = Pattern.compile("(?ims)<meta[^>^\\/]*(name|http-equiv)=[\\\"|\\'][^>^\\/]*keywords[\\\"|\\'][^>^\\/]*content=[\\\"|\\'](.*)[\\\"|\\'][^>^\\/]*>| \\/>]");
		Matcher fit = hunter.matcher(content);
		if(fit.matches())
		{
			MatchResult res = fit.toMatchResult();
			logger.info(res.toString());
		}
		return metaKeywords;
	}	
	
	protected static String ConvertToUTF(String content, String charset) throws CharacterCodingException, UnsupportedEncodingException
	{ 
		if(charset.toUpperCase().equals("UTF-8"))
			content = StringUtils.readAsUTF8(content); 
		else
		{
			CharsetDecoder decoder = Charset.forName(charset).newDecoder();
		    ByteBuffer bbuf = ByteBuffer.wrap(content.getBytes("Cp1251"));
		    CharBuffer cbuf = decoder.decode(bbuf);	    
	        return cbuf.toString();
		}
		return content;
	}
	
	protected static String killTagAndContent(String content, String tag)
	{
		content = content.replaceAll("(?ims)<"+tag+"[^>]*>.*<\\/"+tag+">", " ");
		return content;
	}
	
	public static void Analyze(String content, String charset) throws CharacterCodingException, UnsupportedEncodingException
	{		
		content = ContentAnalyzer.ConvertToUTF(content, charset).toLowerCase();
		logger.info("Analyzing content "+content);
		Parser parser = new Parser();
		try {
			parser.setInputHTML(content);
			NodeFilter filter = new TagNameFilter ("meta");
			NodeFilter attrfilter = new HasAttributeFilter("http-equiv","keywords");
			NodeList lst = parser.parse(filter);
			lst = lst.extractAllNodesThatMatch(attrfilter);
			for(int i=0;i<lst.size();++i)
			{
				Node node = lst.elementAt(i);
				logger.info(node.toHtml().toString());				
			}
		} catch (ParserException e) {
			logger.error("Error parsing html: " + e.getMessage());
		}
		content = StringUtils.KillTags(content);
		logger.info("Content after tags killing:" + content);
	}
}
