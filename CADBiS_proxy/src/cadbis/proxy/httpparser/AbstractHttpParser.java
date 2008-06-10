package cadbis.proxy.httpparser;

import java.util.HashMap;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


import cadbis.utils.StringUtils;

public abstract class AbstractHttpParser {
	protected HashMap<Integer, String> Headers;
	protected String RequestString;
	protected String Body = "";
	protected String FullHeader;
	protected final Logger logger = LoggerFactory.getLogger(getClass());
	
	public AbstractHttpParser()
	{		
		Headers = new HashMap<Integer, String>();
	}
	
	
	protected void ParseHeaders(String[] HeadStrings)
	{
		RequestString = "";
		if(RequestString.equals("") && HeadStrings.length>0)
			RequestString = HeadStrings[0];		
		for(int i=1;i<HeadStrings.length;++i)
		{
			String HeadString = HeadStrings[i];
			String[] HeadVal = HeadString.split(": ");
			if(HeadVal.length > 1)
				Headers.put(HeadVal[0].hashCode(), HeadVal[1]);			
		}		
	}
	

	public void ClearHeaders()
	{
		Headers.clear();
	}
	
	public String GetHeader(String Header)
	{
		if(Headers.containsKey(Header.hashCode()))
			return Headers.get(Header.hashCode()); 
		return "";
	}	
	
	public String GetRequestString()
	{
		return RequestString;
	}
		
	public String GetFullHeader()
	{
		return FullHeader;
	}
		

	public String getBody() {
		return Body;
	}


	public boolean ResponseContainsHeader(byte[] packet) {
		String PacketString = new String(StringUtils.getChars(packet));
		return PacketString.matches("(?s).*HTTP/.* OK.*");
	}

	public String GetCharset()
	{
		//text/html; charset=windows-1251
		String res = "utf-8";
		String ctype = GetHeader("Content-Type");
		Integer iofcharset = ctype.indexOf("charset=");
		if(ctype.length()!=0 && iofcharset>=0)
		{
			res = ctype.substring(iofcharset + 8);
		}
		return res;
	}
	
}
