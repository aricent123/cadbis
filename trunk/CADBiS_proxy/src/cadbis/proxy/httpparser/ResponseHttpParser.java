package cadbis.proxy.httpparser;



import cadbis.utils.StringUtils;

public class ResponseHttpParser extends AbstractHttpParser {
	private boolean isResponseParsed = false;
	
	public void ParseResponseHeaders(String FullHeader)
	{
		String[] HeadBody = FullHeader.split("\r\n\r\n");
		this.FullHeader = HeadBody[0];
		if(HeadBody.length > 1)
			this.Body = HeadBody[1];
		ParseHeaders(this.FullHeader.split("\r\n"));
		isResponseParsed = true;
	}


	public boolean isResponseParsed() {
		return isResponseParsed;
	}

	public boolean ResponseContainsHeader(byte[] packet) {
		String PacketString = new String(StringUtils.getChars(packet));
		return PacketString.matches("(?s).*HTTP/.* OK.*");
	}

	public String GetCharset()
	{
		//text/html; charset=windows-1251
		String res = "UTF-8";
		String ctype = GetHeader("Content-Type");
		Integer iofcharset = ctype.indexOf("charset=");
		if(!ctype.isEmpty() && iofcharset>=0)
			res = ctype.substring(iofcharset + 8);
		return res;
	}
	
}
