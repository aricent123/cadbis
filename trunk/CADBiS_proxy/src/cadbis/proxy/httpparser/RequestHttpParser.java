package cadbis.proxy.httpparser;

import cadbis.utils.StringUtils;

public class RequestHttpParser extends AbstractHttpParser {
	private String RequestMethod;
	private int HttpPort = 80;
	private String HttpHost = "";
	private boolean isRequestParsed = false;
	private boolean isEncodingAcceptable = false;
	
	
	public void ParseRequestHeaders(String FullHeader)
	{
		this.FullHeader = FullHeader;
		ParseHeaders(this.FullHeader.split("\r\n"));	
		isRequestParsed = true;
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
		String[] ReqMethString = RequestString.split(" ");
		RequestMethod = (ReqMethString.length>0)?ReqMethString[0]:"";		
	}

	public String getHttpHost()
	{
		return HttpHost;
	}
	
	public int getHttpPort()
	{
		return HttpPort;
	}
	

	public String GetRequestString()
	{
		return RequestString;
	}
		
	public String GetFixedFullHeader()
	{
		if(!HttpHost.equals("") && (RequestMethod.equals("GET") || RequestMethod.equals("POST")) 
				&& !RequestString.matches("^(?s)" + RequestMethod + " https?:\\/\\/"+ HttpHost + ".*"))
		{
			String Protocol = RequestString.matches("(?s).+https:\\/\\/.+")?"https://":"http://";
			String fixedReq = FullHeader.replace(RequestMethod + " ", RequestMethod + " "+Protocol + HttpHost); 
			logger.debug("Request String is wrong, fixing... Fixed value='"+fixedReq+"'");
			return fixedReq;
		}
		if(!isEncodingAcceptable)
			FullHeader = FullHeader.replaceAll("Accept-Encoding: (.)+\r\n", "");
		
		logger.debug("Request String is OK");
		return FullHeader;
	}
	
	public byte[] GetFixedPacket(byte[] packet)
	{
		String PacketString = new String(StringUtils.getChars(packet));		
		if(PacketString.indexOf("HTTP/1.")>0)
		{
			if(!isEncodingAcceptable)
				PacketString = PacketString.replaceFirst("Accept-Encoding: (.)*\r\n", "");
			if(!HttpHost.equals("") && (RequestMethod.equals("GET") || RequestMethod.equals("POST")) 
					&& !RequestString.matches("^(?s)" + RequestMethod + " https?:\\/\\/"+ HttpHost + ".*"))
			{
				String Protocol = RequestString.matches("(?s).+https:\\/\\/.+")?"https://":"http://";
				String fixedPacket = PacketString.replace(RequestMethod + " ", RequestMethod + " "+Protocol + HttpHost); 
				logger.debug("Request String is wrong, value='"+PacketString+"'");
				logger.debug("Request String is wrong, fixing... Fixed value='"+fixedPacket+"'");
				return fixedPacket.getBytes();
			}
			logger.debug("Request String is OK");
			return PacketString.getBytes();
		}
		return packet;
	}

	public boolean isRequestParsed() {
		return isRequestParsed;
	}
	
	public boolean isEncodingAcceptable() {
		return isEncodingAcceptable;
	}


	public void setEncodingAcceptable(boolean isEncodingAcceptable) {
		this.isEncodingAcceptable = isEncodingAcceptable;
	}	
}
