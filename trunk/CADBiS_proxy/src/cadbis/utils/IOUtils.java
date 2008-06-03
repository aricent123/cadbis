package cadbis.utils;

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.ByteArrayInputStream;
import java.io.ByteArrayOutputStream;
import java.io.EOFException;
import java.io.FileWriter;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.List;
import java.util.zip.DataFormatException;
import java.util.zip.GZIPInputStream;
import java.util.zip.Inflater;

public class IOUtils {
    /**
     * GZIP header magic number.
     */
    public final static int GZIP_MAGIC = 0x8b1f;

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
			os.flush();
		 }		
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
	
    /*
     * Reads unsigned byte.
     */
    private static int readUByte(InputStream in) throws IOException {
	int b = in.read();
	if (b == -1) {
	    throw new EOFException();
	}
        if (b < -1 || b > 255) {
            // Report on this.in, not argument in; see read{Header, Trailer}.
            throw new IOException(in.getClass().getName()
                + ".read() returned value out of range -1..255: " + b);
        }
        return b;
    }
	
    public static int readUShort(InputStream in) throws IOException {
    	int b = readUByte(in);
    	return ((int)readUByte(in) << 8) | b;
        }
    
    public static int readUShort(byte[] compressedData) throws IOException {
    	ByteArrayInputStream bis = new ByteArrayInputStream(compressedData);
    	int b = readUByte(bis);
    	return ((int)readUByte(bis) << 8) | b;
        }
    
	
	public static byte[] UnGzipArray(byte[] compressedData) throws DataFormatException,IOException
	{
		ByteArrayInputStream bis = new ByteArrayInputStream(compressedData);
		GZIPInputStream gis = new GZIPInputStream(bis);
		ByteArrayOutputStream bos = new ByteArrayOutputStream();
		int rtn = -1;
		byte[] b = new byte[1];
		while (true)
		{
			rtn = gis.read(b);
			if (rtn == -1)
				break;
			bos.write(b,0,1);
		}
		return bos.toByteArray();
	}	
	
	
	public static void WriteStringToFile(String filename, String data) throws IOException
	{
		FileWriter fstream = new FileWriter(filename);
	    BufferedWriter out = new BufferedWriter(fstream);
	    out.write(data);
	    out.close();
	}
}
