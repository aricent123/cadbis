package cadbis.proxy;

import java.util.HashMap;

import org.slf4j.Logger;
import org.slf4j.LoggerFactory;

public class HttpParser {
	private HashMap<Integer, String> Headers;
	private String RequestString;
	private String FullRequestHeader;
	private String RequestMethod;
	private final Logger logger = LoggerFactory.getLogger(getClass());
	
	public HttpParser()
	{		
		Headers = new HashMap<Integer, String>();
	}
	
	public void ParseHeaders(String FullReqHeader)
	{
		String[] HeadStrings =FullReqHeader.split("\r\n");
		RequestString = "";
		FullRequestHeader = FullReqHeader;
		for(String HeadString : HeadStrings)
		{
			if(RequestString.equals(""))
				RequestString = HeadString;
			String[] HeadVal = HeadString.split(": ");
			if(HeadVal.length > 1)
				Headers.put(HeadVal[0].hashCode(), HeadVal[1]);			
		}		
		
		String[] ReqMethString = RequestString.split(" ");
		RequestMethod = ReqMethString[0];
	}
	
	public void ClearHeaders()
	{
		Headers.clear();
	}
	
	public String GetHeader(String Header)
	{
		if(Headers.containsKey(Header.hashCode()))
			return Headers.get(Header.hashCode()); 
		return null;
	}	
	
	public String GetRequestString()
	{
		return RequestString;
	}
		
	public String GetFullRequestHeader()
	{
		return FullRequestHeader;
	}
	
	public String GetFixedFullRequestHeader()
	{
		if(RequestString.indexOf("http://") == -1)
		{
			String fixedReq = FullRequestHeader.replace(RequestMethod + " ", RequestMethod + " http://" + GetHeader("Host")); 
			logger.debug("Request String is wrong, fixing... Fixed value='"+fixedReq+"'");
			return fixedReq;
		}
		logger.debug("Request String is OK");
		return FullRequestHeader;
	}
}
