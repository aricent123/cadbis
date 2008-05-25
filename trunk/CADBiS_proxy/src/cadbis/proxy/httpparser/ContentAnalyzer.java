package cadbis.proxy.httpparser;

import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;
import java.nio.charset.CharsetDecoder;
import java.util.ArrayList;
import java.util.HashMap;
import java.util.Iterator;
import java.util.List;

import org.htmlparser.NodeFilter;
import org.htmlparser.Parser;
import org.htmlparser.filters.HasAttributeFilter;
import org.htmlparser.filters.TagNameFilter;
import org.htmlparser.tags.MetaTag;
import org.htmlparser.util.NodeList;
import org.htmlparser.util.ParserException;
import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

import cadbis.proxy.exc.AnalyzeException;
import cadbis.utils.StringUtils;
import cadbis.bl.ContentCategory;

public class ContentAnalyzer {
	public static final int MINIMAL_WORDLEN = 3;
	public static final int META_KWD_COEF = 20;
	protected static final Logger logger = LoggerFactory.getLogger("ContentAnalyzer");

	protected static String killPunctuation(String content)
	{
		String[] punctuation = {
				"&quot;","&nbsp;","&nbsp;","&lt;","&gt;","&laquo;","&raquo;","&middot;",
				".",";",":","[","]","-","(",")","_","/","|",
				"\\","^","{","}",">","<","%","$","#","@","?",
				"\"",",","!","=","+","©","\"","«","»","&"
				};
		try{
			content = StringUtils.replaceAll(punctuation,content);
		}catch(AnalyzeException e)
		{logger.error("killPunctuation error: " + e.getMessage());}
		return content;
	}
	
	protected static String killUnsenseWords(String content, List<String> uswords)
	{
		try{
			content = StringUtils.replaceAll(uswords,content);
		}catch(AnalyzeException e)
		{logger.error("killUnsenseWords error: " + e.getMessage());}
		return content;		
	}
	
	
	protected static String killNewLines(String content)
	{
		try{
			content = StringUtils.replaceAll(new String[]{"\n","\r","\t"},content);
		}catch(AnalyzeException e)
		{logger.error("killNewLines error: " + e.getMessage());}
		return content;
	}	
	protected static String killDoubleSpaces(String content)
	{
		while(content.indexOf("  ")>=0)
			content = content.replaceAll("  ", " ");
		return content;
	}
	
	protected static String getTagAttribute(String content, String tagName, String hasAttr, String hasAttrVal, String attr)
	{
		String res = "";
		Parser parser = new Parser();
		try {
			parser.setInputHTML(content);
			NodeFilter filter = new TagNameFilter (tagName);
			NodeFilter attrfilter = new HasAttributeFilter(hasAttr,hasAttrVal);
			NodeList lst = parser.parse(filter);
			lst = lst.extractAllNodesThatMatch(attrfilter);
			for(int i=0;i<lst.size();++i)
			{
				MetaTag meta = (MetaTag)lst.elementAt(i);
				res += " " + meta.getAttribute(attr);
			}
		} catch (ParserException e) {
			logger.error("Error parsing html: " + e.getMessage());
		}
		return res;
	}	
	
	protected static String getKeywords(String content)
	{
		String res = "";
		res += getTagAttribute(content, "meta", "http-equiv", "keywords", "content");
		res += getTagAttribute(content, "meta", "name", "keywords", "content");
		return res;		
	}	
	protected static String getDescription(String content)
	{
		String res = "";
		res += getTagAttribute(content, "meta", "http-equiv", "description", "content");
		res += getTagAttribute(content, "meta", "name", "description", "content");
		return res;
	}	
	
	protected static String getCharset(String content)
	{
		String res = "";
		res = getTagAttribute(content, "meta", "http-equiv", "content-type", "content");
		if(res.isEmpty())
			res = getTagAttribute(content, "meta", "name", "content-type", "content");
		if(!res.isEmpty())
			res = StringUtils.getCharset(res);
		return res;
	}		
	
