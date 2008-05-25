package cadbis.proxy.httpparser;



import cadbis.utils.StringUtils;

public class ResponseHttpParser extends AbstractHttpParser {
	private boolean isResponseParsed = false;
	protected int respStatus = 200;
	
	public void ParseResponseHeaders(String FullHeader)
	{
		String[] HeadBody = FullHeader.split("\r\n\r\n");
		this.FullHeader = HeadBody[0];
		if(HeadBody.length > 1)
			this.Body = HeadBody[1];
		String[] lines = this.FullHeader.split("\r\n");
		int spPos = lines[0].indexOf(" ");
		if(spPos>0)
		{
			int spPos2 = lines[0].indexOf(" ",spPos+2);
			try{
				respStatus = Integer.parseInt(lines[0].substring(spPos+1,spPos2));
			}
			catch(NumberFormatException e){logger.debug("Resp status recognition failed for: '"+lines[0]+"'("+spPos2+")("+spPos+")");}
			catch(StringIndexOutOfBoundsException e){logger.debug("Resp status recognition failed for: '"+lines[0]+"'("+spPos2+")("+spPos+")");}
		}
		ParseHeaders(lines);
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
		return StringUtils.getCharset(GetHeader("Content-Type"));
	}


	public int getRespStatus() {
		return respStatus;
	}
	
}
