package cadbis.proxy;

import java.util.HashMap;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;


import cadbis.utils.StringUtils;

public class HttpParser {
	private HashMap<Integer, String> Headers;
	private String RequestString;
	private String Body = "";
	private String FullHeader;
	private String RequestMethod;
	private int HttpPort = 80;
	private String HttpHost = "";
	private final Logger logger = LoggerFactory.getLogger(getClass());
	private boolean isResponseParsed = false;
	private boolean isRequestParsed = false;
	
	public HttpParser()
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
		
		String[] ReqMethString = RequestString.split(" ");
		RequestMethod = (ReqMethString.length>0)?ReqMethString[0]:"";
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
		isRequestParsed = true;
	}
	
	public void ParseResponseHeaders(String FullHeader)
	{
		String[] HeadBody = FullHeader.split("\r\n\r\n");
		this.FullHeader = HeadBody[0];
		if(HeadBody.length > 1)
			this.Body = HeadBody[1];
		ParseHeaders(this.FullHeader.split("\r\n"));
		isResponseParsed = true;
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
		if(!HttpHost.equals("") && !RequestString.matches("^(?s)" + RequestMethod + " https?:\\/\\/.+"))
		{
			String Protocol = RequestString.matches("(?s).+https:\\/\\/.+")?"https://":"http://";
			String fixedReq = FullHeader.replace(RequestMethod + " ", RequestMethod + " "+Protocol + HttpHost); 
			logger.debug("Request String is wrong, fixing... Fixed value='"+fixedReq+"'");
			return fixedReq;
		}
		logger.debug("Request String is OK");
		return FullHeader;
	}
	
	public byte[] GetFixedPacket(byte[] packet)
	{
		String PacketString = new String(StringUtils.getChars(packet));
		if(!HttpHost.equals("") && !PacketString.matches("^(?s)" + RequestMethod + " https?:\\/\\/.+"))
		{
			String Protocol = RequestString.matches("(?s).+https:\\/\\/.+")?"https://":"http://";
			String fixedPacket = PacketString.replace(RequestMethod + " ", RequestMethod + " "+Protocol + HttpHost); 
			logger.debug("Request String is wrong, value='"+PacketString+"'");
			logger.debug("Request String is wrong, fixing... Fixed value='"+fixedPacket+"'");
			return fixedPacket.getBytes();
		}
		logger.debug("Request String is OK");
		return packet;
	}


	public String getBody() {
		return Body;
	}


	public boolean isResponseParsed() {
		return isResponseParsed;
	}


	public boolean isRequestParsed() {
		return isRequestParsed;
	}	
}
