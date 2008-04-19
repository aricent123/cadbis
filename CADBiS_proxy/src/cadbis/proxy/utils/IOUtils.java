package cadbis.proxy.utils;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.List;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class IOUtils {

	private final static Logger logger = LoggerFactory.getLogger("IOUtils");
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
	
	public static String readStreamAsArray(InputStream is, List<byte[]> buffer) throws IOException
	{		
		StringBuffer res = new StringBuffer();	
		 InputStreamReader rdr= new InputStreamReader(is);
		 BufferedReader bufRead = new BufferedReader(rdr);
	     int sAvail = 0;
	    	while((sAvail = is.available())>0)
	    	{
	    		byte[] buf = new byte[sAvail];
	    		is.read(buf);
	    		res.append(buf);
	    		logger.debug("reading input: len="+sAvail+";");
	    		buffer.add(buf);
	    	}		 
		 return res.toString();	
	}	
	
	public static void writeArrayToStream(OutputStream os, List<byte[]> buffer) throws IOException
	{		
		 for(int i=0;i<buffer.size();++i)
		 {
			os.write(buffer.get(i));
			logger.debug("writing output: i="+i+", len="+buffer.get(i).length+";");
		 }
		os.flush();
	}		
}
