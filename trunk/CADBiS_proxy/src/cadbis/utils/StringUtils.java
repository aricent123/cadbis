package cadbis.utils;


import java.io.UnsupportedEncodingException;
import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.CharacterCodingException;
import java.nio.charset.Charset;
import java.nio.charset.CharsetDecoder;
import java.nio.charset.CharsetEncoder;
import java.util.List;
import java.util.regex.Pattern;


import cadbis.proxy.exc.AnalyzeException;

public class StringUtils {	
	public static final String DEFAULT_CHARSET = Charset.defaultCharset().name();
	public static final String UTF_CHARSET = "UTF-8";
	public static final String ISO_CHARSET = "ISO-8859-1";
	
	// Returns a pattern where all punctuation characters are escaped.
    static Pattern escaper = Pattern.compile("([\\^\\(\\)\\]\\[\\.\\+\\*\\?\\/\\\\\\{\\}\\|\\_])");
    public static String escapeRE(String str) {
        return escaper.matcher(str).replaceAll("\\\\$1");
    }	
	
	public static long ip2value(String ip) throws NumberFormatException
	  {
		long res = 0;
		String[] aip = ip.split("\\.");
		if(aip.length<4)
			return res;
		res = Long.valueOf(aip[0])*256*256*256 + Long.valueOf(aip[1])*256*256 + Long.valueOf(aip[2])*256 + Integer.valueOf(aip[3]);
		return res;
	  }
	
	public static String replaceAll(String[] needle, String haystack) throws AnalyzeException
	{
		for(String sym : needle )
		{
			try{
			sym = escapeRE(sym);
			haystack = haystack.replaceAll(sym, " ");
			}catch(Exception e){ throw new AnalyzeException("error replacing '"+sym+"' : " + e.getMessage());}
		}
		return haystack;		
	}
	
	public static String replaceAllFullWords(List<String> needle, String haystack) throws AnalyzeException
	{
		for(String sym : needle )
		{
			try{
			sym = escapeRE(sym);
			haystack = haystack.replaceAll(" "+sym+" ", " ");
			}catch(Exception e){ throw new AnalyzeException("error replacing '"+sym+"' : " + e.getMessage());}
		}
		return haystack;		
	}
		
	
	public static char[] getChars (byte[] bytes) {
		Charset cs = Charset.forName (UTF_CHARSET);
		ByteBuffer bb = ByteBuffer.allocate (bytes.length);
		bb.put (bytes);
			bb.flip ();
		CharBuffer cb = cs.decode (bb);
		
		return cb.array();
	}
	
	public static char[] getCharsInDefaultCharset(byte[] bytes) {
		Charset cs = Charset.forName (StringUtils.DEFAULT_CHARSET);
		ByteBuffer bb = ByteBuffer.allocate(bytes.length);
		bb.put (bytes);
			bb.flip ();
		CharBuffer cb = cs.decode (bb);
		
		return cb.array();
	}	

	public static String readAsUTF8(String str,String encoding) throws UnsupportedEncodingException
	{
		return new String(str.getBytes(encoding),UTF_CHARSET);
	}
	
	public static String readAsUTF8(String str) throws UnsupportedEncodingException
	{
		return new String(str.getBytes(DEFAULT_CHARSET),UTF_CHARSET);
	}	
	
	public static String readInDefaultCharset(String str, String encoding) throws UnsupportedEncodingException
	{
		return new String(str.getBytes(encoding),DEFAULT_CHARSET);
	}		
	
	public static String decodeCharset(String content, String charsetFrom) throws CharacterCodingException, UnsupportedEncodingException
	{ 
			CharsetDecoder decoderFrom = Charset.forName(charsetFrom).newDecoder();
		    ByteBuffer bbuf = ByteBuffer.wrap(content.getBytes(charsetFrom));
		    CharBuffer cbuf = decoderFrom.decode(bbuf);	
	        return cbuf.toString();
	}	
	
	public static String encodeWithCharset(String content, String charsetWith) throws CharacterCodingException, UnsupportedEncodingException
	{ 
			CharsetEncoder encoderTo = Charset.forName(charsetWith).newEncoder();
			CharBuffer fbuf = CharBuffer.wrap(content.toCharArray());
		    ByteBuffer tbuf = encoderTo.encode(fbuf);
	        return tbuf.toString();
	}			
	
	public static byte[] getBytes (char[] chars) {
		Charset cs = Charset.forName (UTF_CHARSET);
		CharBuffer cb = CharBuffer.allocate (chars.length);
		cb.put (chars);
			cb.flip ();
		ByteBuffer bb = cs.encode (cb);
		
		return bb.array();
        }
	
	public static String join(String delimiter, List<String> array)
	{
		String res = "";
		for(int i=0;i<array.size();++i)
			res += ((res.length()==0)?"":delimiter) + array.get(i);
		return res;
	}
	
	public static String cyrUtfWin(String str, boolean fromUtf)
	{
		String encAlpha = "Ð°Ð±Ð²Ð³Ð´ÐµÑ‘Ð¶Ð·Ð¸Ð¹ÐºÐ»Ð¼Ð½Ð¾Ð¿Ñ€ÑÑ‚ÑƒÑ„Ñ…Ñ†Ñ‡ÑˆÑ‰ÑŠÑ‹ÑŒÑÑŽÑ";
		String utfAlpha = "абвгдеёжзийклмнопрстуфхцчшщъыьэюя";
		  for(int i=0;i<encAlpha.length();i+=2)
		  {
			String letter = encAlpha.substring(i,i+2);
			String letterUTF = utfAlpha.substring(i/2,i/2+1);
			if(fromUtf)
				str = str.replaceAll(letter,letterUTF);
			else
				str = str.replaceAll(letterUTF,letter);
		  }
		return str;
	}
		
	public static String getCharset(String ctype)
	{
		String res = UTF_CHARSET;
		Integer iofcharset = ctype.lastIndexOf("charset=");
		if(ctype.length()!=0 && iofcharset>=0)
			res = ctype.substring(iofcharset + 8);
		if(res.endsWith(";"))
			res = res.substring(0,res.length()-1);
		return res;
	}

}
