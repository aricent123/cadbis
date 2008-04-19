package cadbis.proxy.utils;


import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;

public class StringUtils {
	
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