	protected static String ConvertToUTF(String content, String charset) throws CharacterCodingException, UnsupportedEncodingException
	{ 
		if(charset.toUpperCase().equals("UTF-8"))
			content = StringUtils.readAsUTF8(content); 
		else
		{
			CharsetDecoder decoder = Charset.forName(charset).newDecoder();
		    ByteBuffer bbuf = ByteBuffer.wrap(content.getBytes(StringUtils.DEFAULT_CHARSET));
		    CharBuffer cbuf = decoder.decode(bbuf);	    
	        return cbuf.toString();
		}
		return content;
	}
	
	protected static String killTagAndContent(String content, String tag)
	{
		content = content.replaceAll("(?ims)<"+tag+"[^>]*>.*?<\\/"+tag+">", " ");
		return content;
	}
	
	protected static String killTags(String content)
	{
		content = killTagAndContent(content, "script");
		content = killTagAndContent(content, "style");
		content = content.replaceAll("(?s)<[^>]*?>", " ");
		return content;
	}

	protected static String killNumbers(String content)
	{
		content = content.replaceAll("(?sim)([0-9])", " ");
		return content;
	}
	
	public static void Analyze(String content, List<ContentCategory> cats, List<String> uswords, String charset) throws CharacterCodingException, UnsupportedEncodingException
	{		
		String metaCharset = getCharset(content.toLowerCase());
		if(!metaCharset.isEmpty())
			charset = metaCharset;
		logger.info("Converting charset from '"+charset+"'...");
		content = ContentAnalyzer.ConvertToUTF(content, charset).toLowerCase();
		String metaKeywords = getKeywords(content);
		String metaDescription = getDescription(content);
		content = killTags(content);
		content = killNumbers(content);
		content = killPunctuation(content);
		content = killUnsenseWords(content,uswords);
		content = killNewLines(content);
		content = killDoubleSpaces(content);
		HashMap<String, Integer> keywords = new HashMap<String, Integer>();
		String[] arrKeywords = content.split(" ");
		
		for(String keyword:arrKeywords)
		{
			if(MINIMAL_WORDLEN<=keyword.length()){
				if(!keywords.containsKey(keyword))
					keywords.put(keyword, 1);
				else
					keywords.put(keyword,keywords.get(keyword)+1);
			}
		}

		
		Iterator<String> kIterator = keywords.keySet().iterator();
	    while (kIterator.hasNext()) {
	    	String keyword = kIterator.next();
	    	logger.info(keyword+"("+keywords.get(keyword)+")");
		}
		
		
		
		HashMap<Integer, Integer> cats_coefs = new HashMap<Integer, Integer>();
	    for(ContentCategory cat : cats)
	    {	    	
	    	List<String> catkwds = cat.getKeywords();
			Iterator<String> kwIterator = keywords.keySet().iterator();
		    for(String catkw : catkwds)
		    {
		    	int coef = 0;
		    	while (kwIterator.hasNext()) {
			    	String keyword = kwIterator.next();
			    	if(catkw.equals(keyword))
			    		coef += keywords.get(keyword);
		    	}
	    		if(metaKeywords.matches("(?ims).*, *"+catkw+" *,.*") || metaDescription.matches("(?ims).* *"+catkw+" *.*"))
	    			coef += META_KWD_COEF;
    			if(cats_coefs.containsKey(cat.getCid()))
    				cats_coefs.put(cat.getCid(),cats_coefs.get(cat.getCid())+coef);
    			else
    				cats_coefs.put(cat.getCid(), coef);
	    	}
	    }
	    
	    HashMap<Integer, ContentCategory> cats_by_cid = new HashMap<Integer, ContentCategory>();
	    for(ContentCategory cat : cats)
	    	cats_by_cid.put(cat.getCid(),cat);
	    
		logger.info("Keywrods: "+ metaKeywords);
		logger.info("Description: "+ metaDescription);
		Iterator<Integer> iter = cats_coefs.keySet().iterator();
	    while (iter.hasNext()) {
	    	Integer cid = iter.next();
	    	ContentCategory cat = cats_by_cid.get(cid);
	    	logger.info("'"+cat.getTitle()+"'="+cats_coefs.get(cid));
	    }	    
	}
}
