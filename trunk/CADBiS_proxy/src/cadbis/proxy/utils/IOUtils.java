package cadbis.proxy.utils;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.io.OutputStream;
import java.util.List;

public class IOUtils {

	public char[] readStreamAsString(InputStream is) throws IOException
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
    	return res.toString().toCharArray();
	}
	
	public char[] readStreamAsArray(InputStream is, List<byte[]> buffer) throws IOException
	{		
		StringBuffer res = new StringBuffer();	
//		 InputStreamReader rdr= new InputStreamReader(is);
//		 BufferedReader bufRead = new BufferedReader(rdr);
	     int sAvail = 0;
	    	while((sAvail = is.available())>0)
	    	{
	    		byte[] buf = new byte[sAvail];
	    		is.read(buf);
	    		res.append(new StringUtils().getChars(buf));
	    		buffer.add(buf);
	    	}		 
		 return res.toString().toCharArray();	
	}	
	
	public void writeArrayToStream(OutputStream os, List<byte[]> buffer) throws IOException
	{		
		 for(int i=0;i<buffer.size();++i)
		 {
			os.write(buffer.get(i));
		 }
		os.flush();
	}		
}
