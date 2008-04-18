package cadbis.proxy.utils;

import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;

public class FileUtils {
	private static final int MAX_STR_LEN = 2550;

	public static String readFileAsStream(InputStream is) throws IOException
	{    	
		StringBuffer res = new StringBuffer();
		InputStreamReader rdr= new InputStreamReader(is);
    	BufferedReader bufRead = new BufferedReader(rdr);
    	String line =null;
    	do{
    		line = bufRead.readLine();
    		if(line != null)
    			res.append(line + "\r\n");
    	}while(line != null);
    	return res.toString();
	}
}
