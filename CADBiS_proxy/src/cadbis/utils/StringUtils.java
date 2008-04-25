package cadbis.utils;


import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;
import java.util.regex.Pattern;

public class StringUtils {	
	// Returns a pattern where all punctuation characters are escaped.
    static Pattern escaper = Pattern.compile("([^a-zA-z0-9])");
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
	
	public static char[] getChars (byte[] bytes) {
		Charset cs = Charset.forName ("UTF-8");
		ByteBuffer bb = ByteBuffer.allocate (bytes.length);
		bb.put (bytes);
			bb.flip ();
		CharBuffer cb = cs.decode (bb);
		
		return cb.array();
	}
	
	public static byte[] getBytes (char[] chars) {
		Charset cs = Charset.forName ("UTF-8");
		CharBuffer cb = CharBuffer.allocate (chars.length);
		cb.put (chars);
			cb.flip ();
		ByteBuffer bb = cs.encode (cb);
		
		return bb.array();
        }	

}
