package cadbis.proxy;

import java.util.HashMap;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class HttpParser {
	private HashMap<Integer, String> Headers;
	private String RequestString;
	private String FullHeader;
	private String RequestMethod;
	private int HttpPort = 80;
	private String HttpHost = "";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	public HttpParser()
	{		
		Headers = new HashMap<Integer, String>();
	}
	
	
	protected void ParseHeaders(String[] HeadStrings)
	{
		RequestString = "";
		if(RequestString.equals(""))
			RequestString = HeadStrings[0];		
		for(int i=1;i<HeadStrings.length;++i)
		{
			String HeadString = HeadStrings[i];
			String[] HeadVal = HeadString.split(": ");
			if(HeadVal.length > 1)
				Headers.put(HeadVal[0].hashCode(), HeadVal[1]);			
		}		
		
		String[] ReqMethString = RequestString.split(" ");
		RequestMethod = ReqMethString[0];
		HttpHost = GetHeader("Host");
		
		try{
			if(HttpHost.indexOf(":")>0){
				String[] buf = HttpHost.split(":");
				HttpHost = buf[0];
				if(buf.length > 1)
					HttpPort = Integer.valueOf(buf[1]);
			}
		}
		catch(NumberFormatException e)
		{
			logger.debug("Port recognition failed: " + e.getMessage());
		}
				
	}
	
	public void ParseRequestHeaders(String FullHeader)
	{
		this.FullHeader = FullHeader;
		ParseHeaders(this.FullHeader.split("\r\n"));		
	}
	
	public void ParseResponseHeaders(String FullHeader)
	{
		String[] HeadBody = FullHeader.split("\r\n\r\n");
		this.FullHeader = HeadBody[0];
		ParseHeaders(this.FullHeader.split("\r\n"));
	}
	public String getHttpHost()
	{
		return HttpHost;
	}
	
	public int getHttpPort()
	{
		return HttpPort;
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
	
	public String GetFixedFullHeader()
	{
		if(!HttpHost.equals("") && !RequestString.matches("^" + RequestMethod + " https?:\\/\\/.+"))
		{
			String Protocol = RequestString.matches(".+https:\\/\\/.+")?"https://":"http://";
			String fixedReq = FullHeader.replace(RequestMethod + " ", RequestMethod + " "+Protocol + HttpHost); 
			logger.debug("Request String is wrong, fixing... Fixed value='"+fixedReq+"'");
			return fixedReq;
		}
		logger.debug("Request String is OK");
		return FullHeader;
	}
}
