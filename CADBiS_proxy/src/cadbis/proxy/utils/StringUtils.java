package cadbis.proxy.utils;


import java.nio.ByteBuffer;
import java.nio.CharBuffer;
import java.nio.charset.Charset;

public class StringUtils {
	
	public long ip2value(String ip) throws NumberFormatException
	  {
		long res = 0;
		String[] aip = ip.split("\\.");
		if(aip.length<4)
			return res;
		res = Integer.valueOf(aip[0])*256*256*256 + Integer.valueOf(aip[1])*256*256 + Integer.valueOf(aip[2])*256 + Integer.valueOf(aip[3]);
		return res;
	  }
	
	public char[] getChars (byte[] bytes) {
		Charset cs = Charset.forName ("UTF-8");
		ByteBuffer bb = ByteBuffer.allocate (bytes.length);
		bb.put (bytes);
			bb.flip ();
		CharBuffer cb = cs.decode (bb);
		
		return cb.array();
	}
	
	public byte[] getBytes (char[] chars) {
		Charset cs = Charset.forName ("UTF-8");
		CharBuffer cb = CharBuffer.allocate (chars.length);
		cb.put (chars);
			cb.flip ();
		ByteBuffer bb = cs.encode (cb);
		
		return bb.array();
        }	

}
