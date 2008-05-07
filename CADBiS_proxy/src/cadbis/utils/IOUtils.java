package cadbis.utils;

import java.io.BufferedReader;
import java.io.ByteArrayOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.List;
import java.util.zip.DataFormatException;
import java.util.zip.Inflater;

public class IOUtils {

	public static String readStreamAsString(InputStream is) throws IOException
	{    	
		StringBuffer res = new StringBuffer();		
		InputStreamReader rdr= new InputStreamReader(is);
    	BufferedReader bufRead = new BufferedReader(rdr);
    	int sAvail = 0;
    	do{
    		sAvail = is.available();
    		char[] buf = new char[sAvail];
    		bufRead.read(buf);
   			res.append(buf);
    	}while(sAvail > 0);
    	return res.toString();
	}
	
	//public static String readStreamAsArray(InputStream is, List<byte[]> buffer) throws IOException
	public static int readStreamAsArray(InputStream is, List<byte[]> buffer) throws IOException
	{		
		//StringBuffer res = new StringBuffer();
		int rcvd = 0;
	     int sAvail = 0;
	    	while((sAvail = is.available())>0)
	    	{
	    		byte[] buf = new byte[sAvail];
	    		is.read(buf);
	    		//res.append(StringUtils.getChars(buf));
	    		rcvd += sAvail;
	    		buffer.add(buf);
	    	}		 
	    return rcvd;
		 //return res.toString();	
	}	
	
	public static void writeArrayToStream(OutputStream os, List<byte[]> buffer) throws IOException
	{		
		 for(int i=0;i<buffer.size();++i)
		 {
			os.write(buffer.get(i));
		 }
		os.flush();
	}
	
	public static byte[] UnzipArray(byte[] compressedData) throws DataFormatException,IOException
	{
		 // Create the decompressor and give it the data to compress
	    Inflater decompressor = new Inflater();
	    decompressor.setInput(compressedData);
	    
	    // Create an expandable byte array to hold the decompressed data
	    ByteArrayOutputStream bos = new ByteArrayOutputStream(compressedData.length);
	    
	    // Decompress the data
	    byte[] buf = new byte[1024];
	    while (!decompressor.finished()) {
	            int count = decompressor.inflate(buf);
	            bos.write(buf, 0, count);
	    	}
        bos.close();
	    
	    // Get the decompressed data
	    return bos.toByteArray();
	}
}
