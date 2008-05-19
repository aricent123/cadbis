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
	public static final String DEFAULT_CHARSET = "Cp1251"; 
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
	
	public static char[] getChars (byte[] bytes) {
		Charset cs = Charset.forName ("UTF-8");
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

	public static String readAsUTF8(String str) throws UnsupportedEncodingException
	{
		return new String(str.getBytes(),"UTF-8");
	}
	
	
	public static String ConvertCharset(String content, String charsetFrom, String charsetTo) throws CharacterCodingException, UnsupportedEncodingException
	{ 
			CharsetDecoder decoderFrom = Charset.forName(charsetFrom).newDecoder();
		    ByteBuffer bbuf = ByteBuffer.wrap(content.getBytes(charsetFrom));
		    CharBuffer cbuf = decoderFrom.decode(bbuf);	
	        return cbuf.toString();
	}	
	
	public static byte[] getBytes (char[] chars) {
		Charset cs = Charset.forName ("UTF-8");
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
			res += ((res.isEmpty())?"":delimiter) + array.get(i);
		return res;
	}

}
